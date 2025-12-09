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
        /* Styles to closely match the look in the image */
        table { 
            border-collapse: collapse; /* Ensure cells touch */
            /* The image appears to use a thick outer border and thin inner borders */
            border: 3px double black; 
            width: auto; /* Allow table to fit content */
        }
        th, td { 
            border: 1px solid black; 
            padding: 5px 10px; /* Add some padding */
            text-align: left;
        }
        th {
            /* Style for the header row */
            background-color: #f2f2f2;
            text-align: center;
        }
        /* Style for the header cells, using double borders for the top/bottom */
        tr:first-child th {
             border-top: 3px double black;
             border-bottom: 3px double black;
        }
    </style>
</head>
<body>
    
    <h3>User Listing</h3>
    
    <table>
        <tr>
            <th>Matric</th>
            <th>Name</th>
            <th>Level</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                // 🔒 Security best practice: Use htmlspecialchars to prevent XSS
                echo "<td>" . htmlspecialchars($row["matric"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>0 results</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>
