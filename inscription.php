<?php

    session_start();

    $bdd = new PDO("mysql:host=localhost;dbname=moduleconnexion;charset=utf8", "root", "");
    $message = "";

    function testPassword($pass){

        $errors = [];

        if(strlen($pass) < 8){
            array_push($errors, "Doit avoir au moins 8 charactère");
        }if (!preg_match('/[A-Z]/', $pass)){
            array_push($errors, "Doit contenir au moins 1 majuscule");
        }if (!preg_match('/[a-z]/', $pass)){
            array_push($errors, "Doit contenir au moins 1 minuscule");
        }if (!preg_match('~[0-9]~', $pass)){
            array_push($errors, "Doit contenir au moins 1 numéro");
        }if (preg_match("/[\'^£$%&*()}{@#~?><>,|=_+¬-]/", $pass)){
            array_push($errors, "Doit contenir au moins 1 charactère spécial");
        }
        return $errors;
    }

    if (isset($_POST["submit"])){

        $passtest = testPassword($_POST["password"]);
        
        if(empty($_POST["login"])){
            $message = "Entrer login";
        }
        elseif(empty($_POST["prenom"])){
            $message = "Entrer prenom";
        }
        elseif(empty($_POST["prenom"])){
            $message = "Entrer nom";
        }
        elseif(empty($_POST["password"]) || count($passtest) > 0){
            $message = "Mot de passe invalide.";
        }
        else {

            $username = $_POST["login"];
            $prenom = $_POST["prenom"];
            $nom = $_POST["nom"];
            $password = $_POST["password"];

            $req = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ?");
            $req->execute([$username]);
            $user = $req->fetch();

            if($user) {
                $message = "L'utilisateur existe déja";
            } else {
                $sql = "INSERT INTO utilisateurs (login, prenom, nom, password) VALUES (?,?,?,?)";
                $req = $bdd->prepare($sql);
                $req->execute([$username, $prenom, $nom, hash("sha256", $password)]);
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
            <menu>
                <a href="index.php">Accueil</a>
                
                <?php if(empty($_SESSION)) : ?>

                    <a href="connexion.php">Connexion</a>
                    <a href="inscription.php">Inscription</a>
                    
                <?php else : ?>
                    
                    <?php if($_SESSION["id"] == 1) : ?>
                        <a href="admin.php">Admin</a>
                    <?php endif ?>
                    
                    <a href="profil.php">Profil</a>
                    <a href="deconnexion.php">Déconnexion</a>
                <?php endif ?>
            </menu>
        </nav>
    </header>
    <main>
        <?php 
            if(!empty($message)){
                echo $message . "</br>";;
                foreach($passtest as $error){
                    echo $error . "</br>";
                }
            }
        ?>
        <form action="" method="POST">
            <input type="text" name="login" placeholder="login" required>
            <input type="text" name="prenom" placeholder="prenom" required>
            <input type="text" name="nom" placeholder="nom" required>
            <input type="password" name="password" placeholder="password" required>
            <input type="submit" name="submit" value="Inscription">
        </form>
    </main>
    <footer></footer>
</body>
</html>