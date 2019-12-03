<!DOCTYPE html>
<html lang="en" style="position: relative; min-height: 100%;">
<head>
    <title>Online Course Registration</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="src/AlgCommon/Contents/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="src/AlgCommon/Contents/AlgCss/Site.css" rel="stylesheet" type="text/css"/>
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
</head>
<body style="padding-top: 50px; margin-bottom: 60px;">
<nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" style="padding: 10px" href="http://www.algonquincollege.com">
                <img src="src/AlgCommon/Contents/img/AC.png"
                     alt="Algonquin College" style="max-width:100%; max-height:100%;"/>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="Index.php">Home</a></li>
                <li><a href="MyFriends.php">My Friends</a></li>
                <li><a href="MyAlbums.php">My Albums</a></li>
                <li><a href="MyPictures.php">My Pictures</a></li>
                <li><a href="UploadPictures.php">Upload Pictures</a></li>
                <?php
                session_start();
                if(isset($_SESSION['userid'])){ ?>
                    <li><a href="logout.php">Log out</a></li>
                <?php }else{ ?>
                    <li><a href="Login.php">Log in</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
