<?php
    include_once './src/Lab5Common/Header.php';
    include_once './src/Lab5Common/Connection.php';
    include_once './src/Lab5Common/Functions.php';
    include_once './src/Lab5Common/Footer.php';
    
    if(!isset($_SESSION['userid'])){
        header("Location: login.php");
    }
    
    $sql = "SELECT Name from User WHERE UserId= :userId";
    $pStmt = $myPdo->prepare($sql);
    $pStmt->execute([':userId' => $_SESSION["userid"]]);
    $printName = $pStmt->fetch();
    
    $friendsError = "";
    
    $sql = "SELECT Friendship.Friend_RequesterId, Friendship.Friend_RequesteeId FROM Friendship "
            . "WHERE (Friend_RequesterId = :userId OR Friend_RequesteeId = :userId) AND Status = 0 ";
    $pStmt = $myPdo->prepare($sql);
    $pStmt->execute ( [':userId' => $_SESSION['userid'] ]);
    $checkFriends = $pStmt->fetchAll();
    
    $friendIdArray = array();
    foreach ($checkFriends as $row){
        if ($row[0] != $_SESSION['userid'] && (!in_array($row[0], $friendIdArray))){
            array_push($friendIdArray, $row[0]);
        }
        if ($row[1] != $_SESSION['userid'] && (!in_array($row[1], $friendIdArray))){
            array_push($friendIdArray, $row[1]);
        }
    }
    if(isset($_POST['accept'])){
        if(isset($_POST['acceptDeny'])){
            foreach ($_POST['acceptDeny'] as $row){
                $sql = "UPDATE Friendship SET Status = 0 WHERE Friend_RequesterId = :requesteeId AND Friend_RequesteeId = :requesterId";
                $pStmt = $myPdo->prepare($sql);
                $pStmt->execute(array(':requesterId' => $_SESSION['userid'], ':requesteeId' => $row));
                $pStmt->commit;
                
                $sql = "INSERT INTO Friendship (Friend_RequesterId, Friend_RequesteeId, Status)"
                        . "VALUES(:requesterId, :requesteeId, :status)";
                $pStmt =$myPdo->prepare($sql);
                $pStmt->execute(array(':requesterId' => $_SESSION['userid'], ':requesteeId' => $row, 'status' => 0));
                $pStmt->commit;
                
            }
            header('Location: MyFriends.php');
            exit; 
            
        }
        else{
            $friendsError = "You must select at least one checkbox";
        }
    }
    
    if(isset($_POST['deny'])){
        if(isset($_POST['acceptDeny'])){
            foreach ($_POST['acceptDeny'] as $row){
                $sql = "DELETE FROM Friendship WHERE Friend_RequesterId = :requesterId AND Friend_RequesteeId = :requesteeId";
                $pStmt =$myPdo->prepare($sql);
                $pStmt->execute(array(':requesteeId' => $_SESSION['userid'], ':requesterId' => $row));
                $pStmt->commit;
            }
            header('Location: MyFriends.php');
            exit; 
        }
        else{
            $friendsError = "You must select at least one checkbox";
        }
    }
    
    
    if(isset($_POST['defriendButton'])){
        if(isset($_POST['defriend'])){
            foreach($_POST['defriend'] as $row){
                $sql = "DELETE FROM Friendship "
                        . "WHERE (Friend_RequesterId = :userId AND Friend_RequesteeId = :friendId) "
                        . "OR (Friend_RequesterId = :friendId AND Friend_RequesteeId = :userId)"; 
                $pStmt =$myPdo->prepare($sql);
                $pStmt->execute(array('userId' => $_SESSION['userid'], ':friendId' => $row));
                $pStmt->commit;
            }
            header('Location: MyFriends.php');
            exit; 
        }
        else{
            $friendsError= "You must select at least on checkbox";
        }
    }
?>
<div class="container">
        <br>
        <h1>My Friends</h1>
        <br>
        <h4>Welcome <b><?php print $printName[0];?></b>! (Not you? Change your session <a href="Login.php">here</a>)</h4>
        <br><br>
        <form method='post' action=MyFriends.php> 
            <table class="table">
            <thead>
                <tr>
                    <th scope="col">Friends:</th>
                    <th scope="col"></th>
                    <th scope="col"><a href="AddFriend.php">Add Friends</a></th>                                                                             
                </tr>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Shared Albums</th>
                    <th scope="col">Defriend</th>                                                                             
                </tr>
            </thead>              
            <div class='col-lg-4' style='color:red'> <?php print $friendsError;?></div><br>
            <tbody>
            <?php   
            foreach ($friendIdArray as $row){
                $sql="SELECT User.UserId, User.Name, Album.Accessibility_Code "
                        . "FROM User LEFT JOIN Album ON Album.Owner_Id = User.UserId "
                        . "WHERE User.UserId = :userId "
                        . "ORDER BY User.UserId ";
                $pStmt = $myPdo->prepare($sql);
                $pStmt->execute ([ ':userId' => $row ]);
                $sharedAlbums = $pStmt->fetchAll(); 
                $albumCount = 0;
                foreach ($sharedAlbums as $albums)
                {
                    if ($albums[2] == "shared")
                    {
                        $albumCount = $albumCount + 1;   
                    }   
                }    
                    echo "<tr>";
                    echo "<td scope='col'><a href='FriendPictures.php?id=".$albums[0]."'>".$albums[1]."</a></td>";
                    echo "<td scope='col'>".$albumCount."</td>";
                    echo "<td scope='col'><input type='checkbox' name='defriend[]' value='$albums[0]'/></td>";          
                    echo "</tr>";           
            }
            ?>              
        </tbody>
        </table>
        <div>                    
            <button type='submit' name='defriendButton' class='btn btn-primary' onclick='return confirm("The selected friend will be defriended!")'>Defriend Selected</button>  
        </div>
        <br>
        <br>
        <table class="table">
        <thead>
            <tr>
                <th scope="col">Friend Requests:</th>
                <th scope="col"></th>                                                                             
            </tr>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Accept or Deny</th>                                                                             
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT User.UserId, User.Name FROM User "
                . "INNER JOIN Friendship ON Friendship.Friend_RequesterId = User.UserId "
                . "WHERE Friendship.Status = 1 AND Friendship.Friend_RequesteeId = :userId ";        
        $pStmt = $myPdo->prepare($sql);
        $pStmt->execute ( [':userId' => $_SESSION['userid'] ]);
        $requestFriend = $pStmt->fetchAll();
        foreach ($requestFriend as $friendName)
        {
            echo "<tr>";
            echo "<td scope='col'>".$friendName[1]."</td>";
            echo "<td>&nbsp;</td>";
            echo "<td scope='col'><input type='checkbox' name='acceptDeny[]' value='$friendName[0]' /></td>";            
            echo "</tr>";
        }            
        ?>   
        </tbody>
        </table>         
        <div>                    
            <button type='submit' name='accept' class='btn btn-primary'>Accept Selected</button>
        </div> 
        <br>
        <div> 
            <button type='submit' name='deny' class='btn btn-primary ' onclick='return confirm("The selected request will be denied!")'>Deny Selected</button>
        </div>
    </div>