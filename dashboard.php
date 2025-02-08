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

?>

<!-- Dashboard Content -->
<div class="container">
    <h2>Welcome to Your Dashboard</h2>

    <!-- Display User Balance -->
    <section>
        <h3>Your Balance: <?php echo $user['balance']; ?> USD</h3>
    </section>

    <!-- Button Container -->
    <div class="button-container">
        <!-- "Check Balance & Transactions" Button -->
        <a href="check.php">
            <button>Check Balance & Transactions</button>
        </a>

        <!-- "Send Payment" Button -->
        <a href="send.php">
            <button>Send Payment</button>
        </a>

        <!-- "Logout" Button -->
        <a href="logout.php">
            <button>Logout</button>
        </a>
    </div>
</div>

<?php
$conn->close();
?>
