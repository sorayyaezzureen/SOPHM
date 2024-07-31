<?php
session_start(); // Ensure the session is started
require('connection.php');

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['donorid'])) {
    header("Location: login.php"); // Replace with your actual login page URL
    exit();
}

if (isset($_POST["submit"])) {
    $datetime = $_POST["datetime"];
    $place = $_POST["place"];
    $quantity = $_POST["quantity"];
    $otherquantity = $_POST["otherquantity"]; // Fetch this from POST

    if ($quantity == 'ADA') {
        $quantity = $otherquantity;
    } else {
        $quantity = 'TIADA';
    }
    
    $amountrm = $_POST["amountrm"];
    $category = $_POST["category"];
    $proof = $_FILES["proof"]["name"]; // Fetch this from FILES

    // Check if a file was uploaded
    if (isset($_FILES['proof']) && $_FILES['proof']['error'] == 0) {
        // Get the file extension
        $extension = pathinfo($proof, PATHINFO_EXTENSION);

        // Create a new file name
        $new_filename = rand(1000,10000)."-".$extension;

        // Move the file to the desired location
        move_uploaded_file($_FILES['proof']['tmp_name'], 'uploads/'.$new_filename);
    } else {
        echo "<div class='alert alert-danger'>File upload failed or no file was uploaded.</div>";
        exit();
    }

    $errors = array();
    $donorid = $_POST["donorid"];

    $required_fields = [
        'place' => $place,
        'quantity' => $quantity,
        'amountrm' => $amountrm,
        'category' => $category,
        'donorid' => $donorid
    ];

    foreach ($required_fields as $field => $value) {
        if (empty($value)) {
            array_push($errors, ucfirst($field) . " is required");
        }
    }

    // Specific field validations
    if (!preg_match('/^\d+(\.\d{1,2})?$/', $amountrm)) {
        array_push($errors, "Amount must be a valid number with up to two decimal places");
    }

    // Check if donor exists
    // $sql = "SELECT * FROM donor WHERE DONORID = ?";
    // $stmt = mysqli_prepare($con, $sql);
    // mysqli_stmt_bind_param($stmt, "s", $donorid);
    // mysqli_stmt_execute($stmt);
    // $result = mysqli_stmt_get_result($stmt);
    // if (mysqli_num_rows($result) == 0) {
    //     array_push($errors, "Donor ID does not exist");
    // }
    // mysqli_stmt_close($stmt);

    // Display errors if any
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        // Insert data into the donation table
        $sql = "INSERT INTO donation (datetime, place, quantity, amountrm, category, proof) VALUES ( ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        if ($stmt) {
            $proof = $new_filename; // Update proof with the new filename
            mysqli_stmt_bind_param($stmt, "ssssss", $datetime, $place, $quantity, $amountrm, $category, $proof);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            echo '<script>alert("SUMBANGAN BERJAYA DIHANTAR!")</script>';
            echo "<script type='text/javascript'> document.location ='adddonation.php'; </script>";
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
    <title>Sumbang | Penderma</title>
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
                        Aktiviti
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="adddonation.php"> Sumbang</a>
                        <a class="dropdown-item" href="viewdonation.php"> Senarai Sumbangan</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Akaun</a>
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
    <h2 class="text-center">Rekod Sumbangan</h2>
    <form action="adddonation.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
       <div class="form-group">
            <label for="datetime"><i class="fas fa-calendar"></i> Tarikh dan Masa:</label>
            <input type="datetime-local" class="form-control" id="datetime" name="datetime" min="<?php echo date('Y-m-d\TH:i'); ?>" required>
        </div>
        <div class="form-group">
            <label for="place"><i class="fas fa-handshake"></i> Lokasi:</label>
            <select class="form-control" id="place" name="place" required>
                <option value="">Pilih</option>
                <option value="ONLINE BANKING">Online Banking</option>
                <option value="SECARA FIZIKAL (DI ASRAMA)">Asrama Sekendi</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity"><i class="fas fa-handshake"></i> Jumlah kuantiti:</label>
            <select class="form-control" id="quantity" name="quantity" required>
                <option value="">Pilih</option>
                <option value="ADA">Ada</option>
                <option value="TIADA">Tiada</option>
            </select>
            <input type="text" class="form-control mt-2" id="otherquantity" name="otherquantity" placeholder="Nyatakan Kuantiti">
        </div>
        <div class="form-group">
            <label for="amountrm"><i class="fas fa-dollar-sign"></i> Jumlah (RM):</label>
            <input type="number" class="form-control" id="amountrm" placeholder="Kos" name="amountrm" pattern="\d+(\.\d{1,2})?" title="Cost must be a valid number with up to two decimal places" required>
        </div>
        <div class="form-group">
            <label for="category"><i class="fas fa-user-check"></i> Kategori:</label>
            <select class="form-control" id="category" name="category" required>
                <option value="">Pilih</option>
                <option value="FASILITI ASRAMA">Fasiliti Asrama</option>
                <option value="KESIHATAN">Kesihatan</option>
                <option value="AKTIVITI ATAU MAJLIS ASRAMA">Aktiviti atau Majlis Asrama</option>
                <option value="PAKAIAN">Pakaian</option>
                <option value="PENDIDIKAN">Pendidikan</option>
                <option value="MAKANAN">Makanan</option>
            </select>
        </div>
        <div class="form-group">
            <label for="proof"><i class="fas fa-file-alt"></i> Resit:</label>
            <input type="file" class="form-control" id="proof" name="proof" enctype="multipart/form-data"required>
        </div>
        <div class="form-group">
          <label for="donorid"> Username Penderma:</label>
          <input type="text" class="form-control" id="donorid" name="donorid" value="<?php echo $_SESSION['donorid']; ?>" readonly>
        </div>
        <center><button type="submit" class="btn btn-custom" name="submit"><i class="fas fa-paper-plane"></i> Hantar</button></center>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const quantitySelect = document.getElementById('quantity');
        const otherQuantityInput = document.getElementById('otherquantity');
        quantitySelect.addEventListener('change', function () {
            if (this.value === 'ADA') {
                otherQuantityInput.style.display = 'block';
                otherQuantityInput.setAttribute('required', 'required');
            } else {
                otherQuantityInput.style.display = 'none';
                otherQuantityInput.removeAttribute('required');
            }
        });
    });
</script>

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
