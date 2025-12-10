<?php
require 'db.php';
include 'header.php';

if (empty($_SESSION['user_id'])) {
    echo "<p>You must be logged in to see your products. <a href='login.php'>Login here</a></p>";
    include 'footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

$message = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == "deleted") {
        $message = "Item deleted successfully.";
    } elseif ($_GET['msg'] == "updated") {
        $message = "Item updated successfully.";
    }
}

$sql = "SELECT * FROM items WHERE user_id=$user_id ORDER BY created_at DESC";
$res = mysqli_query($conn, $sql);
?>

<h2>My Products</h2>

<?php if ($message != ""): ?>
    <div class="success-box"><?php echo $message; ?></div>
<?php endif; ?>

<div class="items-grid">
    <?php if ($res && mysqli_num_rows($res) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($res)): ?>
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

                <a class="btn-view" href="item.php?id=<?php echo $row['id']; ?>">View</a>

                <a class="btn-view" href="edit_item.php?id=<?php echo $row['id']; ?>">
                    Edit
                </a>

                <a
                    class="btn-view"
                    href="delete_item.php?id=<?php echo $row['id']; ?>"
                    onclick="return confirm('Are you sure you want to delete this item?');"
                >
                    Delete
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>You have not added any products yet.</p>
    <?php endif; ?>
</div>

<?php
include 'footer.php';
?>
