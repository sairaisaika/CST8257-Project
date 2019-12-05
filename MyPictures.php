<?php
    include_once("./src/Lab5Common/Header.php");
    include_once("./src/Lab5Common/Connection.php");
    @session_start();
    //echo $_SESSION['userid'];
    if(!isset($_SESSION['userid'])){
        header("Location: login.php");
    }
    $userId = $_SESSION['userid'];
    $isPostback = isset($_POST['album']);
    $TheAlbum;
    $nullImages=false;
    if(isset($_POST['album'])){
        $_SESSION['album'] = $_POST['album'];
        $TheAlbum = $_SESSION['album'];
    }else {
        if(isset($_SESSION['album'])){
            $TheAlbum = $_SESSION['album'];
        }else{
            $sqlAl = "SELECT * from Album where Owner_Id=:userId";
            $Albs = $myPdo->prepare($sqlAl);
            $Albs->execute(['userId'=>$userId]);
            $fA = $Albs->fetch();
            $TheAlbum = $fA["Album_Id"];
        }
    }

    //$TheImg;

    //redirect user if not loged in

    //$albumName;
    //$selectedA = "001";
    // if($isPostback){
    //     $selectedA = $_GET['album'];
    // }
    // if(isset($_GET['album'])){
    //     $selectedA = $_GET['album'];
    // }else {
    //
    // }
    if(isset($_GET['img'])){
        $defImgSql = "SELECT * from Picture where Picture_Id=:pcid";
        $defImgStatement = $myPdo->prepare($defImgSql);
        $defImgStatement->execute(['pcid'=>$_GET['img']]);
        $pic = $defImgStatement->fetch();
        if($pic["Album_Id"]!=$TheAlbum&&$_GET['img']!=null){
            header("Location: MyPictures.php");
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
        header("Location: MyPictures.php?img=".$defImg);
    }
    //     /*
    //     $sqlAl = "SELECT * from Album where Owner_Id=:userId";
    //     $Albs = $myPdo->prepare($sqlAl);
    //     $Albs->execute(['userId'=>$userId]);
    //     $fA = $Albs->fetch();
    //     $fA["Album_Id"];*/
    // }
?>
<link rel="stylesheet" type="text/css" href="./src/css/gallery.css" />
<div class="container main">
    <h2>My Pictures</h2>
    <div class="row main">
        <div class="col-md-8 col-sm-12">
            <form action="MyPictures.php" method="post">
                <select name="album" onchange="this.form.submit()" class="drp">
                    <?php
                        $sqlAl = "SELECT * from Album where Owner_Id=:userId";
                        $Albs = $myPdo->prepare($sqlAl);
                        $Albs->execute(['userId'=>$userId]);
                        if($Albs->rowCount()==0){
                            echo "<script>alert('You do not have any Album!');window.location.href('index.php');</script>";
                            return;
                        }
                        foreach ($Albs as $aa) {
                            ?>
                                 <option value="<?php echo $aa["Album_Id"]; ?>" <?php if($TheAlbum==$aa["Album_Id"]) echo "selected"; ?> > <?php echo $aa["Title"]." -- updated on ".$aa["Date_Updated"]; ?></option>
                            <?php
                            /*if($aa["Album_Id"]==$selectedA){
                                $albumName = $aa["Album_Id"];
                            }*/
                        }

                    ?>
                </select>
            </form>
            <h2><?php echo $TheAlbum; ?></h2>
            <?php if($nullImages){ echo "<script>alert('You do not have any Photos in this Album!');</script>"; return; } ?>
            <div id="canvas"></div>
            <div id="control" hidden>
                <button id="rc" onclick="loadimage('acw')"></button>
                <button id="lc" onclick="loadimage('cw')"></button>
                <button id="dl" onclick="downloadImg('<?php echo $TheImg; ?>')"></button>
                <button id="rm" onclick=""></button>
            </div>
            <div id="thumbnail">
                <?php
                    $sqlAl = "SELECT * from Album where Owner_Id=:userId";
                    $Albs = $myPdo->prepare($sqlAl);
                    $Albs->execute(['userId'=>$userId]);
                    $theA = $Albs->fetch();
                    if($theA["Owner_Id"]==$userId){

                    }
                    $sql = "SELECT * from Picture where Album_Id=:Album_Id";
                    $imgTn = $myPdo->prepare($sql);
                    $imgTn->execute(['Album_Id'=>$TheAlbum]);
                    foreach ($imgTn as $img) {
                        $img_name = $img["FileName"];
                        $img_path = "./imgs/".$img_name;
                        ?>
                        <button style="height:100%;width:20%;background:url(<?php echo $img_path; ?>);background-size:cover;<?php if($img['Picture_Id']==$TheImg) echo 'border:blue solid;' ?>" onclick="changeImg(<?php echo $img['Picture_Id']; ?>)"/>
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
                <textarea class="comments" name="comments" placeholder="Leave Comment..."></textarea>
                <input type="submit" class="btn-primary submit" value="Add Comment" />
            </form>
        </aside>
    </div>
</div>
<script type="text/javascript" src="./src/js/gallery.js"></script>
<script src="https://kit.fontawesome.com/211a6b90b0.js" crossorigin="anonymous"></script>
<?php include_once("./src/Lab5Common/Footer.php"); ?>
