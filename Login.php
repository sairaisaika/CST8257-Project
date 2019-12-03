<?php
    ob_start();
    include_once './src/Lab5Common/Header.php';
    include_once './src/Lab5Common/Footer.php';
    include_once './src/Lab5Common/Connection.php';
    include_once './src/Lab5Common/functions.php';
    @session_start();
    if(isset($_POST['btnSubmit']))
        $retry = 1;
    else
        $retry = 0;
    if($retry){
        $userId = $_POST['id'];
        $userPassword = sha1($_POST['password']);
        $sqlQ = "SELECT UserId, Password from User where UserId=:UserId";
        $pQ = $myPdo->prepare($sqlQ);
        $pQ->execute(['UserId'=>$userId]);
        if($pQ->rowCount()==1){
            foreach($pQ as $row){
                if($row['Password']==$userPassword){
                    $_SESSION['userid'] = $userId;
                    header("Location: Index.php");
                }else{
                    $fine = False;
                }
            }
        }else{
                $fine = False;
        }
    }
 ?>
    <div class="container">
        <div class="row">
            <h1>Log In</h1>
            <form action="Login.php" method="post">
                <br>
                <p>You need to <a href="NewUser.php">sign up</a> if you a new user.</p>
                <div class="form-group row">
                    <?php if($retry&&isset($fine)&&!$fine){ ?> <span class="error col-5">Incorrect student ID and/or Password</span> <?php }?>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Student ID:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="id" id="id" type="text" placeholder="" value="<?php if($retry) echo $_POST['id']; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Password:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="password" id="password" type="password" placeholder="" value="<?php if($retry) echo $_POST['password']; ?>">
                    </div>
                </div>
                <hr>
                <button class="btn btn-primary col-3" name="btnSubmit" type="submit">Submit</button>
                <button class="btn btn-primary ml-2 col-2" type="clear">Clear</button>
            </form>
        </div>
    </div>