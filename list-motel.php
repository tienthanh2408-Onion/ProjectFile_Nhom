<?php

include("config/database.php");

$district_sql = "SELECT * FROM districts";
$district_result = mysqli_query($conn, $district_sql);

$category_sql = "SELECT * FROM categories";
$category_result = mysqli_query($conn, $category_sql);

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$district_id = isset($_GET['district_id']) ? $_GET['district_id'] : '';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'all';

$sql = "

SELECT
motels.*,
categories.name AS category_name,
districts.name AS district_name,
users.fullname AS poster_name

FROM motels

LEFT JOIN categories
ON motels.category_id = categories.id

LEFT JOIN districts
ON motels.district_id = districts.id

LEFT JOIN users
ON motels.user_id = users.id

WHERE 1

";

if(!empty($keyword)){
    $sql .= "\n AND (title LIKE '%$keyword%' OR utilities LIKE '%$keyword%' OR address LIKE '%$keyword%')\n";
}

if(!empty($district_id)){
    $sql .= "\n AND motels.district_id = '$district_id'\n";
}

if(!empty($category_id)){
    $sql .= "\n AND motels.category_id = '$category_id'\n";
}

if(!empty($min_price)){
    $sql .= "\n AND motels.price >= '$min_price'\n";
}

if(!empty($max_price)){
    $sql .= "\n AND motels.price <= '$max_price'\n";
}

if($tab == 'popular'){
    $sql .= "\nORDER BY motels.count_view DESC, motels.id DESC\n";
} elseif($tab == 'newest'){
    $sql .= "\nORDER BY motels.created_at DESC, motels.id DESC\n";
} elseif($tab == 'near_vinh'){
    $sql .= "\nORDER BY motels.district_id = 1 DESC, motels.id DESC\n";
} else {
    $sql .= "\nORDER BY motels.id DESC\n";
}

