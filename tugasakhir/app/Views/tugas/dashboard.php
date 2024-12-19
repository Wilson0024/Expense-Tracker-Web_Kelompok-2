<?php
    // Cek apakah username tersedia di sesi
    $username = session()->get('username') ?? 'Guest'; // Default jika username tidak ada
    $initial = strtoupper($username[0]); // Ambil huruf pertama dan ubah menjadi huruf besar

    if (session()->getFlashdata('successlogin')) {
        $temp = session()->getFlashdata('successlogin');
        echo "<script> alert('$temp') </script>";
    }

    $sumIncomeNew = number_format((float)$sumIncome, 0, ',', '.');
    $sumExpenseNew = number_format((float)$sumExpense, 0, ',', '.');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Expense Tracker Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <a href="/profile" style="text-decoration: none; color: inherit;">
                    <!-- Menampilkan inisial sebagai profil -->
                    <div class="profile-initial" style="cursor: pointer;">
                        <?= $initial; ?>
                    </div>
                    <h3 style="cursor: pointer;"><?= $username; ?></h3>
                </a>
            </div>
            <ul class="nav">
                <li><a href="/dashboard" onclick="showSection('dashboard')" class="nav-link">Dashboard</a></li>
                <li><a href="/income" onclick="showSection('income')" class="nav-link">Income</a></li>
                <li><a href="/expense" onclick="showSection('expense')" class="nav-link">Expense</a></li>
                <li><a href="/logout" class="logout-btn" style="text-decoration: none">Log Out</li><a></a>
            </ul>
        </div>

        <div class="content">
            <div class="top-bar">
                <h2>Expense Tracker</h2>
            </div>

            <div id="dashboard-section" class="section visible">
                <h2>Dashboard Overview</h2>
                <p>Welcome to your Expense Tracker Dashboard!</p>
                <br><br>

                <!-- Hidden data for JavaScript -->
                <div id="income-data-by-month" style="display:none;"><?= json_encode($incomeDataByMonth) ?></div>
                <div id="expense-data-by-month" style="display:none;"><?= json_encode($expenseDataByMonth) ?></div>
                <div id="income-data-by-year" style="display:none;"><?= json_encode($incomeDataByYear) ?></div>
                <div id="expense-data-by-year" style="display:none;"><?= json_encode($expenseDataByYear) ?></div>

                <div class="top-bar">
                    <!-- Dropdown untuk memilih periode waktu -->
                    <label for="timeFilter">Select Time Period:</label>
                    <select id="timeFilter">
                        <option value="month">Month</option>
                        <option value="year">Year</option>
                    </select>
                    <div style="text-align: right;">
                        <select id="yearFilter" style="display: none;"></select> <!-- Tahun akan diisi secara dinamis (sehingga tidak perlu ada option(value) lagi) -->
                    </div>
                </div>
                
                <div class="graph">
                    <canvas id="incomeExpenseChart" style="width:100%; max-width:700px; background-color:#FFFFFF;"></canvas>
                </div>
                <br><br>
                <div class="cards">
                    <div class="card">
                        <h2>All Time Income</h2>
                        <p class="income"><?= 'Rp. ' . $sumIncomeNew ?></p> <!-- Show sumIncome as integer -->
                    </div>
                    <div class="card">
                        <h2>All Time Expense</h2>
                        <p class="expense"><?= 'Rp. ' . $sumExpenseNew ?></p> <!-- Show sumExpense as integer -->
                    </div>
                    <div class="card">
                        <h2>All Time Balance</h2>
                        <?php 
                        $balance = (int)$sumIncome - (int)$sumExpense;
                        if ($balance > 0) {
                            $balanceClass = 'positive'; // Kelas untuk balance positif
                        } elseif ($balance < 0) {
                            $balanceClass = 'negative'; // Kelas untuk balance negatif
                        } else {
                            $balanceClass = 'neutral'; // Kelas untuk balance nol
                        }
                        $balanceNew = number_format((float)$balance, 0, ',', '.');
                        ?>
                        <p class="balance <?= $balanceClass ?>"><?= 'Rp. ' . $balanceNew ?></p> <!-- Calculate and display balance -->
                    </div>
                </div>
            </div>

            <div id="income-section" class="section hidden">
                <h2>Income Overview</h2>
                <p>Here is the Income section with details about your earnings.</p>
                <p>Total Income This Month: $2000</p>
                <p>Sources of Income: Salary, Freelance Work</p>
            </div>

            <div id="expense-section" class="section hidden">
                <h2>Expense Overview</h2>
                <p>Here is the Expense section with details about your spendings.</p>
                <p>Total Expense This Month: $1500</p>
                <p>Categories: Rent, Food, Utilities, Entertainment</p>
            </div>
        </div>
    </div>

    <script src="js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/chartCanvas.js"></script>
</body>
</html>
