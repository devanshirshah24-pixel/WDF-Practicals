
<?php
session_start();
require_once "db.php";

// Restrict the demo management page to a logged-in admin.
if (empty($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function flashRedirect($message) {
    header("Location: index.php?message=" . urlencode($message));
    exit;
}

$uploadDir = __DIR__ . "/uploads/";
$allowedMime = [
    "image/jpeg" => "jpg",
    "image/png"  => "png",
    "image/webp" => "webp"
];
$maxSize = 2 * 1024 * 1024; // 2 MB

// Ensure the upload folder exists.
if (!is_dir($uploadDir) &&
    !mkdir($uploadDir, 0755, true) &&
    !is_dir($uploadDir)) {
    exit("Unable to create the uploads directory.");
}

$error = "";
$message = $_GET["message"] ?? "";
$editEvent = null;

function savePoster($field, $uploadDir, $allowedMime, $maxSize) {
    if (!isset($_FILES[$field]) ||
        $_FILES[$field]["error"] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    $file = $_FILES[$field];

    if ($file["error"] !== UPLOAD_ERR_OK) {
        throw new Exception("Poster upload failed.");
    }

    if ($file["size"] <= 0 || $file["size"] > $maxSize) {
        throw new Exception("Poster must be smaller than or equal to 2 MB.");
    }

    if (!is_uploaded_file($file["tmp_name"])) {
        throw new Exception("Invalid uploaded file.");
    }

    $imageInfo = @getimagesize($file["tmp_name"]);
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file["tmp_name"]);

    if (
        $imageInfo === false ||
        !isset($allowedMime[$mime]) ||
        $imageInfo["mime"] !== $mime
    ) {
        throw new Exception(
            "Invalid poster. Upload a JPG, PNG, or WEBP image."
        );
    }

    // Use a generated name instead of the original filename.
    $filename = bin2hex(random_bytes(16)) . "." . $allowedMime[$mime];
    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($file["tmp_name"], $destination)) {
        throw new Exception("Could not save the poster.");
    }

    return $filename;
}

function removePoster($filename, $uploadDir) {
    if (
        $filename !== null &&
        $filename !== "" &&
        basename($filename) === $filename &&
        is_file($uploadDir . $filename)
    ) {
        unlink($uploadDir . $filename);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["csrf_token"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        $error = "Invalid request. Refresh the page and try again.";
    } else {
        $action = $_POST["action"] ?? "";

        try {
            if ($action === "add" || $action === "update") {
                $id = filter_var(
                    $_POST["id"] ?? "",
                    FILTER_VALIDATE_INT
                );
                $title = trim($_POST["title"] ?? "");
                $description = trim($_POST["description"] ?? "");
                $date = $_POST["event_date"] ?? "";
                $venue = trim($_POST["venue"] ?? "");
                $status = $_POST["status"] ?? "";

                $dateObject = DateTime::createFromFormat("!Y-m-d", $date);
                $validDate = $dateObject &&
                    $dateObject->format("Y-m-d") === $date;

                if (
                    ($action === "update" && ($id === false || $id < 1)) ||
                    $title === "" || strlen($title) > 150 ||
                    $description === "" ||
                    $venue === "" || strlen($venue) > 150 ||
                    !$validDate ||
                    !in_array($status, ["Open", "Closed"], true)
                ) {
                    throw new Exception("Please enter valid event details.");
                }

                $oldPoster = null;

                if ($action === "update") {
                    $check = $conn->prepare(
                        "SELECT poster FROM events WHERE id = ?"
                    );
                    $check->bind_param("i", $id);
                    $check->execute();
                    $existing = $check->get_result()->fetch_assoc();

                    if (!$existing) {
                        throw new Exception("Event not found.");
                    }

                    $oldPoster = $existing["poster"];
                }

                $newPoster = savePoster(
                    "poster", $uploadDir, $allowedMime, $maxSize
                );

                $poster = $newPoster ?? $oldPoster;

                if ($action === "add") {
                    $stmt = $conn->prepare(
                        "INSERT INTO events
                         (title, description, event_date, venue, status, poster)
                         VALUES (?, ?, ?, ?, ?, ?)"
                    );
                    $stmt->bind_param(
                        "ssssss",
                        $title, $description, $date, $venue, $status, $poster
                    );
                    $stmt->execute();

                    flashRedirect("Event added successfully.");
                } else {
                    $stmt = $conn->prepare(
                        "UPDATE events SET title = ?, description = ?,
                         event_date = ?, venue = ?, status = ?, poster = ?
                         WHERE id = ?"
                    );
                    $stmt->bind_param(
                        "ssssssi",
                        $title, $description, $date, $venue,
                        $status, $poster, $id
                    );
                    $stmt->execute();

                    if ($newPoster !== null) {
                        removePoster($oldPoster, $uploadDir);
                    }

                    flashRedirect("Event updated successfully.");
                }
            } elseif ($action === "delete") {
                $id = filter_var(
                    $_POST["id"] ?? "",
                    FILTER_VALIDATE_INT
                );

                if ($id === false || $id < 1) {
                    throw new Exception("Invalid event ID.");
                }

                $stmt = $conn->prepare(
                    "SELECT poster FROM events WHERE id = ?"
                );
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $event = $stmt->get_result()->fetch_assoc();

                if (!$event) {
                    throw new Exception("Event not found.");
                }

                $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
                $stmt->bind_param("i", $id);
                $stmt->execute();

                removePoster($event["poster"], $uploadDir);
                flashRedirect("Event deleted successfully.");
            }
        } catch (Throwable $ex) {
            error_log($ex->getMessage());
            $error = $ex instanceof Exception
                ? $ex->getMessage()
                : "An unexpected error occurred.";
        }
    }
}

// Load an event for editing.
if (isset($_GET["edit"])) {
    $id = filter_var($_GET["edit"], FILTER_VALIDATE_INT);

    if ($id !== false && $id > 0) {
        $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $editEvent = $stmt->get_result()->fetch_assoc();

        if (!$editEvent) {
            $error = "Event not found.";
        }
    }
}

// Read all events.
$result = $conn->query(
    "SELECT * FROM events ORDER BY event_date DESC, id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Event Management</title>
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            background: #eaf2ff;
            margin: 0;
            padding: 25px;
            color: #222;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            padding: 24px;
            background: white;
            border-radius: 10px;
        }

        h1 { color: #0b5ed7; }

        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin: 6px 0 12px;
            border: 1px solid #bbb;
            border-radius: 5px;
        }

        button, .button {
            background: #0b5ed7;
            color: white;
            border: 0;
            padding: 9px 12px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            display: inline-block;
        }

        .danger { background: #dc3545; }
        .secondary { background: #555; }
        .success { background: #d1e7dd; padding: 12px; }
        .error { background: #f8d7da; padding: 12px; }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 18px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th { background: #0b5ed7; color: white; }
        .poster { max-width: 100px; max-height: 80px; }
        .actions { min-width: 155px; }
        .actions form { margin-top: 6px; }

        @media (max-width: 650px) {
            body { padding: 10px; }
            .container { padding: 14px; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Event Management System</h1>
    <p>Admin can manage events and upload validated posters.</p>
    <p><a class="button secondary" href="logout.php">Logout</a></p>

    <?php if ($message !== ""): ?>
        <p class="success"><?= e($message) ?></p>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <p class="error"><?= e($error) ?></p>
    <?php endif; ?>

    <h2><?= $editEvent ? "Edit Event" : "Add New Event" ?></h2>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token"
               value="<?= e($_SESSION["csrf_token"]) ?>">
        <input type="hidden" name="action"
               value="<?= $editEvent ? "update" : "add" ?>">

        <?php if ($editEvent): ?>
            <input type="hidden" name="id" value="<?= e($editEvent["id"]) ?>">
        <?php endif; ?>

        <div class="grid">
            <div>
                <label>Event title</label>
                <input type="text" name="title" maxlength="150"
                       required value="<?= e($editEvent["title"] ?? "") ?>">
            </div>

            <div>
                <label>Event date</label>
                <input type="date" name="event_date" required
                       value="<?= e($editEvent["event_date"] ?? "") ?>">
            </div>

            <div>
                <label>Venue</label>
                <input type="text" name="venue" maxlength="150" required
                       value="<?= e($editEvent["venue"] ?? "") ?>">
            </div>

            <div>
                <label>Status</label>
                <select name="status" required>
                    <?php foreach (["Open", "Closed"] as $s): ?>
                        <option value="<?= e($s) ?>"
                            <?= ($editEvent["status"] ?? "Open") === $s
                                ? "selected" : "" ?>>
                            <?= e($s) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <label>Description</label>
        <textarea name="description" rows="3" required><?= e(
            $editEvent["description"] ?? ""
        ) ?></textarea>

        <label>Event poster (JPG, PNG or WEBP; maximum 2 MB)</label>
        <input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

        <?php if (!empty($editEvent["poster"])): ?>
            <p>Current poster:</p>
            <img class="poster"
                 src="<?= "uploads/" . rawurlencode($editEvent["poster"]) ?>"
                 alt="Current event poster">
            <p><small>Leave the upload field empty to keep the existing poster.</small></p>
        <?php endif; ?>

        <button type="submit">
            <?= $editEvent ? "Update Event" : "Add Event" ?>
        </button>

        <?php if ($editEvent): ?>
            <a class="button secondary" href="index.php">Cancel</a>
        <?php endif; ?>
    </form>

    <h2>All Events</h2>

    <div class="table-wrap">
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Date</th>
                <th>Venue</th>
                <th>Status</th>
                <th>Poster</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($event = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= e($event["id"]) ?></td>
                        <td>
                            <strong><?= e($event["title"]) ?></strong><br>
                            <?= e($event["description"]) ?>
                        </td>
                        <td><?= e($event["event_date"]) ?></td>
                        <td><?= e($event["venue"]) ?></td>
                        <td><?= e($event["status"]) ?></td>
                        <td>
                            <?php if (!empty($event["poster"])): ?>
                                <img class="poster"
                                     src="<?= "uploads/" . rawurlencode($event["poster"]) ?>"
                                     alt="Event poster">
                            <?php else: ?>
                                No poster
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a class="button"
                               href="index.php?edit=<?= e($event["id"]) ?>">
                                Edit
                            </a>

                            <form method="post"
                                  onsubmit="return confirm('Delete this event?');">
                                <input type="hidden" name="csrf_token"
                                       value="<?= e($_SESSION["csrf_token"]) ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id"
                                       value="<?= e($event["id"]) ?>">
                                <button type="submit" class="danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No events found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
