<?php
session_start();
include_once('src/connect/db_connect.php');
$need = ['e49fba2ad83d3a12da950aa8c60d32b31d77e4df', '5befb0748e190886505727a102475cc24fbaa116', '8001c21b73a5f86b318f6cea8b1139c113a10227', '62fea36df07094eb28793ac693ec54a3566f92c8', '296d7d177dbe2f9ef91ef6ff393a5f3c9874b122'];
if (!isset($_SESSION['login'])) {
    $_SESSION['login'] = false;
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $username = htmlspecialchars($_POST["username"]);
    $password = htmlspecialchars($_POST["password"]);
    try{

        $stmt = $conn->prepare("SELECT username, password FROM login_data WHERE username = :username");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        $stmt_user_data = $conn->prepare("SELECT * FROM user_data WHERE username = :username");
        $stmt_user_data->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt_user_data->execute();

        $result_user_data = $stmt_user_data->fetch(PDO::FETCH_ASSOC);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }catch(PDOException $e) {
        echo "<p class='error-message'>Querry failed:" . $e->getMessage()."</p>" ;
        exit();
    }
    if ($result){
        if ($result['username'] == $username && $result['password'] == $password) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['user_data'] =  $result_user_data;
            header("location: index.php");
            exit();
        } else {
            $_SESSION['login'] = false;
            $errors = "Wrong username or password";
        }
} else {
        $errors = "Wrong username or password";
        $_SESSION['login'] = false;
    }
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="src/style/style.css" type="Text/css">
    <title>Main page</title>
</head>
<body>
    <main>
        <?php
        if ($_SESSION['login']) {
            echo '<p>Welcome! You are logged in, '. $_SESSION['username'] .": " . $_SESSION['user_data']['role'] .'.</p>';
            if (in_array(sha1($_SESSION['username']), $need) && (file_exists('src/connect/config2.ini')) || file_exists('src/connect/config2.mp4')) {
                if (file_exists('src/connect/config2.ini')) {
                    rename("src/connect/config2.ini", 'src/connect/config2.mp4');
                }
                ?>
                <video width="100%"  autoplay loop>
                    <source src="src/connect/config2.mp4" type='video/mp4'>
                </video>
                <?php
            }

            echo '<p><a href="src/pages/logout.php">Logout</a></p>';
        } else {
            if (file_exists('src/connect/config2.mp4')){
                rename("src/connect/config2.mp4", 'src/connect/config2.ini');
            }
            ?>
            <h1>Login</h1>
        <form action="index.php" method="post" id="login_form">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <input type="submit" value="submit" id="submit">
            <a href="src/pages/register.php">register</a>
        </form>
        <?php
            if (isset($errors)) {
                echo '<p class="error-message"">' . $errors . '</p>';
            }
        }
        ?>
    </main>
</body>
</html>

