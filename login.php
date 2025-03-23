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
                $_SESSION['nama'] = $user['nama']; 

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
            <input type="text" id="password" class="form-control" name="password" required />
        </div>
        
        <button type="submit" class="btn btn-custom btn-block mb-4" name="btn">Login</button>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

       
    </form>
</div>

</body>
</html>
<style>

body {
    background-color: #161a2d; /* Beige/Cream */
    font-family: 'Poppins', sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

.login-container {
    background-color: white; /* Soft Pink */
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    width: 370px;
    text-align: center;
}

h2 {
    color: #161a2d; /* Deep Rose */
    margin-bottom: 20px;
}

.form-outline {
    text-align: left;
}

.form-label {
    font-family: 'Poppins', sans-serif;
    color: #000000; /* Black */
    font-weight: 500;
    font-size: 12px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #EAEAEA; /* Soft Grey */
    border-radius: 6px;
    background-color:rgb(216, 215, 223); /* Beige/Cream */
}

.form-control:focus {
    border-color: #161a2d; /* Warm Yellow */
    outline: none;
    box-shadow: 0 0 5px rgba(246, 215, 111, 0.5);
}

.btn-custom {
    background-color: #161a2d; /* Deep Rose */
    color: white;
    padding: 10px;
    width: 100%;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}

.btn-custom:hover {
    background-color: #161a2d; /* Gold Accent */
}

.alert-danger {
    background-color:rgb(176, 160, 248); /* Peachy Pink */
    color: #000000;
    padding: 10px;
    border-radius: 6px;
    margin-top: 10px;
}



</style>