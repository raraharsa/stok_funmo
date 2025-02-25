<?php
session_start();
include 'lib/koneksi.php'; // Koneksi database dengan PDO

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Query untuk mencari user berdasarkan email
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Cek apakah user ditemukan
        if ($user) {
            // Bandingkan password biasa (plaintext)
            if ($password == $user['password']) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['level'] = $user['level'];

                // Redirect sesuai level
                if ($user['level'] == 'admin') {
                    header("Location: modul/admin_dashboard.php");
                } elseif ($user['level'] == 'petugas') {
                    header("Location: modul/petugas_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                echo "<div style='color: red;'>Password salah.</div>";
            }
        } else {
            echo "<div style='color: red;'>Email tidak ditemukan.</div>";
        }
    } catch (PDOException $e) {
        echo "<div style='color: red;'>Error: " . $e->getMessage() . "</div>";
    }  
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #F0F8FF; /* Soft Blue background */
            font-family: Arial, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #003366; /* Navy color */
        }
        .form-label {
            color: #003366;
        }
        .form-control {
            border-radius: 5px;
            border: 1px solid #003366;
        }
        .btn-custom {
            color: #fff;
            background-color: #003366; /* Navy background */
            border: none;
            padding: 10px 20px;
            width: 100%;
            border-radius: 5px;
        }
        .btn-custom:hover {
            background-color: #002244; /* Darker navy for hover effect */
        }
        .alert {
            margin-top: 15px;
        }
        .text-center p {
            color: #003366;
        }
        .text-center a {
            color: #003366;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login</h2>
    <form method="POST">
        <div class="form-outline mb-4">
            <label class="form-label">Masukkan Email:</label>
            <input type="email"  id="email" class="form-control" name="email" required />
        </div>
        
        <div class="form-outline mb-4">
            <label class="form-label">Masukkan Password:</label>
            <input type="password" id="password" class="form-control" name="password" required />
        </div>
        
        <button type="submit" class="btn btn-custom btn-block mb-4" name="btn">Login</button>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="text-center">
            <p>Belum punya akun? <a href="modul/user.php" class="btn btn-link">Daftar Akun</a></p>
        </div>
    </form>
</div>

</body>
</html>
