<?php 
    if (session()->getFlashdata('successreset'))
    {
        $temp = session()->getFlashdata('successreset');
        echo "<script>alert('$temp')</script>"; 
    }
    if (session()->getFlashdata('successreg'))
    {
        $temp = session()->getFlashdata('successreg');
        echo "<script>alert('$temp')</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In & Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <!-- Sign In Form -->
        <div class="form-container" id="signInForm">
    <h2>Sign In</h2>
    <form id="Form" action="/" method="POST">
        <label for="username">Username/Email</label>
        <input type="text" name="username" id="username" placeholder="Enter your email or username" value="<?php echo isset($_COOKIE['username']) ? htmlspecialchars($_COOKIE['username']) : ''; ?>" required>
    
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" value="<?php echo isset($_COOKIE['password']) ? htmlspecialchars($_COOKIE['password']) : ''; ?>" required>
        
        <div>
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Remember Me</label>
        </div>
        
        <button type="submit" name="login">Sign in</button>
        
        <div class="bottom-text">
            <span>
                Don't have an account? <a href="/register" class="link">Register here</a>
            </span>
            <br><br>
            <span>
                <a href="/forgotpass" class="link">Forgot Password?</a>
            </span>
        </div>
    </form>
</div>

    </div> 
    
<script>
    document.getElementById("Form").addEventListener("submit", function(event) {
        // Get the username and password input values
        var username = document.getElementById("username").value;
        var password = document.getElementById("password").value;

        // Check if username is at least 3 characters and contains only alphabetic characters
        var usernameRegex = /^[a-zA-Z]+$/;
        if (username.length < 3) {
            alert("Username must be at least 3 characters long.");
            event.preventDefault(); // Prevent form submission
            return false;
        }

        // Check if password is at least 7 characters
        if (password.length < 7) {
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


