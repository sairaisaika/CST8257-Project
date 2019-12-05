<?php
    include_once("./src/Lab5Common/Connection.php");
    @session_start();
    if(!isset($_SESSION['userid'])){
        header("Location: login.php");
    }
    if(isset($_GET['imgId'])){
        $imgSql = "SELECT Album.Owner_Id from Picture Join Album on Picture.Album_Id=Album.Album_Id where Picture_Id=:imgid;";
        $imgStatement = $myPdo->prepare($imgSql);
        $imgStatement->execute([':imgid'=>$_GET['imgId']]);
        $imgInfo = $imgStatement->fetch();
        if($imgInfo["Owner_Id"]==$_SESSION['userid']){
            $delSql = "delete from Picture where Picture_Id=:imgid";
            $delStatement = $myPdo->prepare($delSql);
            if($delStatement->execute([':imgid'=>$_GET['imgId']]))
                header("Location: MyPictures.php");
        }
    }
?>
