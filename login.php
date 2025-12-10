<?php
require 'db.php';
include 'header.php';

$email = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);

    if ($email == "" || $pass == "") {
        $error = "Email and Password required.";
    } else {
        $email_safe = mysqli_real_escape_string($conn, $email);
        $sql = "SELECT * FROM users WHERE email='$email_safe' LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) == 1) {
            $user = mysqli_fetch_assoc($res);
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header("Location: index.php");
                exit;
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<h2>Login</h2>

<?php if ($error != ""): ?>
    <div class="error-box"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" class="auth-form">
    <label>Email</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>
    <p>New here? <a href="register.php">Create an account</a></p>
</form>

<?php
include 'footer.php';
?>
