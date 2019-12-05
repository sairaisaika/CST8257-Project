<?php
    include_once("./src/Lab5Common/Connection.php");
    @session_start();
    if(!isset($_SESSION['userid'])){
        header("Location: login.php");
    }
    $userId = $_SESSION['userid'];
    $img = $_POST['imgId'];
    $comment = htmlspecialchars($_POST['comments'], ENT_QUOTES);
    $sql = "INSERT INTO `Comment` (`Comment_Id`, `Author_Id`, `Picture_Id`, `Comment_Text`, `Date`) VALUES (NULL, :userId, :ImgId, :content, CURRENT_TIMESTAMP);";
    $imgDownload = $myPdo->prepare($sql);
    if($imgDownload->execute(['userId'=>$userId,'ImgId'=>$img,'content'=>$comment])){
        header("Location: MyPictures.php?img=".$img);
    }else{
        echo "Failed to add comment";
    }
?>
