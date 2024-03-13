<?php
include "config.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Escape the user input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);

    // Prepare and execute the DELETE query
    $query = "DELETE FROM land_titles WHERE id = '$id'";
    $result = mysqli_query($conn, $query);


    // Redirect to the main page after deletion
    header('location: index.php');
    exit();
} else {
    // If 'id' is not set, redirect to the main page
    header('location: index.php');
    exit();
}
