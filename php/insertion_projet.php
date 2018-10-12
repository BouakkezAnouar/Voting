<?php

$nomProjet ="";
$team="";
$description ="";
$error ="";
    
if (isset($_POST['team'])&& isset($_POST['nomProjet']) && isset($_POST['description']))
{
    $nomProjet =$_POST['nomProjet'];
    $team=$_POST['team'];
    $description =$_POST['description'];
    
    if (!empty(trim($_POST['team']))&& !empty(trim($_POST['nomProjet'])) && !empty(trim($_POST['description'])))
    {
        include("connection.php");
        try
        {
            $sql = "INSERT INTO projets VALUES ( '','$nomProjet' ,'$team', '$description','')";
            // use exec() because no results are returned
            $conn->exec($sql);
            echo "New record created successfully";

            //vider tous les champs
            $nomProjet ="";
            $team="";
            $description ="";
            $error ="";
        }
        catch(PDOException $e)
        {
        echo $sql . "<br>" . $e->getMessage();
        //close connection
        $conn = null;
        }

        //close connection
        $conn = null;
    }
    else 
    $error ="all champs must be replied!!!";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Insertion projet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>

<body>

    <form action="insertion_projet.php" method="POST">
        Projet :
        <input type="text" name="nomProjet" value="<?php if (!empty($nomProjet)) echo $nomProjet ?>"> Team :
        <input type="text" name="team" value="<?php if (!empty($team)) echo $team ?>"> description :
        <textarea name="description"><?php if (!empty($description)) echo $description ?></textarea>
        <button>Envoyer</button>
        <div ><?php if (!empty($error)) echo $error ?></div>
        
    </form>

</body>

</html>