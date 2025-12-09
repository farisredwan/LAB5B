<?php
session_start();
include 'db.php'; // Ensure db.php connects to the database

// Session Check
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Initialize variables for the form
$user = ['matric' => '', 'name' => '', 'role' => ''];
$errorMessage = '';

// --- 2. Handle Update Submission ---
if (isset($_POST['update'])) {
    // Sanitize and validate POST data
    $matric = $_POST['matric'] ?? '';
    $name = $_POST['name'] ?? '';
    $role = $_POST['role'] ?? '';

    // Prepared statement for UPDATE
    $stmt = $conn->prepare("UPDATE users SET name = ?, role = ? WHERE matric = ?");
    if ($stmt === false) {
        $errorMessage = "Database prepare error: " . $conn->error;
    } else {
        // Bind parameters: 'sss' means three string parameters
        $stmt->bind_param("sss", $name, $role, $matric);

        if ($stmt->execute()) {
            // Success: Redirect to display page
            header("Location: display.php");
            exit;
        } else {
            $errorMessage = "Error updating record: " . $stmt->error;
        }
        $stmt->close();
    }
}

// --- 1. Get existing data (using GET or POST after a failed update) ---
// Use POST matric if update failed, otherwise use GET matric
$current_matric = $_GET['matric'] ?? $_POST['matric'] ?? '';

if ($current_matric) {
    // Prepared statement for SELECT
    $stmt = $conn->prepare("SELECT matric, name, role FROM users WHERE matric = ?");
    if ($stmt === false) {
        $errorMessage = "Database prepare error: " . $conn->error;
    } else {
        $stmt->bind_param("s", $current_matric);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
        } else {
            // This is the correct way to handle "no results"
            $errorMessage = "User not found or invalid Matric ID.";
        }
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head><title>Update User</title></head>
<body>
    <h3>Update User</h3>

    <?php if ($errorMessage): ?>
        <p style="color: red; font-weight: bold;">Error: <?php echo htmlspecialchars($errorMessage); ?></p>
        <p><a href="display.php">Return to User List</a></p>
    <?php endif; ?>

    <form action="update.php" method="post">
        <label>Matric:</label>
        <input type="text" name="matric" value="<?php echo htmlspecialchars($user['matric']); ?>" readonly><br>
        
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>
        
        <label>Access Level:</label>
        <input type="text" name="role" value="<?php echo htmlspecialchars($user['role']); ?>" required><br>
        
        <input type="submit" name="update" value="Update">
        <a href="display.php">Cancel</a>
    </form>
</body>
</html>
