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

	include 'DiagHealth_database.php';
	global $db;

	$_SESSION['page'] = "mdp_oublie";

	$msg = '';

	if(isset($_POST['envoie'])) {
		$Mail = $_POST['mail'];
		if(filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
			// on vérifie que l'adresse mail n'est pas déjà utilisée par un autre compte
			$req_mail = $db -> prepare('SELECT mail FROM users');
			$req_mail -> execute();
			$fetch_mail = $req_mail -> fetchAll();

			$mailindatabase = array(count($fetch_mail));
			for ($i = 0; $i <= count($fetch_mail)-1; $i++) {
				$mailindatabase[$i] = $fetch_mail[$i][0];
			}

			if (in_array("$Mail", $mailindatabase,true)) {
				$longueurKey = 6;
				$key = "";
				for($i=0;$i<$longueurKey;$i++) {
					$key .= mt_rand(0,9);
				}
				$header="MIME-Version: 1.0\r\n";
				$header.='From:"DiagHealth.com"<support@DiagHealth.com>'."\n";
				$header.='Content-Type:text/html; charset="uft-8"'."\n";
				$header.='Content-Transfer-Encoding: 8bit';
				$message='
				<html>
					<body>
						<div align="center">
							<h2>Bonjour, vous souhaitez réinitialiser votre mot de passe ; voici votre code de confirmation</h2>
							<br />
							<br />
							<h2>'.$key.'</h2>
						</div>
					</body>
				</html>
				';
				mail($Mail, "Réinitialisation du mot de passe", $message, $header);
				?>
				<div id="overlay">
					<div id="popup">
						<h2 class="popup_titre">Code de confirmation</h2>
						<p class="popup_msg">Un code à été envoyé à l'adresse <?= $Mail; ?>, insérer ce code pour confirmer le changement de mot de passe.</p>
						<form method="post">
							<div align="center">
								<div style="display: none;">
									<input type="text" value="<?= $Mail; ?>" name="mail">
									<input type="text" value="<?= $key; ?>" name="key">
								</div>
								<input type="text" class="key" name="key1" required>
								<input type="text" class="key" name="key2" required>
								<input type="text" class="key" name="key3" required>
								<input type="text" class="key" name="key4" required>
								<input type="text" class="key" name="key5" required> 
								<input type="text" class="key" name="key6" required>
							</div>
							<div align="center" class="conf">
								<input type="submit" class="confirmation" name="confirmation" value="Confirmer">
							</div>
						</form>	
					</div>
				</div>
				<?php
			} else {
				$msg = "Cette adresse mail n'existe pas !";
			}
		}
	}

	if(isset($_POST['confirmation'])) {
		$Mail = $_POST['mail'];
		$key = $_POST['key'];
		$keyConfirm = $_POST['key1'] .$_POST['key2'] .$_POST['key3'] .$_POST['key4'] .$_POST['key5'] .$_POST['key6'];
		if($keyConfirm == $key) {
			?>
			<div id="overlay">
				<div id="popup">
					<h2 class="popup_titre">Nouveau mot de passe</h2>
					<form method="post">
						<div style="display: none;">
							<input type="text" value="<?= $Mail; ?>" name="mail">
						</div>
						<table>
							<tr>
								<td class="titre">Votre nouveau mot de passe :</td>
								<td class="input"><input type="password" name="mdp" id="mdp" required></td>
							</tr>
							<tr class="espace"></tr>
							<tr>
								<td class="titre">Confirmez votre nouveau mot de passe :</td>
								<td class="input"><input type="password" name="cmdp" id="cmdp" oninput="verifPassword()" required><a id="msg_erreur_mdp" class="msg_erreur" style="display: none;"></a></td>
							</tr>
						</table>
						<div class="conf" align="center">
							<input type="submit" class="confirmation" name="valider" value="Valider">
						</div>
					</form>	
				</div>
			</div>
			<?php
		} else {
			?>
			<div id="overlay">
				<div id="popup">
					<h2 class="popup_titre">Action échouée</h2>
					<p class="popup_msg">Le code que vous avez rentré n'est pas le bon.</p>
					<div align="center">
						<a class="popup_lien" href="DiagHealth_mdp_oublie.php">Tenter de nouveau</a>
						<a class="popup_lien" href="DiagHealth_accueil.php">Accueil</a>
					</div>
				</div>
			</div>
			<?php
		}
	} 

	if(isset($_POST['valider'])) {
		$Mail = $_POST['mail'];
		$Mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
		$Cmdp = $_POST['cmdp'];
		if(password_verify($Cmdp, $Mdp)) {
			$modif_mdp = "UPDATE users SET mdp='$Mdp' where mail='$Mail'";
			$db -> exec($modif_mdp);
			?>
			<div id="overlay">
					<div id="popup">
						<h2 class="popup_titre">Mot de passe modifié !</h2>
						<p class="popup_msg">Votre mot de passe a bien été modifié.</p>
						<div align="center">
							<a class="popup_lien" href="DiagHealth_connexion.php">Se connecter</a>
							<a class="popup_lien" href="DiagHealth_accueil.php">Accueil</a>
						</div>
					</div>
				</div>
			<?php
		} else {
			?>
			<div id="overlay">
				<div id="popup">
					<h2 class="popup_titre">Action échouée</h2>
					<p class="popup_msg">Les mots de passe de correspondent pas.</p>
					<div align="center">
						<a class="popup_lien" href="DiagHealth_mdp_oublie.php">Tenter de nouveau</a>
						<a class="popup_lien" href="DiagHealth_accueil.php">Accueil</a>
					</div>
				</div>
			</div>
			<?php
		}
	}


?> 

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Mot de passe oublié</title>
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_mdp_oublie.css">
	</head>
	<?php include 'DiagHealth_header.php'; ?>
	<body>
		<form method="post">
			<div class="contenu">
				<label>Votre adresse mail :</label>
				<input type="email" name="mail" placeholder="exemple: martin.dupont@exemple.fr" required> <br>
				<label class="message"><?= $msg; ?></label> <br>
				<input type="submit" id="envoie" name="envoie" value="Envoyer le mail">
			</div>
		</form>
		<p>
			Un mail sera envoyé à l'adresse indiquée pour que vous puissiez modifier votre mot de passe.
		</p>
	</body>
	<?php include 'DiagHealth_footer.php'; ?>
</html>