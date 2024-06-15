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

$query = "SELECT * FROM books ORDER BY post_date DESC LIMIT 6";
$result = $db->query($query);

include 'header.php';
?>

<div class="container">
    <h1>Recently Posted Books</h1>
    <div class="book-list">
        <?php while ($book = $result->fetch_assoc()) : ?>
            <div class="book-item">
                <h2><?= htmlspecialchars($book['title']) ?></h2>
                <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <a href="book.php?id=<?= $book['id'] ?>" class="button">Read More</a>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
