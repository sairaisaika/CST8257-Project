<?php
    include_once("./Lab5Common/Header.php");
    ob_start();
    $imgRes = imagecreatefromjpeg("./imgs/1.jpeg");
    imagejpeg($imgRes);
    $imgBuffer = ob_get_clean();
    $base64Img = base64_encode($imgBuffer);
?>
<img style="width:25%;" src="data:image/jpeg;base64,<?php echo($base64Img); ?>" />


<?php include_once("./Lab5Common/Footer.php"); ?>
