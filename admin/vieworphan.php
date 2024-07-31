<?php
session_start(); // Ensure the session is started
require('connection.php');

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['staffid'])) {
    header("Location: login.php"); // Replace with your actual login page URL
    exit();
}

$staffid = $_SESSION['staffid']; // Get the logged-in staff ID

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekod Anak Yatim | Admin</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #ddd;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        th {
            background-color: #8A9A5B;
            color: white;
            text-align: center;
        }

        .action-btn {
          display: flex;
          justify-content: space-around;
        }

        .action-btn a {
          padding: 5px 10px;
          color: white;
          text-decoration: none;
          border-radius: 5px;
        }

        .action-btn a.edit {
          background-color: #005d58;
        }

        .action-btn a.edit:hover {
          background-color: #8A9A5B;
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

<!-- Table Section -->
<div class="container" style="padding:85px 80px">
    <h2 class="text-center">Rekod Anak Yatim</h2>
    <table border="0">
      <thead>
        <tr>
          <th><strong>Nama</strong></th>
<th><strong>No. K/P</strong></th>
<th><strong>Jantina</strong></th>
<th><strong>Umur</strong></th>
<th><strong>Tarikh Lahir</strong></th>
<th><strong>Kesihatan</strong></th>
<th><strong>Status</strong></th>
<th><strong>Tahun/Tingkatan</strong></th>
<th><strong>Bil. Adik-beradik</strong></th>
<th><strong>Nama Bapa</strong></th>
<th><strong>No. Sijil Kematian</strong></th>
<th><strong>Nama Penjaga</strong></th>
<th><strong>Hubungan</strong></th>
<th><strong>No. K/P</strong></th>
<th><strong>Alamat</strong></th>
<th><strong>Pekerjaan</strong></th>
<th><strong>Pendapatan</strong></th>
<th><strong>No. telefon</strong></th>
<th><strong>Kategori</strong></th>
<th><strong>Status</strong></th>
<th><strong>Tarikh Daftar</strong></th>
<!-- <th><strong>Username</th> -->
<!-- <th><strong>Staff ID</th> -->
<th><strong>Edit</strong></th>
        </tr>
      </thead>
      <tbody>

        <?php
        // Prepare the SQL query
        $sql = "SELECT orphanid,name,idno,gender,age,birthdate,healthdetails,edugrade,edudetails,nosiblings,fathersname,deathcertno,guardianname,relationship,guardianidentificationno,guardianaddress,occupation,salary,phoneno,category,status,date,staffid from orphan ORDER BY orphanid DESC";

        // Execute the query
        if ($result = mysqli_query($con, $sql)) {
            while ($row = mysqli_fetch_assoc($result)) {
        ?>

<!-- <td align="center"><--?php echo $row['orphanid']?></td> -->
                               <td align="center"><?php echo $row['name']?></td>
                               <td align="center"><?php echo $row['idno']?></td>
                               <td align="center"><?php echo $row['gender']?></td>
                               <td align="center"><?php echo $row['age']?></td>
                               <td align="center"><?php echo $row['birthdate']?></td>
                               <td align="center"><?php echo $row['healthdetails']?></td>
                               <td align="center"><?php echo $row['edudetails']?></td>
                               <td align="center"><?php echo $row['edugrade']?></td>
                               <td align="center"><?php echo $row['nosiblings']?></td>
                               <td align="center"><?php echo $row['fathersname']?></td>
                               <td align="center"><?php echo $row['deathcertno']?></td>
                               <td align="center"><?php echo $row['guardianname']?></td>
                               <td align="center"><?php echo $row['relationship']?></td>
                               <td align="center"><?php echo $row['guardianidentificationno']?></td>
                               <td align="center"><?php echo $row['guardianaddress']?></td>
                               <td align="center"><?php echo $row['occupation']?></td>
                               <td align="center"><?php echo $row['salary']?></td>
                               <td align="center"><?php echo $row['phoneno']?></td>
                               <td align="center"><?php echo $row['category']?></td>
                               <td align="center"><?php echo $row['status']?></td>
                               <td align="center"><?php echo $row['date']?></td>
          <td class="action-btn">
              <a href="manageorphan.php?id=<?php echo htmlspecialchars($row['orphanid']); ?>" class="edit">Edit</a>
          </td>
        </tr>
        <?php 
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
        ?>
      </tbody>
    </table>
  </div><br><br><br><br><br><br><br><br><br><br>

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
