<!-- Page Connexion -->

<?php
	session_start();

	$error_function = function(int $value, string $message, string $file, int $line) {
                switch ($value) {
                    case E_NOTICE:
                        echo '';
                        break;
                    default:
                        echo 'Ligne ' .$line .'<br/>';
                        echo 'Le problème est : ' .$message .'<br/>';
                        break;
                }
            };

    set_error_handler($error_function);

if($_SESSION['connexion'] != '1') {	

	include 'DiagHealth_database.php';
	global $db;

	$message = '';
	$confirmation = '';

	$_SESSION['page'] = "connexion";


	if (isset($_POST['login'])) {
		$Mail = $_POST['mail'];
		$Mdp = $_POST['mdp'];

		$rechercheMdp = $db -> prepare('SELECT mdp FROM users where mail=:mail');
		$rechercheMdp -> execute(array('mail' => $Mail));
		$resultatMdp = $rechercheMdp -> fetch();

		$MdpBon = password_verify($Mdp, $resultatMdp['mdp']);

		$rechercheRole = $db -> prepare('SELECT role FROM users where mail=:mail');
		$rechercheRole -> execute(array('mail' => $Mail));
		$resultatRole = $rechercheRole -> fetch();

		$rechercheAvatar = $db -> prepare('SELECT pp FROM users where mail=:mail');
		$rechercheAvatar -> execute(array('mail' => $Mail));
		$resultatAvatar = $rechercheAvatar -> fetch();

		$rechercheCentre = $db -> prepare('SELECT centre FROM users where mail=:mail');
		$rechercheCentre -> execute(array('mail' => $Mail));
		$resultatCentre = $rechercheCentre -> fetch();

		$rechercheID = $db -> prepare('SELECT idUtilisateur FROM users where mail=:mail');
		$rechercheID -> execute(array('mail' => $Mail));
		$resultatID = $rechercheID -> fetch();

		if (!$resultatMdp) {
			$message = "Identifiant ou mot de passe incorrect";
		}
		else {
			if ($MdpBon) {
				$_SESSION['role'] = $resultatRole['role'];
        		$_SESSION['connexion'] = '1';
        		//$_SESSION['pp'] = $resultatAvatar['pp'];
        		$_SESSION['centre'] = $resultatCentre['centre'];
        		$_SESSION['idUtilisateur'] = $resultatID['idUtilisateur'];
        		$confirmation = "ok";
        		header('Location: DiagHealth_accueil.php');
			}
			else {
				$message = "Identifiant ou mot de passe incorrect";
			}
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Page connexion</title>
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_connexion.css">
	</head>
	<body>

		<div class="division">
			<form method="post" action="" name="log">
			<div class="logo">
				<a href="DiagHealth_accueil.php" class="retour_menu"><img class="logo_img" src="../Public/Images/logo.png" alt=""/></img></a>
                <p class="message" align="center">
                	Connectez-vous à votre compte
                </p>
			</div>
			<p>
				<label for="identifiant">Identifiant</label></p>
				<input type="mail" placeholder="Entrez votre adresse mail" id="mail" name="mail" required>
		
			<p>
				<label for="">Mot de passe</label></p>
				<input type="password" placeholder="Entrez votre mot de passe" id="mdp" name="mdp" required>
		
			<p id="motd">
				<a href="DiagHealth_mdp_oublie.php" class="motd">Mot de passe oublié ?</a>
			</p>
		
			<p>
				<input type="submit" name="login" class="bouton_connexion" value="Connexion">
			</p>
			<?php
				echo $message;
				if ($confirmation == "ok") {
					echo "Vous êtes connecté. Retour à l'";
					?>
					<a href="DiagHealth_accueil.php"><i class="fas fa-home"></i> accueil</a>
					<?php
				}
			?>
			<div class="division2">
				<p>
					<a href="DiagHealth_inscription.php" class="bouton_inscription">S'inscrire</a>
				</p>
			</div>
		</form>
		</div>
	</body>
</html>
<?php 
}
else {
	header('Location: DiagHealth_accueil.php');
}
?>