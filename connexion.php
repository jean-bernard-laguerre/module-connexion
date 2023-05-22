<?php

    session_start();

    $bdd = new PDO("mysql:host=localhost;dbname=moduleconnexion;charset=utf8", "root", "");
    $message = "";

    if (isset($_POST["submit"])){

        if(empty($_POST["login"])){
            $message = "Entrer login";
        }
        elseif(empty($_POST["password"])){
            $message = "Entrer mot de passe";
        }
        else {

            $username = $_POST["login"];
            $password = $_POST["password"];

            $req = $bdd->prepare("SELECT id, login, prenom, nom FROM utilisateurs WHERE login = ? AND password = ?");
            $req->execute([$username, hash("sha256", $password)]);
            $user = $req->fetch();

            if(!$user) {
                $message = "Login ou Password invalide.";
            } else {
                $_SESSION["id"] = $user["id"];
                $_SESSION["login"] = $user["login"];
                $_SESSION["prenom"] = $user["prenom"];
                $_SESSION["nom"] = $user["nom"];
                header("location: index.php");
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
    <title>Module de connexion - Connexion</title>
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
                echo $message;
            }
        ?>
        <form action="" method="POST">
            <input type="text" name="login" placeholder="login" required>
            <input type="password" name="password" placeholder="password" required>
            <input type="submit" name="submit" value="Connexion">
        </form>
    </main>
    <footer></footer>
</body>
</html>