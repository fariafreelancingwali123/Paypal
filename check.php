<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'uerjofazgnjro', 'aj5oyo2qza7g', 'dbuc3bldgklt9g');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Get the user's balance
$sql = "SELECT balance FROM Users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Get the user's recent transactions (if available)
$sql = "SELECT * FROM Transactions WHERE sender_id = $user_id OR recipient_id = $user_id ORDER BY date DESC";
$transactions = $conn->query($sql);

?>

<!-- Display User Balance -->
<h2>Your Balance: <?php echo $user['balance']; ?> USD</h2>

<!-- Display Recent Transactions -->
<h3>Recent Transactions</h3>
<table>
    <tr>
        <th>Sender</th>
        <th>Recipient</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>
    <?php while ($transaction = $transactions->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $transaction['sender_id']; ?></td>
            <td><?php echo $transaction['recipient_id']; ?></td>
            <td><?php echo $transaction['amount']; ?> USD</td>
            <td><?php echo $transaction['date']; ?></td>
        </tr>
    <?php } ?>
</table>

<?php
$conn->close();
?>
