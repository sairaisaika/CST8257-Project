<?php
    ob_start();
    include_once './src/Lab5Common/Header.php';
    include_once './src/Lab5Common/Footer.php';
    include_once './src/Lab5Common/Connection.php';
    include_once './src/Lab5Common/functions.php';
    @session_start();
    
    //define constants for convenience
    define(ORIGINAL_IMAGE_DESTINATION, "./originals"); 

    define(IMAGE_DESTINATION, "./imgs"); 
    define(IMAGE_MAX_WIDTH, 800);
    define(IMAGE_MAX_HEIGHT, 600);

    define(THUMB_DESTINATION, "./thumbnails");  
    define(THUMB_MAX_WIDTH, 100);
    define(THUMB_MAX_HEIGHT, 100);
    
    $title;
    $description;
    $album_id;
    if(isset($_POST['title'])){
        $title=$_POST['title'];
    }
    if(isset($_POST['description'])){
        $description=$_POST['description'];
    }
    if(isset($_POST['album'])){
        $album_id = $_POST['album'];
    }
//    echo $album_id;
//    
    //Use an array to hold supported image types for convenience
    $supportedImageTypes = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
    
    if(!isset($_SESSION['userid'])){
        header("Location: Login.php");
        exit();
    }
    
    $userId = $_SESSION['userid'];
    
    if (isset($_POST['btnUpload'])) 
    {
        if ($_FILES['imgUpload']['error'][0] == 0)
        { 	
            $total = count($_FILES['imgUpload']['name']);
            for( $i=0 ; $i < $total ; $i++ ) {

                //Get the temp file path
                $tmpFilePath = $_FILES['imgUpload']['tmp_name'][$i];

                //Make sure we have a file path
                if ($tmpFilePath != ""){
                    //Setup our new file path
                    $newFilePath = "./imgs/" . $_FILES['imgUpload']['name'][$i];

                    //Upload the file into the temp dir
                    if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                        //echo "<script>alert('copy success')</script>";
                        $sqlQ = "INSERT INTO Picture (Album_Id, FileName, Title, Description, Date_Added) VALUES (:Album_Id, :FileName, :Title, :Description, :Date_Added);";
                        $pQ = $myPdo -> prepare($sqlQ);
    //                    echo $_FILES['imgUpload']['name'][$i];

                        $pQ -> execute(['Album_Id' => $album_id, 'FileName' => $_FILES['imgUpload']['name'][$i], 'Title' => $title, 'Description' => $description, 'Date_Added' => date('Y-m-d')]);
    //                    var_dump($pQ->errorInfo());
                    }
//                    else{
//                    echo "<script>alert('copy failed')</script>";
//                    }
                }
            }
//            $filePath = save_uploaded_file(ORIGINAL_IMAGE_DESTINATION);
//            
//            $total = count($_FILES['imgUpload']['name']);
//            for( $i=0 ; $i < $total ; $i++ ) {
//                $sqlQ = "INSERT INTO Picture (Album_Id, FileName, Title, Description, Date_Added) VALUES (:Album_Id, :FileName, :Title, :Description, :Date_Added)";
//                $pQ = $myPdo -> prepare($sqlQ);
//                $pQ -> execute(['Album_Id' => $userId, 'FileName' => $imgUpload, 'Title' => $title, 'Description' => $description, 'Date_Added' => date('Y-m-d')]);
//            }
//            
//            $imageDetails = getimagesize($filePath);
//
//            if ($imageDetails && in_array($imageDetails[2], $supportedImageTypes))
//            {
//                resamplePicture($filePath, IMAGE_DESTINATION, IMAGE_MAX_WIDTH, IMAGE_MAX_HEIGHT);
//
//                resamplePicture($filePath, THUMB_DESTINATION, THUMB_MAX_WIDTH, THUMB_MAX_HEIGHT);
//            }
//            else
//            {
//                $error = "Uploaded file is not a supported type"; 
//                unlink($filePath);
//            }
        }
        elseif ($_FILES['imgUpload']['error'] == 1)
        {
            $error = "Upload file is too large"; 
        }
        elseif ($_FILES['imgUpload']['error'] == 4)
        {
            $error = "No upload file specified";
        }
        else
        {
            $error  = "Error happened while uploading the file. Try again late ".var_dump($_FILES['imgUpload']['error']); 
        }
    }
    ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 text-center">
            <h1>Upload Pictures</h1>
        </div>
    </div>        
    <br/>

    <h4>Accepted picture types: JPG(JPEG), GIF and PNG.</h4>
    <h4>You can upload multiple pictures at a time by pressing the shift key while selecting pictures.</h4>
    <h4>When uploading multiple pictures, the title and description fields will be applied to all pictures.</h4>
    <br/>

    <form method='post' action='UploadPictures.php' enctype="multipart/form-data">
        <span class='error'><?php echo $error;?></span>
        <br>
        <br>
        <div class='form-group row'>
            <div class='col-lg-2 col-md-2 col-sm-2'>
                <label for='title' class='col-form-label'><b>Upload to Album:</b> </label>
            </div>
            <div class='col-lg-4'>                
                <select name='album' class='form-control' >       
                        <option value='0'></option>;  
                        <?php   
                            $sqlQ = "SELECT Album_Id,Title FROM Album WHERE Owner_Id = :UserId";
                            $pQ = $myPdo -> prepare($sqlQ);
                            $pQ -> execute(['UserId' => $userId]);
                            foreach ($pQ as $row){
                                echo '<option value="'.$row['Album_Id'].'">'.$row['Title'];
                                echo '</option>';
                            }
                        ?>         
                </select>  
            </div>  
        </div>        

        <div class='form-group row'>
            <div class='col-lg-2 col-md-2 col-sm-2'>
                <label for='acessibility' class='col-form-label'><b>File to Upload:</b></label>
            </div>
            <div class='col-lg-4'>  
                <input type="file" name="imgUpload[]" size="40" accept="image/*" multiple="multiple"/>
            </div>  
        </div>

        <div class='form-group row'>
            <div class='col-lg-2 col-md-2 col-sm-2'>
                <label for='title' class='col-form-label'><b>Title:</b> </label>
            </div>
            <div class='col-lg-4'> 
                <input type="text" name="title" class='form-control'>
            </div>
        </div>

        <div class='form-group row'>
            <div class='col-lg-2 col-md-2 col-sm-2'>
                <label for='description' class='col-form-label'><b>Description:</b> </label>
            </div>
            <div class='col-lg-4'> 
                <textarea  class='form-control' id='descriptionTxt'  name='description' style='height:150px'></textarea>
            </div>
        </div>
        <br/>

        <div class='row'>
            <div class="col-lg-2 col-md-0 col-sm-0"></div>
            <div class='col-lg-2 col-md-2 col-sm-2 text-left'>
                <button type='submit' name="btnUpload" value="Upload" class='btn btn-block btn-primary col-lg-2'>Submit</button>
            </div>
            <div class='col-lg-2 col-md-2 col-sm-2 text-left'>
                <button type='reset' name="btnReset" value="Reset" class='btn btn-block btn-primary col-lg-3'>Clear</button>
            </div>
        </div> 
    </form>
    <br>
    <br>
</div>