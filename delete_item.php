<?php
require 'db.php';
session_start();

if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: my_items.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$item_id = intval($_GET['id']);

$sql = "SELECT user_id FROM items WHERE id = $item_id LIMIT 1";
$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {

    header("Location: my_items.php");
    exit;
}

$row = mysqli_fetch_assoc($res);

if ($row['user_id'] != $user_id) {
  
    header("Location: my_items.php");
    exit;
}


$delSql = "DELETE FROM items WHERE id = $item_id";
mysqli_query($conn, $delSql);


header("Location: my_items.php?msg=deleted");
exit;
