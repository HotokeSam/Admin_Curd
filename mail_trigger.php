<?php
include("_/global.php");
if(isset($_GET['email'])) {
    $up = mysqli_query($con, "UPDATE emails SET `open`='true' WHERE `open`='".$_GET['email']."'");
}
header('Content-Type: image/png');
echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=');