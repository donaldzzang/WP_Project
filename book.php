<?php
session_start();

if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}

$db = mysqli_connect('localhost', 'root', '', 'book_platform') or die('Unable to connect. Check your connection parameters.');

$book_id = $_GET['id'];
$user_id = $_SESSION['userid'];

$query = "SELECT books.*, users.username FROM books JOIN users ON books.user_id = users.id WHERE books.id = $book_id";
$result = mysqli_fetch_assoc(mysqli_query($db, $query));
$book = $result;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["comment"])) {
        $comment = $_POST["comment"];
        $query = "INSERT INTO comments (book_id, user_id, comment, comment_date) VALUES ('$book_id', '$user_id', '$comment', NOW())";
        mysqli_query($db, $query);
    } elseif (isset($_POST["like"])) {
        $like_check_query = "SELECT * FROM likes WHERE user_id = $user_id AND book_id = $book_id";
        $like_check_result = mysqli_query($db, $like_check_query);

        if (mysqli_num_rows($like_check_result) > 0) {
            $message = "You can only like a book once.";
            $message_class = 'error';
        } elseif ($user_id == $book['user_id']) {
            $message = "You cannot like your own post.";
            $message_class = 'error';
        } else {
            $query = "UPDATE books SET likes = likes + 1 WHERE id = $book_id";
            mysqli_query($db, $query);

            $query = "INSERT INTO likes (user_id, book_id) VALUES ('$user_id', '$book_id')";
            mysqli_query($db, $query);

            header("Location: book.php?id=$book_id");
            exit();
        }
    }
}

$query = "SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE book_id = $book_id";
$comments = mysqli_query($db, $query);

include 'header.php';
?>

<div class="container">
    <h1><?= htmlspecialchars($book['title']) ?></h1>
    <div class="book-details">
        <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
        <p><strong>Posted by:</strong> <?= htmlspecialchars($book['username']) ?> on <?= htmlspecialchars($book['post_date']) ?></p>
        <div class="description-box">
            <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
        </div>
        <?php if (isset($message)) : ?>
            <p class="message <?= $message_class ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>
    <h2>Comments</h2>
    <form method="post" action="book.php?id=<?= htmlspecialchars($book_id) ?>" class="comment-form">
        <textarea name="comment" required></textarea>
        <input type="submit" value="Add Comment">
    </form>
    <div id="comments" class="comments-box">
        <?php while ($comment = mysqli_fetch_assoc($comments)) : ?>
            <div class="comment">
                <p><strong><?= htmlspecialchars($comment['username']) ?></strong> on <?= htmlspecialchars($comment['comment_date']) ?></p>
                <p><?= nl2br(htmlspecialchars($comment['comment'])) ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
