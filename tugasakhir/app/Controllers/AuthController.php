<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

use function PHPUnit\Framework\equalTo;

class AuthController extends BaseController
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
            'user_id' => $user['user_id']
        ]);
    }

    public function home()
    {
        return view('tugas/home');
    }
    
    public function login(): ResponseInterface
    {
        if ($this->request->getMethod() === 'POST') {
            $usernameOrEmail = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $remember = $this->request->getPost('remember');
            
            // Mengambil data user berdasarkan username
            $user = $this->userModel->getUserByUsername($usernameOrEmail);

            if ($user) {
                if (password_verify($password, $user['password'])) {
                    // Panggil method untuk mengatur session
                    $this->setUserSession($user);
                    
                    if ($remember) {
                        setcookie('username', $usernameOrEmail, time() + (86400 * 30), "/"); // 30 days
                        setcookie('password', $password, time() + (86400 * 30), "/"); // 30 days (not secure)
                    } else {
                        // Clear cookies if not remembered
                        setcookie('username', '', time() - 3600, "/");
                        setcookie('password', '', time() - 3600, "/");
                    }

                    return redirect()->to('/dashboard')->with('successlogin', 'login success');

                } 
                else {
                    echo "<script>alert('Incorrect password. Please try again.');</script>";
                } 
            }
            else {
                echo "<script>alert('Username does not exist. Please check your username.');</script>";
            }
        }

        return $this->response->setBody(view('tugas/login'));
    }
        
    public function register(): ResponseInterface
    {
        if ($this->request->getMethod() === 'POST') {
            $first_name = $this->request->getPost("firstName");
            $last_name = $this->request->getPost("lastName");
            $email = $this->request->getPost("email");
            $usernameReg = $this->request->getPost("usernameReg");
            $passwordReg = $this->request->getPost("passwordReg");
            $passwordReg2 = $this->request->getPost("passwordReg2");

            if ($passwordReg !== $passwordReg2) {
                echo "<script>alert('Passwords do not match.');</script>";
            }
            // Mengecek apakah username atau email sudah ada
            else if ($this->userModel->isUsernameOrEmailExists($usernameReg, $email)) {
                echo "<script>alert('Username or Email already exists. Please choose another one.');</script>";
            } 
            else {
                $hashed_pass = password_hash($passwordReg, PASSWORD_BCRYPT);

                // Data yang akan dimasukkan ke database
                $data = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'username' => $usernameReg,
                    'password' => $hashed_pass,
                ];

                // Menambahkan user ke database
                if ($this->userModel->createUser($data)) {
                    // Set session setelah registrasi
                    $user = $this->userModel->getUserByUsername($usernameReg);
                    $this->setUserSession($user); // Panggil method session

                    return redirect()->to('/')->with('successreg', 'Register Success.');
                } 
                else {
                    echo "<script>alert('Registration failed. Please try again.');</script>";
                }
            }
        }

        return $this->response->setBody(view('tugas/register'));
    }

    public function logout(): string
    {
        $session = session();
        $session->destroy();
        session()->setFlashdata('successlogout', 'logout success.');
        return view('tugas/logout');
    }

    public function update(){
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/');
        }
    
        $username = $session->get('username'); // Current user's username from session
        $firstName = $this->request->getPost('firstName');
        $lastName = $this->request->getPost('lastName');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        if (!$this->validate([
            'firstName' => 'required|min_length[0]',
            'lastName' => 'required|min_length[0]',
            'email' => 'required|valid_email',
            'password' => 'permit_empty|min_length[0]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        // Prepare data for update
        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
        ];
    
        // If a new password is provided, hash it before saving
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
    
        // Update the user's data in the database
        if ($this->userModel->updateUserByUsername($username, $data)) {
            // Success: Redirect with a success message
            return redirect()->to('/dashboard')->with('successlogin', 'Profile updated successfully.');
        } else {
            // Failure: Redirect with an error message
            return redirect()->back()->with('error', 'Failed to update profile.');
        }
    }
}
