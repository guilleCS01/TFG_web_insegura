<?php

session_start();

$mysqli = new mysqli("localhost", "root", "password", "web_users");

if ($mysqli->connect_error) {
    die("There has been an error in the connection to the database try again later " . $mysqli->connect_error);
}
$username = '';

if (isset($_GET['id'])) {

    $userId = $_GET['id'];
    
    
    $stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();
    

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($username);
        $stmt->fetch();
    } else {
        echo "User not found";
        exit();
    }
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debate Forum</title>
    <link rel="stylesheet" type="text/css" href="forum.css">
</head>
<body>
    <header>
        <h1>Debate Forum</h1>
    </header>
    <div class="pregunta">
        <h2><b>Let us know you opinion about the 2024 climate summit</b></h2>
        <a href="https://www.theclimategroup.org/us-climate-action-summit-2024">Reference</a>
    </div>
    <div id="messages">
        <?php include 'getMessages.php'; ?>
    </div>

    <?php if(isset($_GET['id']) ) { ?> 
    <div id="messageForm">
        <form>
            <div>
                <?php if(isset($_GET['id']) ) { ?> 
                    <label for="name">Name: <?php echo $username; ?></label>
                    <input type="hidden" id="name" value="<?php echo $username; ?>">
                <?php } else { ?>
                    <label for="name">Name:</label>
                    <input type="text" id="name" required>
                <?php } ?>
            </div>

            <div>
                <label for="message">Message:</label>
                <textarea id="message" placeholder="Write a message" required></textarea>
                <div id="errorMessages" style="color: red;"></div>
            </div>

            <button type="button" onclick="postMessage()">Send</button>
        </form>
        </div>
    <?php } else { ?> 
        <p style="text-align: center; color: red; font-style: italic;">You must login in the platform to be able to write in the forum.</p>

    <?php } ?>

    
    <div class="back">
        <h3><b><a href="<?php echo isset($_SESSION['loggedin']) ? 'dashboard.php?id=' . $_SESSION['id'] : 'index.html'; ?>">Go Back</a></b></h3>
    </div>
    <script>
        // Funcion para enseñar los mensajes
        function displayMessages() {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var messagesDiv = document.getElementById('messages');
                    messagesDiv.innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'getMessages.php', true);
            xhr.send();
        }


        function postMessage() {
            var name = '<?php echo $username; ?>'; 
            var message = document.getElementById('message').value;
            var errorMessagesDiv = document.getElementById('errorMessages');
            errorMessagesDiv.innerHTML = ''; 



            if (message) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            displayMessages();
                            document.getElementById('name').value = '';
                            document.getElementById('message').value = '';
                        } else {
                            errorMessagesDiv.innerHTML = 'There was an error tryig to send the message: ' + xhr.responseText;
                        }
                    }
                };
                xhr.open('POST', 'postMessage.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.send(JSON.stringify({ name: name, message: message }));
            }
        }


        displayMessages();
    </script>
</body>
</html>
