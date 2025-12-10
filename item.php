<?php
require 'db.php';
include 'header.php';

if (!isset($_GET['id'])) {
    echo "<p>Invalid item.</p>";
    include 'footer.php';
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT items.*, 
               users.name AS owner_name, 
               users.email AS owner_email,
               users.facebook_url AS owner_facebook
        FROM items 
        JOIN users ON items.user_id = users.id
        WHERE items.id = $id
        LIMIT 1";

$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) == 0) {
    echo "<p>Item not found.</p>";
    include 'footer.php';
    exit;
}

$item = mysqli_fetch_assoc($res);
?>

<h2><?php echo htmlspecialchars($item['title']); ?></h2>

<div class="item-details">
    <?php if (!empty($item['image_path'])): ?>
        <div class="item-image-wrapper">
            <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Product Image" class="item-image-large">
        </div>
    <?php endif; ?>

    <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
    <p><strong>Type:</strong> <?php echo htmlspecialchars($item['item_type']); ?></p>
    <p><strong>Condition:</strong> <?php echo htmlspecialchars($item['item_condition']); ?></p>
    <p><strong>
        <?php echo ($item['item_type'] == 'Lend') ? "Rent:" : "Price:"; ?>
    </strong> <?php echo number_format($item['price'], 2); ?> ৳</p>

    <p><strong>Description:</strong><br>
        <?php echo nl2br(htmlspecialchars($item['description'])); ?>
    </p>

    <hr>

    <h3>Contact Owner</h3>
    <p><strong>Name:</strong> <?php echo htmlspecialchars($item['owner_name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($item['owner_email']); ?></p>

    <p><strong>Facebook:</strong>
        <?php if (!empty($item['owner_facebook'])): ?>
            <a href="<?php echo htmlspecialchars($item['owner_facebook']); ?>" target="_blank">View Facebook Profile</a>
        <?php else: ?>
            <span>Not provided</span>
        <?php endif; ?>
    </p>

    <p class="small-note">(You can contact via university email or Facebook to meet on campus and complete the exchange.)</p>
</div>

<?php
include 'footer.php';
?>
