<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ExpenseModel;

class ExpenseController extends BaseController
{
    protected $expenseModel;

    public function __construct()
    {
        $this->expenseModel = new ExpenseModel();
    }
    public function submitExpense()
    {
        $user_id = session()->get('user_id');
        $expense_name = $this->request->getPost('expense_name');
        $amount = $this->request->getPost('amount');
        $expense_date = $this->request->getPost('expense_date');
    
        $expenseData = [
            'user_id' => $user_id,
            'expense_name' => $expense_name,
            'amount' => $amount,
            'expense_date' => $expense_date,
        ];
    
        $this->expenseModel->insertExpense($expenseData);
    
        return $this->response->setJSON(['success' => true]);
    }
    

    public function showExpense()
    {
        $user_id = session()->get('user_id');
        $data = $this->expenseModel->getDataById($user_id);

        return view('tugas/history-expense', ['data' => $data]);
    }

    public function getTotalExpense()
    {
        $user_id = session()->get('user_id');
        $totalExpense = $this->expenseModel->getSum($user_id);

        return $this->response->setJSON([
            'total_expense' => $totalExpense['amount'] ?? 0
        ]);
    }

    public function getTotalExpenseByMonthAndYear($year, $month)
    {
        $user_id = session()->get('user_id');
        $totalExpense = $this->expenseModel->getTotalExpenseByMonthAndYear($user_id, $year, $month);

        return $this->response->setJSON([
            'total_expense' => $totalExpense['amount'] ?? 0
        ]);
    }   

    public function deleteExpense()
    {
        $expense_id = $this->request->getGet('id');
        $this->expenseModel->deleteById($expense_id);
        return redirect()->to('/expense-history');
    }
}