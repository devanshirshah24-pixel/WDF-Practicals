<?php

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


    // Full name validation

    if (!preg_match("/^[A-Za-z\s]{3,}$/", $fullname)) {

        $errors[] = "Please enter a valid full name.";

    }


    // Email validation

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $errors[] = "Please enter a valid email.";

    }


    // Mobile validation

    if (!preg_match("/^[6-9][0-9]{9}$/", $mobile)) {

        $errors[] = "Please enter a valid 10-digit mobile number.";

    }


    // Course validation

    if ($course == "") {

        $errors[] = "Please select a course.";

    }


    // Year validation

    if ($year == "") {

        $errors[] = "Please select your year.";

    }


    // Gender validation

    if ($gender == "") {

        $errors[] = "Please select your gender.";

    }


    // Password validation

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


    // Terms validation

    if ($terms != "accepted") {

        $errors[] =
        "You must accept the Terms and Conditions.";

    }


    // If there are errors

    if (!empty($errors)) {

        echo "<h2>Registration Failed</h2>";

        echo "<ul>";

        foreach ($errors as $error) {

            echo "<li>" .
                 htmlspecialchars($error) .
                 "</li>";

        }

        echo "</ul>";

        echo '<a href="Sign up.php">Go Back</a>';

        exit;
    }


    // Sanitize input

    $fullname = htmlspecialchars(
        $fullname,
        ENT_QUOTES,
        "UTF-8"
    );

    $email = htmlspecialchars(
        $email,
        ENT_QUOTES,
        "UTF-8"
    );

    $mobile = htmlspecialchars(
        $mobile,
        ENT_QUOTES,
        "UTF-8"
    );

    $course = htmlspecialchars(
        $course,
        ENT_QUOTES,
        "UTF-8"
    );

    $year = htmlspecialchars(
        $year,
        ENT_QUOTES,
        "UTF-8"
    );

    $gender = htmlspecialchars(
        $gender,
        ENT_QUOTES,
        "UTF-8"
    );


    // Create registration record

    $registration = [

        "name" => $fullname,

        "email" => $email,

        "mobile" => $mobile,

        "course" => $course,

        "year" => $year,

        "gender" => $gender

    ];


    // JSON file

    $file = "registrations.json";


    // Read existing data

    if (file_exists($file)) {

        $data = file_get_contents($file);

        $registrations = json_decode(
            $data,
            true
        );

    } else {

        $registrations = [];

    }


    // Add new registration

    $registrations[] = $registration;


    // Save data

    file_put_contents(
        $file,
        json_encode(
            $registrations,
            JSON_PRETTY_PRINT
        )
    );


    // Success message

    echo "<h2>Registration Successful!</h2>";

    echo "<p>Welcome to StudentHub, "
         . htmlspecialchars($fullname)
         . "!</p>";

    echo "<p>Your registration has been saved successfully.</p>";

    echo '<a href="Sign up.php">Go Back to Sign Up</a>';

}

else {

    echo "Invalid Request.";

}

?>