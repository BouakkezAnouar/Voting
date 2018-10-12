<?php
// Start the session
session_start();
 if(isset($_SESSION['login'])){ //if login in session is not set
    header("Location: afficher_projets.php");
}

$error = "";
$email= "";
$password="";
$password2 ="";
 
if ((isset($_POST['nom'])) && (isset($_POST['email'])) && (isset($_POST['password'])) && (isset($_POST['password2'])))
{
    $nom= $_POST['nom'];
    $email =$_POST['email'];
    $password=$_POST['password'];
    $password2=$_POST['password2'];

    if ((!empty(trim($_POST['nom']))) && (!empty(trim($_POST['email']))) && (!empty(trim($_POST['password']))) && (!empty(trim($_POST['password2']))))
    {
        
        if ( !($_POST['password']== $_POST['password2'])) 
        $error = "passwords must be equals!!!";
        /*
        * TODO some verification
        */    

        // all information are exact 
        else 
        {
          
            try
            {
                include("connection.php");
                //test if email exist 
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->execute([$_POST['email']]);
                $user = $stmt->fetch();

                if ($user){
                    $error ="user a deja un compte!!!";
                    //vider les champs password
                    $password2="";
                }
                    //n'existe pas un compte de ce email
                else 
                {
                    $nom= $_POST['nom'];
                    $email =$_POST['email'];
                    $password=$_POST['password'];

                    $sql = "INSERT INTO users VALUES ('', '$nom' ,'$email', '$password', '', '', now())";
                    // use exec() because no results are returned
                    $conn->exec($sql);
                    echo "register succes";
                    
                    //vider tous les champs
                    $nom = "";
                    $email= "";
                    $password="";
                    $password2 ="";
                    $error="";
                }
                //close connection
                $conn = null;
            }
            catch(PDOException $e)
            {
            echo $sql . "<br>" . $e->getMessage();
                //close connection
                $conn = null;
            }
        }
    }
    else {
        $error =  "all champs must be remplied";
    }
   
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>

<body>
    <form action="register.php" method="POST">
        nom :
        <input type="text" name="nom" minlength="3" required value="<?php if (!empty($nom)) echo $nom ?>"> email
        <input type="email" name="email" required value="<?php if (!empty($email)) echo $email ?>"> password
        <input type="password" name="password" required value="<?php if (!empty($password)) echo $password ?>">
        <input type="password" name="password2" required value="<?php if (!empty($password2)) echo $password2 ?>">
        <button>Register</button>
        <div ><?php if ($error && !empty($error)) echo $error ?></div>
    </form>
</body>

</html>