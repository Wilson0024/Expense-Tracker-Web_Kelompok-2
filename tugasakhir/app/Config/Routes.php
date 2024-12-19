<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
 
 $routes->get('/home', 'AuthController::home');
 $routes->get('/', 'AuthController::login');
 $routes->post('/', 'AuthController::login');
 $routes->get('/register', 'AuthController::register');
 $routes->post('/register', 'AuthController::register');
 $routes->get('/logout', 'AuthController::logout');
 $routes->post('/update', 'AuthController::update');

 $routes->get('/forgotpass', 'ResetPassController::forgotpass');
 $routes->post('/sendReset', 'ResetPassController::send_reset');
 $routes->get('/reset-password', 'ResetPassController::resetpass');
 $routes->get('/sendReset', 'ResetPassController::send_reset');
 $routes->post('/processreset', 'ResetPassController::processreset');

 $routes->get('/profile', 'otherController::profile');
 $routes->get('/dashboard', 'otherController::dashboard');
 $routes->get('/income', 'OtherController::income');
 $routes->get('/expense', 'OtherController::expense'); 
 $routes->get('/delete-acc', 'OtherController::deleteacc');

 $routes->post('/submit-income', 'IncomeController::submitIncome');
 $routes->get('/income-history', 'IncomeController::showIncome');
 $routes->get('/get-total-income', 'IncomeController::getTotalIncome');
 $routes->get('/delete-income', 'IncomeController::deleteIncome');
 $routes->get('/get-total-income/(:num)/(:num)', 'IncomeController::getTotalIncomeByMonthAndYear/$1/$2');

 $routes->post('/submit-expense', 'ExpenseController::submitExpense');
 $routes->get('/expense-history', 'ExpenseController::showExpense');
 $routes->get('/get-total-expense', 'ExpenseController::getTotalExpense');
 $routes->get('/delete-expense', 'ExpenseController::deleteExpense');
 $routes->get('/get-total-expense/(:num)/(:num)', 'ExpenseController::getTotalExpenseByMonthAndYear/$1/$2');

 



