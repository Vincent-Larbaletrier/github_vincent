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
							<h2>Hello, you want to reset your password; here is your confirmation code</h2>
							<br />
							<br />
							<h2>'.$key.'</h2>
						</div>
					</body>
				</html>
				';
				mail($Mail, "Reset password", $message, $header);
				?>
				<div id="overlay">
					<div id="popup">
						<h2 class="popup_titre">Confirmation code</h2>
						<p class="popup_msg">A code has been sent to the email address <?= $Mail; ?>, insert this code to confirm the password change.</p>
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
								<input type="submit" class="confirmation" name="confirmation" value="Confirm">
							</div>
						</form>	
					</div>
				</div>
				<?php
			} else {
				$msg = "This email address does not exist !";
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
					<h2 class="popup_titre">New password</h2>
					<form method="post">
						<div style="display: none;">
							<input type="text" value="<?= $Mail; ?>" name="mail">
						</div>
						<table>
							<tr>
								<td class="titre">Your new password :</td>
								<td class="input"><input type="password" name="mdp" id="mdp" required></td>
							</tr>
							<tr class="espace"></tr>
							<tr>
								<td class="titre">Confirm your new password :</td>
								<td class="input"><input type="password" name="cmdp" id="cmdp" oninput="verifPassword()" required><a id="msg_erreur_mdp" class="msg_erreur" style="display: none;"></a></td>
							</tr>
						</table>
						<div class="conf" align="center">
							<input type="submit" class="confirmation" name="valider" value="Validate">
						</div>
					</form>	
				</div>
			</div>
			<?php
		} else {
			?>
			<div id="overlay">
				<div id="popup">
					<h2 class="popup_titre">Failed action</h2>
					<p class="popup_msg">The code you entered is incorrect.</p>
					<div align="center">
						<a class="popup_lien" href="en_DiagHealth_mdp_oublie.php">Try again</a>
						<a class="popup_lien" href="en_DiagHealth_accueil.php">Home</a>
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
						<h2 class="popup_titre">Password changed successfully !</h2>
						<p class="popup_msg">Your password has been changed successfully.</p>
						<div align="center">
							<a class="popup_lien" href="en_DiagHealth_connexion.php">Sign in</a>
							<a class="popup_lien" href="en_DiagHealth_accueil.php">Home</a>
						</div>
					</div>
				</div>
			<?php
		} else {
			?>
			<div id="overlay">
				<div id="popup">
					<h2 class="popup_titre">Failed action</h2>
					<p class="popup_msg">The passwords do not match.</p>
					<div align="center">
						<a class="popup_lien" href="en_DiagHealth_mdp_oublie.php">Try again</a>
						<a class="popup_lien" href="en_DiagHealth_accueil.php">Home</a>
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
		<title>Forgot your password</title>
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_mdp_oublie.css">
	</head>
	<?php include 'en_DiagHealth_header.php'; ?>
	<body>
		<form method="post">
			<div class="contenu">
				<label>Your email :</label>
				<input type="email" name="mail" placeholder="exemple: martin.dupont@exemple.fr" required> <br>
				<label class="message"><?= $msg; ?></label> <br>
				<input type="submit" id="envoie" name="envoie" value="Send mail">
			</div>
		</form>
		<p>
			An email will be sent to the address indicated so that you can change your password.
		</p>
	</body>
	<?php include 'en_DiagHealth_footer.php'; ?>
</html>