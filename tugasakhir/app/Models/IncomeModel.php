<?php

namespace App\Models;

use CodeIgniter\Model;

class IncomeModel extends Model
{
    protected $table = 'income';
    protected $allowedFields = ['user_id', 'income_name', 'amount', 'income_date'];

    public function getIncomeByid($id)
    {
        return $this->where('user_id', $id)->findAll();
    }

    public function getSum($id)
    {
        return $this->where('user_id', $id)->selectSum('amount')->first();
    }

    public function insertIncome(array $incomeData)
    {
        return $this->insert($incomeData);
    }

    public function getDataById($user_id)
    {
        return $this->where('user_id', $user_id)->findAll();
    }

    public function getIncomeByMonth($userId)
    {
        return $this->select("DATE_FORMAT(income_date, '%Y-%m') as month, SUM(amount) as total_income")
            ->where('user_id', $userId)
            ->groupBy("DATE_FORMAT(income_date, '%Y-%m')")
            ->orderBy("month")
            ->findAll(); // Group income by month
    }

    public function getIncomeByYear($userId)
    {
        return $this->select("DATE_FORMAT(income_date, '%Y') as year, SUM(amount) as total_income")
            ->where('user_id', $userId)
            ->groupBy("DATE_FORMAT(income_date, '%Y')")
            ->orderBy("year")
            ->findAll(); // Group income by month
    }

    public function getTotalIncomeByMonthAndYear($user_id, $year, $month)
    {
        return $this->selectSum('amount')
                    ->where('user_id', $user_id)
                    ->where('MONTH(income_date)', $month)
                    ->where('YEAR(income_date)', $year)
                    ->first();
    }

    public function deleteById($income_id)
    {
        return $this->where('income_id', $income_id)->delete();
    }
}