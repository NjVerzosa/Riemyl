<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process the form submission
    $id = $_POST["id"];
    $date_filed = $_POST["date_filed"];
    $applicant_name = $_POST["applicant_name"];
    $remarks = "Untitled";

    // Insert the new subdivided record into subdivided_titles table
    $insert_sql = "INSERT INTO subdivided_titles (lot_number, date_filed, applicant_name, location, remarks, position, land_title_id)
            SELECT lot_number, '$date_filed', '$applicant_name', location, '$remarks', $new_position, $id
            FROM land_titles
            WHERE id = $id";



    if (mysqli_query($conn, $insert_sql)) {
        // Update the isSubdivide column in the land_titles table to 1
        $update_sql = "UPDATE subdivided_titles SET status = 0 WHERE id = $id";
        if (mysqli_query($conn, $update_sql)) {
            echo "Record subdivided successfully";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
        // Redirect to index.php after successful insertion and update
        header("Location: history.php?id=$id");
        exit;
    } else {
        echo "Error: " . $insert_sql . "<br>" . mysqli_error($conn);
    }
    mysqli_close($conn);
    exit;
}

// If the form is not submitted, show the form
$id = $_GET["id"];

// Get the current record details
$sql = "SELECT * FROM subdivided_titles WHERE id = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subdivide Land</title>
</head>

<body>
    <h1>Subdivide Land</h1>
    <a href='index.php?id=".$row["id"]."'>Back</a>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="date_filed">Date Filed:</label><br>
        <input type="date" id="date_filed" name="date_filed" required><br>
        <label for="applicant_name">Applicant Name:</label><br>
        <input type="text" id="applicant_name" name="applicant_name" required><br><br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>