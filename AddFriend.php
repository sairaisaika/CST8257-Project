<?php
    include_once './src/Lab5Common/Header.php';
    include_once './src/Lab5Common/Connection.php';
    include_once './src/Lab5Common/Functions.php';
    include_once './src/Lab5Common/Footer.php';
    
    if(!isset($_SESSION['userid'])){
        header("Location: login.php");
    }
    
    $friendId = $_POST["friendId"];
    $_SESSION['friendId'] = $_POST["friendId"];
    
    $friendError ="";
    
    $sql = "SELECT Name from User WHERE UserId=:UserId";
    $pStmt = $myPdo->prepare($sql);
    $pStmt->execute([':UserId' => $_SESSION["userid"]]);
    $printName = $pStmt->fetch();
    
    if(isset($_POST['sendRequest'])){
        $sql = "SELECT * FROM User WHERE UserId = :userId";
        $pStmt = $myPdo->prepare($sql);
        $pStmt->execute([':userId' => $friendId]);
        $chekId = $pStmt->fetch();
        
        $sql= "SELECT * FROM Friendship"
                . "WHERE Friend_RequesterId = :requestrId AND Friend_RequesteeId = :requesteeId AND Status = :status";
        $pStmt = $myPdo->prepare($sql);
        $pStmt->execute(array(':requestrId' => $_SESSION['userid'], ':requesteeId' => $_SESSION['friendId'], 'status' => 0));
        $request1 = $pStmt->fetch();
        
        $sql= "SELECT * FROM Friendship"
                . "WHERE Friend_RequesterId = :requestrId AND Friend_RequesteeId = :requesteeId AND Status = :status";
        $pStmt = $myPdo->prepare($sql);
        $pStmt->execute(array(':requestrId' => $_SESSION['friendId'], ':requesteeId' => $_SESSION['userid'], 'status' => 0));
        $request2 = $pStmt->fetch();
        
        $sql = "SELECT * FROM Friendship"
                . "WHERE Friend_RequesterId = :friendId AND Friend_RequesteeId = :userId AND Status = :status";
        $pStmt =$myPdo->prepare($sql);
        $pStmt->execute(array(':userId' => $_SESSION['userid'], ':friendId' => $_SESSION['friendId'], ':status' => 1));
        $pending = $pStmt->fetch();
        
        $sql = "SELECT * FROM Friendship"
                . "WHERE Friend_RequesterId = :userId AND Friend_RequesteeId = :friendId AND Status = :status";
        $pStmt =$myPdo->prepare($sql);
        $pStmt->execute(array(':userId' => $_SESSION['userid'], ':friendId' => $_SESSION['friendId'], ':status' => 1));
        $pendingFriend = $pStmt->fetch();
        
        
        if($pendingFriend != null){
            $friendError = "You can not send an invitation twice";
        }
        else{
            $sql = "SELECT UserId, Name FROM User WHERE UserId = :friendId";
            $pStmt = $myPdo->prepare($sql);        
            $pStmt ->execute([':friendId' => $_SESSION['friendId']]);  
            $id = $pStmt->fetch();
            
            if($chekId == null){
                $friendError = "There is no User with the ID!";
            }
            else if($_SESSION['userid'] == $_SESSION['friendId']){
                $friendError = "You can not send a friend request to yourself";
            }
            else if($request1 != null || $request2 != null){
                $friendError = "This user is already a friend";
            }
            else if($pending != null){
                $sql = "UPDATE Friendship SET Status = 'accepted'"
                        . "WHERE Friend_RequesterId = : requesteeId AND Friend_RequesteeId = ':requesterId'";
                $pStmt = $myPdo->prepare($sql);
                $pStmt->execute(array(':requesterId' => $_SESSION['userid'], ':requesteeId' => $_SESSION['friendId']));
                $pStmt->commit;
                
                $sql = "INSERT INTO Friendship(Friend_RequesterId, Friend_RequesteeId, Status)"
                        . "VALUES(:requesterId, :requesteeId, :status)";
                $pStmt = $myPdo->prepar($sql);
                $pStmt->execute(array(':requesterId' => $_SESSION['userid'], ':requesteeId' => $_SESSION['friendId'], ':status' => 0));
                $pStmt->commit;
                
                $friendError = "You and ".$id[1]." (ID:".$id[0].") are now friends.";
            }
            else{
                $sql = "INSERT INTO Friendship (Friend_RequesterId, Friend_RequesteeId, Status)"
                        . "VALUES(:requesterId, :requesteeId, :status)";
                $pStmt = $myPdo->prepare($sql);
                $pStmt->execute(array(':requesterId' => $_SESSION['userid'], ':requesteeId' => $_SESSION['friendId'], ':status' => 1));
                $pStmt->commit;
                
                $friendError = "Your request was sent to ".$id[1]." (ID:".$id[0].")."
                        ."<br>"
                        ."Once ".$id[1]." accepts your request, you and ".$id[1]." will be friends"
                        . " and will be able to see each others shared albums. ";
            }
        }
    }
?>
    <div class="container">
        <h1> Add Friend</h1>  
        <br>
            <h4> Welcome <b><?php print $printName[0];?></b>! (Not you? Change your session <a href="Login.php">here</a>)</h4>
            <h4> Enter the ID of the user you want to be friends with:</h4>

        <form method='post' action=AddFriend.php>             
            <br><br><div class="row">
                <div class="col-lg-1" >
                    <label for='friendId' class='col-form-label'><b>ID:</b> </label>
                </div>
                <div class="col-lg-3" >
                    <input type='text' class='form-control' id='friendId' name='friendId' value='<?php print $_SESSION['friendId']; ?>' >
                </div> 
                <br>
                <div class="col-lg-5" >
                    <button type='submit' name='sendRequest' class='btn btn-primary'>Send Friend Request</button>
                </div>
                <br><div class='col-lg-10' style='color:red'> <?php print $friendError;?></div>
            </div>
        </form>
    </div>   

