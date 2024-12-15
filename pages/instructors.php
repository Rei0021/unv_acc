<h3>Manage Instructor</h3>

<h4>Add Instructor</h4>

<form action="" method="post">
    <input type="text" name="name" placeholder="Name" required>
    <input type="text" name="phone_no" placeholder="Phone Number" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="instructor_room" placeholder="Instructor Room" required>
    <button type="submit">Add Instructor</button>
</form>

<?php
// Add Instructor Logic
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $phone_no = $_POST['phone_no'];
        $email = $_POST['email'];
        $instructor_room = $_POST['instructor_room'];

        $sql = "INSERT INTO instructors (name, phone_no, email, instructor_room)
                VALUES ('$name', '$phone_no', '$email', '$instructor_room')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Instructor added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>

<br><hr>

<h4>List</h4>

<table border="1">
    <tr>
        <th>Instructor ID</th>
        <th>Name</th>
        <th>Phone Number</th>
        <th>Email</th>
        <th>Instructor's Room</th>
        <th>Actions</th>
    </tr>

    <?php
        // Fetch Instructor Data
        $sql = "SELECT * FROM instructors";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                    <tr>
                        <td>" . $row['instructor_id'] . "</td>
                        <td>" . $row['name'] . "</td>
                        <td>" . $row['phone_no'] . "</td>
                        <td>" . $row['email'] . "</td>
                        <td>" . $row['instructor_room'] . "</td>
                        <td>
                            <a href='?type=nstrctr&edit={$row['instructor_id']}'>Edit</a>
                            <a href='?type=nstrctr&delete={$row['instructor_id']}'>Delete</a>
                        </td>
                    </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='6'>No Instructor found</td></tr>";
        }
    ?>
</table>