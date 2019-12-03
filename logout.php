<?php
session_start();
if(!(isset($_SESSION['userid'])&&$_SESSION['userid'])){
    header("Location: Index.php");
}else{
    session_destroy();
    header("Location: Index.php");
}
?>
