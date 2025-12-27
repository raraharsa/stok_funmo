<?php
session_start();
include 'lib/koneksi.php';

$email_error = $password_error = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $valid = true;

    if (empty($email)) {
        $email_error = "Email wajib diisi";
        $valid = false;
    }

    if (empty($password)) {
        $password_error = "Password wajib diisi";
        $valid = false;
    }

    if ($valid) {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $password == $user['password']) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['level'] = $user['level'];
            $_SESSION['nama']  = $user['nama'];
            header("Location: modul/{$user['level']}_dashboard.php");
            exit();
        } else {
            $email_error = "Email / Password salah";
        }
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#00AEEF;
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}


.login-box{
    width:100%;
    max-width:420px;
    background:#fff;
    padding:42px 38px;
    border-radius:14px;
    box-shadow:0 18px 40px rgba(0,0,0,.1);
}

.logo{
    width:110px;
    display:block;
    margin:0 auto 16px;
}

.title{
    text-align:center;
    font-size:20px;
    font-weight:600;
    margin-bottom:6px;
}

.subtitle{
    text-align:center;
    font-size:13px;
    color:#777;
    margin-bottom:28px;
}

/* INPUT */
.form-group{
    position:relative;
    margin-bottom:22px;
}

.form-group i.icon-left{
    position:absolute;
    top:50%;
    left:14px;
    transform:translateY(-50%);
    color:#aaa;
    font-size:14px;
}

.form-group input{
    width:100%;
    padding:12px 44px 12px 40px; /* kanan dikasih ruang buat mata */
    border-radius:10px;
    border:1px solid #ddd;
    font-size:14px;
    transition:.2s;
}

.form-group input:focus{
    border-color:#00AEEF;
    box-shadow:0 0 0 3px rgba(0,174,239,.15);
    outline:none;
}

/* EYE */
.toggle-password{
    position:absolute;
    top:50%;
    right:14px;
    transform:translateY(-50%);
    cursor:pointer;
    color:#999;
    font-size:14px;
}

.toggle-password:hover{
    color:#00AEEF;
}

.error{
    font-size:12px;
    color:#e74c3c;
    margin-top:4px;
}

/* BUTTON */
.btn-login{
    width:100%;
    background:#00AEEF;
    color:#fff;
    border:none;
    border-radius:12px;
    padding:12px;
    font-weight:500;
    transition:.3s;
}

.btn-login:hover{
    background:#0095cc;
}

.btn-login.loading{
    pointer-events:none;
    opacity:.85;
}
</style>
</head>

<body>

<div class="login-box">
    <img src="asset/img/SMS logo.png" class="logo">

    <div class="title">Login Akun</div>
    <div class="subtitle">Silakan masuk untuk melanjutkan</div>

    <form method="POST" onsubmit="loadingBtn()">
        <div class="form-group">
            <i class="fa fa-envelope icon-left"></i>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email) ?>">
            <?php if($email_error): ?><div class="error"><?= $email_error ?></div><?php endif; ?>
        </div>

        <div class="form-group">
            <i class="fa fa-lock icon-left"></i>
            <input type="password" name="password" id="password" placeholder="Password">
            <span class="toggle-password" onclick="togglePassword()">
                <i class="fa fa-eye"></i>
            </span>
            <?php if($password_error): ?><div class="error"><?= $password_error ?></div><?php endif; ?>
        </div>

        <button class="btn-login" id="btnLogin">
            Login
        </button>
    </form>
</div>

<script>
function togglePassword(){
    const pass = document.getElementById('password');
    const icon = document.querySelector('.toggle-password i');

    if(pass.type === 'password'){
        pass.type = 'text';
        icon.classList.replace('fa-eye','fa-eye-slash');
    }else{
        pass.type = 'password';
        icon.classList.replace('fa-eye-slash','fa-eye');
    }
}

function loadingBtn(){
    const btn = document.getElementById('btnLogin');
    btn.classList.add('loading');
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Memproses...';
}
</script>

</body>
</html>