$result = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Danh sách phòng trọ</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
            margin:0;
        }

        .container{
            width:1200px;
            margin:auto;
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

        .search-form{
            background:white;
            padding:20px;
            border-radius:10px;
            margin-bottom:20px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        .search-grid{
            display:grid;
            grid-template-columns:repeat(6,1fr);
            gap:15px;
            align-items:end;
        }

        .tabs{
            display:flex;
            gap:10px;
            margin-bottom:20px;
            background:white;
            padding:15px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        .tab-link{
            padding:10px 20px;
            background:#e9ecef;
            border:none;
            border-radius:5px;
            cursor:pointer;
            text-decoration:none;
            color:#333;
            font-weight:bold;
            transition:0.3s;
        }

        .tab-link:hover,.tab-link.active{
            background:#007bff;
            color:white;
        }

        .room-list{
            display:grid;
            grid-template-columns:repeat(3,1fr);
            gap:20px;
        }

        .card{
            background:white;
            border-radius:10px;
            overflow:hidden;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            transition:all 0.3s;
        }

        .card:hover{
            transform:translateY(-8px);
            box-shadow:0 8px 16px rgba(0,0,0,0.15);
        }

        .card img{
            width:100%;
            height:200px;
            object-fit:cover;
            display:block;
        }

        .content{
            padding:15px;
        }

        .card h2{
            margin:0 0 10px 0;
            font-size:18px;
            color:#333;
            height:45px;
            overflow:hidden;
            text-overflow:ellipsis;
            display:-webkit-box;
            -webkit-line-clamp:2;
            line-clamp:2;
            -webkit-box-orient:vertical;
        }

        .info-row{
            display:flex;
            align-items:center;
            margin:8px 0;
            font-size:13px;
            color:#555;
        }

        .info-row strong{
            color:#333;
            min-width:100px;
        }

        .icon{
            margin-right:5px;
            font-size:14px;
        }

        .price{
            color:red;
            font-weight:bold;
        }

        .action-buttons{
            display:flex;
            gap:5px;
            margin-top:10px;
            flex-wrap:wrap;
        }

        .action-buttons a{
            flex:1;
            min-width:60px;
            text-align:center;
            padding:8px 5px;
            font-size:12px;
            text-decoration:none;
            color:white;
            border-radius:5px;
        }

        .action-buttons a:hover{
            opacity:0.8;
        }

    </style>

</head>

<body>

<div class="navbar">
    <div class="logo">GTPT</div>
    <div class="menu">
        <a href="in.php">Trang chủ</a>
        <a href="list-motel.php" class="active">Phòng trọ</a>

        <a href="add-motel.php" class="post-btn">Đăng phòng</a>
        <a href="#">Liên hệ</a>
        <a href="auth/logout.php">Đăng xuất</a>
    </div>
</div>

<div class="container">

    <h1>Danh sách phòng trọ</h1>

    <div class="tabs">
        <a href="?tab=all" class="tab-link <?php echo $tab === 'all' ? 'active' : ''; ?>">Tất cả</a>
        <a href="?tab=popular" class="tab-link <?php echo $tab === 'popular' ? 'active' : ''; ?>">Xem nhiều nhất</a>
        <a href="?tab=newest" class="tab-link <?php echo $tab === 'newest' ? 'active' : ''; ?>">Mới đăng tải</a>
        <a href="?tab=near_vinh" class="tab-link <?php echo $tab === 'near_vinh' ? 'active' : ''; ?>">Gần ĐH Vinh</a>
    </div>

    <div class="search-form">
        <form method="GET">
            <input type="hidden" name="tab" value="<?php echo htmlspecialchars($tab); ?>">
            <div class="search-grid">
                <input type="text" name="keyword" placeholder="Tìm theo tên, tiện ích, địa chỉ" value="<?php echo htmlspecialchars($keyword); ?>">
                <select name="district_id">
                    <option value="">Chọn khu vực</option>
                    <?php while($district = mysqli_fetch_assoc($district_result)): ?>
                        <option value="<?php echo $district['id']; ?>" <?php echo $district_id == $district['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($district['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <select name="category_id">
                    <option value="">Chọn loại phòng</option>
                    <?php while($category = mysqli_fetch_assoc($category_result)): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="number" name="min_price" placeholder="Giá từp (VNĐ)" value="<?php echo htmlspecialchars($min_price); ?>">
                <input type="number" name="max_price" placeholder="Giá tối đa (VNĐ)" value="<?php echo htmlspecialchars($max_price); ?>">
                <button type="submit" name="search">Tìm kiếm</button>
            </div>
            <div style="display:flex;gap:10px;margin-top:10px;">
                <button type="reset" style="flex:1;background:#6c757d;">Xóa bộ lọc</button>
                <a href="list-motel.php" style="flex:1;text-align:center;padding:10px;background:#28a745;color:white;text-decoration:none;border-radius:5px;border:none;cursor:pointer;">Xem tất cả</a>
            </div>
        </form>
    </div>

    <div class="room-list">

        <?php
        while($row = mysqli_fetch_assoc($result)){
        ?>

        <div class="card">

            <img src="<?php echo $row['images']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">

            <div class="content">

                <h2><?php echo htmlspecialchars($row['title']); ?></h2>

                <div class="info-row">
                    <span class="icon">💰</span>
                    <strong><?php echo number_format($row['price']); ?> VNĐ</strong>
                </div>

                <div class="info-row">
                    <span class="icon">📍</span>
                    <?php echo htmlspecialchars($row['address']); ?>
                </div>

                <div class="info-row">
                    <span class="icon">📏</span>
                    <strong><?php echo $row['area']; ?> m²</strong>
                </div>

                <div class="info-row">
                    <span class="icon">🏷️</span>
                    <?php echo htmlspecialchars($row['category_name']); ?>
                </div>

                <div class="info-row">
                    <span class="icon">🔧</span>
                    <?php echo htmlspecialchars($row['utilities']); ?>
                </div>

                <div class="info-row">
                    <span class="icon">👤</span>
                    <?php echo htmlspecialchars($row['poster_name'] ?? 'N/A'); ?>
                </div>

                <div class="info-row">
                    <span class="icon">📅</span>
                    <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
                </div>

                <div class="info-row">
                    <span class="icon">👁️</span>
                    <strong><?php echo number_format($row['count_view']); ?> lượt xem</strong>
                </div>

                <div class="action-buttons">
                    <a href="detail.php?id=<?php echo $row['id']; ?>" style="background:#007bff;">
                        Xem chi tiết
                    </a>
                    <a href="edit-motel.php?id=<?php echo $row['id']; ?>" style="background:#28a745;">
                        Sửa
                    </a>
                    <a href="delete-motel.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Bạn có chắc muốn xóa không?')" style="background:#dc3545;">
                        Xóa
                    </a>
                </div>

            </div>

        </div>

        <?php } ?>

    </div>

</div>

</body>
</html>