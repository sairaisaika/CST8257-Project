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
        if(ValidateName($_POST['name'])&&ValidatePhone($_POST['phone'])&&($_POST['passwordre']==$_POST['password'])&&ValidatePassword($_POST['password'])){
            $userId = $_POST['id'];
            $userName = $_POST['name'];
            $userPhone = $_POST['phone'];
            $userPassword = sha1($_POST['password']);
            $sqlQ = "SELECT UserId from User where UserId=:UserId";
            $pQ = $myPdo->prepare($sqlQ);
            $pQ->execute(['UserId'=>$userId]);
            //print("<script>alert('".$pQ->rowCount()."')</script>");
            if($pQ->rowCount()==0){
                $sqlQ = "INSERT INTO User VALUES (:UserId, :Name, :Phone, :Password)";
                $pQ = $myPdo->prepare($sqlQ);
                if($pQ->execute(['UserId'=>$userId,'Name'=>$userName,'Phone'=>$userPhone,'Password'=>$userPassword])){
                    $_SESSION['userid'] = $userId;
                    header("Location: Login.php");
                }

            }else{
                $sameid = true;
            }
        }else{
            //
        }
    }
 ?>
    <div class="container">
        <div class="row">
            <h1>Sign Up</h1>
            <br>
            <p>All fields are required.</p>
            <br>
            <form action="NewUser.php" method="post">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">User ID:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="id" id="id" type="text" placeholder="" value="<?php if($retry) echo $_POST['id']; ?>">
                        <?php if($retry&&!ValidateName($_POST['id'])){ ?> <span class="error col-5">Must Not be Empty</span> <?php }?>
                        <?php if($retry&&isset($sameid)){ ?> <span class="error col-5">A student with this ID has already signed up</span> <?php }?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Name:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="name" id="name" type="text" placeholder="" value="<?php if($retry) echo $_POST['name']; ?>">
                        <?php if($retry&&!ValidateName($_POST['name'])){ ?> <span class="error col-5">Must Not be Empty</span> <?php }?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Phone Number:<br>(nnn-nnn-nnnn)</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="phone" id="phone" type="text" placeholder="" value="<?php if($retry) echo $_POST['phone']; ?>">
                        <?php if($retry&&!ValidatePhone($_POST['phone'])){ ?> <span class="error col-5">Incorrect Phone Number</span> <?php }?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Password:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="password" id="password" type="password" placeholder="" value="<?php if($retry) echo $_POST['password']; ?>">
                        <?php if($retry&&!ValidatePassword($_POST['password'])){ ?> <span class="error col-5">is empty or so week</span> <?php }?>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Password Again:</label>
                    <div class="col-sm-10">
                        <input class="form-control" name="passwordre" id="passwordre" type="password" placeholder="" value="<?php if($retry) echo $_POST['passwordre']; ?>">
                        <?php if($retry&&($_POST['passwordre']!=$_POST['password'])){ ?> <span class="error col-5">Password do not match</span> <?php }?>
                    </div>
                </div>
                <hr>
                <button class="btn btn-primary" name="btnSubmit" type="submit">Submit</button>
                <button class="btn btn-primary" type="clear">Clear</button>
            </form>
        </div>
    </div>