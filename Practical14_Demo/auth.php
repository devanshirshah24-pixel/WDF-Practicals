
<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function requireLogin(): void {
    if (!isset($_SESSION["user_id"], $_SESSION["role"])) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin(): void {
    requireLogin();

    if ($_SESSION["role"] !== "admin") {
        http_response_code(403);
        exit("Access denied. Admin privileges are required.");
    }
}

function logAction(mysqli $conn, string $action, string $details): void {
    $userId = $_SESSION["user_id"] ?? null;

    $stmt = $conn->prepare(
        "INSERT INTO pr14_audit_logs (user_id, action, details)
         VALUES (?, ?, ?)"
    );

    $stmt->bind_param("iss", $userId, $action, $details);
    $stmt->execute();
}
?>
