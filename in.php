<?php
session_start();

if(!isset($_SESSION['user'])){
    header("Location: auth/login.php");
}

include("config/database.php");

$sql = "SELECT motels.*, categories.name AS category_name, users.fullname AS poster_name FROM motels LEFT JOIN categories ON motels.category_id = categories.id LEFT JOIN users ON motels.user_id = users.id ORDER BY motels.count_view DESC, motels.id DESC";

$result = mysqli_query($conn, $sql);

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>GTPT - Trang chủ</title>

    <link rel="stylesheet"
    href="assets/style.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial;
            background:#f5f5f5;
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

.menu a:hover{
    background:rgba(255,255,255,0.2);
}

.post-btn{
    background:#ff9800;
}

        .banner{
            height:350px;

            background:url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?q=80&w=1200')
            center/cover;

            display:flex;
            justify-content:center;
            align-items:center;

            color:white;
            text-align:center;
        }

        .banner-content{
            background:rgba(0,0,0,0.5);
            padding:30px;
            border-radius:10px;
        }

        .banner h1{
            font-size:42px;
            margin-bottom:15px;
        }

        .banner p{
            font-size:18px;
        }

        .container{
            width:1200px;
            margin:40px auto;
        }

        .welcome-box{
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
            margin-bottom:30px;
        }

        .welcome-box h2{
            margin-bottom:10px;
            color:#007bff;
        }

        .section-title{
            margin-bottom:20px;
            font-size:28px;
        }

        .carousel-wrapper{
            position:relative;
            overflow:hidden;
            margin-bottom:20px;
        }

        .room-list{
            display:flex;
            gap:20px;
            transition:transform 0.4s ease;
            will-change:transform;
            padding-bottom:20px;
        }

        .room-card{
            min-width:calc((100% - 40px) / 3);
            flex:0 0 calc((100% - 40px) / 3);
            background:white;
            border-radius:10px;
            overflow:hidden;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
            transition:transform 0.3s;
            cursor:grab;
        }

        .room-card:active{
            cursor:grabbing;
        }

        .carousel-dots{
            display:flex;
            justify-content:center;
            gap:10px;
            margin-top:20px;
        }

        .dot{
            width:12px;
            height:12px;
            border-radius:50%;
            background:#d1d5db;
            cursor:pointer;
            transition:background 0.2s;
        }

        .dot.active{
            background:#007bff;
        }

        .room-card:hover{
            transform:translateY(-5px);
        }

        .room-card img{
            width:100%;
            height:220px;
            object-fit:cover;
        }

        .room-info{
            padding:15px;
        }

        .room-title{
            font-size:20px;
            margin-bottom:10px;
            color:#333;
        }

        .info-row{
            display:flex;
            align-items:flex-start;
            gap:10px;
            margin:8px 0;
            font-size:14px;
            color:#555;
            flex-wrap:wrap;
        }

        .info-row .icon{
            min-width:20px;
            font-size:16px;
            line-height:1;
        }

        .price{
            color:red;
            font-weight:bold;
            margin-bottom:10px;
        }

        .address{
            color:#666;
            margin-bottom:10px;
        }

        .btn{
            display:inline-block;
            padding:10px 15px;
            background:#007bff;
            color:white;
            text-decoration:none;
            border-radius:5px;
            margin-top:10px;
        }

        .footer{
            background:#222;
            color:white;
            text-align:center;
            padding:20px;
            margin-top:50px;
        }

    </style>

</head>

<body>

<!-- MENU -->

<div class="navbar">

    <div class="logo">
        GTPT
    </div>

    <div class="menu">

    <a href="in.php">
        Trang chủ
    </a>

    <a href="list-motel.php">
        Phòng trọ
    </a>

    <a href="add-motel.php"
    class="post-btn">
        Đăng phòng
    </a>

    <a href="#">
        Liên hệ
    </a>

    <a href="auth/logout.php">
        Đăng xuất
    </a>

</div>

</div>

<!-- BANNER -->

<div class="banner">

    <div class="banner-content">

        <h1>
            Website Giới Thiệu Phòng Trọ
        </h1>

        <p>
            Tìm kiếm phòng trọ nhanh chóng và tiện lợi
        </p>

    </div>

</div>

<!-- CONTENT -->

