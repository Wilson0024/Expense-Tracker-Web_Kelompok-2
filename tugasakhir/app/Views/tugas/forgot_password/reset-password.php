<?php 
if (session()->getFlashdata('missMatch')) {
    $temp = session()->getFlashdata('missMatch');
    echo "<script>alert(" . json_encode($temp) . ");</script>";
}

$token = session()->get('tokenpass');

if ($success) {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Document</title>
        <link rel='stylesheet' href='css/style.css'>
    </head>
    <body>
        <div class='container'>
            <div class='form-container' id='signInForm'>
                <h2>Reset Password</h2>

                <form method='post' action='/processreset'>
                    <input type='hidden' name='token' value='$token'>

                    <label for='password'>New Password</label>
                    <input type='password' id='password' name='password' placeholder='Enter your new password' required
                        pattern='.{7,}' title='Password must be at least 7 characters long'>
                    
                    <label for='password_confirmation'>Repeat Password</label>
                    <input type='password' id='password_confirmation' name='password_confirmation' placeholder='Confirm your password' required
                        pattern='.{7,}' title='Password must be at least 7 characters long'>
                    
                    <button>RESET</button>
                </form>
            </div>
        </div>
    </body>
    </html>
    ";
} else {
    echo $message; // Show the error message or any other message
}
?>
