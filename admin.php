<?php
    session_start();

    $bdd = new PDO("mysql:host=localhost;dbname=moduleconnexion;charset=utf8", "root", "");

    $sql = "SELECT login, firstname, lastname FROM user";
    $req = $bdd->prepare($sql);
    $req->execute();
    $users = $req->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/style.css">
    <title>Module de connexion - Admin</title>
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
        <div class="table-container">
            <table>
                <caption>Utilisateurs</caption>
                <thead>
                    <td>Login</td>
                    <td>Prénom</td>
                    <td>Nom</td>
                </thead>
                <tbody>
                    <?php
                        foreach($users as $user){
                            echo "<tr>";
                            foreach($user as $col){
                                echo "<td>" . $col . "</td>";
                            }
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
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