<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

use function PHPUnit\Framework\equalTo;

class ResetPassController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Method untuk mengatur session user
    private function setUserSession(array $user): void
    {
        $session = session();
        $session->set([
            'logged_in' => true,
            'username' => $user['username'],
        ]);
    }

    public function forgotpass()
    {
        return view('tugas/forgot_password/forgotpass');
    }

    public function send_reset()
    {
        $email = $this->request->getPost('email');

        if ($this->userModel->isUsernameOrEmailExists(null, $email))
        {
            $token = bin2hex(random_bytes(16)); // generate 16bytes of cryptographically secure random data.
            $token_hash = hash("sha256", $token); // hash the token to be more secure
            $expdate = date("Y-m-d H-i-s",time() + 60 * 10);
    
            $data = [
                'reset_token_hash' => $token_hash,
                'reset_token_expires_at' =>  $expdate
            ];
    
            $this->userModel->updateUserTokenbyEmail($email, $data);

            $mail = require __DIR__ . "/../Views/tugas/mailer.php";

            $mail->setFrom("wilsonangelietann@gmail.com");
            $mail->addAddress($email);
            $mail->Subject = "Password Reset";
            $mail->Body = <<<END
            
            Click <a href="http://localhost:8080/reset-password?token=$token">wilson reset link</a> to reset your password.

            END;
            
            try
            {
                $mail->send();
            }
            catch(Exception $e)
            {
                return redirect()->to('/forgotpass')->with('error', "email couldn't be send. Mailer Error");
            }
        }
        else
        {
            return redirect()->to('/forgotpass')->with('error', 'Email Not Found in Database.');
        }

        return redirect()->to('/forgotpass')->with('error', 'Email Sent, Check Your Inbox.');
    }
    
    public function resetpass()
    {
        $token = $this->request->getGet('token');

        if (!$token) {
            return view('tugas/forgot_password/reset-password', [
                'success' => false,
                'message' => 'Token is missing.',
            ]);
        }

        $token_hash = hash("sha256", $token);

        $user = $this->userModel->getUserbyToken($token_hash);

        if (!$user)
        {
            return view('tugas/forgot_password/reset-password', [
                'success' => false,
                'message' => 'Invalid or unknown token.',
            ]);           
        }

        if (strtotime($user['reset_token_expires_at']) <= time())
        {
            return view('tugas/forgot_password/reset-password', [
                'success' => false,
                'message' => 'Token has expired.',
            ]);
        }

        session()->set('tokenpass', $token);
        return view('tugas/forgot_password/reset-password', [
            'success' => true,
            'message' => 'Token is valid.',
        ]);
    }

    public function processreset()
    {
        $password = $this->request->getPost('password');
        $passwordconf = $this->request->getPost('password_confirmation');
        $token = $this->request->getPost('token');

        if ($password == $passwordconf)
        {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $token_hash = hash("sha256", $token);

            $data = [
                'reset_token_hash' => NULL,
                'reset_token_expires_at' => NULL,
                'password' => $password_hash
            ];

            $this->userModel->updateUserPassByToken($token_hash, $data);
            return redirect()->to('/')->with('successreset', "Password successfully reset!!");
        }
        else
        {
            session()->setFlashdata('missMatch', "Password doesn't match");
            return view('tugas/forgot_password/reset-password', ['success' => true]);
        }
    }
}
