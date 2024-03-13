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

        $position = ''; 
        $status = 0;   
    
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            header("Location: main.php?error=CSRF token validation failed");
            exit();
        }

        $stmt_insert = mysqli_prepare($conn, "INSERT INTO land_titles (lot_number, date_filed, applicant_name, location, remarks, position, status) VALUES (?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt_insert, "issssss", $lot_number, $date_filed, $applicant_name, $location, $remarks, $position, $status);
        if (mysqli_stmt_execute($stmt_insert)) {
            header('location: index.php');
            exit();
        } else {
            header('location: add-lot.php');
            exit();
        }



    }
    ?>
    <form action="" method="POST">
        <label for="">New Lot #</label>
        <input type="text" name="lot_number"><br>
        <label for="">Date Filed</label>
        <input type="date" name="date_filed"><br>
        <label for="">Applicant Name</label>
        <input type="text" name="applicant_name"><br>
        <label for="">Location</label>
        <input type="text" name="location"><br>
        <label for="">Remarks</label>
        <input type="text" name="remarks"><br>

        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

        <button type="submit" name="submit">Submit</button><br>
        <a href="index.php">Main</a>
        
    </form>
</body>

</html>