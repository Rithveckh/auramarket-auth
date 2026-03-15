<?php
session_start();

if(isset($_SESSION["user"])){
    header("Location: portal.php");
    exit();
}

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $username = $_POST["username"];
    $password = $_POST["password"];

    if($username == "admin" && $password == "admin123"){

        $_SESSION["user"] = $username;

        setcookie("user",$username,time()+3600);

        header("Location: portal.php");
        exit();

    } else {
        $error = "Invalid login";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>AuraMarket Login</title>
</head>

<body>

<h2>AuraMarket Clerk Login</h2>

<form method="POST">

Username:
<input type="text" name="username" required>

<br><br>

Password:
<input type="password" name="password" required>

<br><br>

<button type="submit">Login</button>

</form>

<p style="color:red"><?php echo $error ?></p>

</body>
</html>