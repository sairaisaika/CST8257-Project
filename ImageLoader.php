<?php
    session_start();
    include_once("./src/Lab5Common/Connection.php");
    $rotate = 0.0;
    $direction = null;
    if(isset($_SESSION['rotate'])){
        $rotate = $_SESSION['rotate'];
    }else{
        $_SESSION['rotate'] = 90.0;
        $rotate = 90.0;
    }
    if(isset($_POST['imgId'])){
        $is_rotate = false;
        if(isset($_POST['r']))
            $is_rotate = true;
        else
            $is_rotate = false;

        if($is_rotate){
            $direction=$_POST['r'];
        }
        $sql = "SELECT FileName from Picture where Picture_Id = :picid";
        $imgDownload = $myPdo->prepare($sql);
        $imgDownload->execute(['picid'=>$_POST['imgId']]);
        $img_name = $imgDownload->fetch();
        $img_path = "./imgs/".$img_name[0];
        ob_start();
        $imgRes = imagecreatefromjpeg($img_path);
        if($is_rotate){
            if($direction=="acw"){
                $imgRes = imagerotate($imgRes, $rotate+90, 0);
                $_SESSION['rotate'] += 90;
            }else{
                $imgRes = imagerotate($imgRes, $rotate-90, 0);
                $_SESSION['rotate'] -= 90;
            }
        }
        imagejpeg($imgRes);
        $imgBuffer = ob_get_clean();
        $base64Img = base64_encode($imgBuffer);
        $mes = "Got ImgId: ".$_POST['imgId']."  Rotate?: ".($is_rotate?"yes":"no")."  Direction: ".$direction." Degree:".$rotate;
        echo json_encode(array("err"=>$mes, "img"=> '<img style="width:100%;" src="data:image/jpeg;base64,'.$base64Img.'" />'));

    }else{
        return;
    }
?>
