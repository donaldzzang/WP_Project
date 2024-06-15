<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'book_platform');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $db->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['userid'] = $user['id'];
            header('Location: profile.php');
            exit();
        } else {
            $message = "Invalid password!";
            $message_class = 'error';
        }
    } else {
        $message = "No user found!";
        $message_class = 'error';
    }
}

include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <?php if (isset($message)) : ?>
            <p class="message <?= $message_class ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
