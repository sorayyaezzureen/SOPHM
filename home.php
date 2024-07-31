<?php
require('connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOPHM</title>
    <link rel="icon" type="image" href="/SOPHM/images/logo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>

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
            position: fixed; /* Change from sticky to fixed */
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* Optional: adds a shadow for better visibility */
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

        .header {
            background-image: url("/SOPHM/images/KVTI (1).jpg");
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
            min-height: 80%;
        }

        .header h1 {
            font-size: 3.5em;
            font-weight: bold;
        }

        .header p {
            font-size: 1.2em;
            margin: 20px 0;
        }

        .section {
            padding: 50px 0;
            text-align: center;
            background-color: #f2f2f2;
        }

        .section h2 {
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        .card {
            margin: 15px;
            border: none;
            transition: transform 0.3s;
            background-color: #f2f2f2;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card img {
            max-width: 100%;
            height: auto;
        }

        .sejarah-section {
            display: flex;
            align-items: center;
            padding: 50px 0;
            background-color: #f2f2f2;
        }

        .sejarah-section h2 {
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        .sejarah-section img {
            max-width: 200px;
            margin-right: 30px;
        }

        .sejarah-section .description {
            text-align: justify;
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

        .donation-container {
            display: flex;
            flex-wrap: wrap;
            padding: 0 40px;
            justify-content: center;
        }

        .donation-item {
            flex: 1 0 21%;
            margin: 10px;
            text-align: center;
            border: 1px solid #ddd;
            padding: 10px;
            box-sizing: border-box;
            background-color: #fff;
            border-radius: 5px;
        }

        .donation-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .donation-item p {
            margin: 10px 0;
        }

        #hubungi {
            background-color: #f2f2f2;
            padding: 20px 0;
        }

        #hubungi i {
            margin-right: 10px; /* Adjust spacing between icon and text */
        }

        #hubungi p {
            text-align: justify;
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
                    <a class="nav-link" href="#sejarah">Sejarah</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#organisasi">Organisasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#album">Album</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sumbangan">Sumbangan</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#login" id="navbarDropdown" role="button">
                        Log Masuk
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/SOPHM/orphans/registration.php" target="_blank">Anak Yatim</a>
                        <a class="dropdown-item" href="/SOPHM/volunteer/login.php" target="_blank">Sukarelawan</a>
                        <a class="dropdown-item" href="/SOPHM/donor/login.php" target="_blank">Penderma</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#hubungi">Hubungi kami</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Header Section -->
<div class="header">
    <div class="container"><br><br>
        <h1>ASRAMA KEBAJIKAN ANAK-ANAK YATIM DAN MISKIN SEKENDI</h1><br><br><br>
        <p>Jadilah sebahagian daripada komuniti yang membantu rumah anak yatim</p>
    </div>
</div>

<!-- Sejarah Section -->
<div class="sejarah-section" id="sejarah">
    <div class="container d-flex align-items-center">
        <img src="/SOPHM/images/logo.jpg" alt="Logo">
        <div class="description">
            <h2>Sejarah</h2>
            <p>Asrama Kebajikan Anak-Anak Yatim Miskin Sekendi Batu 36 Jalan Sekendi Sabak Bernam bernombor pendaftaran PPM 030-10-22032013 telah diasaskan oleh Al-Marhum Tuan Haji Hayat @ Haji Abdul Latif Bin Haji Syukor dan balunya Hajjah Jamilah Binti Maulan pada tahun 1985. Asrama Kebajikan Anak Anak Yatim Batu 36 bekeluasan 3 ekar yang menempatkan dua blok bangunan asrama lelaki dan perempuan merupakan pusat kebajikan dan pendidikan ilmu bagi anak-anak yatim Islam agar mereka mendapat pendidikan yang sewajarnya dan menjadi manusia yang bertaqwa kepada Allah. Asrama Kebajikan Anak-Anak Yatim Miskin Sekendi pada ketika ini menempatkan 11 kanak-kanak perempuan dan 9 kanak-kanak lelaki dalam lingkungan umur 10 hingga 17 tahun. Asrama anak yatim itu mempunyai tujuh kakitangan: dua warden, seorang kerani, dua tukang masak, seorang pemandu van dan seorang tukang cuci.</p>
            <a href="/SOPHM/fail/Sijil Pendaftaran.pdf" class="btn btn-custom" target="_blank">Sijil Pendaftaran</a>
            <a href="/SOPHM/fail/Sijil Perakuan JKM.pdf" class="btn btn-custom" target="_blank">Sijil Perakuan JKM</a>
        </div>
    </div>
</div>

<!-- Organisasi Section -->
<div class="section bg-light" id="organisasi">
    <div class="container">
        <h2>Organisasi</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">1985 - 1996</h5>
                        <p class="card-text">Pengerusi (Al-Marhum Ustaz Hayat @ Haji Abdul Latif Bin Haji Syukor)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">1996 - 2017</h5>
                        <p class="card-text">Pengerusi (Al-Marhum Ustaz Halimi bin Hayat)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">2017 - Jun 2024</h5>
                        <p class="card-text">Pengerusi (Tuan Haji Sholehuddin bin Kayat)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Julai 2024</h5>
                        <p class="card-text">Pengerusi (Tuan Haji Ahmad Bustamam bin Abdul Latif)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">2023 - 2024</h5>
                        <p class="card-text">Timbalan Pengerusi (Pn. Khadijah Binti Hayat)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">2024</h5>
                        <p class="card-text">Setiausaha (Pn. Sholehah Binti Hayat @ Ab Latiff)</p>
                    </div>
                </div>
            </div>
        </div><br><br>
        <a href="/SOPHM/images/CARTA ORGANISASI.jpg" class="btn btn-custom" target="_blank">Carta Organisasi</a>
    </div>
</div>

<!-- Sumbangan Section -->
<div class="section" id="sumbangan">
    <div class="container">
        <h2>Jumlah Sumbangan</h2>
        <div class="donation-container">
            <?php
            // Check connection
            if ($con->connect_error) {
                die("Connection failed: " . $con->connect_error);
            }

            // Query to get donation categories and totals
            $sql = "SELECT category, SUM(amountrm) AS total FROM donation GROUP BY category";
            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                // Output data for each category
                while($row = $result->fetch_assoc()) {
                    echo '<div class="donation-item">';
                    // echo '<img src="' . $row["symbol_image"] . '" alt="' . $row["category"] . '"/>';
                    echo '<p><strong>' . $row["category"] . '</strong></p>';
                    echo '<p>RM ' . number_format($row["total"], 2) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>No donations available.</p>';
            }
            $con->close();
            ?>
        </div>
    </div><br><h6>Sebarang sumbangan boleh dihulurkan ke akaun berikut:</h6><br>
    <i class="fa fa-bank w3-margin-right"></i>&nbsp;&nbsp;&nbsp;Bank Rakyat: 22-006-114049-6 (Asrama Anak-Anak Yatim Batu 36 Sekendi)<br><i class="fa fa-bank w3-margin-right"></i>&nbsp;&nbsp;&nbsp;Bank Islam: 1205 6010 0162 82 (Asrama Kebajikan Anak-Anak Yatim Sekendi)</center></h3></p>
</div>

<!-- Album Section -->
<div class="section bg-light" id="album">
    <div class="container">
        <h2>Album</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <img src="/SOPHM/images/KVTI (2).jpg" alt="Service 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="/SOPHM/images/KVTI (3).jpg" alt="Service 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="/SOPHM/images/KVTI (4).jpg" alt="Service 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="/SOPHM/images/KVTI (5).jpg" alt="Service 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="/SOPHM/images/KVTI (6).jpg" alt="Service 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="/SOPHM/images/KVTI (7).jpg" alt="Service 2">
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <img src="/SOPHM/images/KVTI (8).jpg" alt="Service 2">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hubungi Kami Section -->
<div class="section" id="hubungi">
    <div class="container">
        <h2>Hubungi Kami</h2>
        <p>
            <i class="fa fa-map-marker fa-fw w3-xxlarge w3-margin-right"></i> ASRAMA KEBAJIKAN ANAK-ANAK YATIM MISKIN SEKENDI LOT 753, BATU 36 JALAN SEKENDI 45200 SABAK BERNAM, SELANGOR DARUL EHSAN<br>
            <i class="fa fa-facebook fa-fw w3-xxlarge w3-margin-right"></i> FACEBOOK: (OFFICIAL) ASRAMA KEBAJIKAN ANAK-ANAK YATIM SEKENDI<br>
            <i class="fa fa-phone fa-fw w3-xxlarge w3-margin-right"></i> PEJABAT: 03-32163818 / KERANI (Cik Widad): 0176220968<br>
            <i class="fa fa-envelope fa-fw w3-xxlarge w3-margin-right"></i> EMEL: ppakaysofficial@gmail.com
        </p>
    </div>
</div>

<!-- Footer Section -->
<footer class="bg-light text-center text-lg-start mt-4">
    <div class="text-center p-3">
        &copy; 2023 Asrama Kebajikan Anak-Anak Yatim Miskin Sekendi. Hakcipta Terpelihara.
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
