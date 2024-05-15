<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
if (isset($_POST['login'])) {
    session_start();

    $HOST = 'localhost';
    $USER = 'root';
    $PASS = 'password';
    $NAME = 'web_users';
    $connect = mysqli_connect($HOST, $USER, $PASS, $NAME);

    if (mysqli_connect_error()) {
        exit('There has been an error in the connection to the database, try again later' . mysqli_connect_error());
    }
    $stmt = $connect->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $pass);
        $stmt->fetch();

        if (sha1($password) == $pass) { 
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php?id=$id");
            exit;
        } else {
            $_SESSION['error'] = "Incorrect password";
        }
    } else {
        $_SESSION['error'] = "User does not exist";
    }

    $stmt->close();
    $connect->close();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login_page.css">

</head>

<body>
    <div id="login-form-wrap">
        <h2>Login</h2>
        <?php

        if (isset($_SESSION['error'])) {
            echo '<i><p style="color:red;">' . $_SESSION['error'] . '</p></i>';
            unset($_SESSION['error']); // Borrar el mensaje
        }
        ?>
        <form action="login.php" method="post" id="login-form">
            <p><input id="username" name="username" type="text" placeholder="Usuario" /></p>
            <p><input id="password" name="password" type="password" placeholder="Contraseña" /></p>
            <p><input name="login" type="submit" value="Login" /></p>
        </form>
        <div id="create-account-wrap">
            <p>Not a member? <a href="register.php">Create Account</a>
            <p>
        </div>
    </div>
    <div class="back">
        <h3><b><a href="index.html">Go Back</a></b></h3>
    </div>
</body>

</html>
