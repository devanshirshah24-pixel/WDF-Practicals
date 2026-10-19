<?php

require_once "db.php";

// Check whether form was submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get form values
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $course = trim($_POST["course"] ?? "");
    $year = trim($_POST["year"] ?? "");
    $gender = trim($_POST["gender"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";
    $terms = $_POST["terms"] ?? "";

    // Validation
    $errors = [];

    // Full name
    if (!preg_match("/^[A-Za-z\s]{3,}$/", $fullname)) {
        $errors[] = "Please enter a valid full name.";
    }

    // Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email.";
    }

    // Mobile
    if (!preg_match("/^[6-9][0-9]{9}$/", $mobile)) {
        $errors[] = "Please enter a valid 10-digit mobile number.";
    }

    // Course
    if ($course == "") {
        $errors[] = "Please select a course.";
    }

    // Year
    if ($year == "") {
        $errors[] = "Please select your year.";
    }

    // Gender
    if ($gender == "") {
        $errors[] = "Please select your gender.";
    }

    // Password
    if (!preg_match(
        "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@$!%*?&]).{8,}$/",
        $password
    )) {
        $errors[] =
        "Password must contain uppercase, lowercase, number and special character.";
    }

    // Confirm password
    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Terms
    if ($terms != "accepted") {
        $errors[] = "You must accept the Terms and Conditions.";
    }

    // If there are validation errors
    if (!empty($errors)) {

        echo "<h2>Registration Failed</h2>";
        echo "<ul>";

        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }

        echo "</ul>";

        echo '<a href="signup.php">Go Back</a>';

        exit;
    }

    // Hash password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert into students table
    $sql = "INSERT INTO students
            (name, email, password, gender, mobile, course, year)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        die("Database error: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssi",
        $fullname,
        $email,
        $hashedPassword,
        $gender,
        $mobile,
        $course,
        $year
    );

    // Execute query
    if (mysqli_stmt_execute($stmt)) {

        echo "<h2>Registration Successful!</h2>";

        echo "<p>Welcome to StudentHub, "
             . htmlspecialchars($fullname)
             . "!</p>";

        echo "<p>Your registration has been saved successfully.</p>";

        echo '<a href="Sign%20up.php">Go Back to Sign Up</a>';

    } else {

        // Duplicate email
        if (mysqli_errno($conn) == 1062) {

            echo "<h2>Email Already Registered</h2>";
            echo "<p>This email address is already registered.</p>";

        } else {

            echo "<h2>Registration Failed</h2>";
            echo "<p>"
                 . htmlspecialchars(mysqli_error($conn))
                 . "</p>";
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {

    echo "Invalid Request.";

}

?>