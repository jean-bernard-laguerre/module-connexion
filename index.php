<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module de connexion - Accueil</title>
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
            if(!empty($_SESSION)){
                echo "Bonjour " . $_SESSION["prenom"];
            }
        ?>
    </main>
    <footer></footer>
</body>
</html>