<?php
include 'db.php';

if (isset($_POST['submit'])) {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure hashing
    $role = $_POST['role'];

    $sql = "INSERT INTO users (matric, name, password, role) VALUES ('$matric', '$name', '$password', '$role')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head><title>Registration</title></head>
<body>
    <form action="register.php" method="post">
        <label>Matric:</label>
        <input type="text" name="matric" required><br>
        
        <label>Name:</label>
        <input type="text" name="name" required><br>
        
        <label>Password:</label>
        <input type="password" name="password" required><br>
        
        <label>Role:</label>
        <select name="role" required>
            <option value="">Please select</option>
            <option value="student">Student</option>
            <option value="lecturer">Lecturer</option>
        </select><br>
        
        <input type="submit" name="submit" value="Submit">
    </form>
</body>
</html>
