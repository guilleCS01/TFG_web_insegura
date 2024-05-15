<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php"); 
    exit();
}
$mysqli = new mysqli("localhost", "root", "password", "web_users");

if ($mysqli->connect_error) {
    die("There has been an error in the connection to the database try again later");
}

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    if ($userId != $_SESSION['id']) {
        header("Location: login.php");
        exit();
    }
} else {
    echo "User's ID was not provided.";
    exit();
}

if (isset($_GET['changePassword'])) {
    $newPassword = sha1($_GET['newPassword']);

    $stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPassword, $userId);
    $stmt->execute();
    $stmt->close();
    $_SESSION['success'] = "Password changed successfully";
    header("Location: changepass.php?id=$userId&success=1");
    exit();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Change Password</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<i><p style="color:red; text-align: center;">' . $_SESSION['error'] . '</p></i>';
        unset($_SESSION['error']);
    } elseif (isset($_SESSION['success'])) {
        echo '<i><p style="color:blue; text-align: center;">' . $_SESSION['success'] . '</p></i>';
        unset($_SESSION['success']); 
    }
    ?>
    <form action="changepass.php?id=<?php echo $userId; ?>&success=1" method="get">
    <label for="newPassword">New Password:</label>
    <input type="password" id="newPassword" name="newPassword" required>
    <input type="hidden" name="id" value="<?php echo $userId; ?>"> 
    <input type="submit" name="changePassword" value="Change Password">
</form>
    <div class="back">
        <h3><b><a href="dashboard.php?id=<?php echo $userId; ?>">Go Back</a></b></h3>
    </div>
</body>
</html>
