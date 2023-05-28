<?php
    session_start();
    $bdd = new PDO("mysql:host=localhost;dbname=moduleconnexion;charset=utf8", "root", "");

    if (isset($_POST["submit"])){

        $username = $_POST["login"];
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $password = $_POST["password"];

        if( empty($username) || empty($firstname) || empty($lastname) || empty($password) ){
            $message = "Veuillez remplir tout les champs";
        }
        else {

            $req = $bdd->prepare("UPDATE user 
                SET login = ?, firstname = ?, lastname = ?, password = ?
                WHERE id = ?");
            $req->execute([$username, $firstname, $lastname, $_SESSION["id"]]);

            $_SESSION["login"] = $username;
            $_SESSION["firstname"] = $firstname;
            $_SESSION["lastname"] = $lastname;

            header("location: index.php");
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
    <title>Module de connexion - Profil</title>
</head>
<body>
    <header>
        <nav>
            <div>
                <a href="index.php">Accueil</a>
            </div>
            <div>
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
            </div>
        </nav>
    </header>
    <main>
        <?php if (!empty($_SESSION)) : ?>
            <div class="form-container">
                <form action="" method="post">
                    <h2>Editer profil</h2>
                    <?php
                        echo "<input name='login' value=" . $_SESSION["login"] . ">";
                        echo "<input name='firstname' value=" . $_SESSION["firstname"] . ">";
                        echo "<input name='lastname' value=" . $_SESSION["lastname"] . ">";
                        echo "<input name='password' placeholder='Nouveau mot de passe'>";
                        echo "<input type='submit' name='submit' value='Modifier'>";
                    ?>
                </form>
            </div>
        <?php endif ?>
    </main>
    <footer>
        <span>Laguerre Jean-Bernard</span>
        <a href="https://github.com/jean-bernard-laguerre/module-connexion">
            <img src="./style/images/Github.png" alt="logo github" />
        </a>   
    </footer>
</body>
</html>