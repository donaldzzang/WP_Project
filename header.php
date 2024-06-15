<?php
// 세션을 시작하여 로그인 상태를 확인
session_start();
$logged_in = isset($_SESSION['userid']);
?>
<!DOCTYPE html>
<html?>
<head>
    <title>Book Platform</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">
            <!-- 로고 이미지 삽입 -->
            <img src="https://cdn-icons-png.flaticon.com/512/5833/5833263.png" alt="Book Platform Logo">
        </div>
        <nav>
            <ul>
                <!-- 네비게이션 메뉴 -->
                <li><a href="index.php">Home</a></li>
                <li><a href="search.php">Search Books</a></li>
                <li><a href="post_book.php">Post Book</a></li>
                <?php if ($logged_in) : ?>
                    <!-- 로그인 상태일 때 표시 -->
                    <li><a href="profile.php">Edit Profile</a></li>
                    <li><a href="logout.php" class="logout-button">Logout</a></li>
                <?php else : ?>
                    <!-- 비로그인 상태일 때 표시 -->
                    <li><a href="login.php" class="login-button">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
</body>
</html>
