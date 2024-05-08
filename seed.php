<?php

// Include necessary files
include("_includes/config.inc");
include("_includes/dbconnect.inc");
include("_includes/functions.inc");

// Array of student data
$array_students = array(
    array(
        "studentid" => "20000001",
        "password" => "dot",
        "firstname" => "Bob",
        "lastname" => "Kent",
        "DOB" => "1973-11-10",
        "house" => "2 ring Road",
        "town" => "High Wycombe",
        "county" => "Bucks",
        "country" => "UK",
        "postcode" => "HP13 6SL"
    ),
    array(
        "studentid" => "20000002",
        "password" => "dot",
        "firstname" => "Marlow",
        "lastname" => "Kent",
        "DOB" => "1973-11-10",
        "house" => "2 ring Road",
        "town" => "High Wycombe",
        "county" => "Bucks",
        "country" => "UK",
        "postcode" => "HP13 6SL"
    ),
    array(
        "studentid" => "20000003",
        "password" => "dot",
        "firstname" => "mina",
        "lastname" => "Kent",
        "DOB" => "1973-11-10",
        "house" => "2 ring Road",
        "town" => "High Wycombe",
        "county" => "Bucks",
        "country" => "UK",
        "postcode" => "HP13 6SL"
    ),
    array(
        "studentid" => "20000004",
        "password" => "dot",
        "firstname" => "levis",
        "lastname" => "Kent",
        "DOB" => "1973-11-10",
        "house" => "2 ring Road",
        "town" => "High Wycombe",
        "county" => "Bucks",
        "country" => "UK",
        "postcode" => "HP13 6SL"
    ),
    array(
        "studentid" => "20000005",
        "password" => "dot",
        "firstname" => "Rina",
        "lastname" => "Kent",
        "DOB" => "1973-11-10",
        "house" => "2 ring Road",
        "town" => "High Wycombe",
        "county" => "Bucks",
        "country" => "UK",
        "postcode" => "HP13 6SL"
    )
);

// Loop through each student and insert into database
foreach ($array_students as $student_array) {
    // Escape each value
    $escaped_values = array_map('mysqli_real_escape_string', array_fill(0, count($student_array), $conn), $student_array);
    // Convert to string and wrap in quotes
    $values = "'" . implode("', '", $escaped_values) . "'";
    
    // Build and execute the query
    $sql = "INSERT INTO student (studentid, password, firstname, lastname, DOB, house, town, county, country, postcode) 
            VALUES ($values)";
    $result = mysqli_query($conn, $sql);
    
    // Check if insertion was successful
    if ($result) {
        echo "Record inserted successfully: {$student_array['firstname']} {$student_array['lastname']}<br>";
    } else {
        echo "Error inserting record: " . mysqli_error($conn) . "<br>";
    }
}

// Close the database connection
mysqli_close($conn);
?>
