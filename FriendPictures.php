<?php
    include_once("./src/Lab5Common/Header.php");
    include_once("./src/Lab5Common/Connection.php");
    @session_start();
    if(!isset($_SESSION['userid'])){
        header("Location: login.php");
    }
    $userId = $_SESSION['userid'];
    $isPostback = isset($_POST['album']);
    $TheAlbum;
    $nullImages=false;
    $friendId;
    $friendName;
    $isFriend = false;
    if(isset($_GET['friendId'])){
        $_SESSION['friendId']=$_GET['friendId'];
        $friendId = $_SESSION['friendId'];
    }else if(isset($_SESSION['friendId'])){
        $friendId = $_SESSION['friendId'];
    }else{
        header("Location: login.php");
    }
    if(!$friendId){
        Header("Location: Index.php");
        return;
    }else {
        $sqlFriendValidation = "SELECT * FROM CST8257.Friendship where (Friendship.Friend_RequesteeId=:userId or Friendship.Friend_RequesterId=:userId) and Friendship.Status=0;";
        $fv = $myPdo->prepare($sqlFriendValidation);
        $fv->execute(['userId'=>$userId]);
        foreach ($fv as $friShip) {
            if($friShip["Friend_RequesterId"]==$friendId||$friShip["Friend_RequesteeId"]==$friendId){
                if($friShip["Status"]==0)
                    $isFriend = true;
            }
        }
    }
    if(!$isFriend){
        Header("Location: Index.php");
    }


    if(isset($_POST['album'])){
        $_SESSION['albumFr'] = $_POST['album'];
        $TheAlbum = $_SESSION['albumFr'];
    }else {
        if(isset($_SESSION['albumFr'])){
            $TheAlbum = $_SESSION['albumFr'];
        }else{
            $sqlAl = "SELECT * from Album where Owner_Id=:userId";
            $Albs = $myPdo->prepare($sqlAl);
            $Albs->execute(['userId'=>$friendId]);
            $fA = $Albs->fetch();
            $TheAlbum = $fA["Album_Id"];
        }
    }
    if(isset($_GET['img'])&&$_GET['img']){
        $defImgSql = "SELECT * from Picture where Picture_Id=:pcid";
        $defImgStatement = $myPdo->prepare($defImgSql);
        $defImgStatement->execute(['pcid'=>$_GET['img']]);
        $pic = $defImgStatement->fetch();
        if($pic["Album_Id"]!=$TheAlbum&&$_GET['img']!=null){
            header("Location: FriendPictures.php");
        }else if($_GET['img']==null){
            $nullImages = true;
        }
        $TheImg = $_GET['img'];
    }else {
        $defImgSql = "SELECT * from Picture where Album_Id=:alid";
        $defImgStatement = $myPdo->prepare($defImgSql);
        $defImgStatement->execute(['alid'=>$TheAlbum]);
        $defImgInfo = $defImgStatement->fetch();
        $defImg = $defImgInfo['Picture_Id'];
        $TheImg = $defImg;
        if($TheImg)
            header("Location: FriendPictures.php?img=".$defImg);
        else{
            $nullImages=true;
        }
    }
    // $sqlFriendValidation = "SELECT  from User where "
    // if(isset($_POST['album'])){
    //     $_SESSION['album'] = $_POST['album'];
    //     $TheAlbum = $_SESSION['album'];
    // }else {
    //     if(isset($_SESSION['album'])){
    //         $TheAlbum = $_SESSION['album'];
    //     }else{
    //         $sqlAl = "SELECT * from Album where Owner_Id=:userId";
    //         $Albs = $myPdo->prepare($sqlAl);
    //         $Albs->execute(['userId'=>$userId]);
    //         $fA = $Albs->fetch();
    //         $TheAlbum = $fA["Album_Id"];
    //     }
    // }
    // if(isset($_GET['img'])){
    //     $defImgSql = "SELECT * from Picture where Picture_Id=:pcid";
    //     $defImgStatement = $myPdo->prepare($defImgSql);
    //     $defImgStatement->execute(['pcid'=>$_GET['img']]);
    //     $pic = $defImgStatement->fetch();
    //     if($pic["Album_Id"]!=$TheAlbum&&$_GET['img']!=null){
    //         header("Location: FriendPictures.php");
    //     }else if($_GET['img']==null){
    //         $nullImages = true;
    //     }
    //     $TheImg = $_GET['img'];
    // }else {
    //     $defImgSql = "SELECT * from Picture where Album_Id=:alid";
    //     $defImgStatement = $myPdo->prepare($defImgSql);
    //     $defImgStatement->execute(['alid'=>$TheAlbum]);
    //     $defImgInfo = $defImgStatement->fetch();
    //     $defImg = $defImgInfo['Picture_Id'];
    //     $TheImg = $defImg;
    //     header("Location: FriendPictures.php?img=".$defImg);
    // }
