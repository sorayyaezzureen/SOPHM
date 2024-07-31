<?php
session_start();
require('connection.php');

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['staffid'])) {
    header("Location: login.php"); // Replace with your actual login page URL
    exit();
}

// Fetch donor usernames
$volunteers = [];
$sql = "SELECT VOLUNTEERID FROM volunteer";
$result = mysqli_query($con, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $volunteers[] = $row['VOLUNTEERID'];
    }
} else {
    echo "<div class='alert alert-danger'>Error fetching volunteers: " . mysqli_error($con) . "</div>";
}

if (isset($_POST["submit"])) {
    $name =$_POST["name"];
    $description =$_POST["description"];
    $venue =$_POST["venue"];
    $estimatedcost = $_POST["estimatedcost"];
    $eventproposal = $_POST["eventproposal"];

    // Get the file name
    $filename = $file['name'];

    // Get the file extension
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    // Create a new file name
    $new_filename = rand(1000,10000)."-".$extension;

    // Move the file to the desired location
    move_uploaded_file($file['tmp_name'], '/uploads/'.$new_filename);
 
    #sql query to insert into database
    $sql = "INSERT into events(eventproposal) VALUES('$eventproposal')";
 
    if(mysqli_query($con,$sql)){
 
    echo "File Sucessfully uploaded";
    }
    else{
        echo "Error";
    }

    $errors = array();
    $datetime = $_POST["datetime"];
    $volunteerid = $_POST["volunteerid"];

    // Required field validation
    $required_fields = [
        'name' => $name,
        'description' => $description,
        'venue' => $venue,
        'estimatedcost' => $estimatedcost,
        'eventproposal' => $eventproposal,
        'volunteerid' => $volunteerid
    ];

    foreach ($required_fields as $field => $value) {
        if (empty($value)) {
            array_push($errors, ucfirst($field) . " is required");
        }
    }

    // Specific field validations
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $estimatedcost)) {
        array_push($errors, "Cost must be a valid number with up to two decimal places");
    }
    if (!filter_var($estimatedcost)) {
        array_push($errors, "Estimated cost is not valid");
    }
    if (!filter_var($datetime)) {
        array_push($errors, "Date and time details is not valid");
    }
    if (!filter_var($volunteerid)) {
        array_push($errors, "Volunteer ID is not valid");
    }

    // Check if username is already registered
    $sql = "SELECT * FROM events WHERE name = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "NAMA PROGRAM SUDAH DIDAFTARKAN!");
        }
        mysqli_stmt_close($stmt);
    } else {
        die("SQL error");
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {

        $sql = "INSERT INTO events (name,description,venue,estimatedcost,datetime,eventproposal) VALUES ( ?, ?, ?, ?, ?,?)";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss",$name, $description, $venue,$estimatedcost,$datetime,$eventproposal);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo '<script>alert("CADANGAN AKTIVITI BERJAYA DIHANTAR!")</script>';
            echo "<script type='text/javascript'> document.location ='addevent.php'; </script>";
        } else {
            die("Something went wrong");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktiviti | Admin</title>
    <link rel="icon" type="image" href="/SOPHM/images/logo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Poppins';
            font-size: 13px;
        }
        .navbar {
            background-color: #8A9A5B;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
            color: white;
        }
        .navbar-nav .nav-link {
            color: white;
            margin-right: 20px;
            position: relative;
        }
        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transition: width 0.4s, height 0.4s, top 0.4s, left 0.4s;
        }
        .navbar-nav .nav-link:hover::before {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .dropdown-menu {
            background-color: #005d58;
            display: none;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .dropdown-item {
            color: white;
            transition: background-color 0.3s, color 0.3s;
        }

        .dropdown-item:hover {
            background-color: #8A9A5B;
            color: #ddd;
        }

        .form-container {
            margin-top: 80px;
            padding: 5px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .form-group i {
            margin-right: 10px;
        }

        .btn-custom {
            margin: 10px;
            padding: 10px 20px;
            font-size: 1.1em;
            background-color: #8A9A5B;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #6B8E23;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Asrama Kebajikan Anak-Anak Yatim Miskin Sekendi</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#login" id="navbarDropdown" role="button">
                        Anak yatim
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="addorphan.php"> Anak yatim</a>
                        <a class="dropdown-item" href="vieworphan.php"> Senarai Anak yatim</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#login" id="navbarDropdown" role="button">
                        Sumbangan
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="adddonation.php"> Sumbang</a>
                        <a class="dropdown-item" href="viewdonation.php"> Senarai Sumbangan</a>
                        <a class="dropdown-item" href="adddonor.php"> Penderma</a>
                        <a class="dropdown-item" href="viewdonor.php"> Senarai Penderma</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#login" id="navbarDropdown" role="button">
                        Sukarelawan
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="addvolunteer.php"> Sukarelawan</a>
                        <a class="dropdown-item" href="viewvolunteer.php"> Senarai Sukarelawan</a>
                        <a class="dropdown-item" href="addevent.php"> Aktiviti</a>
                        <a class="dropdown-item" href="viewevent.php"> Senarai Aktiviti</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#login" id="navbarDropdown" role="button">
                        Staff
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="addstaff.php"> Staff</a>
                        <a class="dropdown-item" href="viewstaff.php"> Senarai Staff</a>
                        <a class="dropdown-item" href="profile.php">Akaun</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Keluar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Form Section -->
<div class="container form-container">
    <h2 class="text-center">Borang Cadangan Aktiviti</h2>
    <form action="addevent.php" method="post" class="needs-validation" novalidate>
        <div class="form-group">
            <label for="name"><i class="fa fa-certificate"></i> Nama Program:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Program" title="Sila isi nama penuh badan organisasi" pattern="[a-zA-Z][a-zA-Z0-9\s]*" required>
        </div>
        <div class="form-group">
            <label for="description"><i class="fa fa-check"></i> Objektif Program:</label>
            <textarea class="form-control" id="description" name="description" rows="5" placeholder="Objektif" cols="219" pattern="[a-zA-Z][a-zA-Z0-9\s]*"></textarea>
        </div>
        <div class="form-group">
            <label for="venue"><i class="fas fa-map-marker-alt"></i> Lokasi:</label>
            <input type="text" class="form-control" id="venue" name="venue" placeholder="Lokasi" title="Sila isi lokasi" pattern="[a-zA-Z][a-zA-Z0-9\s]*" required>
        </div>
        <div class="form-group">
            <label for="estimatedcost"><i class="fas fa-dollar-sign"></i> Kos program:</label>
            <input type="number" class="form-control" id="estimatedcost" placeholder="Kos" name="estimatedcost" pattern="\d+(\.\d{1,2})?" title="Cost must be a valid number with up to two decimal places" required>
        </div>
        <div class="form-group">
            <label for="eventproposal"><i class="fas fa-file-alt"></i> Dokumen Sokongan:</label>
            <input type="file" class="form-control" id="eventproposal" name="eventproposal" enctype="multipart/form-data"required>
        </div>
        <div class="form-group">
            <label for="datetime"><i class="fas fa-calendar"></i> Tarikh dan Masa:</label>
            <input type="datetime-local" class="form-control" id="datetime" name="datetime" min="<?php echo date('Y-m-d\TH:i'); ?>" required>
        </div>
        <div class="form-group">
            <label for="donorid"> Username Sukarelawan:</label>
            <select class="form-control" id="volunteerid" name="volunteerid" required>
                <option value="">Pilih Sukarelawan</option>
                <?php foreach ($volunteers as $volunteer): ?>
                    <option value="<?php echo htmlspecialchars($volunteer); ?>"><?php echo htmlspecialchars($volunteer); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <center><button type="submit" class="btn btn-custom" name="submit"><i class="fas fa-paper-plane"></i> Hantar</button></center>
    </form>
</div>

<!-- Footer Section -->
<footer class="bg-light text-center text-lg-start mt-4">
    <div class="text-center p-3">
        &copy; 2023 Asrama Kebajikan Anak-Anak Yatim Miskin Sekendi. Hakcipta Terpelihara.
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>
</body>
</html>
