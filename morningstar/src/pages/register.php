<?php
include_once('../connect/db_connect.php');
$sql = "INSERT INTO user_data (username, name, surname, email) VALUES (?,?,?,?)";
$sql_password = "INSERT INTO login_data (username, password) VALUES (?,?)";
$stmt = $conn->prepare($sql);
$stmt_password = $conn->prepare($sql_password);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../style/register.css" type="text/css">
    <title>Register</title>
</head>
<body>
    <main>
        <h1>Registration form</h1>
        <form action="register.php" method="post" id="login_form">

            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>
            <label for="surname">Surname:</label>
            <input type="text" name="surname" id="surname" required>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <label for="password_2">Repeat password:</label>
            <input type="password" name="password_2" id="password_2" required>
            <input type="submit" value="register" id="submit">
            <a href="../../index.php">login</a>
        </form>
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password_2"]) && isset($_POST['username'])) {
                $username = htmlspecialchars($_POST["username"]);
                $name = htmlspecialchars($_POST["name"]);
                $surname = htmlspecialchars($_POST["surname"]);
                $email = htmlspecialchars($_POST["email"]);
                $password = htmlspecialchars($_POST["password"]);
                $password2 = htmlspecialchars($_POST["password_2"]);
                $check = $conn->prepare("SELECT username FROM login_data WHERE username = :username");
                $check->bindParam(':username', $username, PDO::PARAM_STR);
                $check->execute();
                if ($check->rowCount() == 0) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if (strlen($password) >= 8) {
                            if ($password == $password2) {
                                $stmt_password->execute([$username, $password2]);
                                $stmt->execute([$username, $name, $surname, $email]);
                                echo '<p class="success-message">All good!</p>';
                            } else {
                                echo '<p class="error-message">Passwords did not match!</p>';
                            }
                        } else {
                            echo '<p class="error-message">Password is too short!</p>';
                        }
                    } else {
                        echo '<p class="error-message">Email is invalid!</p>';
                    }
                } else {
                    echo '<p class="error-message">Username already exists!</p>';
                }
            }

        }
        ?>
    </main>
</body>
</html>
