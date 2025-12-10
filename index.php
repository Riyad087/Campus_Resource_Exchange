<?php
require 'db.php';
include 'header.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$item_type = isset($_GET['item_type']) ? $_GET['item_type'] : '';

$sql = "SELECT items.*, users.name AS owner_name 
        FROM items 
        JOIN users ON items.user_id = users.id 
        WHERE 1";

if ($category != '') {
    $category_safe = mysqli_real_escape_string($conn, $category);
    $sql .= " AND category = '$category_safe'";
}
if ($item_type != '') {
    $type_safe = mysqli_real_escape_string($conn, $item_type);
    $sql .= " AND item_type = '$type_safe'";
}

$sql .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $sql);
?>
<h1>Campus Resource Exchange</h1>
<p>Buy, sell, swap or lend books, notes and electronics inside your campus.</p>

<form method="GET" class="filter-form">
    <select name="category">
        <option value="">All Categories</option>
        <option value="Book" <?php if($category=="Book") echo "selected"; ?>>Books</option>
        <option value="Notes" <?php if($category=="Notes") echo "selected"; ?>>Class Notes</option>
        <option value="Electronics" <?php if($category=="Electronics") echo "selected"; ?>>Electronics</option>
        <option value="Other" <?php if($category=="Other") echo "selected"; ?>>Other</option>
    </select>

    <select name="item_type">
        <option value="">All Types</option>
        <option value="Sell" <?php if($item_type=="Sell") echo "selected"; ?>>For Sell</option>
        <option value="Swap" <?php if($item_type=="Swap") echo "selected"; ?>>For Swap</option>
        <option value="Lend" <?php if($item_type=="Lend") echo "selected"; ?>>For Lend</option>
    </select>

    <button type="submit">Filter</button>
</form>

<div class="items-grid">
    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="item-card">
                <?php if (!empty($row['image_path'])): ?>
                    <div class="thumb-wrapper">
                        <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Product Image" class="item-thumb">
                    </div>
                <?php endif; ?>

                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p class="item-meta">
                    Category: <?php echo htmlspecialchars($row['category']); ?> |
                    Type: <?php echo htmlspecialchars($row['item_type']); ?> |
                    Condition: <?php echo htmlspecialchars($row['item_condition']); ?>
                </p>
                <p class="item-price">
                    <?php
                    if ($row['item_type'] == 'Lend') {
                        echo "Rent: " . number_format($row['price'], 2) . " ৳";
                    } else {
                        echo "Price: " . number_format($row['price'], 2) . " ৳";
                    }
                    ?>
                </p>
                <p class="item-owner">Posted by: <?php echo htmlspecialchars($row['owner_name']); ?></p>
                <a class="btn-view" href="item.php?id=<?php echo $row['id']; ?>">View Details</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No items found. Be the first to post something!</p>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
?>
