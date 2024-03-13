<?php
include "config.php";
$abc = range('A', 'Z');
$position = 26;

echo $abc[$position-1];

// Fetch data from the database
$sql = "SELECT * FROM land_titles";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rowena</title>
</head>

<body>
    <a href="add-lot.php">Add</a>
    <?php
    if ($result->num_rows > 0) {
        // Display the table if there are records
        echo "<table border='1'>
            <tr>
                <th>ID</th>
                <th>Lot Number</th>
                <th>Date Filed</th>
                <th>Applicant Name</th>
                <th>Location</th>
                <th>Remarks</th>
                <th>History</th>
                <th>Action</th>
            </tr>";
        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row["id"] . "</td>
                <td>" . $row["lot_number"] . "</td>
                <td>" . $row["date_filed"] . "</td>
                <td>" . $row["applicant_name"] . "</td>
                <td>" . $row["location"] . "</td>
                <td>" . $row["remarks"] . "</td>
                <td>";

            if ($row["status"] == 0) {
                echo "<a href='' onclick='return confirm(\"No subdivider yet\")'>View</a>";
            } else {
                echo "<a href='history.php?id=" . $row["id"] . "'>View</a>";
            }
            echo "</td>
            <td>";


            if ($row["remarks"] == 'Titled') {
                echo "<a href='' onclick='return confirm(\"Not available for subdivision\")'>Subdivide</a> |                 
                <a href='edit.php?applicant_name=" . $row["applicant_name"] . "'>Edit</a> | 
                <a href='delete.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>";
            } else {
                echo "<a href='subdivide.php?id=" . $row["id"] . "'>Subdivide</a> |
                      <a href='edit.php?applicant_name=" . $row["applicant_name"] . "'>Edit</a> | 
                      <a href='delete.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a>
                      ";
            }
            echo "</td>
            </tr>";
        }
        echo "</table>";
    } else {
        // Display a message if the table is empty
        echo "No records found.";
    }
    ?>
</body>

</html>