<div class="container">

    <!-- USER INFO -->

    <div class="welcome-box">

        <h2>
            Xin chào,
            <?php echo $user['fullname']; ?>
        </h2>

        <p>
            Email:
            <?php echo $user['email']; ?>
        </p>

    </div>

    <!-- ROOM SECTION -->

    <h2 class="section-title">
        Phòng trọ nổi bật
    </h2>

    <div class="carousel-wrapper">
        <div class="room-list" id="roomList">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="room-card">

                        <img src="<?php echo $row['images']; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">

                        <div class="room-info">

                            <h3 class="room-title">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </h3>

                            <div class="info-row">
                                <span class="icon">💰</span>
                                <strong><?php echo number_format($row['price']); ?> VNĐ</strong>
                            </div>

                            <div class="info-row">
                                <span class="icon">📍</span>
                                <?php echo htmlspecialchars($row['address']); ?>
                            </div>

                            <?php if(!empty($row['area'])): ?>
                                <div class="info-row">
                                    <span class="icon">📏</span>
                                    <?php echo htmlspecialchars($row['area']); ?> m²
                                </div>
                            <?php endif; ?>

                            <div class="info-row">
                                <span class="icon">🏷️</span>
                                <?php echo htmlspecialchars($row['category_name']); ?>
                            </div>

                            <?php if(!empty($row['utilities'])): ?>
                                <div class="info-row">
                                    <span class="icon">🔧</span>
                                    <?php echo htmlspecialchars($row['utilities']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($row['poster_name'])): ?>
                                <div class="info-row">
                                    <span class="icon">👤</span>
                                    <?php echo htmlspecialchars($row['poster_name']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($row['created_at'])): ?>
                                <div class="info-row">
                                    <span class="icon">📅</span>
                                    <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
                                </div>
                            <?php endif; ?>

                            <div class="info-row">
                                <span class="icon">👁️</span>
                                Lượt xem: <?php echo number_format($row['count_view']); ?>
                            </div>

                            <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn">
                                Xem chi tiết
                            </a>

                        </div>

                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="min-width:100%; padding:20px; background:white; border-radius:10px; text-align:center;">
                    Hiện chưa có phòng trọ nào được đăng.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="carousel-dots" id="carouselDots"></div>

    <script>
        const track = document.getElementById('roomList');
        const dotsContainer = document.getElementById('carouselDots');
        const cards = document.querySelectorAll('.room-card');
        const cardsPerPage = 3;
        const totalPages = Math.ceil(cards.length / cardsPerPage);
        let currentPage = 0;
        let startX = 0;
        let isDragging = false;

        function updateCarousel() {
            const pageWidth = track.parentElement.clientWidth;
            const offset = currentPage * pageWidth;
            track.style.transform = `translateX(-${offset}px)`;
            document.querySelectorAll('.dot').forEach((dot, index) => {
                dot.classList.toggle('active', index === currentPage);
            });
        }

        function createDots() {
            dotsContainer.innerHTML = '';
            for (let i = 0; i < totalPages; i++) {
                const dot = document.createElement('div');
                dot.className = 'dot';
                if (i === 0) dot.classList.add('active');
                dot.addEventListener('click', () => {
                    currentPage = i;
                    updateCarousel();
                });
                dotsContainer.appendChild(dot);
            }
        }

        function handleDragStart(e) {
            isDragging = true;
            startX = e.pageX || e.touches[0].pageX;
            track.style.transition = 'none';
        }

        function handleDragMove(e) {
            if (!isDragging) return;
            const x = e.pageX || e.touches[0].pageX;
            const delta = x - startX;
            const pageWidth = track.parentElement.clientWidth;
            track.style.transform = `translateX(${ -currentPage * pageWidth + delta }px)`;
        }

        function handleDragEnd(e) {
            if (!isDragging) return;
            isDragging = false;
            track.style.transition = 'transform 0.4s ease';
            const x = e.pageX || (e.changedTouches ? e.changedTouches[0].pageX : startX);
            const delta = x - startX;
            if (delta < -80 && currentPage < totalPages - 1) {
                currentPage++;
            } else if (delta > 80 && currentPage > 0) {
                currentPage--;
            }
            updateCarousel();
        }

        if (cards.length > 0) {
            createDots();
            updateCarousel();
            track.addEventListener('mousedown', handleDragStart);
            track.addEventListener('touchstart', handleDragStart, { passive: true });
            document.addEventListener('mousemove', handleDragMove);
            document.addEventListener('touchmove', handleDragMove, { passive: true });
            document.addEventListener('mouseup', handleDragEnd);
            document.addEventListener('touchend', handleDragEnd);
            window.addEventListener('resize', updateCarousel);
        }
    </script>

</div>

<!-- FOOTER -->

<div class="footer">

    <p>
        © 2026 - Website Giới Thiệu Phòng Trọ
    </p>

</div>

</body>
</html>