.<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Load latest balance
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>

<style>
body{
    font-family: Arial;
    background: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container{
    width: 700px;
    margin: 40px auto;
    background: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,.2);
}

h1, h2, h3{
    color: #003366;
}

input{
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button{
    padding: 12px 20px;
    background: #003366;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    margin-right: 10px;
}

button:hover{
    background: #0055a5;
}

.success{
    background: #d4edda;
    padding: 10px;
    margin-bottom: 15px;
    color: #155724;
    border-radius: 5px;
}

table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td{
    border: 1px solid #ddd;
    padding: 10px;
    text-align: left;
}

table th{
    background: #003366;
    color: white;
}

.logout{
    display: inline-block;
    margin-top: 20px;
    background: red;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
}

.logout:hover{
    background: #cc0000;
}

.nav-buttons{
    margin: 20px 0;
}
</style>
</head>

<body>
<div class="container">

<h1>Welcome, <?php echo $user['fullname']; ?> 👋</h1>
<h3>Username: <?php echo $user['username']; ?></h3>
<h3>Balance: Rs. <?php echo number_format($user['balance'],2); ?></h3>

<?php
if(isset($_GET['msg'])){
    echo "<div class='success'>".$_GET['msg']."</div>";
}
?>

<div class="nav-buttons">
    <a href="deposit.php"><button>Deposit</button></a>
    <a href="withdraw.php"><button>Withdraw</button></a>
    <a href="transfer.php"><button>Transfer</button></a>
    <a href="transferhistory.php"><button>History</button></a>
    <a href="profile.php"><button>My Profile</button></a>
</div>

<h2>Transaction History</h2>

<table>
<tr>
    <th>Type</th>
    <th>Amount</th>
    <th>Date</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM transactions WHERE user_id=$user_id ORDER BY id DESC");

while($row = $result->fetch_assoc()){
    echo "<tr>
    <td>".$row['type']."</td>
    <td>Rs. ".number_format($row['amount'],2)."</td>
    <td>".$row['created_at']."</td>
    </tr>";
}
?>

</table>

<a class="logout" href="logout.php">Logout</a>

</div>
</body>
</html>