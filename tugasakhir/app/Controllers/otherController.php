<?php

namespace App\Controllers;
use App\Models\ExpenseModel;
use App\Models\IncomeModel;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class otherController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function dashboard(): ResponseInterface
    {
        $session = session();
        if ($session->get('logged_in')) {
            $userId = session()->get('user_id'); // Ambil user ID dari session (pastikan session aktif)
                $incomeModel = new IncomeModel();
                $expenseModel = new ExpenseModel();

                // Dapatkan data income dan expense berdasarkan bulan
                $incomeDataByMonth = $incomeModel->getIncomeByMonth($userId);
                $expenseDataByMonth = $expenseModel->getExpenseByMonth($userId);

                // Dapatkan data income dan expense berdasarkan tahun
                $incomeDataByYear = $incomeModel->getIncomeByYear($userId);
                $expenseDataByYear = $expenseModel->getExpenseByYear($userId);

                $sumIncome = $incomeModel->getSum($userId);
                $sumExpense = $expenseModel->getSum($userId);

                // Siapkan data untuk dikirim ke view
                $data = [
                    'incomeDataByMonth' => $incomeDataByMonth,
                    'expenseDataByMonth' => $expenseDataByMonth,
                    'incomeDataByYear' => $incomeDataByYear,
                    'expenseDataByYear' => $expenseDataByYear,
                    'sumExpense' => $sumExpense['amount'],
                    'sumIncome' => $sumIncome['amount']
                ];

            return $this->response->setBody(view('tugas/dashboard', $data));
        } else {
            return redirect()->to('/');
        }
    }

    public function profile()
    {
        $session = session();
    
        if ($session->get('logged_in')) {
            $user = $this->userModel->getUserByUsername($session->get('username'));
            return view('tugas/profile', ['user' => $user]);
        } 
        else {
            return view('tugas/login');
        }
    }

    public function income()
    {
        return view('tugas/income'); // Ganti dengan lokasi file view untuk halaman Income
    }

    public function expense()
    {
        return view('tugas/expense'); // Ganti dengan lokasi file view untuk halaman Expense
    }

    public function deleteacc()
    {
        $session = session();
    
        if ($session->get('logged_in')) {
            $res = $this->userModel->deleteUserByID($session->get('user_id'));
            if (!$res)
            {
                $user = $this->userModel->getUserByUsername($session->get('username'));
                return view('tugas/profile', ['user' => $user]);
            }
            else
            {
                $session = session();
                $session->destroy();
                session()->setFlashdata('successlogout', 'Delete Account Success!!');
                return view('tugas/logout');
            }
        }
        else
        {
            redirect()->to('/');
        }
    }
}