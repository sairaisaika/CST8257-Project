<?php
    include_once("./src/Lab5Common/Header.php");
    include_once("./src/Lab5Common/Connection.php");
    if(!isset($_SESSION['userid'])){
        header("Location: login.php");
    }
    $userId = $_SESSION['userid'];

    //redirect user if not loged in
    $isPostback = isset($_GET['album']);
    //$albumName;
    $selectedA = "001";
    if($isPostback){
        $selectedA = $_GET['album'];
    }
?>
<link rel="stylesheet" type="text/css" href="./src/css/gallery.css" />
<div class="container main">
    <h2>My Pictures</h2>
    <div class="row main">
        <div class="col-md-8 col-sm-12">
            <form action="" method="get">
                <select name="album" onchange="this.form.submit()" class="drp">
                    <?php
                        $sqlAl = "SELECT * from Album where Owner_Id=:userId";
                        $Albs = $myPdo->prepare($sqlAl);
                        $Albs->execute(['userId'=>$userId]);
                        foreach ($Albs as $aa) {
                            ?>
                                 <option value="<?php echo $aa["Album_Id"]; ?>"><?php echo $aa["Title"]." -- updated on ".$aa["Date_Updated"]; ?></option>
                            <?php
                            /*if($aa["Album_Id"]==$selectedA){
                                $albumName = $aa["Album_Id"];
                            }*/
                        }

                    ?>
                </select>
            </form>
            <h2><?php echo $selectedA; ?></h2>
            <div id="canvas"></div>
            <div id="control" hidden>
                <button id="rc" onclick="loadimage('acw')"></button>
                <button id="lc" onclick="loadimage('cw')"></button>
                <button id="dl" onclick="downloadImg('<?php echo $_GET['img']; ?>')"></button>
                <button id="rm" onclick=""></button>
            </div>
            <div id="thumbnail">
                <?php
                    $sql = "SELECT * from Picture where Album_Id=:Album_Id";
                    $imgTn = $myPdo->prepare($sql);
                    $imgTn->execute(['Album_Id'=>$selectedA]);
                    foreach ($imgTn as $img) {
                        $img_name = $img["FileName"];
                        $img_path = "./imgs/".$img_name;
                        ?>
                        <button style="height:100%;width:20%;background:url(<?php echo $img_path; ?>);background-size:cover;" onclick="changeImg(<?php echo $img['Picture_Id']; ?>)"/>
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
                    $imgInfo->execute(['picid'=>$_GET['img']]);
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
                    $commentInfo->execute(['picid'=>$_GET['img']]);
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
