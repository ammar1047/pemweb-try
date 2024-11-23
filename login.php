<?php
session_start();
require 'fungsi/db.php';

$error = ""; // Inisialisasi variabel error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Periksa apakah input sudah diisi
  if (!empty($_POST['username_or_email']) && !empty($_POST['password'])) {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Cek apakah pengguna ada di database berdasarkan username atau email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
      // Jika password benar, simpan informasi pengguna dalam sesi
      $_SESSION['user_id'] = $user['user_id'];
      $_SESSION['role'] = $user['role'];

      // Alihkan pengguna sesuai dengan role mereka
      if ($user['role'] === 'admin') {
        header("Location: admin/admin_dashboard.php");
        exit();
      } elseif ($user['role'] === 'user') {
        header("Location: user/user_dashboard.php");
        exit();
      }
    } else {
      $error = "Username, email, atau password salah.";
    }
  } else {
    $error = "Harap isi semua kolom.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <!-- Bootstrap -->
  <link rel="icon" type="image/png" href="img/favicon.png">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@4.5.2/dist/cyborg/bootstrap.min.css"
    integrity="sha384-nEnU7Ae+3lD52AK+RGNzgieBWMnEfgTbRHIwEvp1XXPdqdO6uLTd/NwXbzboqjc2" crossorigin="anonymous">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

  <style>
    body {
      background-image: url('img/bg1.jpg');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
      height: 100vh;
      color: #fff;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
    }


    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
    }



    .clock {
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 26px;
      animation: fadeIn 1s;
    }

    .login-container {
      margin-top: 100px;
      /* Atur sesuai kebutuhan */
      border-radius: 20px;
      background-color: rgba(50, 50, 50, 0.5);
      box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
      animation: slideIn 0.5s forwards;
      padding: 20px;
      max-width: 1000px;
      color: black;
    }

    .title {
      text-align: center;
      font-size: 36px;
      font-weight: 800;
      animation: fadeIn 1s;
      color: black;
      text-shadow: 2px 2px 0px white;
    }

    .card {
      background-color: rgba(200, 200, 200, 0.5);
    }

    label {
      color: white;
    }

    @keyframes slideIn {
      from {
        transform: translateY(-200%);
      }

      to {
        transform: translateY(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }
  </style>
</head>

<body>

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark w-100">
    <a class="navbar-brand" href="#"><i class="fa-solid fa-music"></i> Studio Reservation</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php"><i class="fas fa-house"></i> Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="register.php"><i class="fas fa-sign-in"></i></i> Register</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="clock py-5" id="clock"></div>
  <script>
    function updateClock() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      document.getElementById('clock').innerText = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock(); // Initialize clock immediately
  </script>

  <div class="login-container">
    <div class="card">
      <div class="card-header text-center">
        <h4 class="text-white">Login</h4>
      </div>
      <!-- Tampilkan pesan sukses atau error jika ada -->
      <div class="text-center">
        <?php
        if (!empty($error)) {
          echo "<div class='alert alert-danger'>$error</div>";
        }
        ?>
      </div>

      <div class="card-body">
        <form action="" method="POST">
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username_or_email" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="text-center mt-3">
          <p>Belum memiliki akun? <b><a href="register.php" class="btn btn-warning">Daftar di
                sini</a></b></p>
        </div>
      </div>
    </div>
  </div>

  <div class="container text-center py-5 mt-5">
    <p class="mb-0">&copy; 2024 Iqbal Alfian. All Rights Reserved.</p>
    <div class="social-icons mt-3">
      <a href="#" class="mx-2"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="mx-2"><i class="fab fa-twitter"></i></a>
      <a href="#" class="mx-2"><i class="fab fa-instagram"></i></a>
      <a href="#" class="mx-2"><i class="fab fa-linkedin-in"></i></a>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>