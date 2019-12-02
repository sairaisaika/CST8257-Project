<?php
    include_once("./Lab5Common/Header.php");
?>
<h2 style="text-align:center">My Pictures</h2>
<div style="">
<div style="width:45%;height:300px" id="canvas"></div>
<div id="control">
    <button id="rc" onclick="loadimage('acw')">Rotate anit Clockwise</button>
    <button id="lc" onclick="loadimage('cw')">Rotate Clockwise</button>
</div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        $.ajax({
            type:"POST",
            url:"./ImageLoader.php",
            data:{imgId:"<?php if(!isset($_GET['img'])) header("Location: index.php"); echo $_GET['img']; ?>"},
            success: function(response){
                var jsonData = JSON.parse(response);
                if(jsonData.img!='error'){
                    $("#canvas").append(jsonData.img);
                }else{
                    alert("Opps, something went wrong!");
                }
            }
        });
        $("#canvas").append('')
    });

    function loadimage(direction) {
        $.ajax({
            type:"POST",
            url:"ImageLoader.php",
            data: {imgId:"<?php if(!isset($_GET['img'])) header("Location: index.php"); echo $_GET['img']; ?>", r:direction},
            success: function(response){
                var jsonData = JSON.parse(response);
                //alert(jsonData.err);
                if(jsonData.img!='error'){
                    $("#canvas").html("");
                    $("#canvas").append(jsonData.img);
                }else{
                    alert("Opps, something wrong!");
                }
            }
        });
    }
</script>
<?php include_once("./Lab5Common/Footer.php"); ?>
