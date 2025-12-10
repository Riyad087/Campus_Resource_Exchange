<?php
require 'db.php';
include 'header.php';

if (empty($_SESSION['user_id'])) {
    echo "<p>You must be logged in to add a product. <a href='login.php'>Login here</a></p>";
    include 'footer.php';
    exit;
}

$title = "";
$description = "";
$category = "";
$item_type = "";
$item_condition = "";
$price = "";
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $item_type = $_POST['item_type'];
    $item_condition = $_POST['item_condition'];
    $price = trim($_POST['price']);

    if ($title == "" || $category == "" || $item_type == "" || $item_condition == "") {
        $error = "Title, category, type and condition are required.";
    } else {
        if ($price == "") {
            $price = 0;
        }

        
        $image_path = "";

    
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $fileTmp  = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileType = $_FILES['image']['type'];

            
            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif'
            ];

            if (array_key_exists($fileType, $allowedTypes)) {
                $ext = $allowedTypes[$fileType];
                $newName = time() . '_' . rand(1000, 9999) . '.' . $ext;
                $uploadDir = 'uploads/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fullPath = $uploadDir . $newName;

                if (move_uploaded_file($fileTmp, $fullPath)) {
                    $image_path = $fullPath;
                } else {
                    $error = "Image upload failed. You can try again.";
                }
            } else {
                $error = "Only JPG, PNG or GIF images are allowed.";
            }
        }

        if ($error == "") {
            $title_safe   = mysqli_real_escape_string($conn, $title);
            $desc_safe    = mysqli_real_escape_string($conn, $description);
            $category_safe = mysqli_real_escape_string($conn, $category);
            $type_safe    = mysqli_real_escape_string($conn, $item_type);
            $cond_safe    = mysqli_real_escape_string($conn, $item_condition);
            $price_val    = floatval($price);
            $user_id      = $_SESSION['user_id'];
            $image_safe   = mysqli_real_escape_string($conn, $image_path);

            $sql = "INSERT INTO items (user_id, title, description, image_path, category, item_type, item_condition, price)
                    VALUES ($user_id, '$title_safe', '$desc_safe', '$image_safe', '$category_safe', '$type_safe', '$cond_safe', $price_val)";
            
            if (mysqli_query($conn, $sql)) {
                $success = "Product added successfully!";
                // reset form fields
                $title = $description = $category = $item_type = $item_condition = $price = "";
            } else {
                $error = "Something went wrong while saving.";
            }
        }
    }
}
?>

<h2>Add New Product</h2>

<?php if ($error != ""): ?>
    <div class="error-box"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success != ""): ?>
    <div class="success-box"><?php echo $success; ?></div>
<?php endif; ?>

<form method="POST" class="item-form" enctype="multipart/form-data">
    <label>Product Title</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

    <label>Description</label>
    <textarea name="description" rows="4"><?php echo htmlspecialchars($description); ?></textarea>

    <label>Product Image (optional)</label>
    <input type="file" name="image" accept="image/*">

    <label>Category</label>
    <select name="category" required>
        <option value="">Select Category</option>
        <option value="Book" <?php if($category=="Book") echo "selected"; ?>>Book</option>
        <option value="Notes" <?php if($category=="Notes") echo "selected"; ?>>Class Notes</option>
        <option value="Electronics" <?php if($category=="Electronics") echo "selected"; ?>>Electronics</option>
        <option value="Other" <?php if($category=="Other") echo "selected"; ?>>Other</option>
    </select>

    <label>Type</label>
    <select name="item_type" required>
        <option value="">Select Type</option>
        <option value="Sell" <?php if($item_type=="Sell") echo "selected"; ?>>For Sell</option>
        <option value="Swap" <?php if($item_type=="Swap") echo "selected"; ?>>For Swap</option>
        <option value="Lend" <?php if($item_type=="Lend") echo "selected"; ?>>For Lend (rent)</option>
    </select>

    <label>Condition</label>
    <select name="item_condition" required>
        <option value="">Select Condition</option>
        <option value="New" <?php if($item_condition=="New") echo "selected"; ?>>New</option>
        <option value="Used" <?php if($item_condition=="Used") echo "selected"; ?>>Used</option>
    </select>

    <label>Price / Rent (৳)</label>
    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($price); ?>">

    <button type="submit">Save</button>
</form>

<?php
include 'footer.php';
?>

