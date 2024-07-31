<?php
session_start(); // Ensure the session is started
require('connection.php');

// Check if user is logged in; if not, redirect to login page
if (!isset($_SESSION['donorid'])) {
    header("Location: login.php"); // Replace with your actual login page URL
    exit();
}

$donorid = $_SESSION['donorid']; // Get the logged-in donor ID
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Sumbangan | Penderma</title>
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

        .action-btn a.view {
          background-color: #005d58;
        }

        .action-btn a.view:hover {
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
                        Sumbangan
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

<!-- Table Section -->
<div class="container" style="padding:85px 80px">
    <h2 class="text-center">Rekod Sumbangan</h2>
    <table border="0">
      <thead>
        <tr>
            <th><strong>ID Penderma</strong></th>
          <th><strong>Tarikh</strong></th>
            <th><strong>Tempat/ Kaedah</strong></th>
            <th><strong>Kategori</strong></th>
            <th><strong>Kuantiti barang</strong></th>
            <th><strong>Jumlah Sumbangan</strong></th>
            
        </tr>
      </thead>
      <tbody>

        <?php
        // Use prepared statements to prevent SQL injection
        $sql = "SELECT donation.donationid, donation.datetime, donation.place, donation.quantity, donation.amountrm, donation.donorid, donation.category, donor.name 
                FROM donation 
                INNER JOIN donor ON donation.donorid = donor.donorid 
                WHERE donor.username = ?";

        if ($stmt = $con->prepare($sql)) {
            // Bind the parameter
            $stmt->bind_param('s', $donorid); // 's' for string type
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) { ?>
              <tr>
                <td><?php echo htmlspecialchars($row['donorid']); ?></td>
                <td><?php echo htmlspecialchars($row['datetime']); ?></td>
                <td><?php echo htmlspecialchars($row['place']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['amountrm']); ?></td>
                
              </tr>
            <?php
            }
            
            $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement.";
        }
        ?>
      </tbody>
    </table>
  </div><br><br><br><br><br><br><br><br>

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
