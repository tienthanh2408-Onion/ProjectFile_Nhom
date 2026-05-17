<?php

include("config/database.php");

$sql = "SELECT * FROM users";

$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_assoc($result)){

    echo $row['fullname'];
    echo "<br>";
}

?>