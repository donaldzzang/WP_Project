<?php
session_start();
$db = new mysqli('localhost', 'root', '', 'book_platform');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $db->real_escape_string($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM users WHERE username='$username'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $message = "Username already exists!";
        $message_class = 'error';
    } else {
        $query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($db->query($query)) {
            $_SESSION['userid'] = $db->insert_id;
            header('Location: profile.php');
            exit();
        } else {
            $message = "Registration failed: " . $db->error;
            $message_class = 'error';
        }
    }
}

include 'header.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <?php if (isset($message)) : ?>
            <p class="message <?= $message_class ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        <form method="post" action="register.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Register">
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>

<?php include 'footer.php'; ?>