?>
<link rel="stylesheet" type="text/css" href="./src/css/gallery.css" />
<div class="container main">
    <?php
        $friendNameSql = "SELECT Name from User where UserId=:userId;";
        $fns = $myPdo->prepare($friendNameSql);
        $fns->execute(['userId'=>$friendId]);
        $friendName=$fns->fetch();
    ?>
    <h2><?php echo $friendName[0]; ?> Pictures</h2>
    <div class="row main">
        <div class="col-md-8 col-sm-12">
            <form action="FriendPictures.php" method="post">
                <select name="album" onchange="this.form.submit()" class="drp">
                    <?php
                        $sqlAl = "SELECT * from Album where Owner_Id=:userId and Accessibility_Code='shared'";
                        $Albs = $myPdo->prepare($sqlAl);
                        $Albs->execute(['userId'=>$friendId]);
                        if($Albs->rowCount()==0){
                            echo "<script>alert('Your Friend does not have any Album!');window.location.href('index.php');</script>";
                            return;
                        }
                        foreach ($Albs as $aa) {
                            ?>
                                 <option value="<?php echo $aa["Album_Id"]; ?>" <?php if($TheAlbum==$aa["Album_Id"]) echo "selected"; ?> > <?php echo $aa["Title"]." -- updated on ".$aa["Date_Updated"]; ?></option>
                            <?php
                        }

                    ?>
                </select>
            </form>
            <h2><?php echo $TheAlbum; ?></h2>
            <?php if($nullImages){ echo "<script>alert('Your Friend does not have any Photos in this Album!');</script>"; return; } ?>
            <div id="canvas"></div>
            <div id="thumbnail">
                <?php
                    $sqlAl = "SELECT * from Album where Owner_Id=:userId";
                    $Albs = $myPdo->prepare($sqlAl);
                    $Albs->execute(['userId'=>$friendId]);
                    $theA = $Albs->fetch();
                    if($theA["Owner_Id"]==$friendId){

                    }
                    $sql = "SELECT * from Picture where Album_Id=:Album_Id";
                    $imgTn = $myPdo->prepare($sql);
                    $imgTn->execute(['Album_Id'=>$TheAlbum]);
                    foreach ($imgTn as $img) {
                        $img_name = $img["FileName"];
                        $img_path = "./imgs/".$img_name;
                        ?>
                        <button style="height:100%;width:20%;background:url(<?php echo $img_path; ?>);background-size:cover;<?php if($img['Picture_Id']==$TheImg) echo 'border:blue solid;' ?>" onclick="changeImgFri(<?php echo $img['Picture_Id']; ?>)"/>
                        <?php
                    }
                ?>
            </div>
        </div>
        <aside class="col-md-4 col-sm-12">
            <div class="sidetextbox">
                <br/>
                <b>Description:</b><br/>
                <?php

                    $sql = "SELECT * from Picture where Picture_Id = :picid";
                    $imgInfo= $myPdo->prepare($sql);
                    $imgInfo->execute(['picid'=>$TheImg]);
                    $img_info = $imgInfo->fetch();
                    echo $img_info['Description'];
                ?>
                <br/>
                <br/>
                <br/>
                <b>Comments:</b><br/>
                <?php
                    $sql = "SELECT Date, Comment_Text, Name from Comment join User on UserId=Author_Id where Picture_Id = :picid";
                    $commentInfo= $myPdo->prepare($sql);
                    $commentInfo->execute(['picid'=>$TheImg]);
                    foreach ($commentInfo as $comment) {
                        echo "<div style='color:blue'>".$comment['Name']."(".$comment['Date']."):</div>".$comment["Comment_Text"]."<br/><br/>";
                    }
                ?>

            </div>
            <form action="add_comment.php" method="post">
                <input hidden value="<?php echo $_GET["img"]; ?>" name="imgId" type="text" />
                <input hidden value="friend" name="friend" type="text" />
                <textarea class="comments" name="comments" placeholder="Leave Comment..."></textarea>
                <input type="submit" class="btn-primary submit" value="Add Comment" />
            </form>
        </aside>
    </div>
</div>
<script type="text/javascript" src="./src/js/gallery.js"></script>
<script src="https://kit.fontawesome.com/211a6b90b0.js" crossorigin="anonymous"></script>
<?php include_once("./src/Lab5Common/Footer.php"); ?>
