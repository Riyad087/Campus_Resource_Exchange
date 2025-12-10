<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Campus Resource Exchange</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
<header class="main-header">
    <div class="logo">
        <a href="index.php">Campus Resource Exchange</a>
    </div>
    <nav class="nav-links">
        <a href="index.php">Home</a>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="add_item.php">Add Product</a>
            <a href="my_items.php">My Products</a>
            <span class="welcome-text">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="logout.php" class="btn-logout">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>
<main class="container">
