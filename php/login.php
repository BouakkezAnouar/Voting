<?php
// Start the session
session_start();
 if(isset($_SESSION['login'])){ //if login in session is not set
    header("Location: afficher_projets.php");
}



$email ="";
$password ="" ;
$error="";

if (isset($_POST['email']) && isset($_POST['password']))
{
    include("connection.php");

    $email = htmlspecialchars($_POST['email']);
    $password =htmlspecialchars($_POST['password']);
    
    //if email or password is vide
    if(empty(trim($email)) || empty(trim($password)))
        $error = "all champs must be remplied !!" ;

    else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? and password = ?");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch();

    if ($user)
    {
        //create session 
        session_start();
        $_SESSION['login']="logedin";
        $_SESSION['id']=$user['id'];
        $_SESSION['nom']=$user['nom'];
        $_SESSION['email']=$user['email'];
        header("Location: afficher_projets.php");

    } else {
        $error = "email or password is worng !";
    }
}
//close connection
$conn = null;
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>

<body>
    <form action="login.php" method="POST">
        email
        <input type="email" name="email" required value="<?php if (!empty($email)) echo $email ?>"> password
        <input type="password" name="password" required  value="<?php if (!empty($password)) echo $password ?>">
        <button>Login</button>
        <div ><?php if ($error && !empty($error)) echo $error ?></div>
    </form>
</body>

</html>