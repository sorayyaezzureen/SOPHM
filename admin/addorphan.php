<?php
session_start();
require('connection.php');

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['staffid'])) {
    header("Location: login.php"); // Replace with your actual login page URL
    exit();
}

// Fetch donor usernames
$orphans = [];
$sql = "SELECT ORPHANID FROM orphan";
$result = mysqli_query($con, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orphans[] = $row['ORPHANID'];
    }
} else {
    echo "<div class='alert alert-danger'>Error fetching volunteers: " . mysqli_error($con) . "</div>";
}

if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $idno = $_POST["idno"];
    $gender = $_POST["gender"];
    $age = $_POST["age"];
    $birthdate = $_POST["birthdate"];
    $healthdetails = $_POST["healthdetails"];
    $edugrade = $_POST["edugrade"];
    $edudetails = $_POST["edudetails"];
    $school = $_POST["school"];
    $nosiblings = $_POST["nosiblings"];
    $fathersname = $_POST["fathersname"];
    $deathcertno = $_POST["deathcertno"];
    if ($deathcertno == 'ada') {
        $deathcertno = $_POST["deathcertnumber"];
    } else {
        $deathcertno = 'tiada';
    }
    $guardianname = $_POST["guardianname"];
    $relationship = $_POST["relationship"];
    if ($relationship == 'LAIN-LAIN') {
        $relationship = $_POST["otherrelationship"];
    }
    $guardianidentificationno = $_POST["guardianidentificationno"];
    $guardianaddress = $_POST["guardianaddress"];
    $occupation = $_POST["occupation"];
    $salary = $_POST["salary"];
    $phoneno = $_POST["phoneno"];
    $category = $_POST["category"];
    $status = $_POST["status"];
    $staffid = $_POST["staffid"];

    $errors = array();

    // Required field validation
    $required_fields = [
        'name', 'idno', 'gender', 'age', 'birthdate', 'healthdetails',
        'edugrade', 'edudetails', 'school', 'nosiblings', 'fathersname',
        'guardianname', 'relationship', 'guardianidentificationno',
        'guardianaddress', 'occupation', 'salary', 'phoneno', 'category', 'status', 'staffid'
    ];

    foreach ($required_fields as $field) {
        if (empty($$field)) {
            array_push($errors, ucfirst($field) . " is required");
        }
    }

    // Specific field validations
    if (!preg_match('/^\d{12}$/', $idno)) {
        array_push($errors, "Identification No must be exactly 12 digits");
    }

    if (!in_array($gender, ['lelaki', 'perempuan'])) {
        array_push($errors, "Gender is not valid");
    }

    if (!filter_var($age, FILTER_VALIDATE_INT, ["options" => ["min_range" => 7, "max_range" => 17]])) {
        array_push($errors, "Age must be between 7 and 17 years old");
    }

    if (!in_array($edugrade, ['tahun 1', 'tahun 2', 'tahun 3', 'tahun 4', 'tahun 5', 'tahun 6', 'tingkatan 1', 'tingkatan 2', 'tingkatan 3', 'tingkatan 4', 'tingkatan 5'])) {
        array_push($errors, "Education grade is not valid");
    }

    if (!in_array($edudetails, ['bersekolah', 'tidak bersekolah'])) {
        array_push($errors, "Education details is not valid");
    }

    if (!in_array($school, ['rendah', 'menengah'])) {
        array_push($errors, "School is not valid");
    }

    if ($deathcertno != 'tiada' && !preg_match('/^\d{12}$/', $deathcertno)) {
        array_push($errors, "Death Certification No must be exactly 12 digits if provided");
    }

    if (!preg_match('/^\d+(\.\d{1,2})?$/', $salary)) {
        array_push($errors, "Salary must be a valid number with up to two decimal places");
    }

    if (!preg_match('/^\d{10,11}$/', $phoneno)) {
        array_push($errors, "Phone Number must be 10 to 11 digits");
    }

    if (!in_array($category, ['anak yatim', 'miskin'])) {
        array_push($errors, "Category is not valid");
    }

    if (!in_array($status, ['baru mendaftar', 'telah mendaftar'])) {
        array_push($errors, "Status is not valid");
    }

    if ($relationship == 'LAIN-LAIN' && !preg_match('/^[a-zA-Z\s]+$/', $relationship)) {
        array_push($errors, "Other Relationship must be alphabetic characters only if provided");
    }

    // Check if ID number is already registered
    require_once "database.php";

    $sql = "SELECT * FROM orphan WHERE idno = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        die("SQL error");
    } else {
        mysqli_stmt_bind_param($stmt, "s", $idno);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            array_push($errors, "NOMBOR KAD PENGENALAN SUDAH DIDAFTARKAN!");
        }
    }

    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
    } else {
        $sql = "INSERT INTO orphan (name,idno,gender,age,birthdate,healthdetails,edugrade,edudetails,school,nosiblings,fathersname,deathcertno,guardianname,relationship,guardianidentificationno,guardianaddress,occupation,salary,phoneno,category,status,staffid) VALUES ( ?, ?, ?, ?, ?, ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt,$sql);
            if ($prepareStmt) {
                mysqli_stmt_bind_param($stmt,"ssssssssssssssssssssss",$name, $idno, $gender,$age,$birthdate,$healthdetails,$edugrade,$edudetails,$school,$nosiblings,$fathersname,$deathcertno,$guardianname,$relationship,$guardianidentificationno,$guardianaddress,$occupation,$salary,$phoneno,$category, $status, $staffid);
                mysqli_stmt_execute($stmt);
                echo '<script>alert("BERJAYA DIDAFTAR")</script>';
                echo "<script type='text/javascript'> document.location ='registration.php'; </script>";
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
    <title>Anak Yatim | Admin</title>
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
    <h2 class="text-center">Daftar Anak Yatim</h2>
    <form action="addorphan.php" method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
       <div class="form-group">
            <label for="name"><i class="fas fa-user"></i> Nama:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="idno"><i class="fas fa-id-card"></i> Nombor Kad Pengenalan:</label>
            <input type="text" class="form-control" id="idno" name="idno" pattern="\d{12}" title="Identification No must be exactly 12 digits" required>
        </div>
        <div class="form-group">
            <label for="gender"><i class="fas fa-venus-mars"></i> Jantina:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="">Pilih</option>
                <option value="lelaki">Lelaki</option>
                <option value="perempuan">Perempuan</option>
            </select>
        </div>
        <div class="form-group">
            <label for="age"><i class="fas fa-hourglass"></i> Umur:</label>
            <input type="number" class="form-control" id="age" name="age" min="7" max="17" required>
        </div>
        <div class="form-group">
            <label for="birthdate"><i class="fas fa-calendar"></i> Tarikh Lahir:</label>
            <input type="date" class="form-control" id="birthdate" name="birthdate" required>
        </div>
        <div class="form-group">
            <label for="healthdetails"><i class="fas fa-heartbeat"></i> Status Kesihatan:</label>
            <textarea class="form-control" id="healthdetails" name="healthdetails" required></textarea>
        </div>
        <div class="form-group">
            <label for="edugrade"><i class="fas fa-graduation-cap"></i> Tahap Pendidikan:</label>
            <select class="form-control" id="edugrade" name="edugrade" required>
                <option value="">Pilih</option>
                <option value="tahun 1">Tahun 1</option>
                <option value="tahun 2">Tahun 2</option>
                <option value="tahun 3">Tahun 3</option>
                <option value="tahun 4">Tahun 4</option>
                <option value="tahun 5">Tahun 5</option>
                <option value="tahun 6">Tahun 6</option>
                <option value="tingkatan 1">Tingkatan 1</option>
                <option value="tingkatan 2">Tingkatan 2</option>
                <option value="tingkatan 3">Tingkatan 3</option>
                <option value="tingkatan 4">Tingkatan 4</option>
                <option value="tingkatan 5">Tingkatan 5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="edudetails"><i class="fas fa-school"></i> Status Pendidikan:</label>
            <select class="form-control" id="edudetails" name="edudetails" required>
                <option value="">Pilih</option>
                <option value="bersekolah">Bersekolah</option>
                <option value="tidak bersekolah">Tidak Bersekolah</option>
            </select>
        </div>
        <div class="form-group">
            <label for="school"><i class="fas fa-school"></i> Sekolah:</label>
            <select class="form-control" id="school" name="school" required>
                <option value="">Pilih</option>
                <option value="rendah">Rendah</option>
                <option value="menengah">Menengah</option>
            </select>
        </div>
        <div class="form-group">
            <label for="nosiblings"><i class="fas fa-users"></i> Bilangan Adik-Beradik:</label>
            <input type="number" class="form-control" id="nosiblings" name="nosiblings" required>
        </div>
        <div class="form-group">
            <label for="fathersname"><i class="fas fa-user-tie"></i> Nama Bapa:</label>
            <input type="text" class="form-control" id="fathersname" name="fathersname" required>
        </div>
        <div class="form-group">
            <label for="deathcertno"><i class="fas fa-file-alt"></i> No. Sijil Kematian:</label>
            <select class="form-control" id="deathcertno" name="deathcertno" required>
                <option value="">Pilih</option>
                <option value="ada">Ada</option>
                <option value="tiada">Tiada</option>
            </select>
            <input type="text" class="form-control mt-2" id="deathcertnumber" name="deathcertnumber" placeholder="No Sijil Kematian" pattern="\d{12}" title="Death Certification No must be exactly 12 digits">
        </div>
        <div class="form-group">
            <label for="guardianname"><i class="fas fa-user-shield"></i> Nama Penjaga:</label>
            <input type="text" class="form-control" id="guardianname" name="guardianname" required>
        </div>
        <div class="form-group">
            <label for="relationship"><i class="fas fa-handshake"></i> Hubungan:</label>
            <select class="form-control" id="relationship" name="relationship" required>
                <option value="">Pilih</option>
                <option value="BAPA KANDUNG">Bapa Kandung</option>
                <option value="IBU KANDUNG">Ibu Kandung</option>
                <option value="IBU KANDUNG">Abang Kandung</option>
                <option value="IBU KANDUNG">Kakak Kandung</option>
                <option value="IBU KANDUNG">Abang Angkat</option>
                <option value="IBU KANDUNG">Kakak Angkat</option>
                <option value="IBU KANDUNG">Bapa Angkat</option>
                <option value="IBU KANDUNG">Ibu Angkat</option>
                <option value="SAUDARA">Saudara</option>
                <option value="LAIN-LAIN">Lain-Lain</option>
            </select>
            <input type="text" class="form-control mt-2" id="otherrelationship" name="otherrelationship" placeholder="Nyatakan Hubungan">
        </div>
        <div class="form-group">
            <label for="guardianidentificationno"><i class="fas fa-id-card-alt"></i> No. KP Penjaga:</label>
            <input type="text" class="form-control" id="guardianidentificationno" name="guardianidentificationno" required>
        </div>
        <div class="form-group">
            <label for="guardianaddress"><i class="fas fa-map-marker-alt"></i> Alamat Penjaga:</label>
            <textarea class="form-control" id="guardianaddress" name="guardianaddress" required></textarea>
        </div>
        <div class="form-group">
            <label for="occupation"><i class="fas fa-briefcase"></i> Pekerjaan Penjaga:</label>
            <input type="text" class="form-control" id="occupation" name="occupation" required>
        </div>
        <div class="form-group">
            <label for="salary"><i class="fas fa-dollar-sign"></i> Pendapatan Penjaga:</label>
            <input type="text" class="form-control" id="salary" name="salary" pattern="\d+(\.\d{1,2})?" title="Salary must be a valid number with up to two decimal places" required>
        </div>
        <div class="form-group">
            <label for="phoneno"><i class="fas fa-phone"></i> No. Telefon Penjaga:</label>
            <input type="text" class="form-control" id="phoneno" name="phoneno" pattern="\d{10,11}" title="Phone Number must be 10 to 11 digits" required>
        </div>
        <div class="form-group">
            <label for="category"><i class="fas fa-child"></i> Kategori:</label>
            <select class="form-control" id="category" name="category" required>
                <option value="">Pilih</option>
                <option value="anak yatim">Anak Yatim</option>
                <option value="miskin">Miskin</option>
            </select>
        </div>
        <div class="form-group">
            <label for="status"><i class="fas fa-user-check"></i> Status Pendaftaran:</label>
            <select class="form-control" id="status" name="status" required>
                <option value="">Pilih</option>
                <option value="baru mendaftar">Baru Mendaftar</option>
                <option value="telah mendaftar">Telah Mendaftar</option>
            </select>
        </div>
        <div class="form-group">
            <label for="staffid"><i class="fas fa-user-tie"></i> Staff ID:</label>
            <input type="text" class="form-control" id="staffid" name="staffid" value="1" readonly ="readonly">
        </div>
        <center><button type="submit" class="btn btn-custom" name="submit"><i class="fas fa-paper-plane"></i> Hantar</button></center>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        const deathCertSelect = document.getElementById('deathcertno');
        const deathCertInput = document.getElementById('deathcertnumber');
        deathCertSelect.addEventListener('change', function () {
            if (this.value === 'ada') {
                deathCertInput.style.display = 'block';
                deathCertInput.setAttribute('required', 'required');
            } else {
                deathCertInput.style.display = 'none';
                deathCertInput.removeAttribute('required');
            }
        });

        const relationshipSelect = document.getElementById('relationship');
        const otherRelationshipInput = document.getElementById('otherrelationship');
        relationshipSelect.addEventListener('change', function () {
            if (this.value === 'LAIN-LAIN') {
                otherRelationshipInput.style.display = 'block';
                otherRelationshipInput.setAttribute('required', 'required');
            } else {
                otherRelationshipInput.style.display = 'none';
                otherRelationshipInput.removeAttribute('required');
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
