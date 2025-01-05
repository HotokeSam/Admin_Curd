<?php
$get = mysqli_query($con, "SELECT * FROM products WHERE supplier='futomushop'");
while($f = mysqli_fetch_assoc($get)) {
    $f['description'] = preg_replace('/&lt;img (.*?)\/&gt;/', '', $f['description']);
    $up = mysqli_query($con, "UPDATE products SET description='".$f['description']."' WHERE productId='".$f['productId']."'");
}


?>