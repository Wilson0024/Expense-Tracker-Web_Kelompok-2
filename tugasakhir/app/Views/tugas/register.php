<?php 

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
    <!-- Register Form -->
        <div class="form-container" id="registerForm">
            <h2>Register</h2>
            <form action="/register" method="POST" id="Form">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" id="firstName" placeholder="Enter your first name" required>
            
                <label for="lastName">Last Name</label>
                <input type="text" name="lastName" id="lastName" placeholder="Enter your last name" required>
            
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
            
                <label for="usernameReg">Username</label>
                <input type="text" name="usernameReg" id="usernameReg" placeholder="Choose a username" required>
            
                <label for="passwordReg">Password</label>
                <input type="password" name="passwordReg" id="passwordReg" placeholder="Create a password" required>
                
                <label for="passwordReg2">Confirm Password</label>
                <input type="password" name="passwordReg2" id="passwordReg2" placeholder="Confirm password" required>

                <button type="submit" name="register">Register</button>
            
                <div class="bottom-text">
                    <span>Already have an account? <a href="/">Sign in here</a></span>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("Form").addEventListener("submit", function(event) {
            // Get the username and password input values
            var username = document.getElementById("usernameReg").value;
            var passwordReg = document.getElementById("passwordReg").value;
            var passwordReg2 = document.getElementById("passwordReg2").value;

            // Check if username is at least 3 characters and contains only alphabetic characters
            var usernameRegex = /^[a-zA-Z]+$/;
            if (username.length < 3) {
                alert("Username must be at least 3 characters long.");
                event.preventDefault(); // Prevent form submission
                return false;
            } else if (!usernameRegex.test(username)) {
                alert("Username can only contain alphabetic characters.");
                event.preventDefault(); // Prevent form submission
                return false;
            }

            // Check if password is at least 7 characters
            if (passwordReg.length < 7 && passwordReg2.length < 7) {
                alert("Password must be at least 7 characters long.");
                event.preventDefault(); // Prevent form submission
                return false;
            }
            // If all checks pass, allow form submission
            return true;
        });
    </script>

    <script src="js/validation.js"></script>
</body>
</html>