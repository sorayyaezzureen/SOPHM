<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk| Penderma</title>
    <link rel="icon" type="image" href="/SOPHM/images/logo.jpg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
        body {
            font-family: 'Poppins';
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            width: 100%;
        }
        h1 {
            color: #8A9A5B;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type='text'], input[type='password'] {
            width: 100%;
            border-radius: 4px;
            border: 1px solid #ccc;
            padding: 10px;
            color: #333;
            font-size: 14px;
            box-sizing: border-box;
        }
        input[type='submit'] {
            width: 100%;
            padding: 10px;
            color: white;
            background-color: #8A9A5B;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        input[type='submit']:hover {
            background-color: #7a8b6a;
        }
        .error-message {
            color: #d9534f;
            text-align: center;
            margin-top: 10px;
        }
        a {
            color: #8A9A5B;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<?php
require('database.php');

// If form submitted, insert values into the database.
if (isset($_POST['username'])){
    $username = stripslashes($_REQUEST['username']);
    $username = mysqli_real_escape_string($conn, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($conn, $password);

    // Checking if user exists in the database or not
    $query = "SELECT * FROM donor WHERE username = '$username' AND password = '$password'";  
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $rows = mysqli_num_rows($result);

    if($rows == 1){
        $_SESSION['donorid'] = $username;
        // Redirect user to dashboard.php
        header("Location: dashboard.php");
        exit();
    } else {
        $error_message = "Username/password is incorrect.";
    }
}
?>
<div class="container">
    <h1>Log Masuk Penderma</h1>
    <form action="" method="post" name="login">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required="required" />
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required="required" />
        </div>
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <input name="submit" type="submit" value="Login" />
    </form>
    <div class="register-link">
        Belum mendaftar sebagai penderma? <a href='registration.php'>Daftar</a>
    </div>
</div>

<!-- Footer Section -->
<!-- <footer class="bg-light text-center text-lg-start mt-4">
    <div class="text-center p-3">
        &copy; 2023 Asrama Kebajikan Anak-Anak Yatim Miskin Sekendi. Hakcipta Terpelihara.
    </div>
</footer> -->

</body>
</html>
