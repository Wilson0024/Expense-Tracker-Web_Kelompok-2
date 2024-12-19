<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\IncomeModel;

class IncomeController extends BaseController
{
    protected $incomeModel;
    public function __construct()
    {
        $this->incomeModel = new IncomeModel();
    }
    public function submitIncome()
    {
        $user_id = session()->get('user_id');
        $income_name = $this->request->getPost('income_name');
        $amount = $this->request->getPost('amount');
        $income_date = $this->request->getPost('income_date');

        $incomeData = [
            'user_id' => $user_id,
            'income_name' => $income_name,
            'amount' => $amount,
            'income_date' => $income_date,
        ];

        $this->incomeModel->insertIncome($incomeData);

        return $this->response->setJSON(['success' => true]);
    }

    public function showIncome()
    {
        $user_id = session()->get('user_id');
        $data = $this->incomeModel->getDataById($user_id);

        return view('tugas/history-income', ['data' => $data]);
    }

    public function getTotalIncome()
    {
        $user_id = session()->get('user_id');
        $totalIncome = $this->incomeModel->getSum($user_id);

        return $this->response->setJSON([
            'total_income' => $totalIncome['amount'] ?? 0
        ]);
    }

    public function getTotalIncomeByMonthAndYear($year, $month)
    {
        $user_id = session()->get('user_id');
        $totalIncome = $this->incomeModel->getTotalIncomeByMonthAndYear($user_id, $year, $month);

        return $this->response->setJSON([
            'total_income' => $totalIncome['amount'] ?? 0
        ]);
    }

    public function deleteIncome()
    {
        $income_id = $this->request->getGet('id');
        $this->incomeModel->deleteById($income_id);
        return redirect()->to('/income-history');
    }
}