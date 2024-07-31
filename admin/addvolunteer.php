<?php
session_start();
require('connection.php');

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['staffid'])) {
    header("Location: login.php"); // Replace with your actual login page URL
    exit();
}

// Initialize variables
$required_fields = [
    'name' => '',
    'email' => '',
    'phoneno' => '',
    'username' => '',
    'password' => '',
    'repeat_password' => ''
];
$errors = [];

// Fetch volunteer IDs
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

// Handle form submission
if (isset($_POST["submit"])) {
    // Process form inputs
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phoneno = $_POST["phoneno"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $passwordRepeat = $_POST["repeat_password"];

    // Validate form inputs
    foreach ($required_fields as $field => $value) {
        if (empty($_POST[$field])) {
            array_push($errors, ucfirst($field) . " is required");
        }
    }

    // Specific field validations
    if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
        array_push($errors, "Name is not valid");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        array_push($errors, "Email is not valid");
    }
    if (!preg_match('/^\d{10,11}$/', $phoneno)) {
        array_push($errors, "Phone Number must be 10 to 11 digits");
    }
    if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        array_push($errors, "Username is not valid");
    }
    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters long");
    }
    if ($password !== $passwordRepeat) {
        array_push($errors, "Passwords do not match");
    }

    // Check if username is already registered
    $sql = "SELECT * FROM volunteer WHERE username = ?";
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "Username already registered!");
        }
        mysqli_stmt_close($stmt);
    } else {
        die("SQL error");
    }

    // Display errors or success message
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Hash the password before saving to the database
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO volunteer (name, email, phoneno, username, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phoneno, $username, $password);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo '<script>alert("Akaun berjaya didaftarkan!")</script>';
            echo "<script type='text/javascript'> document.location ='addvolunteer.php'; </script>";
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
    <title>Sukarelawan | Admin</title>
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
    <h2 class="text-center">Daftar Sukarelawan</h2>
    <form action="addvolunteer.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
       <div class="form-group">
            <label for="name"><i class="fas fa-user"></i> Nama Organisasi:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Nama Organisasi" title="Sila isi nama penuh badan organisasi" pattern="[a-zA-Z][a-zA-Z0-9\s]*" required>
        </div>
        <div class="form-group">
            <label for="email"><i class="fas fa-id-card"></i> Emel:</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Emel" required>
        </div>
        <div class="form-group">
            <label for="phoneno"><i class="fas fa-phone"></i> No. Telefon:</label>
            <input type="text" class="form-control" id="phoneno" name="phoneno" pattern="\d{10,11}" placeholder="No. Telefon" title="Phone Number must be 10 to 11 digits" required>
        </div>
        <div class="form-group">
            <label for="username"><i class="fas fa-users"></i> Username:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{8,20}" title="Sila isi username" required>
        </div>
        <div class="form-group">
            <label for="password"><i class="fas fa-user-shield"></i> Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="required" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{10}" title="Kata laluan tidak boleh melebihi 10 huruf dan termasuk sekurang-kurangnya 1 nombor dan 1 simbol" required>
        </div>
        
        <div class="form-group">
            <label for="repeat_password"><i class="fas fa-user-check"></i> Ulang Password:</label>
            <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="Ulang Password" required="required" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_=+-]).{10}" title="Kata laluan tidak boleh melebihi 10 huruf dan termasuk sekurang-kurangnya 1 nombor dan 1 simbol" required>
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
