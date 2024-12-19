<?php 
    if (session()->getFlashdata('error'))
    {
        $temp = session()->getFlashdata('error');
        echo "<script>alert('$temp')</script>";
    }
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
        <div class="form-container" id="signInForm">
            <h2>Forgot Password</h2>

            <form action="/sendReset" method="POST" >
                
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter your email">

                <button>Send</button>

            </form>
        </div>
    </div>

</body>
</html>