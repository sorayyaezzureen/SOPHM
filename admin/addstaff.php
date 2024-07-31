<?php
session_start();
require('connection.php');

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['staffid'])) {
    header("Location: login.php"); // Replace with your actual login page URL
    exit();
}

// Fetch donor usernames
$donors = [];
$sql = "SELECT STAFFID FROM staff";
$result = mysqli_query($con, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $staffs[] = $row['STAFFID'];
    }
} else {
    echo "<div class='alert alert-danger'>Error fetching donors: " . mysqli_error($con) . "</div>";
}

// Handle form submission
if (isset($_POST["submit"])) {
    // Process form inputs
    $name =$_POST["name"];
            $idno =$_POST["idno"];
            $address =$_POST["address"];
            $phoneno =$_POST["phoneno"];
            $position =$_POST["position"];

    $errors = array();
           
           if (empty($name) OR empty($idno) OR empty($address) OR empty($phoneno) OR empty($position)) {

            array_push($errors,"All fields are required");
           }
           if (!filter_var($name)) {
            array_push($errors, "Name is not valid");
           }
           if (!filter_var($idno)) {
            array_push($errors, "ID No is not valid");
           }
           if (!filter_var($address)) {
            array_push($errors, "Address is not valid");
           }
           if (!filter_var($phoneno)) {
            array_push($errors, "Phone No is not valid");
           }
           if (!filter_var($position)) {
            array_push($errors, "Position is not valid");
           }

           require_once "database.php";
           $sql = "SELECT * FROM staff WHERE idno = '$idno'";
           $result = mysqli_query($conn, $sql);
           $rowCount = mysqli_num_rows($result);
           if ($rowCount>0) {
            array_push($errors,"ID already exists!");
           }
           if (count($errors)>0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           }
           else{
            
            $sql4 = "INSERT INTO staff (name,idno,address,phoneno,position) VALUES ( ?, ?, ?, ?,?)";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql4);
            if ($prepareStmt) {
                 mysqli_stmt_bind_param($stmt,"sssss",$name, $idno,$address, $phoneno,$position);
                mysqli_stmt_execute($stmt);
                echo '<script>alert("BERJAYA DITAMBAH")</script>';
                echo "<script type='text/javascript'> document.location ='addstaff.php'; </script>";
            }else{
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
    <title>Staff | Admin</title>
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
    <h2 class="text-center">Daftar Staff</h2>
    <form action="addstaff.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
       <div class="form-group">
            <label for="name"><i class="fas fa-users"></i> Nama:</label>
            <input type="text" class="form-control" name="name" placeholder="Nama" required="" title="Sila isi nama penuh seperti di dalam kad pengenalan" pattern="[a-zA-Z][a-zA-Z0-9\s]*">
        </div>
        <div class="form-group">
            <label for="email"><i class="fas fa-handshake"></i> No. kad pengenalan:</label>
            <input type="text" class="form-control" name="idno" placeholder="No. kad pengenalan" required="required" title="Sila isi nombor kad pengenalan" pattern="[0-9]+" maxlength="12">
        </div>
        <div class="form-group">
            <label for="phoneno"><i class="fas fa-phone"></i> No. telefon:</label>
            <input type="number" class="form-control" name="phoneno" placeholder="No. telefon" required="required"pattern="[0-9]+" maxlength="10" title="Sila isi nombor telefon">
        </div>
        <div class="form-group">
            <label for="address"><i class="fas fa-child"></i> Alamat:</label>
            <input type="text" class="form-control" name="address" placeholder="Alamat rumah" required="required" title="Sila isi alamat">
        </div>
        <div class="form-group">
            <label for="position"><i class="fas fa-user-check"></i> Jawatan:</label>
            <input type="text" class="form-control" name="position" placeholder="Jawatan" title="Sila isi jawatan" pattern="[a-zA-Z][a-zA-Z0-9\s]*">
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
