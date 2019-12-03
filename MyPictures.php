<?php
    include_once("./src/Lab5Common/Header.php");
    include_once("./src/Lab5Common/Connection.php");
?>
<link rel="stylesheet" type="text/css" href="./src/css/gallery.css" />
<div class="container main">
    <h2>My Pictures</h2>
    <div class="row main">
        <div class="col-md-8 col-sm-12">
            <div id="canvas"></div>
            <div id="control" hidden>
                <button id="rc" onclick="loadimage('acw')"></button>
                <button id="lc" onclick="loadimage('cw')"></button>
                <button id="dl" onclick="downloadImg('<?php echo $_GET['img']; ?>')"></button>
                <button id="rm" onclick=""></button>
            </div>
        </div>
        <aside class="col-md-4 col-sm-12">
            <div contenteditable="true" class="sidetextbox">
                <b>Description:</b><br/>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum<br/>
                <b>Comments:</b><br/>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum
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
