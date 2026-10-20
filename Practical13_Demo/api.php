
<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store");

require_once "db.php";

function respond($success, $message, $data = null, $status = 200) {
    http_response_code($status);

    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ]);

    exit;
}

function readInput() {
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!is_array($data)) {
        respond(false, "Invalid JSON request.", null, 400);
    }

    return $data;
}

function validateStudent($data) {
    $name = trim($data["name"] ?? "");
    $email = trim($data["email"] ?? "");
    $course = $data["course"] ?? "";
    $year = filter_var(
        $data["year"] ?? null,
        FILTER_VALIDATE_INT
    );

    if (
        $name === "" ||
        strlen($name) > 100 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        strlen($email) > 100 ||
        !in_array($course, ["WDF", "C++", "DSA"], true) ||
        $year === false ||
        $year < 1 ||
        $year > 4
    ) {
        respond(false, "Please enter valid student details.", null, 422);
    }

    return [$name, $email, $course, $year];
}

$method = $_SERVER["REQUEST_METHOD"];

try {
    // READ: GET /api.php
    if ($method === "GET") {
        $stmt = $conn->prepare(
            "SELECT id, name, email, course, year
             FROM students ORDER BY id DESC"
        );

        $stmt->execute();
        $result = $stmt->get_result();
        $students = [];

        while ($row = $result->fetch_assoc()) {
            $row["id"] = (int)$row["id"];
            $row["year"] = (int)$row["year"];
            $students[] = $row;
        }

        respond(true, "Students loaded successfully.", $students);
    }

    // All remaining operations require JSON.
    if ($method !== "POST") {
        header("Allow: GET, POST");
        respond(false, "Method not allowed.", null, 405);
    }

    $input = readInput();
    $action = $input["action"] ?? "";

    // CREATE: POST action=add
    if ($action === "add") {
        [$name, $email, $course, $year] = validateStudent($input);

        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, course, year)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("sssi", $name, $email, $course, $year);
        $stmt->execute();

        respond(true, "Student added successfully.", [
            "id" => $conn->insert_id
        ], 201);
    }

    // UPDATE: POST action=update
    if ($action === "update") {
        $id = filter_var(
            $input["id"] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id < 1) {
            respond(false, "Invalid student ID.", null, 422);
        }

        [$name, $email, $course, $year] = validateStudent($input);

        $check = $conn->prepare(
            "SELECT id FROM students WHERE id = ?"
        );
        $check->bind_param("i", $id);
        $check->execute();

        if (!$check->get_result()->fetch_assoc()) {
            respond(false, "Student not found.", null, 404);
        }

        $stmt = $conn->prepare(
            "UPDATE students
             SET name = ?, email = ?, course = ?, year = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sssii", $name, $email, $course, $year, $id
        );
        $stmt->execute();

        respond(true, "Student updated successfully.");
    }

    // DELETE: POST action=delete
    if ($action === "delete") {
        $id = filter_var(
            $input["id"] ?? null,
            FILTER_VALIDATE_INT
        );

        if ($id === false || $id < 1) {
            respond(false, "Invalid student ID.", null, 422);
        }

        $stmt = $conn->prepare(
            "DELETE FROM students WHERE id = ?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows !== 1) {
            respond(false, "Student not found.", null, 404);
        }

        respond(true, "Student deleted successfully.");
    }

    respond(false, "Unknown action.", null, 400);

} catch (mysqli_sql_exception $e) {
    error_log($e->getMessage());

    if ((int)$e->getCode() === 1062) {
        respond(false, "This email address already exists.", null, 409);
    }

    respond(false, "A database operation failed.", null, 500);

} catch (Throwable $e) {
    error_log($e->getMessage());
    respond(false, "An unexpected server error occurred.", null, 500);
}
?>
