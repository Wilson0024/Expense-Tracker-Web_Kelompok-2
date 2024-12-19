<!DOCTYPE html>
<html>
<head>
    <title>Expense List</title>
    <link rel="stylesheet" href="/css/historystyle.css">
    <script type="text/javascript">
        function confirmDelete(expenseId) {
            var result = confirm("Are you sure you want to delete this expense?");
            if (result) {
                window.location.href = "http://localhost:8080/delete-expense?id=" + expenseId; // Replace with your actual delete URL
            }
        }
    </script>
</head>
<body>
    <button id="back-button" onclick="window.location.href='/expense'" style="position: absolute; top: 10px; left: 10px;">
        Back
    </button> 
    <h1>Expense List</h1>
    <table border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data)) : ?>
                <?php $irt = 0; ?>
                <?php foreach ($data as $datas) : ?>
                    <tr>
                        <td><?= $irt+1; ?></td>
                        <?php $irt++; ?>
                        <td><?= $datas['expense_name']; ?></td>
                        <td><?= '-' . $datas['amount']; ?></td>
                        <td><?= $datas['expense_date']; ?></td>
                        <td>
                            <!-- Add a delete button that calls the JavaScript function for confirmation -->
                            <button onclick="confirmDelete(<?= $datas['expense_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">No expenses found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
