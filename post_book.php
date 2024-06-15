<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header('Location: login.php');
    exit();
}

$db = new mysqli('localhost', 'root', '', 'book_platform');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $db->real_escape_string($_POST['title']);
    $author = $db->real_escape_string($_POST['author']);
    $description = $db->real_escape_string($_POST['description']);
    $user_id = $_SESSION['userid'];

    $query = "INSERT INTO books (user_id, title, author, description) VALUES ('$user_id', '$title', '$author', '$description')";
    if ($db->query($query)) {
        $message = "Book posted successfully!";
        $message_class = 'success';
    } else {
        $message = "Failed to post book: " . $db->error;
        $message_class = 'error';
    }
}
include 'header.php';
?>

<div class="container">
    <h1>Post a Book</h1>
    <form method="post" action="post_book.php">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows = "8" required></textarea>
        <input type="submit" value="Post Book">
    </form>
    <?php if (isset($message)) : ?>
        <p class="message <?= $message_class ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
?>
