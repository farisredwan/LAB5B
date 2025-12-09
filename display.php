<?php
session_start();
include 'db.php';

// Session Check 
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT matric, name, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
    </style>
</head>
<body>
    <table>
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Level</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["matric"] . "</td>";
                echo "<td>" . $row["name"] . "</td>";
                echo "<td>" . $row["role"] . "</td>";
                // Update and Delete links
                echo "<td><a href='update.php?matric=" . $row["matric"] . "'>Update</a> | <a href='delete.php?matric=" . $row["matric"] . "'>Delete</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>0 results</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
