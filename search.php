<?php
header("Location: list-motel.php");
exit;

include("config/database.php");

/*
LOAD DISTRICT
*/

$district_sql = "SELECT * FROM districts";

$district_result =
mysqli_query($conn,$district_sql);

/*
LOAD CATEGORY
*/

$category_sql = "SELECT * FROM categories";

$category_result =
mysqli_query($conn,$category_sql);

/*
DEFAULT SQL
*/

$sql = "

SELECT
motels.*,
districts.name AS district_name,
categories.name AS category_name

FROM motels

LEFT JOIN districts
ON motels.district_id = districts.id

LEFT JOIN categories
ON motels.category_id = categories.id

WHERE 1

";

/*
SEARCH
*/

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$district_id = isset($_GET['district_id']) ? $_GET['district_id'] : '';
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : '';
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : '';

if(isset($_GET['search'])){

    /*
    KEYWORD
    */

    if(!empty($keyword)){

        $sql .= "

        AND
        (
            title LIKE '%$keyword%'
            OR
            utilities LIKE '%$keyword%'
            OR
            address LIKE '%$keyword%'
        )

        ";
    }

    /*
    DISTRICT
    */

    if(!empty($district_id)){

        $sql .= "

        AND district_id = '$district_id'

        ";
    }

    /*
    CATEGORY
    */

    if(!empty($category_id)){

        $sql .= "

        AND category_id = '$category_id'

        ";
    }

    /*
    MIN PRICE
    */

    if(!empty($min_price)){

        $sql .= "

        AND price >= '$min_price'

        ";
    }

    /*
    MAX PRICE
    */

    if(!empty($max_price)){

        $sql .= "

        AND price <= '$max_price'

        ";
    }

}

$result = mysqli_query($conn,$sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Tìm kiếm phòng trọ</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
            margin:0;
        }

        .container{
            width:1200px;
            margin:30px auto;
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

        /*
        SEARCH BOX
        */

        .search-box{

            background:white;

            padding:25px;

            border-radius:10px;

            margin-bottom:30px;

            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        .search-grid{

            display:grid;

            grid-template-columns:
            repeat(6,1fr);

            gap:15px;

            align-items:end;
        }

        input,
        select{

            width:100%;

            padding:12px;

            box-sizing:border-box;
        }

        button{

            width:100%;

            padding:14px;

            background:#007bff;

            color:white;

            border:none;

            margin-top:20px;

            cursor:pointer;

            font-size:16px;
        }

        button:hover{
            background:#0056b3;
        }

        /*
        ROOM LIST
        */

        .room-list{

            display:grid;

            grid-template-columns:
            repeat(3,1fr);

            gap:20px;
        }

        .card{

            background:white;

            border-radius:10px;

            overflow:hidden;

            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        .card img{

            width:100%;

            height:220px;

            object-fit:cover;
        }

        .content{
            padding:15px;
        }

        .price{

            color:red;

            font-weight:bold;

            margin:10px 0;
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

        .btn:hover{
            opacity:0.8;
        }

        h1{
            margin-bottom:20px;
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

    <h1>
        Tìm kiếm phòng trọ
    </h1>

    <!-- SEARCH FORM -->

    <div class="search-box">

        <form method="GET">

            <div class="search-grid">

                <!-- KEYWORD -->

                <input
                type="text"

                name="keyword"

                placeholder="Tìm theo tên, tiện ích..."

                value="<?php echo htmlspecialchars($keyword); ?>">

                <!-- DISTRICT -->

                <select name="district_id">

                    <option value="">
                        Chọn khu vực
                    </option>

                    <?php
                    while($district =
                    mysqli_fetch_assoc($district_result)){
                    ?>

                    <option
                    value="<?php echo $district['id']; ?>"
                    <?php echo $district_id == $district['id'] ? 'selected' : ''; ?>>

                        <?php echo htmlspecialchars($district['name']); ?>

                    </option>

                    <?php } ?>

                </select>

                <!-- CATEGORY -->

                <select name="category_id">

                    <option value="">
                        Chọn loại phòng
                    </option>

                    <?php
                    while($category =
                    mysqli_fetch_assoc($category_result)){
                    ?>

                    <option
                    value="<?php echo $category['id']; ?>"
                    <?php echo $category_id == $category['id'] ? 'selected' : ''; ?>>

                        <?php echo htmlspecialchars($category['name']); ?>

                    </option>

                    <?php } ?>

                </select>

                <!-- MIN PRICE -->

                <input
                type="number"

                name="min_price"

                placeholder="Giá từp (VNĐ)"

                value="<?php echo htmlspecialchars($min_price); ?>">

                <!-- MAX PRICE -->

                <input
                type="number"

                name="max_price"

                placeholder="Giá tối đa (VNĐ)"

                value="<?php echo htmlspecialchars($max_price); ?>">

            </div>

            <button
            type="submit"
            name="search"
            style="margin-top:0;">

                Tìm kiếm

            </button>

            <div style="display:flex;gap:10px;margin-top:15px;">

                <button
                type="reset"
                style="flex:1;background:#6c757d;color:white;border:none;padding:14px;border-radius:5px;cursor:pointer;font-size:16px;">

                    Xóa bộ lọc

                </button>

                <a
                href="search.php"
                style="flex:1;text-align:center;padding:14px;background:#28a745;color:white;text-decoration:none;border-radius:5px;font-size:16px;">

                    Xem tất cả

                </a>

            </div>

        </form>

    </div>

    <!-- RESULT -->

    <div class="room-list">

        <?php
        while($row =
        mysqli_fetch_assoc($result)){
        ?>

        <div class="card">

            <img
            src="<?php echo $row['images']; ?>">

            <div class="content">

                <h2>
                    <?php echo $row['title']; ?>
                </h2>

                <p class="price">

                    <?php
                    echo number_format(
                    $row['price']
                    );
                    ?>

                    VNĐ

                </p>

                <p>

                    Khu vực:
                    <?php
                    echo $row['district_name'];
                    ?>

                </p>

                <p>

                    Loại:
                    <?php
                    echo $row['category_name'];
                    ?>

                </p>

                <p>

                    Địa chỉ:
                    <?php
                    echo $row['address'];
                    ?>

                </p>

                <p>

                    Tiện ích:
                    <?php
                    echo $row['utilities'];
                    ?>

                </p>

                <a
                href="detail.php?id=<?php echo $row['id']; ?>"

                class="btn">

                    Xem chi tiết

                </a>

            </div>

        </div>

        <?php } ?>

    </div>

</div>

</body>
</html>