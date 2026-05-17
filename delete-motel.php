<?php

include("config/database.php");

$id = $_GET['id'];

$sql = "DELETE FROM motels WHERE id='$id'";

if(mysqli_query($conn,$sql)){

    header("Location: list-motel.php");

}else{

    echo "Xóa thất bại";
}

?>