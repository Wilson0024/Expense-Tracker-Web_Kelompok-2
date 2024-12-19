<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="css/style1.css">
</head>
<body>
    <div class="container">
        <h1>Profile Page</h1>
        <form id="profileForm" method="POST" action="/update">
        <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Change Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <button type="submit">Update</button>
        </form>
        <br>
        <button onclick="confirmDelete()" style="color: red">Delete Account</button>
        <script>
        function confirmDelete() {
            if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
            window.location.href = '/delete-acc';  // Redirect to delete account page
            }
        }
        </script>
    </div>
    <script src="js/script.js"></script>
</body>
</html>
