<?php
include "config.php";

// Assuming $specific_id is the specific id you want to match
$specific_id = $_GET["id"]; // Get the id from the URL parameter

// Fetch the land title details for the specific id
$land_title_sql = "SELECT * FROM land_titles WHERE id = $specific_id";
$land_title_result = $conn->query($land_title_sql);
$land_title_row = $land_title_result->fetch_assoc();

// Fetch the subdivided titles for the specific land title id, including the applicant's name, sorted by position
$subdivided_titles_sql = "SELECT *
                          FROM subdivided_titles
                          WHERE land_title_id = $specific_id
                          AND subdivided_to IS NULL
                          ORDER BY position";
$subdivided_titles_result = $conn->query($subdivided_titles_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
</head>

<body>
    <h1>Subdivided Land History for
        <?php echo $land_title_row["lot_number"]; ?>
    </h1>
    <a href="index.php">BACK</a>
    <h2>Land Title Details:</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Lot Number</th>
            <th>Date Filed</th>
            <th>Applicant Name</th>
            <th>Location</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td>
                <?php echo $land_title_row["id"]; ?>
            </td>
            <td>
                <?php echo $land_title_row["lot_number"]; ?>
            </td>
            <td>
                <?php echo $land_title_row["date_filed"]; ?>
            </td>
            <td>
                <?php echo $land_title_row["applicant_name"]; ?>
            </td>
            <td>
                <?php echo $land_title_row["location"]; ?>
            </td>
            <td>
                <?php echo $land_title_row["remarks"]; ?>
            </td>
        </tr>
    </table>
    <br>
    <h2>Subdivided Titles:</h2>
    <a href="subdivide.php?id=<?php echo $land_title_row['id'] ?>">Add</a>

    <table border="1">
        <tr>
            <th>Lot Number</th>
            <th>Date Filed</th>
            <th>Applicant Name</th>
            <th>Location</th>
            <th>Remarks</th>
            <th>History</th>
            <th>Action</th>

        </tr>
        <?php
        if ($subdivided_titles_result->num_rows > 0) {
            while ($row = $subdivided_titles_result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["lot_number"] . "</td>
                        <td>" . $row["date_filed"] . "</td>
                        <td>" . $row["applicant_name"] . "</td>
                        <td>" . $row["location"] . "</td>
                        <td>" . $row["remarks"] . "</td>";
                echo "<td>";
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
        } else {
            echo "<tr><td colspan='5'>No subdivided titles found.</td></tr>";
        }
        ?>
    </table>
</body>

</html>