<?php

session_start();

include("config/database.php");

if(!isset($_SESSION['user'])){
    header("Location: auth/login.php");
    exit;
}

$user = $_SESSION['user'];

$user_id = $user['id'];

$sql = "SELECT motels.*, categories.name AS category_name FROM motels LEFT JOIN categories ON motels.category_id = categories.id WHERE motels.user_id = '$user_id' ORDER BY motels.id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang người dùng</title>
    <style>
        body{
            font-family:Arial, sans-serif;
            background:#f5f5f5;
            margin:0;
        }
        .navbar{
            background:#007bff;
            color:white;
            padding:15px 40px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        .logo{
            font-size:24px;
            font-weight:bold;
        }
        .menu a{
            color:white;
            text-decoration:none;
            margin-left:20px;
            padding:8px 12px;
            border-radius:5px;
            transition:0.3s;
        }
        .menu a:hover,
        .menu a.active{
            background:rgba(255,255,255,0.2);
        }
        .post-btn{
            background:#ff9800;
        }
        .container{
            width:1200px;
            margin:30px auto;
        }
        .profile-card{
            background:white;
            border-radius:10px;
            padding:25px;
            box-shadow:0 0 15px rgba(0,0,0,0.08);
            margin-bottom:25px;
        }
        .profile-card h2{
            margin-top:0;
            color:#007bff;
        }
        .profile-row{
            display:grid;
            grid-template-columns:repeat(2,1fr);
            gap:15px;
            margin-top:20px;
        }
        .profile-item{
            background:#f8f9fa;
            padding:15px;
            border-radius:8px;
        }
        .profile-item strong{
            display:block;
            margin-bottom:8px;
            color:#333;
        }
        .room-list{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:20px;
        }
        .room-card{
            background:white;
            border-radius:10px;
            overflow:hidden;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
            transition:transform 0.3s;
        }
        .room-card:hover{
            transform:translateY(-5px);
        }
        .room-card img{
            width:100%;
            height:180px;
            object-fit:cover;
        }
        .room-card .content{
            padding:15px;
        }
        .room-card h3{
            margin:0 0 10px 0;
            font-size:18px;
        }
        .room-card p{
            margin:6px 0;
            color:#555;
            font-size:14px;
        }
        .room-card .price{
            color:#d63031;
            font-weight:bold;
            margin-top:8px;
        }
        .room-card .btn{
            display:inline-block;
            margin-top:12px;
            padding:10px 14px;
            background:#007bff;
            color:white;
            text-decoration:none;
            border-radius:6px;
            font-size:14px;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="logo">GTPT</div>
        <div class="menu">
            <a href="in.php">Trang chủ</a>
            <a href="list-motel.php">Phòng trọ</a>
            <a href="add-motel.php" class="post-btn">Đăng phòng</a>
            <a href="#">Liên hệ</a>
            <a href="auth/logout.php">Đăng xuất</a>
        </div>
    </div>
    <div class="container">
        <div class="profile-card">
            <h2>Thông tin người dùng</h2>
            <div class="profile-row">
                <div class="profile-item">
                    <strong>Họ và tên</strong>
                    <?php echo htmlspecialchars($user['fullname']); ?>
                </div>
                <div class="profile-item">
                    <strong>Email</strong>
                    <?php echo htmlspecialchars($user['email']); ?>
                </div>
                <div class="profile-item">
                    <strong>Số điện thoại</strong>
                    <?php echo htmlspecialchars($user['phone']); ?>
                </div>
                <div class="profile-item">
                    <strong>ID người dùng</strong>
                    <?php echo htmlspecialchars($user['id']); ?>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <h2>Phòng trọ bạn đã đăng</h2>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="room-list">
                    <?php while($room = mysqli_fetch_assoc($result)): ?>
                        <div class="room-card">
                            <img src="<?php echo htmlspecialchars($room['images']); ?>" alt="<?php echo htmlspecialchars($room['title']); ?>">
                            <div class="content">
                                <h3><?php echo htmlspecialchars($room['title']); ?></h3>
                                <p class="price"><?php echo number_format($room['price']); ?> VNĐ</p>
                                <p><?php echo htmlspecialchars($room['address']); ?></p>
                                <p>Loại: <?php echo htmlspecialchars($room['category_name']); ?></p>
                                <a href="detail.php?id=<?php echo $room['id']; ?>" class="btn">Xem chi tiết</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>Bạn chưa đăng phòng trọ nào.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>