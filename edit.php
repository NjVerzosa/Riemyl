<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lot</title>
</head>

<body>
    <?php
    session_start();
    include 'config.php';

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    if (isset($_POST['submit'])) {
        $lot_number = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['lot_number'])));
        $date_filed = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['date_filed'])));
        $applicant_name = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['applicant_name'])));
        $location = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['location'])));
        $remarks = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['remarks'])));
        $position = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['position'])));
        $status = htmlspecialchars(mysqli_real_escape_string($conn, trim($_POST['status'])));

    
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header("Location: index.php?error=CSRF token validation failed");
            exit();
        }

        $stmt_update = mysqli_prepare($conn, "UPDATE land_titles SET date_filed=?, applicant_name=?, location=?, remarks=?, position=?, status=? WHERE lot_number=?");
        mysqli_stmt_bind_param($stmt_update, "ssssssi", $date_filed, $applicant_name, $location, $remarks, $position, $status, $lot_number);

        if (mysqli_stmt_execute($stmt_update)) {
            header('location: index.php');
            exit();
        } else {
            header('location: edit-lot.php?lot_number=' . $lot_number);
            exit();
        }



    }
    if (isset($_GET['applicant_name'])) {
        $applicant_name = $_GET['applicant_name'];
        $query = "SELECT * FROM land_titles WHERE applicant_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $applicant_name);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <form action="" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                <label for="lot_number">Lot Number:</label>
                <input type="text" name="lot_number" value="<?php echo $row['lot_number']; ?>"><br>

                <label for="date_filed">Date Filed:</label>
                <input type="date" name="date_filed" value="<?php echo $row['date_filed']; ?>"><br>

                <label for="applicant_name">Applicant Name:</label>
                <input type="text" name="applicant_name" value="<?php echo $row['applicant_name']; ?>"><br>

                <label for="location">Location:</label>
                <input type="text" name="location" value="<?php echo $row['location']; ?>"><br>

                <label for="remarks">Remarks:</label>
                <input type="text" name="remarks" value="<?php echo $row['remarks']; ?>"><br>

                <label for="position">Position:</label>
                <input type="text" name="position" value="<?php echo $row['position']; ?>"><br>

                <label for="status">Status:</label>
                <input type="text" name="status" value="<?php echo $row['status']; ?>"><br>
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <button type="submit" name="submit">Update</button><br>

            </form>
            <a href="index.php"><button type="submit">BACK</button></a>
            <?php
        } else {
            echo "No records found.";
        }
    }
    ?>
</body>

</html>