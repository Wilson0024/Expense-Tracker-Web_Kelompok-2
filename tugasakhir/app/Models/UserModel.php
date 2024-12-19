<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $allowedFields = ['user_id','first_name', 'last_name', 'email', 'username', 'password', 'reset_token_hash', 'reset_token_expires_at'];

    // Mengambil user berdasarkan username
    public function getUserByUsername(string $username)
    {
        return $this->where('username', $username)
                    ->orWhere('email', $username)
                    ->first();
    }


    // Mengecek apakah email atau username sudah ada
    public function isUsernameOrEmailExists(string $username = null, string $email)
    {
        if ($username) 
        {
            return $this->where('username', $username)
                        ->orWhere('email', $email)
                        ->first();
        } 
        else 
        {
            return $this->where('email', $email)
                        ->first();
        }
    }

    // Menambahkan pengguna baru
    public function createUser(array $data)
    {
        return $this->insert($data);
    }

    public function updateUserByUsername($username, $data)
    {
        $result = $this->db->table('users')
                    ->where('username', $username)
                    ->update($data);
                    
        if (!$result) {
            log_message('error', 'Failed to update user: ' . $this->db->error());
        }
        return $result;
    }   

    public function updateUserPassByToken($token, $data)
    {
        $result = $this->db->table('users')
                    ->where('reset_token_hash', $token)
                    ->update($data);
                    
        if (!$result) {
            log_message('error', 'Failed to update user: ' . $this->db->error());
        }
        return $result;
    } 
    
    public function updateUserTokenbyEmail($email, $data)
    {
        $result = $this->db->table('users')
                    ->where('email', $email)
                    ->update($data);

        if (!$result) {
            log_message('error', 'Failed to update user: ' . $this->db->error());
        }

        return $result;
    }

    public function getUserByToken(string $token)
    {
        return $this->db->table('users')
                           ->where('reset_token_hash', $token)
                           ->get()
                           ->getRowArray();  // This returns the result as an associative array
    }

    public function deleteUserByID(string $id)
    {
        $this->db->table('income')
                        ->where('user_id', $id)
                        ->delete();

        $this->db->table('expense')
                        ->where('user_id', $id)
                        ->delete();
    
        return $this->db->table('users')
                        ->where('user_id', $id)
                        ->delete();
    }

}
