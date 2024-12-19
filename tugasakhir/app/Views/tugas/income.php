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
    <title>Income</title>
    <link rel="stylesheet" href="/css/incomestyle.css">
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
            <li><a href="/income" onclick="showSection('income')" class="nav-link active">Income</a></li>
            <li><a href="/expense" onclick="showSection('expense')" class="nav-link">Expense</a></li>
            <li><a href="/logout" class="logout-btn" style="text-decoration: none">Log Out</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
            <div class="cards">
                <div class="card">
                    <h2>Total Income</h2>
                    <p id="total-income">Rp 0</p>
                </div>
            </div>
            
            <!-- Wrapper for Form and Filter -->
        <div class="filter-form-wrapper">
            <!-- Income Form -->
            <div class="income-form">
                <h2>Add New Income</h2>
                <form id="income-form" action="/submit-income" method="POST">
                    <div class="form-group">
                        <label for="income_name">Income Name:</label>
                        <input type="text" id="income_name" name="income_name" required placeholder="e.g., Food, Transportation" />
                    </div>

                    <div class="form-group">
                        <label for="amount">Amount (Rp):</label>
                        <input type="number" id="amount" name="amount" required placeholder="e.g., 50000" />
                    </div>

                    <div class="form-group">
                        <label for="income_date">Date:</label>
                        <input type="date" id="income_date" name="income_date" required />
                    </div>

                    <button type="submit" class="submit-btn">Submit Income</button>
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
            <a href="/income-history" style="text-decoration: none;">
                <button class="history-btn">
                    View Income History
                </button>
            </a>
        </div>
    </div>
</div>

<script>
    // Listen for the form submission
    document.getElementById('income-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission behavior

        const formData = new FormData(this);

        // Send the data to the server without reloading the page
        fetch('/submit-income', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json()) // Expect a JSON response
        .then(data => {
            if (data.success) {
                alert('Income added successfully!');
                updateTotalIncome(); // Refresh the total income displayed on the page
                this.reset(); // Clear the form fields after submission
            } else {
                alert('Failed to add income.');
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            alert('An error occurred. Please try again.');
        });
    });

    // Function to update the total Income displayed on the page
    function updateTotalIncome() {
    // Get the selected month and year
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;

    // Send the selected month and year to the server to fetch the total income for that period
    fetch(`/get-total-income/${year}/${month}`)
        .then(response => response.json()) // Expect a JSON response
        .then(data => {
            const formattedIncome = new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                    }).format(data.total_income);

                    // Update the total income displayed on the page
                    document.getElementById('total-income').textContent = formattedIncome;
        })
        .catch(error => console.error('Error fetching total income:', error));
}

// Update the total income when the page loads
document.addEventListener('DOMContentLoaded', updateTotalIncome);

// Add event listeners to the dropdowns to update the total income automatically when the user selects a new month or year
document.getElementById('month').addEventListener('change', updateTotalIncome);
document.getElementById('year').addEventListener('change', updateTotalIncome);
</script>
</body>
</html>
