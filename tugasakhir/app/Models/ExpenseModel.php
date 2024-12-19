<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table = 'expense';
    protected $allowedFields = ['user_id', 'expense_name', 'amount', 'expense_date'];

    public function getExpenseByid($id)
    {
        return $this->where('user_id', $id)->findAll();
    }

    public function getSum($id)
    {
        return $this->where('user_id', $id)->selectSum('amount')->first();
    }

    public function insertExpense(array $expenseData)
    {
        return $this->insert($expenseData);
    }

    public function getDataById($user_id)
    {
        return $this->where('user_id', $user_id)->findAll();
    }

    public function getExpenseByMonth($userId)
    {
        return $this->select("DATE_FORMAT(expense_date, '%Y-%m') as month, SUM(amount) as total_expense")
            ->where('user_id', $userId)
            ->groupBy("DATE_FORMAT(expense_date, '%Y-%m')")
            ->orderBy("month")
            ->findAll(); // Group expense by month
    }

    public function getExpenseByYear($userId)
    {
        return $this->select("DATE_FORMAT(expense_date, '%Y') as year, SUM(amount) as total_expense")
            ->where('user_id', $userId)
            ->groupBy("DATE_FORMAT(expense_date, '%Y')")
            ->orderBy("year")
            ->findAll(); // Group income by month
    }

    public function getTotalExpenseByMonthAndYear($user_id, $year, $month)
    {
        return $this->selectSum('amount')
                    ->where('user_id', $user_id)
                    ->where('MONTH(expense_date)', $month)
                    ->where('YEAR(expense_date)', $year)
                    ->first();
    }

    public function deleteById($expense_id)
    {
        return $this->where('expense_id', $expense_id)->delete();
    }
}