<?php

include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");

// Check if the user is logged in
if (isset($_SESSION['id'])) {

    // Include header and navigation
    echo template("templates/partials/header.php");
    echo template("templates/partials/nav.php");

    // Initialize variable to hold form content and error messages
    $data['content'] = '';
    $errors = [];

    // Check if the form has been submitted
    if (isset($_POST['submit'])) {
        // Perform input validation
        
        // Validate first name and last name 
        if (empty($_POST['firstname']) || empty($_POST['lastname'])) {
            $errors[] = "First name and last name are required.";
        }
        
        // Check if a file is uploaded
        if (!empty($_FILES['image']['name'])) {
            // File upload handling
            $targetDirectory = "uploads/"; // Directory where uploaded files will be stored
            $targetFile = $targetDirectory . basename($_FILES['image']['name']);
            $uploadOk = true;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if the file is an image
            $check = getimagesize($_FILES['image']['tmp_name']);
            if ($check === false) {
                $errors[] = "File is not an image.";
                $uploadOk = false;
            }

            // Check file size
            if ($_FILES['image']['size'] > 500000) { // 500 KB
                $errors[] = "File is too large.";
                $uploadOk = false;
            }

            // Allow only certain file formats
            $allowedFormats = ["jpg", "jpeg", "png", "gif"];
            if (!in_array($imageFileType, $allowedFormats)) {
                $errors[] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
                $uploadOk = false;
            }

            // Check if $uploadOk is set to false by an error
            if ($uploadOk) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['content'] .= "<p>The file " . htmlspecialchars(basename($_FILES['image']['name'])) . " has been uploaded.</p>";
                } else {
                    $errors[] = "Error uploading file.";
                }
            }
        }

        if (empty($errors)) {
            // Prepare and bind parameters to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO student (studentid, password, firstname, lastname, dob, town, county, country, postcode, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssss", $_POST['studentid'], $_POST['password'], $_POST['firstname'], $_POST['lastname'], $_POST['dob'], $_POST['town'], $_POST['county'], $_POST['country'], $_POST['postcode'], $targetFile);

            // Execute the prepared statement
            if ($stmt->execute()) {
                $data['content'] .= "<p>New student added successfully.</p>";
            } else {
                $data['content'] .= "<p>Error adding new student: " . $stmt->error . "</p>";
            }

            // Close statement
            $stmt->close();
        } else {
            // Display error messages
            foreach ($errors as $error) {
                $data['content'] .= "<p>$error</p>";
            }
        }
    } else {
        // Display the form if it hasn't been submitted yet
        $data['content'] .= <<<EOD
            <h2>Add New Student</h2>
            <form name="frmdetails" action="" method="post" enctype="multipart/form-data">
            studentid: <input name="studentid" type="text" /><br/>
            password: <input name="password" type="text" /><br/>
                First Name: <input name="firstname" type="text" required /><br/>
                Surname: <input name="lastname" type="text" required /><br/>
                DOB: <input name="dob" type="text" /><br/>
                Town: <input name="town" type="text" /><br/>
                County: <input name="county" type="text" /><br/>
                Country: <input name="country" type="text" /><br/>
                Postcode: <input name="postcode" type="text" /><br/>
                Upload Image: <input type="file" name="image" /><br/>
                <input type="submit" value="Save" name="submit"/>
            </form>
        EOD;
    }

    // Render the template
    echo template("templates/default.php", $data);

    // Include footer
    echo template("templates/partials/footer.php");

} else {
    // Redirect to index.php if the user is not logged in
    header("Location: index.php");
    exit();
}
?>
