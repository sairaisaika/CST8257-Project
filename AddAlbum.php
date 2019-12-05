<?php
    include_once './src/Lab5Common/Header.php';
    include_once './src/Lab5Common/Connection.php';
    include_once './src/Lab5Common/Functions.php';
    include_once './src/Lab5Common/Footer.php';
    
    if(!isset($_SESSION['userid'])){
        header("Location: login.php");
    }
    
    $sql = "SELECT Name from User WHERE UserId=:UserId";
    $pStmt = $myPdo->prepare($sql);
    $pStmt->execute([':UserId' => $_SESSION["userid"]]);
    $printName = $pStmt->fetch();
    
    $title = $_POST['title'];
    $_SESSION['title'] = $title;
    $titleError = "";
    $accessibility = $_POST['accessibility'];
    $accessibilityError = "";
    $description = $_POST['description'];
    $_SESSION['description'] = $description;
    
    $sql = "SELECT * FROM `Accessibility`";
    $pStmt = $myPdo->prepare($sql); 
    $pStmt->execute(); 
    
    foreach ($pStmt as $row)
    {
        $accessibility = array( $row['Accessibility_Code'], $row['Description'] ); 
        $accessibilityArray[] = $accessibility;
    }
    $_SESSION['accessibilityArray'] = $accessibilityArray; 
    
    if(isset($_POST['submit'])){
        
        if(ValidateName($title) == False){
            $titleError = "Please type in an album title!";
        }
        if($acessibility == "0"){
            $accessibilityError = "Please select on type of accessibility!";
        }
        
        $sqlTitle = "SELECT * FROM album WHERE Title = :albumTitle AND Owner_Id = :userID";
        $stmt = $myPdo->prepare($sqlTitle);
        $stmt->execute(['albumTitle' => $title, 'userID' => $_SESSION['userid']]);
        $checkTitle = $stmt->fetchAll();
        
        if($checkTitle != null){
            $titleError = "Album title already exists!";
        }
        
        if($titleError == "" && $acessibilityError == ""){
            $albumId = null;
            $date = date("Y/m/d");
            $access = $_POST['accessibility']; 
            
            //$sql = "INSERT INTO `Album` (Album_Id, Title, Description, Date_Updated, Owner_Id, Accessibility_Code) VALUES (:albumId, :albumTitle, :albumDescription, :albumDate, :userID, :accessibility) ";
            //$mar = "INSERT INTO `Album` (`Album_Id`, `Title`, `Description`, `Date_Updated`, `Owner_Id`, `Accessibility_Code`) VALUES (NULL, 'test', 'a test', '2019-12-04', 'MacG', 'shared')";
            $sql = "INSERT INTO `Album` (`Album_Id`, `Title`, `Description`, `Date_Updated`, `Owner_Id`, `Accessibility_Code`) VALUES (:albumId, :albumTitle, :albumDescription, :albumDate, :userID, :accessibility)";
            $pStmt = $myPdo->prepare($sql); 
            $pStmt->execute(array(':albumId' => $albumId , ':albumTitle' => $title, ':albumDescription' => $description, ':albumDate' => $date, ':userID' => $_SESSION['userid'], ':accessibility' => $access));            
            $pStmt->commit;
            header('Location: MyAlbums.php');
            exit;
        }    
    }
     if(isset($_POST['clear']))
    {
        $_SESSION['titleTxt'] = "";
        $_POST['descriptionTxt'] = "";
        $_POST['accessibility'] = "";
    }
?>

<div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 text-center">
                <h1>Create New Album</h1>
            </div>
        </div>        
        <br/>

        <h4>Welcome <b><?php print $printName[0];?></b>! (Not you? Change your session <a href="Login.php">here</a>).</h4>
        <br/>

        <form method='post' action=AddAlbum.php> 
            <div class='form-group row'>
                <div class='col-lg-2 col-md-2 col-sm-2'>
                    <label for='title' class='col-form-label'><b>Title:</b> </label>
                </div>
                <div class='col-lg-4'>
                    <input type='text' class='form-control' value='<?php print $_SESSION['title'];?>' id='titleTxt' name='title' >
                </div>
                <div class='col-lg-4' style='color:red'> <?php print $titleError;?></div>
            </div>        
        
            <div class='form-group row'>
                <div class='col-lg-2 col-md-2 col-sm-2'>
                    <label for='acessibility' class='col-form-label'><b>Accessibility:</b></label>
                </div>
                <div class='col-lg-4'>                
                <select name='accessibility' class='form-control' >       
                        <option value='0'></option>;  
                        <?php   
                         $accessibilityArray = $_SESSION['accessibilityArray'];
                        foreach ($accessibilityArray as $row)
                        {   
                            echo "<option value='$row[0]' "; 
                            if ($row[0] == $_POST['accessibility'])
                            { 
                                echo "selected='selected'";
                            }
                            echo ">" . $row[1] . "</option>"; 
                        }
                        ?>         
                </select>  
                </div>  
                <div class='col-lg-3' style='color:red'> <?php print $acessibilityError;?></div>
            </div>
            
            <div class='form-group row'>
                <div class='col-lg-2 col-md-2 col-sm-2'>
                    <label for='description' class='col-form-label'><b>Description:</b> </label>
                </div>
                <div class='col-lg-4'> 
                    <textarea  class='form-control' id='descriptionTxt'  name='description' style='height:150px'><?php 
                        if(isset($_POST['description']) ){
                            echo $_POST['description'];
                        }
                        ?></textarea>
                </div>
            </div>
            <br/>
            
            <div class='row'>
                <div class="col-lg-2 col-md-0 col-sm-0"></div>
                <div class='col-lg-2 col-md-2 col-sm-2 text-left'>
                    <button type='submit' name='submit' class='btn btn-block btn-primary col-lg-2'>Submit</button>
                </div>
                <div class='col-lg-2 col-md-2 col-sm-2 text-left'>
                    <button type='submit' name='clear' class='btn btn-block btn-primary col-lg-3'>Clear</button>
                </div>
            </div> 
    </form>
</div>


