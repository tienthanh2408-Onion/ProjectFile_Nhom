<?php
session_start();

include("../config/database.php");

$message = "";

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users
    WHERE email='$email'
    AND password='$password'";

    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user'] = $user;

        header("Location: ../in.php");

    }else{
        $message = "Sai email hoặc mật khẩu";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - GTPT</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            font-family: 'Inter', sans-serif;
        }
        .auth-container {
            background: var(--white);
            padding: 40px;
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .auth-logo {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        .auth-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 24px;
        }
        .auth-form {
            text-align: left;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        .auth-form input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--bg-light);
            font-size: 14px;
            transition: var(--transition);
        }
        .auth-form input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(230, 184, 0, 0.1);
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary-color);
            color: var(--white);
            border: none;
            border-radius: var(--radius-pill);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            margin-top: 8px;
        }
        .btn-submit:hover {
            filter: brightness(0.95);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        .auth-links {
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-muted);
        }
        .auth-links a {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
        }
        .auth-links a:hover {
            text-decoration: underline;
        }
        .message {
            margin-top: 16px;
            padding: 12px;
            border-radius: var(--radius-md);
            font-size: 14px;
        }
        .message.error { background: #f8d7da; color: #dc3545; }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="auth-logo">GTPT</div>
    <h2 class="auth-title">Chào mừng trở lại</h2>

    <form method="POST" class="auth-form">
        <div class="form-group">
            <label>Email của bạn</label>
            <input type="email" name="email" placeholder="Nhập email..." required>
        </div>

        <div class="form-group">
            <label>Mật khẩu</label>
            <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
        </div>

        <button type="submit" name="login" class="btn-submit">
            Đăng nhập
        </button>
    </form>

    <?php if(!empty($message)): ?>
    <div class="message error">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <div class="auth-links">
        Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
    </div>
    <div style="margin-top:16px;">
        <a href="../in.php" style="color:var(--text-muted); font-size:13px; text-decoration:none;">← Quay lại trang chủ</a>
    </div>
</div>

</body>
</html>