<?php
// Start output buffering
ob_start();

include '../includes/db_connect.php';

// Add Hall Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_hall'])) {
    $name = $_POST['name'] ?? null;
    $address = $_POST['address'] ?? null;
    $telephone = $_POST['telephone'] ?? null;
    $manager_name = $_POST['manager_name'] ?? null;

    if ($name && $address && $telephone && $manager_name) {
        $sql = "INSERT INTO halls_of_residence (name, address, telephone, manager_name) 
                VALUES ('$name', '$address', '$telephone', '$manager_name')";

        if ($conn->query($sql) === TRUE) {
            header("Location: accommodation.php?type=hall");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Please fill in all required fields.";
    }
}

// Delete Hall Logic
if (isset($_GET['delete'])) {
    $hall_id = $_GET['delete'];
    $sql = "DELETE FROM halls_of_residence WHERE hall_id = $hall_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: accommodation.php?type=hall");
        exit();
    } else {
        echo "Error deleting hall: " . $conn->error;
    }
}

// Fetch existing hall data for editing
if (isset($_GET['edit'])) {
    $hall_id = $_GET['edit'];
    $sql = "SELECT * FROM halls_of_residence WHERE hall_id = $hall_id";
    $result = $conn->query($sql);
    $hall = $result->fetch_assoc();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_hall'])) {
        $name = $_POST['name'];
        $address = $_POST['address'];
        $telephone = $_POST['telephone'];
        $manager_name = $_POST['manager_name'];

        $updateSql = "UPDATE halls_of_residence SET 
                      name = '$name', address = '$address', telephone = '$telephone', manager_name = '$manager_name' 
                      WHERE hall_id = $hall_id";

        if ($conn->query($updateSql) === TRUE) {
            header("Location: accommodation.php?type=hall");
            exit();
        } else {
            echo "Error updating hall: " . $conn->error;
        }
    }
}

ob_end_flush(); // Send output to browser
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Halls</title>
    <style>
        .h4-header {
            text-align: center;
        }

table {
    margin: 0 auto; 
    border-collapse: collapse; 
}

table {
    width: 80%;
    margin: 20px auto; 
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #34495e;
    color: white;
}

tr:hover {
    background-color: #f1f1f1;
}

.ml-container{
    text-align: center;

}

.form-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 15px; /* Even spacing between elements */
    width: 90%;
    max-width: 400px;
    margin: auto; /* Center horizontally */
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9fc;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
}

/* Style the title */
.form-container h3 {
    margin: 0;
    color: #34495e;
    text-align: center;
}

/* Style the input, select, and textarea */
.form-container input,
.form-container select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

/* Style the button */
.form-container button {
    width: 100%;
    padding: 10px;
    background-color: #f1485b;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
}

/* Button hover effect */
.form-container button:hover {
    background-color: white;
    color: #f1485b;
    border: 1px solid #f1485b;
}
    </style>
</head>
<body>

<h3>Manage Halls of Residence</h3>

<div class="form-container">
    <!-- Add Hall Form -->
    <form action="" method="post">
        <h4>Add Hall</h4>
        <input type="text" name="name" placeholder="Hall Name" required>
        <input type="text" name="address" placeholder="Address" required>
        <input type="text" name="telephone" placeholder="Telephone" required>
        <input type="text" name="manager_name" placeholder="Manager Name" required>
        <button type="submit" name="add_hall">Add Hall</button>
    </form>
</div>


<br><hr>

<h4 class="h4-header">List of Halls</h4>
<table>
    <tr>
        <th>Hall ID</th>
        <th>Name</th>
        <th>Address</th>
        <th>Phone Number</th>
        <th>Manager Name</th>
        <th>Actions</th>
    </tr>
    <?php
    $sql = "SELECT * FROM halls_of_residence";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>" . $row['hall_id'] . "</td>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['address'] . "</td>
                    <td>" . $row['telephone'] . "</td>
                    <td>" . $row['manager_name'] . "</td>
                    <td>
                        <a href='?edit=" . $row['hall_id'] . "'>Edit</a>
                        <a href='?delete=" . $row['hall_id'] . "'>Delete</a>
                    </td>
                </tr>
            ";
        }
    } else {
        echo "<tr><td colspan='6'>No halls found</td></tr>";
    }
    ?>
</table>

<?php if (isset($hall)): ?>
    <div class="form-container">
    <!-- Edit Hall Form -->
    <h4>Edit Hall</h4>
    <form action="" method="post">
        <input type="text" name="name" value="<?php echo $hall['name']; ?>" required>
        <input type="text" name="address" value="<?php echo $hall['address']; ?>" required>
        <input type="text" name="telephone" value="<?php echo $hall['telephone']; ?>" required>
        <input type="text" name="manager_name" value="<?php echo $hall['manager_name']; ?>" required>
        <button type="submit" name="edit_hall">Update Hall</button>
    </form>
</div>
<?php endif; ?>

</body>
</html>
