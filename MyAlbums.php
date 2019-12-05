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

    $accessibilityArray = $_SESSION['accessibilityArray'];

    $sql = "SELECT a.Title, a.Date_Updated, ac.Description, a.Album_Id, COALESCE(picture, 0) number_pictures "
            . "FROM Album a "
            . "LEFT JOIN (SELECT count(*) as Picture, Album_Id FROM Picture GROUP BY Album_Id) p ON a.Album_Id = p.Album_Id "
            . "INNER JOIN Accessibility ac ON ac.Accessibility_Code = a.Accessibility_Code "
            . "WHERE a.Owner_Id = :userId ORDER BY a.Title";
    $pStmt = $myPdo->prepare($sql);
    $pStmt->execute ( [':userId' => $_SESSION['userid']] );
    $albumByUser = $pStmt->fetchAll();

    $sql = "SELECT * FROM Accessibility";
    $pStmt = $myPdo->prepare($sql);
    $pStmt->execute();
    if(isset($_GET['action']))
    if ($_GET['action']== 'delete' && isset($_GET['id'])){
        $ID = $_GET['id'];
        $deletePictures = "DELETE FROM Picture WHERE Album_Id = :albumID";
        $stmt = $myPdo->prepare($deletePictures);
        $delalbum = "DELETE FROM Album WHERE Album.Album_Id = :albumID";
        $stmt1 = $myPdo->prepare($delalbum);
        $stmt->execute([':albumID' => $ID]);
        $stmt1->execute([':albumID' => $ID]);

        header('Location: MyAlbums.php');
        exit;
    }

    $accessibilityArray = null;
    foreach ($pStmt as $row)
    {
        $accessibility = array( $row['Accessibility_Code'], $row['Description'] );
        $accessibilityArray[] = $accessibility;
    }
    $_SESSION['accessibilityArray'] = $accessibilityArray;

    if(isset($_POST['submit'])){
        if(isset($_POST['selectAcessibility'])){
            $sql = "UPDATE Album SET Accessibility_Code = :access_code WHERE Album_Id = :album_id";
            $options = $_POST['selectAcessibility'];
            for ($i=0; $i < count($options); $i++) {
                $albumByUser[$i][2] = $options[$i];
                $pStmt = $myPdo->prepare($sql);
                $pStmt->execute(array(':access_code' => $albumByUser[$i][2], ':album_id' => $albumByUser[$i][3]));
            }
            $pStmt->commit;
            exit(header('Location: MyAlbums.php'));
        }
    }
?>
<div class="container-fluid">
        <br>
        <h1>My Albums</h1>
        <br>
        <h4>Welcome <b><?php print $printName[0];?></b> (Not you? Change your session <a href="Login.php">here</a>)</h4>

        <form method='post' action='MyAlbums.php'>
            <br><br><br>
            <div class='row'>
                <div class='col-lg-10 col-md-9 col-sm-9 col-xs-7'></div>
                <div class='col-lg-2 col-md-3 col-sm-3 col-xs-5'>
                    <b><a href="AddAlbum.php">Create a New Album</a></b>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Date Updated</th>
                        <th scope="col">Number of Pictures</th>
                        <th scope="col">Accessibility</th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($albumByUser as $var)
                    {
                        echo "<tr>";
                        echo '<td scope="col"><a href="MyPictures.php?action=album&id='.$var[3].'">'.$var[0].'</a></td>';
                        echo "<td scope='col'>".$var[1]."</td>";
                        echo "<td scope='col'>". $var[4] . "</td>";
                        echo "<td scope='col'><select name='selectAcessibility[]' class='form-control' >  ";
                        foreach ($accessibilityArray as $row)
                        {
                            echo "<option value='$row[0]' ";
                            if ($row[1] == $var[2])
                                {
                                    echo "selected='selected'";
                                }
                            echo ">" . $row[1] . "</option>";
                        }
                        echo "</select>";
                        echo "<td scope='col'><a href='MyAlbums.php?action=delete&id=$var[3]' onclick='return myFunctionDelete()'/a>Delete</td>"; // delete button
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

            <br>
            <div class='row'>
                <div class='col-lg-9 col-md-9 col-sm-8 col-xs-6'></div>
                <div class='col-lg-2 col-md-2 col-sm-3 col-xs-6'>
                    <button type='submit' name='submit' class='btn btn-block btn-primary'>Save Changes</button>
                </div>
            </div>
    </form>
</div>
