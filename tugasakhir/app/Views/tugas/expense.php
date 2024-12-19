<?php
    // Cek apakah username tersedia di sesi
    $username = session()->get('username') ?? 'Guest'; // Default jika username tidak ada
    $initial = strtoupper($username[0]); // Ambil huruf pertama dan ubah menjadi huruf besar
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/css/expensestyle.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
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
                <li><a href="/logout" class="logout-btn" style="text-decoration: none">Log Out</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="cards">
                <div class="card">
                    <h2>Total Expense</h2>
                    <p id="total-expense">Rp </p>
                </div>
            </div>
            
        <!-- Wrapper for Form and Filter -->
        <div class="filter-form-wrapper">
            <!-- Expense Form -->
            <div class="expense-form">
                <h2>Add New Expense</h2>
                <form id="expense-form" action="/submit-expense" method="POST">
                    <div class="form-group">
                        <label for="expense_name">Expense Name:</label>
                        <input type="text" id="expense_name" name="expense_name" required placeholder="e.g., Food, Transportation" />
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount (Rp):</label>
                        <input type="number" id="amount" name="amount" required placeholder="e.g., 50000" />
                    </div>

                    <div class="form-group">
                        <label for="expense_date">Date:</label>
                        <input type="date" id="expense_date" name="expense_date" required />
                    </div>

                    <button type="submit" class="submit-btn">Submit Expense</button>
                </form>
            </div>

            <!-- Filter for selecting month/year -->
            <div class="filter-container">
            <div class="filter-dropdowns-row">
                <select id="month" class="month-dropdown">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select id="year" class="year-dropdown">
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>
            </div>
        </div>


            <!-- History Button -->
            <div style="margin-top: 20px;">
                <a href="/expense-history" style="text-decoration: none;">
                    <button class="history-btn">
                        View Expense History
                    </button>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Listen for the form submission
        document.getElementById('expense-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent the default form submission behavior

            const formData = new FormData(this);

            // Send the data to the server without reloading the page
            fetch('/submit-expense', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json()) // Expect a JSON response
            .then(data => {
                if (data.success) {
                    alert('Expense added successfully!');
                    updateTotalExpense(); // Refresh the total expense displayed on the page
                    this.reset(); // Clear the form fields after submission
                } else {
                    alert('Failed to add expense.');
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
                alert('An error occurred. Please try again.');
            });
        });

        // Function to update the total expense displayed on the page
        function updateTotalExpense() {
            // Get the selected month and year
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;

            // Send the selected month and year to the server to fetch the total expense for that period
            fetch(`/get-total-expense/${year}/${month}`)
                .then(response => response.json()) // Expect a JSON response
                .then(data => {
                    const formattedExpense = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                    }).format(data.total_expense);

                    // Update the total expense displayed on the page
                    document.getElementById('total-expense').textContent = formattedExpense;
                })
                .catch(error => console.error('Error fetching total expense:', error));
        }

        // Update the total expense when the page loads
        document.addEventListener('DOMContentLoaded', updateTotalExpense);

        // Add event listeners to the dropdowns to update the total expense automatically when the user selects a new month or year
        document.getElementById('month').addEventListener('change', updateTotalExpense);
        document.getElementById('year').addEventListener('change', updateTotalExpense);
    </script>
</body>
</html>
