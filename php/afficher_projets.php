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
            border : 1px solid green  !important;
        }

        .notVoted{
            border : 1px solid red !important;
        }
        </style>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Voting</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">

</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">Tunihack Vote</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <a class="nav-link" href="logout.php">Logout
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Logo"></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container">

    <div class="row">

      <div class="col-lg-3">

        <h1 class="my-4">Workshops</h1>
        <div class="list-group">
          <a href="#" class="list-group-item">Workshop 1</a>
          <a href="#" class="list-group-item">Workshop 2</a>
          <a href="#" class="list-group-item">Workshop 3</a>
        </div>

      </div>
      <!-- /.col-lg-3 -->

      <div class="col-lg-9">

        <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
          </ol>
          <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
              <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="First slide">
            </div>
            <div class="carousel-item">
              <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Second slide">
            </div>
            <div class="carousel-item">
              <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Third slide">
            </div>
          </div>
          <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
          </a>
        </div>

        <div class="row">

<?php
include("connection.php");
try {


    //get all users who voted
    $sql = "SELECT count(*) FROM `users` WHERE vote =1 "; 
    $result = $conn->prepare($sql); 
    $result->execute(); 
    $nbr_users_voted = $result->fetchColumn(); 



    

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


// ordre d'affichage
    $getProjets = $conn->prepare("SELECT * FROM projets ORDER BY VOTES DESC"); 
    $getProjets->execute();
    $projets = $getProjets->fetchAll();

foreach ($projets as $index => $projet) {
       
    ?>
        <div class="col-lg-4 col-md-6 mb-4 ">
            <div class="card <?php if ($idProjetVoted == $projet["id"]) echo " voted";  else echo "notVoted" ;?>">
            <?php if ($index==0 ) echo '<img class="card-img-top" src="res/roi.png" height=200 alt="">'?>
              <div class="card-body">
                <h4 class="card-title">
                  <a href="#"><?php  echo $projet['nom']?></a>
                </h4>
                <h5><?php echo $projet["team"]?></h5>
                <p class="card-text"><?php echo $projet['description']?>
                </p>
              </div>
              <div class="card-footer container ">
                <div class="row">
                  <small class="text-muted col-8 "><?php 
                  
                  $sql3 = "SELECT votes from projets where id= ?";
                  $stmt = $conn->prepare($sql3); 
                  $stmt->execute([$projet['id']]);
                  $votesDeProjetCourant = $stmt->fetch();
                
              
                 $pourventageDechaqueProjet =  floatval($votesDeProjetCourant['votes']) / $nbr_users_voted * 100 ;
                echo round($pourventageDechaqueProjet,2) ."%";
                  
                  ?></small>
                  <?php
                  if ($isVoted==false){
                   ?>
      <a  class="col-4" href="vote.php?projet=<?php echo $projet["id"]."&user=".$_SESSION['id']  ?>"/>voter</a>
      <?php  }  ?>
                </div>
              </div>
            </div>
          </div>
    
    <?php
}
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;

?>

  </div>
        <!-- /.row -->

      </div>
      <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Tunihack 2018</p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>


