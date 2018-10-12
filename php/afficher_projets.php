<?php
// Start the session
session_start();
 if(!isset($_SESSION['login'])){ //if login in session is not set
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <style>
        .voted{
            background-color :green ;
        }
        </style>

  <title>Bootstrap Card</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
</head>
<body>

<?php
include("connection.php");
try {

  //get user active
  $sql2 = "select * from users WHERE id=". $_SESSION['id']; // add user session
  $stmt2 = $conn->query($sql2);
  $user = $stmt2->fetch();
  //get id pojet votee
  $idProjetVoted =$user["projet"];
   //check if user isVoted
   if ($user["vote"]==0)
   {$isVoted =false;}
else $isVoted =true ; 


    $getProjets = $conn->prepare("SELECT * FROM projets"); 
    $getProjets->execute();
    $projets = $getProjets->fetchAll();

foreach ($projets as $projet) {
    ?>

    <div class="container <?php   
            if ($idProjetVoted == $projet["id"]) echo " voted"
    ?>">
    <h2><?php echo $projet["nom"]?></h2>
    <div class="card">
    <?php echo $projet["team"]?>

      <div class="card-body"><?php echo $projet["description"]?></div>
      <?php 
     

 if ($isVoted==false){
     ?>
      <div><a href="vote.php?projet=<?php echo $projet["id"]."&user=".$_SESSION['id']  ?>"/>voter sur ce projet</a></div>
      <?php  }  ?>
    </div> </div>
    
    
    <?php
}
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;

echo "</body></html>";
?>