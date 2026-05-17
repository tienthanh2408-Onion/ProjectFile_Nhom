<?php

include("config/database.php");

$id = $_GET['id'];

mysqli_query($conn, "UPDATE motels SET count_view = count_view + 1 WHERE id='$id'");

$sql = "

SELECT
motels.*,
categories.name AS category_name

FROM motels

LEFT JOIN categories
ON motels.category_id = categories.id

WHERE motels.id='$id'

";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Chi tiết phòng</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
        }

        .container{

            width:800px;
            margin:30px auto;
            background:white;
            padding:20px;
            border-radius:10px;
        }

        .navbar{
            background:#007bff;
            color:white;
            padding:15px 40px;
            display:flex;
            justify-content:space-between;
            align-items:center;
            border-radius:0 0 10px 10px;
            margin-bottom:20px;
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

        img{

            width:100%;
            height:400px;
            object-fit:cover;
        }

        .price{

            color:red;
            font-size:24px;
            font-weight:bold;
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

    <img src="<?php echo $row['images']; ?>">

    <h1>
        <?php echo $row['title']; ?>
    </h1>

    <p class="price">
        <?php echo number_format($row['price']); ?>
        VNĐ
    </p>

    <p>
        <?php echo $row['description']; ?>
    </p>

    <p>
        Địa chỉ:
        <?php echo $row['address']; ?>
    </p>

    <p>
        Tiện ích:
        <?php echo $row['utilities']; ?>
    </p>

    <p>

        Loại phòng:
        <?php echo $row['category_name']; ?>
    </p>

    <p>
        SĐT:
        <?php echo $row['phone']; ?>
    </p>

    <p>
        Lượt xem:
        <?php echo number_format($row['count_view']); ?>
    </p>

    <div style="margin-top:20px; display:flex; gap:10px; flex-wrap:wrap;">
        <a href="list-motel.php" style="padding:10px 15px; background:#6c757d; color:white; text-decoration:none; border-radius:5px;">Quay lại</a>
        <a href="delete-motel.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa phòng này?')" style="padding:10px 15px; background:#dc3545; color:white; text-decoration:none; border-radius:5px;">Xóa</a>
    </div>

</div>

</body>
</html>