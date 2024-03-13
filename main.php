<?php
    include "config.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $search = $_POST['search'];

        // Search Logic
        $sql = "SELECT * FROM land_titles WHERE location = '$search'";
        $result = mysqli_query($conn, $sql);
    } else {
        // Select all records
        $sql = "SELECT * FROM land_titles";
        $result = mysqli_query($conn, $sql);
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>editor</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
        <img src="logo.png" alt="Logo" class="header-logo">
    
            <a class="navbar-brand" href="#"> DENR CENRO Record Tracer</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="insert_box.php">Add Box</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Search</a>
                    </li>
        
                </ul>
            </div>
        </div>
    </nav>

    <div class="search_update">
    <div class="container mt-5 pt-1">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <select name="search" class="dropdown">
                <option value="">Select Location</option>
                <?php
                $sql = "SELECT location FROM land_titles";
                $result_dropdown = mysqli_query($conn, $sql);

                if ($result_dropdown && mysqli_num_rows($result_dropdown) > 0) {
                    while ($row = mysqli_fetch_assoc($result_dropdown)) {
                        $selected = ($_POST['search'] == $row["location"]) ? 'selected' : '';
                        echo "<option value='" . $row["location"] . "' $selected>" . $row["location"] . "</option>";
                    }
                }
                ?>
            </select>

            

            <button type="submit" class="btn btn-primary nav-link-btn">Search</button>
        </form>
    </div>
    </div>
    <div class="update_table">
        <table class='table'>
            <thead>
                <tr class="bg-primary" style="color: white;">
                    <th>Lot Number</th>
                    <th>Date Filed</th>
                    <th>Applicant Name</th>
                    <th>Location</th>
                    <th>Remarks</th>
                    <th>History</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {


                        echo "<tr>";
                        echo "<td>" . $row['lot_number'] . "</td>";
                        echo "<td>" . $row['date_filed'] . "</td>";
                        echo "<td>" . $row['applicant_name'] . "</td>";
                        echo "<td>" . $row['location'] . "</td>";
                        echo "<td>" . $row['remarks'] . "</td>";
                        echo "<td>
                                <a href='update.php?id=" . $row['id'] . "' class='btn btn-primary'>Update</a>
                            </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No results found.</td></tr>";
                }

                ?>
            </tbody>
        </table>
    </div>

    </body>
    </html>