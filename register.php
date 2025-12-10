<?php
require 'db.php';
include 'header.php';

$name = "";
$email = "";
$facebook = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $facebook = trim($_POST['facebook']);
    $pass     = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if ($name == "" || $email == "" || $pass == "" || $confirm == "") {
        $error = "All fields are required.";
    } elseif ($pass !== $confirm) {
        $error = "Password and Confirm Password must match.";
    } else {
        $name_safe     = mysqli_real_escape_string($conn, $name);
        $email_safe    = mysqli_real_escape_string($conn, $email);
        $facebook_safe = mysqli_real_escape_string($conn, $facebook);
        $pass_hash     = password_hash($pass, PASSWORD_DEFAULT);

        $checkSql = "SELECT id FROM users WHERE email='$email_safe'";
        $checkRes = mysqli_query($conn, $checkSql);

        if ($checkRes && mysqli_num_rows($checkRes) > 0) {
            $error = "This email is already registered.";
        } else {
            $sql = "INSERT INTO users (name, email, facebook_url, password) 
                    VALUES ('$name_safe', '$email_safe', '$facebook_safe', '$pass_hash')";
            if (mysqli_query($conn, $sql)) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Something went wrong. Try again.";
            }
        }
    }
}
?>

<h2>Create an Account</h2>

<?php if ($error != ""): ?>
    <div class="error-box"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="auth-form">
    <label>Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

    <label>Email (use your university email)</label>
    <input
        type="email"
        name="email"
        value="<?php echo htmlspecialchars($email); ?>"
        placeholder="example@diu.edu.bd"
        required
    >

    <label>Facebook Profile Link (optional)</label>
    <input
        type="url"
        name="facebook"
        value="<?php echo htmlspecialchars($facebook); ?>"
        placeholder="https://www.facebook.com/your.profile"
    >

    <label>Password</label>
    <input type="password" name="password" required>

    <label>Confirm Password</label>
    <input type="password" name="confirm_password" required>

    <button type="submit">Sign Up</button>
    <p>Already have an account? <a href="login.php">Login</a></p>
</form>

<?php
include 'footer.php';
?>
