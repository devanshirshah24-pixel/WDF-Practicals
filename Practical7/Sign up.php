<!DOCTYPE html>
<html>

<head>
    <title>Signup</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<nav class="navbar">

    <div class="nav-brand">StudentHub</div>

    <div class="nav-right">

        <a href="Home.html">Home</a>

        <button id="themeToggle"
                class="theme-toggle"
                title="Toggle light/dark theme">
            🌙
        </button>

    </div>

</nav>


<h1 align="center">Welcome to StudentHub</h1>

<br>


<center>

<fieldset class="signup-fieldset">

<form id="signupform"
      action="registration.php"
      method="POST"
      novalidate>

    <h3>Student Registration</h3>

    <label for="fullname">
        Full Name:
    </label>

    <input type="text"
           id="fullname"
           name="fullname"
           placeholder="Enter your full name"
           autocomplete="name">

    <span class="error"
          id="fullnameError">
    </span>

    <label for="email">
        Email:
    </label>

    <input type="email"
           id="email"
           name="email"
           placeholder="Enter your email"
           autocomplete="email">

    <span class="error"
          id="emailError">
    </span>

    <label for="mobile">
        Mobile Number:
    </label>

    <input type="tel"
           id="mobile"
           name="mobile"
           placeholder="Enter 10-digit mobile number"
           maxlength="10"
           autocomplete="tel">

    <span class="error"
          id="mobileError">
    </span>

    <label for="course">
        Course:
    </label>

    <select id="course"
            name="course">

        <option value="">
            -- Select Course --
        </option>

        <option value="WDF">
            Web Development Framework
        </option>

        <option value="C++">
            Programming with C++
        </option>

        <option value="DSA">
            Data Structure and Algorithm
        </option>

    </select>

    <span class="error"
          id="courseError">
    </span>

    <label for="year">
        Year:
    </label>

    <select id="year"
            name="year">

        <option value="">
            -- Select Year --
        </option>

        <option value="1">
            1st Year
        </option>

        <option value="2">
            2nd Year
        </option>

        <option value="3">
            3rd Year
        </option>

        <option value="4">
            4th Year
        </option>

    </select>

    <span class="error"
          id="yearError">
    </span>

    <label>
        Gender:
    </label>

    <div class="gender-group">

        <label class="inline-label">

            <input type="radio"
                   name="gender"
                   value="Male">

            Male

        </label>


        <label class="inline-label">

            <input type="radio"
                   name="gender"
                   value="Female">

            Female

        </label>


        <label class="inline-label">

            <input type="radio"
                   name="gender"
                   value="Other">

            Other

        </label>

    </div>

    <span class="error"
          id="genderError">
    </span>

    <label for="createpassword">
        Create Password:
    </label>

    <input type="password"
           id="createpassword"
           name="password"
           placeholder="Create the password"
           autocomplete="new-password">

    <span class="error"
          id="createpasswordError">
    </span>

    <label for="confirmpassword">
        Confirm Password:
    </label>

    <input type="password"
           id="confirmpassword"
           name="confirm_password"
           placeholder="Confirm the password"
           autocomplete="new-password">

    <span class="error"
          id="confirmpasswordError">
    </span>

    <div class="terms-group">

        <label class="inline-label">

            <input type="checkbox"
                   id="terms"
                   name="terms"
                   value="accepted">

            I accept the Terms and Conditions

        </label>

    </div>

    <span class="error"
          id="termsError">
    </span>

    <input type="submit"
           value="Register">


    <p id="message"></p>

</form>

</fieldset>

</center>


<script src="script.js"></script>

</body>

</html>