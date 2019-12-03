<?php
    header('Content-type:image/JPEG');
    include_once("./src/Lab5Common/Connection.php");
    $sql = "SELECT FileName from Picture where Picture_Id = :picid";
    $imgDownload = $myPdo->prepare($sql);
    $imgDownload->execute(['picid'=>$_GET['imgId']]);
    $img_name = $imgDownload->fetch();
    $img_path = "./imgs/".$img_name[0];

    ReadFile($img_path);

?>
