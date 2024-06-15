<?php
include 'header.php';
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$db = new mysqli('localhost', 'root', '', 'book_platform');
$user_id = $_SESSION['userid'];

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $db->real_escape_string($_POST['first_name']);
    $last_name = $db->real_escape_string($_POST['last_name']);
    $bio = $db->real_escape_string($_POST['bio']);

    $query = "REPLACE INTO profiles (user_id, first_name, last_name, bio) VALUES ('$user_id', '$first_name', '$last_name', '$bio')";
    if ($db->query($query)) {
        $message = "Profile updated successfully!";
        $message_class = 'success';
        header('Location: index.php');
        exit();
    } else {
        $message = "Profile update failed: " . $db->error;
        $message_class = 'error';
    }
}

$query = "SELECT * FROM profiles WHERE user_id='$user_id'";
$result = $db->query($query);
$profile = $result->fetch_assoc();
?>

<div class="container">
    <h1>Profile</h1>
    <?php if (isset($message)) : ?>
        <p class="message <?= $message_class ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="post" action="profile.php">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?= htmlspecialchars($profile['first_name']) ?>">
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($profile['last_name']) ?>">
        <label for="bio">Bio:</label>
        <textarea id="bio" name="bio"><?= htmlspecialchars($profile['bio']) ?></textarea>
        <input type="submit" value="Update Profile">
    </form>
</div>

<?php
include 'footer.php';
?>
