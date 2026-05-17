<?php

session_start();

include("config/database.php");

if(!isset($_SESSION['user'])){
    header("Location: auth/login.php");
}

$message = "";

$category_sql = "SELECT * FROM categories";

$category_result = mysqli_query(
    $conn,
    $category_sql
);

if(isset($_POST['add_motel'])){

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $area = $_POST['area'];
    $address = $_POST['address'];
    $utilities = $_POST['utilities'];
    $phone = $_POST['phone'];
    $category_id = $_POST['category_id'];

    $user_id = $_SESSION['user']['id'];

    // upload image

    $image_name = $_FILES['image']['name'];

    $tmp_name = $_FILES['image']['tmp_name'];

    $path = "uploads/" . $image_name;

    move_uploaded_file($tmp_name, $path);

    $sql = "INSERT INTO motels
    (
        title,
        description,
        price,
        area,
        address,
        images,
        user_id,
        utilities,
        phone,
        category_id
    )

    VALUES
    (
        '$title',
        '$description',
        '$price',
        '$area',
        '$address',
        '$path',
        '$user_id',
        '$utilities',
        '$phone',
        '$category_id'
    )";

    if(mysqli_query($conn,$sql)){

        $message = "Đăng phòng thành công";

        $success = true;

    }else{

        $message = "Đăng phòng thất bại";
        $success = false;
    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Đăng phòng trọ</title>

    <style>

        body{
            font-family:Arial;
            background:#f5f5f5;
        }

        .container{
            width:600px;
            margin:30px auto;
            background:white;
            padding:30px;
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

        input,textarea,select{

            width:100%;
            padding:10px;
            margin-top:10px;
        }

        button{

            width:100%;
            padding:12px;
            background:#007bff;
            color:white;
            border:none;
            margin-top:15px;
        }

        h2{
            text-align:center;
        }
        .message{
            color:green;
            margin-top:15px;
        }
        .action-buttons{
            margin-top:20px;
            display:flex;
            gap:10px;
        }
        .action-buttons a{
            flex:1;
            text-align:center;
            padding:12px;
            text-decoration:none;
            color:white;
            border-radius:5px;
            font-weight:bold;
        }
        .btn-home{
            background:#28a745;
        }
        .btn-list{
            background:#007bff;
        }
        .action-buttons a:hover{
            opacity:0.8;
        }
        .popup{

    position:fixed;

    top:0;
    left:0;

    width:100%;
    height:100%;

    background:rgba(0,0,0,0.5);

    display:flex;

    justify-content:center;
    align-items:center;
}

.popup-content{

    background:white;

    width:400px;

    padding:30px;

    border-radius:10px;

    text-align:center;

    animation:showPopup 0.3s;
}

.popup-content h2{

    color:green;

    margin-bottom:15px;
}

.popup-buttons{

    margin-top:20px;

    display:flex;

    gap:10px;
}

.popup-buttons a{

    flex:1;

    padding:12px;

    text-decoration:none;

    color:white;

    border-radius:5px;
}

.popup-buttons a:first-child{

    background:#28a745;
}

.popup-buttons a:last-child{

    background:#007bff;
}

@keyframes showPopup{

    from{

        transform:scale(0.7);
        opacity:0;
    }

    to{

        transform:scale(1);
        opacity:1;
    }
}
    </style>

</head>

<body>

<div class="navbar">
    <div class="logo">GTPT</div>
    <div class="menu">
        <a href="in.php">Trang chủ</a>
        <a href="list-motel.php">Phòng trọ</a>
        <a href="add-motel.php" class="post-btn active">Đăng phòng</a>
        <a href="#">Liên hệ</a>
        <a href="auth/logout.php">Đăng xuất</a>
    </div>
</div>

<div class="container">

    <h2>Đăng tin phòng trọ</h2>

    <form method="POST"
    enctype="multipart/form-data">

        <input type="text"
        name="title"
        placeholder="Tên phòng">

        <textarea
        name="description"
        placeholder="Mô tả"></textarea>

        <input type="number"
        name="price"
        placeholder="Giá phòng">

        <input type="number"
        name="area"
        placeholder="Diện tích">

        <input type="text"
        name="address"
        placeholder="Địa chỉ">

        <input type="text"
        name="utilities"
        placeholder="Tiện ích">

        <input type="text"
        name="phone"
        placeholder="Số điện thoại">

        <select name="category_id">

    <option value="">
        Chọn loại phòng
    </option>

    <?php
    while($cat =
    mysqli_fetch_assoc($category_result)){
    ?>

    <option
    value="<?php echo $cat['id']; ?>">

        <?php echo $cat['name']; ?>

    </option>

    <?php } ?>

</select>

        <input
type="file"
name="image"
id="imageInput"

accept="image/*">

<img
id="previewImage"

style="
width:100%;
height:250px;
object-fit:cover;
margin-top:15px;
display:none;
border-radius:10px;
">

        <button type="submit"
        name="add_motel">

            Đăng phòng

        </button>

    </form>

    <?php
if(isset($success) && $success == true){
?>

<div class="popup">

    <div class="popup-content">

        <h2>
            Đăng phòng thành công
        </h2>

        <p>
            Đang chuyển đến danh sách phòng...
        </p>

        <div class="popup-buttons">
            <a href="list-motel.php">Danh sách phòng</a>
        </div>

    </div>

</div>

<?php } ?>

    <?php
        if(isset($success) && $success == true){
    ?>
    <div class="action-buttons">
        <a href="list-motel.php" class="btn-list">Xem danh sách phòng</a>
    </div>
    <?php } ?>

</div>
        <script>

const imageInput =
document.getElementById("imageInput");

const previewImage =
document.getElementById("previewImage");

imageInput.addEventListener("change",function(){

    const file = this.files[0];

    if(file){

        previewImage.src =
        URL.createObjectURL(file);

        previewImage.style.display = "block";
    }

});

</script>
    <?php
if(isset($success) && $success == true){
?>

<script>

setTimeout(function(){

    window.location =
    "list-motel.php";

},3000);

</script>

<?php } ?>
</body>
</html>