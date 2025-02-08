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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user input
    $recipient_email = $conn->real_escape_string($_POST['recipient_email']);
    $amount = floatval($_POST['amount']);
    $sender_id = $_SESSION['user_id'];

    // Get sender's balance
    $sql = "SELECT balance FROM Users WHERE id = $sender_id";
    $result = $conn->query($sql);
    $sender = $result->fetch_assoc();

    if ($sender['balance'] >= $amount) {
        // Get recipient's ID
        $sql = "SELECT id FROM Users WHERE email = '$recipient_email'";
        $result = $conn->query($sql);
        if ($result->num_rows == 1) {
            $recipient = $result->fetch_assoc();
            $recipient_id = $recipient['id'];

            // Deduct from sender's balance and add to recipient's balance
            $conn->begin_transaction();

            // Update sender's balance
            $sql = "UPDATE Users SET balance = balance - $amount WHERE id = $sender_id";
            if ($conn->query($sql) === TRUE) {
                // Update recipient's balance
                $sql = "UPDATE Users SET balance = balance + $amount WHERE id = $recipient_id";
                if ($conn->query($sql) === TRUE) {
                    $conn->commit();
                    echo "Payment successful!";
                } else {
                    $conn->rollback();
                    echo "Error updating recipient's balance.";
                }
            } else {
                $conn->rollback();
                echo "Error updating sender's balance.";
            }
        } else {
            echo "Recipient not found.";
        }
    } else {
        echo "Insufficient balance.";
    }
}

$conn->close();
?>

<!-- Send Payment Form -->
<form method="post">
    <label for="recipient_email">Recipient Email:</label>
    <input type="email" id="recipient_email" name="recipient_email" required><br>

    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" required><br>

    <button type="submit">Send Payment</button>
</form>
