<?php
include '../includes/db_connect.php';
include '../includes/header.php';
?>
<h3>Manage Course</h3>

<h4>Add Course</h4>

<form action="" method="post">
    <input type="text" name="course_number" placeholder="Course Number" required>
    <input type="text" name="course_title" placeholder="Course Title" required>
    <!--<input type="text" name="instructor_id" placeholder="Instructor ID" required>-->
    <select name="instructor_id">
            <option value="">Select Instructor</option>
            <?php
            // Fetch instructor to populate dropdown
                $sql = "SELECT * FROM instructors";
                $result = $conn->query($sql);

                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['instructor_id'] . "'>
                    " . $row['name'] . "</option>";
                }
            ?>
    </select>
    <input type="text" name="dept_name" placeholder="Department Name" required>
    <button type="submit">Add Course</button>
</form>

<?php
// Add Course Logic
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $courseNum = $_POST['course_number'];
        $courseTitle = $_POST['course_title'];
        $instructor_id = $_POST['instructor_id'];
        $dept_name = $_POST['dept_name'];

        $sql = "INSERT INTO courses (course_number, course_title,
                instructor_id, department_name) VALUES ('$courseNum',
                '$courseTitle', '$instructor_id', '$dept_name')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Course added successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>

<br><hr>

<h4>List</h4>

<table border="1">
    <tr>
        <th>Course Number</th>
        <th>Course Title</th>
        <th>Instructor</th>
        <th>Department Name</th>
        <th>Actions</th>
    </tr>

    <?php
        // Fetch Course Data
        $sql = "SELECT c.course_number, c.course_title, i.name AS instructor_id,
                c.department_name
                FROM courses c
                JOIN instructors i ON c.instructor_id = i.instructor_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                    <tr>
                        <td>" . $row['course_number'] . "</td>
                        <td>" . $row['course_title'] . "</td>
                        <td>" . $row['instructor_id'] . "</td>
                        <td>" . $row['department_name'] . "</td>
                        <td>
                            <a href='?type=crs&edit={$row['course_number']}'>Edit</a>
                            <a href='?type=crs&delete={$row['course_number']}'>Delete</a>
                        </td>
                    </tr>
                ";
            }
        } else {
            echo "<tr><td colspan='5'>No Course found</td></tr>";
        }
    ?>
</table>