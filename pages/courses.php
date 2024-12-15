<?php
include '../includes/db_connect.php';

// Add Course Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_course'])) {
    $courseNum = $_POST['course_number'];
    $courseTitle = $_POST['course_title'];
    $instructor_id = $_POST['instructor_id'];
    $dept_name = $_POST['dept_name'];

    $sql = "INSERT INTO courses (course_number, course_title, instructor_id, department_name) 
            VALUES ('$courseNum', '$courseTitle', '$instructor_id', '$dept_name')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect after adding the course
        header("Location: course_related.php?type=crs");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Edit Course Logic
if (isset($_GET['edit'])) {
    $course_number = $_GET['edit'];

    // Fetch the course data to populate the form for editing
    $sql = "SELECT * FROM courses WHERE course_number = '$course_number'";
    $result = $conn->query($sql);
    $course = $result->fetch_assoc();
}

// Update Course Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_course'])) {
    $courseNum = $_POST['course_number'];
    $courseTitle = $_POST['course_title'];
    $instructor_id = $_POST['instructor_id'];
    $dept_name = $_POST['dept_name'];

    $sql = "UPDATE courses 
            SET course_title = '$courseTitle', instructor_id = '$instructor_id', department_name = '$dept_name' 
            WHERE course_number = '$courseNum'";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect after updating the course
        header("Location: course_related.php?type=crs");
        exit();
    } else {
        echo "Error updating course: " . $conn->error;
    }
}

// Delete Course Logic
if (isset($_GET['delete'])) {
    $course_number = $_GET['delete'];
    $sql = "DELETE FROM courses WHERE course_number = '$course_number'";

    if ($conn->query($sql) === TRUE) {
        // Redirect after deleting the course
        header("Location: course_related.php?type=crs");
        exit();
    } else {
        echo "Error deleting course: " . $conn->error;
    }
}
?>

<h3>Manage Course</h3>

<h4>Add Course</h4>

<form action="" method="post">
    <input type="text" name="course_number" placeholder="Course Number" value="<?php echo isset($course) ? $course['course_number'] : ''; ?>" required>
    <input type="text" name="course_title" placeholder="Course Title" value="<?php echo isset($course) ? $course['course_title'] : ''; ?>" required>

    <select name="instructor_id">
        <option value="">Select Instructor</option>
        <?php
        // Fetch instructors to populate dropdown
        $sql = "SELECT * FROM instructors";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $selected = (isset($course) && $course['instructor_id'] == $row['instructor_id']) ? 'selected' : '';
            echo "<option value='" . $row['instructor_id'] . "' $selected>" . $row['name'] . "</option>";
        }
        ?>
    </select>

    <input type="text" name="dept_name" placeholder="Department Name" value="<?php echo isset($course) ? $course['department_name'] : ''; ?>" required>

    <?php if (isset($course)): ?>
        <button type="submit" name="edit_course">Update Course</button>
    <?php else: ?>
        <button type="submit" name="add_course">Add Course</button>
    <?php endif; ?>
</form>

<br><hr>

<h4>List of Courses</h4>

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
    $sql = "SELECT c.course_number, c.course_title, i.name AS instructor_id, c.department_name
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
        echo "<tr><td colspan='5'>No courses found</td></tr>";
    }
    ?>
</table>
