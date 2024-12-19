document.addEventListener('DOMContentLoaded', () => {
    // Fetch data from hidden elements
    const incomeDataByMonth = JSON.parse(document.getElementById('income-data-by-month').textContent);
    const expenseDataByMonth = JSON.parse(document.getElementById('expense-data-by-month').textContent);
    const incomeDataByYear = JSON.parse(document.getElementById('income-data-by-year').textContent);
    const expenseDataByYear = JSON.parse(document.getElementById('expense-data-by-year').textContent);

    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // Fungsi untuk mengurutkan bulan berdasarkan waktu
    const sortMonths = (months) => {
        return months.sort((a, b) => new Date(a) - new Date(b));
    };

    const convertToMonthNames = (sortedMonthLabels) => {
        return sortedMonthLabels.map(label => {
            const [year, month] = label.split('-'); // Pisahkan "YYYY-MM"
            return `${monthNames[parseInt(month, 10) - 1]} ${year}`; // Format: "Month Year"
        });
    };

    // Function to process data by month or year
    const processData = (data, type) => {
        return data.reduce((acc, curr) => {
            const label = type === 'month' ? curr.month : curr.year; // Use month or year as label
            if (!acc[label]) acc[label] = 0;
            acc[label] += curr.total_income || curr.total_expense;
            return acc;
        }, {});
    };

    const incomeByMonth = processData(incomeDataByMonth, 'month');
    const expenseByMonth = processData(expenseDataByMonth, 'month');
    const incomeByYear = processData(incomeDataByYear, 'year');
    const expenseByYear = processData(expenseDataByYear, 'year');

    // Ensure all months/years have income and expense, even if they are missing in the data
    const fillMissingData = (allLabels, incomeData, expenseData) => {
        const filledIncome = {};
        const filledExpense = {};

        allLabels.forEach(label => {
            filledIncome[label] = incomeData[label] || 0;
            filledExpense[label] = expenseData[label] || 0;
        });

        return { filledIncome, filledExpense };
    };

    // Get the set of all months/years to make sure we have labels for each one
    const allMonthLabels = Array.from(new Set([
        ...Object.keys(incomeByMonth),
        ...Object.keys(expenseByMonth),
    ]));

    const allYearLabels = Array.from(new Set([
        ...Object.keys(incomeByYear),
        ...Object.keys(expenseByYear),
    ]));

    // Fill missing data for months and years
    const { filledIncome: filledIncomeByMonth, filledExpense: filledExpenseByMonth } = fillMissingData(allMonthLabels, incomeByMonth, expenseByMonth);
    const { filledIncome: filledIncomeByYear, filledExpense: filledExpenseByYear } = fillMissingData(allYearLabels, incomeByYear, expenseByYear);

    // Urutkan bulan sebelum digunakan
    const sortedMonthLabels = sortMonths(allMonthLabels);
    const monthLabelsWithNames = convertToMonthNames(sortedMonthLabels); // Ubah menjadi nama bulan

    // Pastikan data income dan expense sesuai dengan urutan bulan
    const sortedIncomeData = sortedMonthLabels.map(label => filledIncomeByMonth[label] || 0);
    const sortedExpenseData = sortedMonthLabels.map(label => filledExpenseByMonth[label] || 0);

    // Get canvas context
    const ctx = document.getElementById('incomeExpenseChart').getContext('2d');

    // Initialize the chart
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthLabelsWithNames, // Default to month data
            datasets: [
                {
                    label: 'Income',
                    data: sortedIncomeData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(0, 0, 255, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Expense',
                    data: sortedExpenseData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Dropdown references
    const timeFilter = document.getElementById('timeFilter');
    const yearFilter = document.getElementById('yearFilter');

    // Populate year dropdown
    allYearLabels.sort().forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearFilter.appendChild(option);
    });

    // Handle filter change
    timeFilter.addEventListener('change', (event) => {
        const selectedPeriod = event.target.value;

        if (selectedPeriod === 'month') {
            checkSelectOption(yearFilter); // Show year dropdown
            updateChartByMonth(yearFilter.value); // Filter by selected year
        } else if (selectedPeriod === 'year') {
            yearFilter.style.display = 'none'; // Hide year dropdown
            updateChartByYear(); // Update chart with yearly data
        }
    });

    yearFilter.addEventListener('change', () => {
        if (timeFilter.value === 'month') {
            checkSelectOption(yearFilter);
            updateChartByMonth(yearFilter.value); // Update chart by month and selected year
        }
    });

    // Update chart for monthly data based on year
    const updateChartByMonth = (selectedYear) => {
        const filteredLabels = sortedMonthLabels.filter(label => label.startsWith(selectedYear));
        const incomeData = filteredLabels.map(label => filledIncomeByMonth[label] || 0);
        const expenseData = filteredLabels.map(label => filledExpenseByMonth[label] || 0);

        chart.data.labels = convertToMonthNames(filteredLabels);
        chart.data.datasets[0].data = incomeData;
        chart.data.datasets[1].data = expenseData;
        chart.update();
    };

    // Update chart for yearly data
    const updateChartByYear = () => {
        chart.data.labels = allYearLabels;
        chart.data.datasets[0].data = Object.values(filledIncomeByYear);
        chart.data.datasets[1].data = Object.values(filledExpenseByYear);
        chart.update();
    };

    // Checking if yearFilter has select option
    function checkSelectOption (selectElement) {
        const options = selectElement.querySelectorAll('option');
        const hasOption = Array.from(options).some(option => option.value.trim() !== ""); // Mengubah options menjadi array dan mengecek select option apakah null atau tidak

        if (!hasOption) {
            yearFilter.style.display = 'none'; // tidak menampilkan dropdown dengan id = yearFilter
        } else {
            yearFilter.style.display = 'inline-block';
        }
    }

    // Initial state
    checkSelectOption(yearFilter);
    updateChartByMonth(yearFilter.value);
});
