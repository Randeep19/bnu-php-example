<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");

// Check if the user is logged in
if (isset($_SESSION['id'])) {
    // Include header and navigation
    echo template("templates/partials/header.php");
    echo template("templates/partials/nav.php");

    // Build SQL statement to select all students
    $sql = "SELECT * FROM student;";
    $result = mysqli_query($conn, $sql);

    // Check for errors in the query
    if (!$result) {
        // Handle the error appropriately (e.g., display an error message)
        die("Error: " . mysqli_error($conn));
    }

    // Display form for selecting students to delete
    $data['content'] = "<form action='deletestudents.php' method='POST'>";
    $data['content'] .= "<div class='table-responsive'>";
    $data['content'] .= "<table class='table table-bordered'>";
    $data['content'] .= "<thead class='thead-dark'>";
    $data['content'] .= "<tr><th>Student ID</th><th>Password</th><th>First Name</th><th>Last Name</th><th>DOB</th>
        <th>Town</th><th>County</th><th>Country</th><th>Postcode</th><th>Image</th><th>Select</th></tr>";
    $data['content'] .= "</thead>";
    $data['content'] .= "<tbody>";

    // Display the students within the HTML table
    while ($row = mysqli_fetch_array($result)) {
        $data['content'] .= "<tr>";
        $data['content'] .= "<td>" . $row['studentid'] . "</td>";
        $data['content'] .= "<td>" . $row['password'] . "</td>";
        $data['content'] .= "<td>" . $row['firstname'] . "</td>";
        $data['content'] .= "<td>" . $row['lastname'] . "</td>";
        $data['content'] .= "<td>" . $row['dob'] . "</td>";
        $data['content'] .= "<td>" . $row['town'] . "</td>";
        $data['content'] .= "<td>" . $row['county'] . "</td>";
        $data['content'] .= "<td>" . $row['country'] . "</td>";
        $data['content'] .= "<td>" . $row['postcode'] . "</td>";

        if (!empty($row["image"])) {
            $data['content'] .= "<td><img src='{$row["image"]}' width='100'></td>";
        } else {
            $data['content'] .= "<td>No Image</td>";
        }

        $data['content'] .= "<td><input type='checkbox' name='students[]' value='" . $row['studentid'] . "' /></td>";
        $data['content'] .= "</tr>";
    }

    $data['content'] .= "</tbody>";
    $data['content'] .= "</table>";
    $data['content'] .= "</div>";

    // Add a delete button only if at least one student is selected
    if (mysqli_num_rows($result) > 0) {
        $data['content'] .= "<input type='submit' name='deletebtn' value='Delete' />";
    } else {
        $data['content'] .= "<p>No students available for deletion.</p>";
    }

    $data['content'] .= "</form>";

    // Add Student Button
    echo "<a href='addstudent.php'><button type='button'>Add Student</button></a>";

    // Render the template
    echo template("templates/default.php", $data);
} else {
    // Redirect to index page if the user is not logged in
    header("Location: index.php");
}

// Include footer
echo template("templates/partials/footer.php");

?>
