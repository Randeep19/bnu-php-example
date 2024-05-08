<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");

// Check if the user is logged in
if (isset($_SESSION['id'])) {
    // Check if students array is set and not empty
    if (!empty($_POST['students'])) {
        // Loop over each student ID
        foreach ($_POST['students'] as $studentId) {
            // Sanitize the student ID to prevent SQL injection
            $sanitizedStudentId = mysqli_real_escape_string($conn, $studentId);
            
            // Build SQL query to delete the student with the given ID
            $sql = "DELETE FROM student WHERE studentid = '$sanitizedStudentId'";
            
            // Run the query
            $result = mysqli_query($conn, $sql);
            
            // Check if the query was successful
            if ($result) {
                echo "Student with ID $studentId deleted successfully.<br>";
            } else {
                echo "Error deleting student with ID $studentId: " . mysqli_error($conn) . "<br>";
                // Handle the error appropriately, maybe log it
            }
        }
    } else {
        echo "No students selected to delete.";
    }

    // Redirect to students.php
    header("Location: students.php");
    exit(); // Ensure that the script stops executing after the redirection
} else {
    // Redirect to index.php if the user is not logged in
    header("Location: index.php");
    exit(); // Ensure that the script stops executing after the redirection
}

?>
