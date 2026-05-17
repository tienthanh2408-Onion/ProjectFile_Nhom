<?php

include("config/database.php");

$id = $_GET['id'];

$sql = "SELECT * FROM motels WHERE id='$id'";

$result = mysqli_query($conn,$sql);

$row = mysqli_fetch_assoc($result);

$message = "";
$category_sql = "SELECT * FROM categories";

$category_result =
mysqli_query($conn,$category_sql);

if(isset($_POST['update_motel'])){

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $area = $_POST['area'];
    $address = $_POST['address'];
    $utilities = $_POST['utilities'];
    $phone = $_POST['phone'];
    $category_id = $_POST['category_id'];
    $image_path = $row['images'];

    if(isset($_FILES['image']) && !empty($_FILES['image']['name'])){
        $image_name = basename($_FILES['image']['name']);
        $tmp_name = $_FILES['image']['tmp_name'];
        $path = "uploads/" . $image_name;
        if(move_uploaded_file($tmp_name, $path)){
            $image_path = $path;
        }
    }
    
    $update_sql = "UPDATE motels SET

    title='$title',
    description='$description',
    price='$price',
    area='$area',
    address='$address',
    images='$image_path',
    utilities='$utilities',
    phone='$phone',
    category_id='$category_id'

    WHERE id='$id'
    ";

    if(mysqli_query($conn,$update_sql)){

        $message = "Cập nhật thành công";

        header("Refresh:1; url=list-motel.php");

    }else{

        $message = "Cập nhật thất bại";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Sửa phòng trọ</title>

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

        input, textarea, select{

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

        .message{
            color:green;
            margin-top:15px;
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

    <a href="list-motel.php" style="display:inline-block;margin-bottom:20px;padding:10px 15px;background:#6c757d;color:white;text-decoration:none;border-radius:5px;">Quay về danh sách</a>

    <h2>Sửa thông tin phòng trọ</h2>

    <form method="POST" enctype="multipart/form-data">

        <input
        type="text"
        name="title"

        value="<?php echo $row['title']; ?>">

        <textarea
        name="description"><?php echo $row['description']; ?></textarea>

        <input
        type="number"
        name="price"

        value="<?php echo $row['price']; ?>">

        <input
        type="number"
        name="area"

        value="<?php echo $row['area']; ?>">

        <input
        type="text"
        name="address"

        value="<?php echo $row['address']; ?>">

        <input
        type="text"
        name="utilities"
        value="<?php echo $row['utilities']; ?>">

        <select name="category_id">

        <?php
            while($cat = mysqli_fetch_assoc($category_result)){
        ?>

        <option value="<?php echo $cat['id']; ?>"

        <?php
        if($cat['id'] == $row['category_id']){

            echo "selected";
        }
        ?>

        >

        <?php echo $cat['name']; ?>

        </option>

        <?php } ?>

        </select>

        <input
        type="file"
        name="image"
        id="imageInput"
        accept="image/*">

        <img id="previewImage"
        src=""
        alt="Preview ảnh mới"
        style="width:100%;height:250px;object-fit:cover;margin-top:15px;display:none;border-radius:10px;">

        <?php if(!empty($row['images'])): ?>
            <img src="<?php echo $row['images']; ?>" 
            alt="Ảnh hiện tại" 
            style="width:100%;height:250px;object-fit:cover;margin-top:15px;border-radius:10px;">
        <?php endif; ?>

        <input
        type="text"
        name="phone"

        value="<?php echo $row['phone']; ?>">

        <button
        type="submit"
        name="update_motel">

            Cập nhật phòng

        </button>

    </form>

    <p class="message">
        <?php echo $message; ?>
    </p>

    <script>
        const imageInput = document.getElementById('imageInput');
        const previewImage = document.getElementById('previewImage');

        if(imageInput){
            imageInput.addEventListener('change', function(){
                const file = this.files[0];
                if(file){
                    previewImage.src = URL.createObjectURL(file);
                    previewImage.style.display = 'block';
                } else {
                    previewImage.src = '';
                    previewImage.style.display = 'none';
                }
            });
        }
    </script>

</div>

</body>
</html>