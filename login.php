<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE matric='$matric'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['matric'] = $row['matric'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role']; // Maps to Access Level
            header("Location: display.php");
            exit;
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Invalid username or password";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
    <form action="login.php" method="post">
        <label>Matric:</label>
        <input type="text" name="matric" required><br>
        
        <label>Password:</label>
        <input type="password" name="password" required><br>
        
        <input type="submit" name="login" value="Login">
    </form>
    
    <p><a href="register.php">Register</a> here if you have not.</p>

    <?php if(isset($error)) { echo "<p style='color:red;'>$error, try <a href='login.php'>login</a> again.</p>"; } ?>
</body>
</html>
