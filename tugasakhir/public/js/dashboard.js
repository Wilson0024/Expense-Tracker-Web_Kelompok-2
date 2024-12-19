
// Mendapatkan elemen tombol dan konten
const dashboardBtn = document.getElementById('dashboard-btn');
const incomeBtn = document.getElementById('income-btn');
const expenseBtn = document.getElementById('expense-btn');

const dashboardContent = document.getElementById('dashboard');
const incomeContent = document.getElementById('income');
const expenseContent = document.getElementById('expense');

function showContent(contentToShow) {

    dashboardContent.style.display = 'none';
    incomeContent.style.display = 'none';
    expenseContent.style.display = 'none';

    contentToShow.style.display = 'block';
}

// Menambahkan event listener untuk tombol
dashboardBtn.addEventListener('click', () => showContent(dashboardContent));
incomeBtn.addEventListener('click', () => showContent(incomeContent));
expenseBtn.addEventListener('click', () => showContent(expenseContent));

showContent(dashboardContent);

// Debugging incomeData and expenseData
console.log('Income Data:', incomeData);
console.log('Expense Data:', expenseData);

// Debug processed months and values
console.log('Months:', months);
console.log('Income Values:', incomeValues);
console.log('Expense Values:', expenseValues);