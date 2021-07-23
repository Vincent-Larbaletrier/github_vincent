<!-- Header -->

<?php
	session_start();

  include 'DiagHealth_database.php';
  global $db;

  $recherchepp = $db -> prepare('SELECT pp FROM users where idUtilisateur=:idUtilisateur');
  $recherchepp -> execute(array('idUtilisateur' => $_SESSION['idUtilisateur']));
  $resultatpp = $recherchepp -> fetch();

  $_SESSION['pp'] = $resultatpp['pp'];
?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8">
      <title>Header</title>
      <script src="https://kit.fontawesome.com/d8ae1e748c.js" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="../Public/CSS/DiagHealth_base.css">
      <link rel="stylesheet" href="../Public/CSS/DiagHealth_header.css">
  </head>
  <body>
    <div class="header">    
        <div class="container">
            <div class="header_navbar">
                <div class="header_navbar_logo">
                    <a href="DiagHealth_accueil.php" class="header_navbar_logo_title"><img class="logo" src="../Public/Images/logo.png" alt=""
                            width="100px" /></img></a>
                </div>
                <div class="header_navbar_menu">
                <?php
                  if(!isset($_SESSION['role'])) {
                  ?>
                  <ul class="ul">
                    <li class="li">
                      <a href="DiagHealth_connexion.php" class="header_navbar_menu_link"><i class="fab fa-expeditedssl"></i> Mon compte</a>
                    </li>       
                    <li class="li">
                      <a href="DiagHealth_connexion.php" class="header_navbar_menu_link"><i class="fab fa-expeditedssl"></i> Mes données</a>
                    </li>
                    <?php
                  }
                ?>
                <?php
                if(isset($_SESSION['role'])) {
                ?>
                  <ul class="ul">
                    <li class="li">
                      <a href="DiagHealth_MonCompte.php" class="header_navbar_menu_link"><i class="fas fa-user-circle"></i> Mon compte</a>
                    </li>
                    <?php
                }
                ?>
                <?php
                if(isset($_SESSION['role'])) {
                  if($_SESSION['role'] == 'Utilisateur') {    
                  ?>
                    <li class="li">
                      <a href="DiagHealth_donnees.php" class="header_navbar_menu_link"><i class="fas fa-poll"></i> Mes données</a>
                    </li>
                    <?php
                  }
                }
                ?>
                <?php
                if(isset($_SESSION['role'])) {
                  if($_SESSION['role'] == 'Gestionnaire') {      
                  ?>
                    <li class="li">
                      <a href="DiagHealth_gestion_gest.php" class="header_navbar_menu_link"><i class="fas fa-users"></i> Gestion des utilisateurs</a>
                    </li>
                    <?php
                  }
                }
                ?>
                <?php
                if(isset($_SESSION['role'])) {
                  if($_SESSION['role'] == 'Administrateur') {
                  ?>
                    <li class="li">
                      <a href="DiagHealth_gestion_admin.php" class="header_navbar_menu_link"><i class="fas fa-users"></i> Gestion des utilisateurs</a>
                    </li>
                    <?php
                  }
                }
                ?>
                <li class="li">
                  <a class="header_navbar_menu_link"><i class="fas fa-globe-europe"></i> Langue</a>
                  <ul class="ul">
                    <li class="li"><a class="header_navbar_menu_link">Français</a></li>
                    <li class="li"><a href="en_DiagHealth_<?= $_SESSION['page']; ?>.php" class="header_navbar_menu_link">Anglais</a></li>
                  </ul>
                </li>
                <?php
                if(!isset($_SESSION['role'])) {           
                ?>
                  <li class="li">
                    <a href="DiagHealth_connexion.php" class="header_navbar_menu_link_co"><i class="fas fa-key"></i> Connexion/Inscription</a>
                  </li>
                </ul>
                <?php
                }
                ?>
                <?php
                if(isset($_SESSION['role'])) {
                ?>
                  <li class="li">
                    <form method="post" name="deconnexion">
                      <a class="header_navbar_menu_link_co"><i class="fas fa-sign-out-alt"></i><input type="submit" name="deconnexion" class="deco" value="Se déconnecter"></a>
                    </form>
                  </li>
                  <?php
                }
                ?>
                <?php 
                if(isset($_SESSION['role'])) {
                ?>
                  <li class="li" class="avatar_pp">
                    <a href="DiagHealth_MonCompte.php" class="avatar"><img class="pp" src="users/pp/<?= $_SESSION['pp'];?>"></a>
                    <div class="statut">
                      <?php if($_SESSION['role'] == 'Utilisateur') {
                          ?> <h3 class="badge"><i class="fas fa-id-badge"></i><h4 class="info_role">Utilisateur</h4></h3> <?php
                      }
                      ?>
                      <?php if($_SESSION['role'] == 'Gestionnaire') {
                          ?> <h3 class="badge"><i class="fas fa-user-tie"></i><h4 class="info_role">Gestionnaire</h4></h3> <?php
                      }
                      ?>
                      <?php if($_SESSION['role'] == 'Administrateur') {
                          ?> <h3 class="badge"><i class="fas fa-crown"></i><h4 class="info_role">Administrateur</h4></h3> <?php
                      }
                      ?>
                    </div>
                  </li>
                </ul>
                <?php
                }
                ?>
              </div>
              <div class="header_navbar_toggle">
                  <span class="header_navbar_toggle_icons"></span>
              </div>
            </div>    
          </div>
        </div>
        <?php if(isset($_POST['deconnexion'])) {
          $_SESSION['connexion'] = '0';
          session_destroy();
          ?><div id="overlay">
              <div id="popup">
                <h2 class="popup_titre">Vous avez été déconnecté</h2>
                <p class="popup_msg">Votre session a été déconnectée.</p>
                <div align="center">
                  <a class="popup_lien" href="DiagHealth_connexion.php">Se reconnecter</a>
                  <a class="popup_lien" href="DiagHealth_accueil.php">Accueil</a>
                </div>
              </div>
            </div><?php
        }
        ?>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="../Public/JS/app.js"></script>
  </body>
</html>