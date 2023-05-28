<?php

    session_start();

    $bdd = new PDO("mysql:host=localhost;dbname=moduleconnexion;charset=utf8", "root", "");
    $message = "";

    function testPassword($pass){

        $conditions = [ ["Doit contenir au moins une lettre majuscule.", '/[A-Z]/'],
                        ["Doit contenir au moins une lettre minuscule.", '/[a-z]/'],
                        ["Doit contenir au moins un chiffre.", '/\d/'],
                        ["Doit contenir au moins un caractère spécial.", "/[\'^£$%&*()}{@#~?><>,|=_+¬-]/"]];

        $errors = [];

        if(strlen($pass) < 8){
            array_push( $errors, "Doit avoir au moins 8 charactère" );
        }
        foreach( $conditions as $condition ){
            if( !preg_match( $condition[1], $pass )){
                array_push( $errors, $condition[0] );
            }
        }
        return $errors;
    }

    if (isset($_POST["submit"])){

        $username = $_POST["login"];
        $firstname = $_POST["firstname"];
        $nom = $_POST["nom"];
        $password = $_POST["password"];

        $passtest = testPassword( $password );
        
        if( empty($username) || empty($firstname) || empty($lastname) ){
            $message = "Entrer login, nom et prénom";
        }
        elseif( empty($_POST["password"]) || count($passtest) > 0 ){
            $message = "Mot de passe invalide.";
        }
        elseif( $_POST["password"] != $_POST["confirm-pass"] ){
            $message = "Veuillez confirmer le mot de passe.";
        }
        else {

            $req = $bdd->prepare( "SELECT * FROM user WHERE login = ?" );
            $req->execute( [$username] );
            $user = $req->fetch();

            if($user) {
                $message = "Ce nom d'utilisateur est déja utilisé";
            } else {
                $sql = "INSERT INTO user (login, firstname, lastname, password) VALUES (?,?,?,?)";
                $req = $bdd->prepare($sql);
                $req->execute( [$username, $firstname, $nom, hash("sha256", $password)] );
                header("location: connexion.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
    <title>Module de connexion - Inscription</title>
</head>
<body>
    <header>
        <nav>
            <div>
                <a href="index.php">Accueil</a>
            </div>
            <div>
                <?php if( empty($_SESSION) ) : ?>
                    <a href="connexion.php">Connexion</a>
                    <a href="inscription.php">Inscription</a>
                
                <?php else : ?>
                
                    <?php if( $_SESSION["id"] == 1 ) : ?>
                        <a href="admin.php">Admin</a>
                    <?php endif ?>
                
                    <a href="profil.php">Profil</a>
                    <a href="deconnexion.php">Déconnexion</a>
                <?php endif ?>
            </div>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <form action="" method="POST">
                <h2>Creation de compte</h2>
                <?php 
                    if( !empty($message) ){
                        echo $message . "</br>";;
                        foreach( $passtest as $error ){
                            echo $error . "</br>";
                        }
                    }
                ?>
                <input type="text" name="login" placeholder="Login" required>
                <input type="text" name="firstname" placeholder="firstname" required>
                <input type="text" name="nom" placeholder="Nom" required>
                <input type="password" name="password" placeholder="Mot de passe" required>
                <input type="password" name="confirm-pass" placeholder="Confirmer mot de passe" required>
                <input type="submit" name="submit" value="Inscription">
            </form>
        </div>
    </main>
    <footer>
        <span>Laguerre Jean-Bernard</span>
        <a href="https://github.com/jean-bernard-laguerre/module-connexion">
            <img src="./style/images/Github.png" alt="logo github" />
        </a>   
    </footer>
</body>
</html>