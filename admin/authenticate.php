<?php
include "../inc/db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Store user ID in session
            $_SESSION['user_id'] = $user_id;
            // Redirect to the protected folder
            header("Location: admin.php");
            exit();
        } else {
            $error = "Invalid username or password.";
            header("Location: index.html");
        }
    } else {
        $error = "Invalid username or password.";
        header("Location: index.html");
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form class="login-form" action="authenticate.php" method="POST">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <button type="submit">Login</button>
    </form>
</body>
</html>
