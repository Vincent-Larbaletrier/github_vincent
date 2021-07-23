<?php 
	// lancement session
	session_start();
?>

<!DOCTYPE html>
<html>
<?php
	// connexion database
	include 'DiagHealth_database.php';
	global $db;

	$_SESSION['page'] = "inscription";
	// on cherche l'id du centre le plus élevé
	$req_centre = $db -> prepare('SELECT idCentre FROM centre ORDER BY dateCreation DESC');
	$req_centre -> execute();
	$fetch_centre = $req_centre -> fetch();
	// id du centre le plus élevé
	$id_max_centre = $fetch_centre['idCentre'];
	// tableau du genre
	$GenreChoix = array('Mrs','Mr.');
	// tableau du rôle
	$RoleChoix = array('Utilisateur','Gestionnaire', 'Administrateur');
	// variable retour
	$retour = "Back to ";
	// variable message d'erreur
	$message = '';

	$testDemande = "0";

	// si le formulaire est rempli
	if(isset($_POST['inscription'])) {
		// définition des variables
		$Numgenre = $_POST['genre'];
		$Genre = $GenreChoix[$_POST['genre']];

		$Numrole = $_POST['role'];
		$Role = $RoleChoix[$_POST['role']];

		$Nom = html_entity_decode(addslashes($_POST['nom']));
		$Prenom = html_entity_decode(addslashes($_POST['prenom']));
		$Centre = $_POST['centre'];
		$Adresse = html_entity_decode(addslashes($_POST['adresse']));
		$Mail = $_POST['mail'];
		$Mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
		$Cmdp = $_POST['cmdp'];
		$Naissance = $_POST['date'];
		$Tel = $_POST['tel'];
		// cookies
		setcookie('nom', $Nom, time() + 300);
		setcookie('prenom', $Prenom, time() + 300);
		setcookie('adresse', $Adresse, time() + 300);
		setcookie('mail', $Mail, time() + 300);
		setcookie('tel', $Tel, time() + 300);
		// attribution pp par défaut en fonction du genre
		if($Numgenre == 0) {
			$pp = '0F.jpg';
		}
		else {
			$pp = '0H.jpg';
		}
		if(!empty($Genre) || !empty($Role) || !empty($Nom) || !empty($Prenom) || !empty($Centre) || !empty($Mail) || !empty($Mdp) || !empty($Cmdp) || !empty($Naissance)) {
			// on vérifie que les mdp correspondent et que l'adresse mail correspond
			if(password_verify($Cmdp, $Mdp)) {
				if(filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
					// on vérifie que l'adresse mail n'est pas déjà utilisée par un autre compte
					$req_mail = $db -> prepare('SELECT mail FROM users');
					$req_mail -> execute();
					$fetch_mail = $req_mail -> fetchAll();

					$mailindatabase = array(count($fetch_mail));
					for ($i = 0; $i <= count($fetch_mail)-1; $i++) {
						$mailindatabase[$i] = $fetch_mail[$i][0];
					}

					$req_mail2 = $db -> prepare('SELECT mail FROM inscription_attentes');
					$req_mail2 -> execute();
					$fetch_mail2 = $req_mail2 -> fetchAll();

					$mailindatabase2 = array(count($fetch_mail2));
					for ($j = 0; $j <= count($fetch_mail2)-1; $j++) {
						$mailindatabase2[$j] = $fetch_mail2[$j][0];
					}

					if (in_array("$Mail", $mailindatabase,true) || in_array("$Mail", $mailindatabase2,true)) {
						$message = "This email address is already in use !";
					}

					else {
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
									<h2>Hello '.$Prenom. ', here is your confirmation code</h2>
									<br />
									<br />
									<h2>'.$key.'</h2>
								</div>
							</body>
						</html>
						';
						mail($Mail, "Sign up confirmation code", $message, $header);
						?>
						<div id="overlay">
							<div id="popup">
								<h2 class="popup_titre">You are almost there !</h2>
								<p class="popup_msg">A code has been sent to the email address <?= $Mail; ?>, insert this code to confirm your registration</p>
								<form method="post">
									<div align="center">
										<div style="display: none;">
											<input type="text" value="<?= $Numgenre; ?>" name="genre">
											<input type="text" value="<?= $Role; ?>" name="role">
											<input type="text" value="<?= $Nom; ?>" name="nom">
											<input type="text" value="<?= $Prenom; ?>" name="prenom">
											<input type="text" value="<?= $Mail; ?>" name="mail">
											<input type="text" value="<?= $Centre; ?>" name="centre">
											<input type="text" value="<?= $Mdp; ?>" name="mdp">
											<input type="text" value="<?= $Adresse; ?>" name="adresse">
											<input type="text" value="<?= $Naissance; ?>" name="date">
											<input type="text" value="<?= $Tel; ?>" name="tel">
											<input type="text" value="<?= $pp; ?>" name="pp">
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
					}
				}
				else {
					$message = "Invalid email address !";
				}
			}
			else {
				$message = "Passwords do not match !";
			}
		}
		else {
			$message = "All fields are not filled !";
		}
	}
    
	if(isset($_POST['confirmation'])) {
		$Numgenre = $_POST['genre'];
		$Role = $_POST['role'];

		$Nom = html_entity_decode(addslashes($_POST['nom']));
		$Prenom = html_entity_decode(addslashes($_POST['prenom']));
		$Centre = $_POST['centre'];
		$Adresse = html_entity_decode(addslashes($_POST['adresse']));
		$Mail = $_POST['mail'];
		$Mdp = $_POST['mdp'];
		$Naissance = $_POST['date'];
		$Tel = $_POST['tel'];
		$pp = $_POST['pp'];
		$key = $_POST['key'];
		$keyConfirm = $_POST['key1'] .$_POST['key2'] .$_POST['key3'] .$_POST['key4'] .$_POST['key5'] .$_POST['key6'];
		if($keyConfirm == $key) {
			// si inscription utilisateur alors ajout dans la base de donnée 
			if ($Role == 'Utilisateur') {
				// insertion dans la BDD
				$insert_user = "INSERT INTO users(genre,nom,prenom,mail,mdp,dateNaissance,tel,role,pp,centre,adresse,testDemande) VALUES ('$Numgenre','$Nom','$Prenom','$Mail','$Mdp','$Naissance','$Tel','$Role','$pp','$Centre','$Adresse','$testDemande')";
				$db -> exec($insert_user);
				// popup de réussite
				?>
				<div id="overlay">
					<div id="popup">
						<h2 class="popup_titre">Successful registration</h2>
						<p class="popup_msg">Your registration is complete, log in or return to home page.</p>
						<div align="center">
							<a class="popup_lien" href="en_DiagHealth_connexion.php">Sign in</a>
							<a class="popup_lien" href="en_DiagHealth_accueil.php">Home</a>
						</div>
					</div>
				</div>
				<?php 
				$retour = "Your registration is complete, you can go back to ";
			}
			// sinon ajout à la file d'attente
			else {
				$insert_attente = "INSERT INTO inscription_attentes(genre,nom,prenom,mail,mdp,dateNaissance,tel,role,pp,centre,adresse,testDemande) VALUES ('$Numgenre','$Nom','$Prenom','$Mail','$Mdp','$Naissance','$Tel','$Role','$pp','$Centre','$Adresse','$testDemande')";
				$db -> exec($insert_attente);
				// popup de réussite
				?>
				<div id="overlay">
					<div id="popup">
						<h2 class="popup_titre">Successful registration request</h2>
						<p class="popup_msg">Your registration request has been sent, you will be able to connect once an administrator has validated your request.</p>
						<div align="center">
							<a class="popup_lien" href="en_DiagHealth_accueil.php">Home</a>
						</div>
					</div>
				</div>
				<?php 
				$retour = "Your registration request has been sent, you can go back to ";
			}
		}
		else {
			?>
			<div id="overlay">
				<div id="popup">
					<h2 class="popup_titre">Failed registration</h2>
					<p class="popup_msg">The code you entered is incorrect.</p>
					<div align="center">
						<a class="popup_lien" href="en_DiagHealth_inscription.php">Attempt to register again</a>
						<a class="popup_lien" href="en_DiagHealth_accueil.php">Home</a>
					</div>
				</div>
			</div>
			<?php
		}
	}
/*
	// si le formulaire est rempli
	if(isset($_POST['inscription'])) {
		// définition des variables
		$Numgenre = $_POST['genre'];
		$Genre = $GenreChoix[$_POST['genre']];

		$Numrole = $_POST['role'];
		$Role = $RoleChoix[$_POST['role']];

		$Nom = html_entity_decode(addslashes($_POST['nom']));
		$Prenom = html_entity_decode(addslashes($_POST['prenom']));
		$Centre = $_POST['centre'];
		$Adresse = html_entity_decode(addslashes($_POST['adresse']));
		$Mail = $_POST['mail'];
		$Mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
		$Cmdp = $_POST['cmdp'];
		$Naissance = $_POST['date'];
		$Tel = $_POST['tel'];
		// cookies
		setcookie('nom', $Nom, time() + 300);
		setcookie('prenom', $Prenom, time() + 300);
		setcookie('adresse', $Adresse, time() + 300);
		setcookie('mail', $Mail, time() + 300);
		setcookie('tel', $Tel, time() + 300);
		// attribution pp par défaut en fonction du genre
		if($Numgenre == 0) {
			$pp = '0F.png';
		}
		else {
			$pp = '0H.jpg';
		}
		if(!empty($Genre) || !empty($Role) || !empty($Nom) || !empty($Prenom) || !empty($Centre) || !empty($Mail) || !empty($Mdp) || !empty($Cmdp) || !empty($Naissance)) {
			// on vérifie que les mdp correspondent et que l'adresse mail correspond
			if(password_verify($Cmdp, $Mdp)) {
				if(filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
					// on vérifie que l'adresse mail n'est pas déjà utilisée par un autre compte
					$req_mail = $db -> prepare('SELECT mail FROM users');
					$req_mail -> execute();
					$fetch_mail = $req_mail -> fetchAll();

					$mailindatabase = array(count($fetch_mail));
					for ($i = 0; $i <= count($fetch_mail)-1; $i++) {
						$mailindatabase[$i] = $fetch_mail[$i][0];
					}

					$req_mail2 = $db -> prepare('SELECT mail FROM inscription_attentes');
					$req_mail2 -> execute();
					$fetch_mail2 = $req_mail2 -> fetchAll();

					$mailindatabase2 = array(count($fetch_mail2));
					for ($j = 0; $j <= count($fetch_mail2)-1; $j++) {
						$mailindatabase2[$j] = $fetch_mail2[$j][0];
					}

					if (in_array("$Mail", $mailindatabase,true) || in_array("$Mail", $mailindatabase2,true)) {
						$message = "Cette adresse mail est déjà utilisée !";
					}

					else {
						// si inscription utilisateur alors ajout dans la base de donnée 
						if ($Role == 'Utilisateur') {
							// insertion dans la BDD
							$insert_user = "INSERT INTO users(genre,nom,prenom,mail,mdp,dateNaissance,tel,role,pp,centre,adresse) VALUES ('$Numgenre','$Nom','$Prenom','$Mail','$Mdp','$Naissance','$Tel','$Role','$pp','$Centre','$Adresse')";
							$db -> exec($insert_user);
							// popup de réussite
							?>
							<div id="overlay">
								<div id="popup">
									<h2 class="popup_titre">Inscription réussie</h2>
									<p class="popup_msg">Votre inscription est terminée, connectez-vous ou retournez à l'accueil.</p>
									<div align="center">
										<a class="popup_lien" href="DiagHealth_connexion.php">Se connecter</a>
										<a class="popup_lien" href="DiagHealth_accueil.php">Accueil</a>
									</div>
								</div>
							</div>
							<?php 
							$retour = "Votre inscription est terminée, vous pouvez retourner à l'";
						}
						// sinon ajout à la file d'attente
						else {
							$insert_attente = "INSERT INTO inscription_attentes(genre,nom,prenom,mail,mdp,dateNaissance,tel,role,pp,centre,adresse) VALUES ('$Numgenre','$Nom','$Prenom','$Mail','$Mdp','$Naissance','$Tel','$Role','$pp','$Centre','$Adresse')";
							$db -> exec($insert_attente);
							// popup de réussite
							?>
							<div id="overlay">
								<div id="popup">
									<h2 class="popup_titre">Demande d'inscription réussie</h2>
									<p class="popup_msg">Votre demande d'inscription a bien été envoyée, vous pourrez vous connecter une fois qu'un administrateur aura validé votre demande.</p>
									<div align="center">
										<a class="popup_lien" href="DiagHealth_accueil.php">Accueil</a>
									</div>
								</div>
							</div>
							<?php 
							$retour = "Votre demande d'inscription à bien été transmise, vous pouvez retourner à l'";
						}
					}
				}
				else {
					$message = "Adresse mail invalide !";
				}
			}
			else {
				$message = "Les mots de passe ne correspondent pas !";
			}
		}
		else {
			$message = "Tous les champs ne sont pas remplis !";
		}
	}
*/
?>
	<head>
		<title>Sign up page</title>
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_inscription.css">
	</head>

	<body>
		<div id="contenu">
			<h1>Sign up</h1>
			<a href="en_DiagHealth_accueil.php" class="logo_a"><img class="logo_img" src="../Public/Images/logo.png" alt=""/></img></a>
            <!-- Formulaire inscription -->
			<form method="post" action="" name="formulaire">
			<p>
				<label for="civilite">Civility* <br></label>
			</p>
			<select id="civilite" name="genre" required>
				<option value=""></option>
				<?php
					foreach($GenreChoix as $NumGenre => $Genre):
					echo '<option value="'.$NumGenre.'">'.$Genre.'</option>';
					endforeach;
				?>
			</select>
			<br><br>
			<div class="categorie">
				<select id="role" name="role" onmouseover="over('attention')" onmouseout="out('attention')" required>
				<option value="">You're a : </option>
				<?php
					foreach($RoleChoix as $NumRole => $Role):
					echo '<option value="'.$NumRole.'">'.$Role.'</option>';
					endforeach;
				?>
			</select>
			</div>
			<div class="categorie">
				<p id="attention" style="display: none;">
					If you register as a manager or administrator, a request will be sent to the site administrators who will have to validate it before you can connect. Therefore, only make this request if you have previously justified it to one of the administrators.
				</p>
			</div>
			<div class="inf">
				<p>
					<label for="Nom">Last-name*</label>
				</p>
				<input type="text" id="nom" name="nom" oninput="verifNom()" onblur="verifNom()" placeholder="exemple: Dupont" value="<?php if(isset($_COOKIE['nom'])){ echo $_COOKIE['nom'];}?>" required>
				<a id="msg_erreur_nom" class="msg_erreur" style="display: none;"></a>
				<p>
					<label for="Nom">First-name*</label>
				</p>
				<input type="text" id="prenom" name="prenom" oninput="verifPrenom()" onblur="verifPrenom()" placeholder="exemple: Martin" value="<?php if(isset($_COOKIE['prenom'])){ echo $_COOKIE['prenom'];}?>" required>
				<a id="msg_erreur_prenom" class="msg_erreur" style="display: none;"></a>
				<p>
					<label for="Nom">Test center*</label>
				</p>
				<input type="radio" name="centre" value="0" required><a class="lien_map">None</a><br>
				<?php 
					while ($id_max_centre > 0) {
						$requeteCentre = $db -> prepare('SELECT idCentre, ville, code, map FROM centre where idCentre="'.$id_max_centre.'"');
						$requeteCentre -> execute();
						$dataCentre = $requeteCentre -> fetch();

						if($dataCentre['ville'] != '') {
						?>
						<input type="radio" name="centre" value='<?= $dataCentre['idCentre'] ?>' required><a class="lien_map" href="<?= $dataCentre['map'] ?>" target="_blank"><?= $dataCentre['ville'] .' (' .$dataCentre['code'] .')'?></a><br>
						<?php
						}
						$id_max_centre = $id_max_centre - 1;
					}
				?>
				<p>
					<label for="Nom">Address (place of residence)</label>
				</p>
				<input type="text" name="adresse" id="adresse" value="<?php if(isset($_COOKIE['adresse'])){ echo $_COOKIE['adresse'];}?>">
				<p>
					<label for="Nom">Username (e-mail)*</label>
				</p>
				<input type="email" name="mail" id="mail" placeholder="exemple: martin.dupont@exemple.fr" required>
				<p>
					<label for="mdp">Password*</label>
				</p>
				<input type="password" name="mdp" id="mdp" required>
				<p>
					<label for="cmdp">Confirm password*</label>
				</p>
				<input type="password" name="cmdp" id="cmdp" required oninput="verifPassword()">
				<a id="msg_erreur_mdp" class="msg_erreur" style="display: none;"></a>
				<p>
					<label for="naissance">Date of birth*</label>
				</p>
				<?php $date = date("Y-m-d"); ?>
				<input type="date" max="<?= $date; ?>" name="date" required>
				<p>
					<label for="">Phone</label>
				</p>
				<input type="tel" id="tel" name="tel" value="<?php if(isset($_COOKIE['tel'])){ echo $_COOKIE['tel'];}?>" oninput="verifTel()" onblur="verifTel()">
				<a id="msg_erreur_tel" class="msg_erreur" style="display: none;"></a>
			</div>
			<div class="sec">
				<p>
					<input type="checkbox" id="idactive" name="active" onclick="disableInput('inscription', this.checked);">
					<label for="condition"> Accept the</label>
					<a href="en_DiagHealth_CGU_Mentions_Legales.php">terms of service</a>*
				</p>
				<p>
					<input type="checkbox" value="None" />
					<label for="condition"> I would like to receive the newsletter</label>
				</p>
			</div>

			<div class="bo">
				* these fields are required
				<p>
					<input type="submit" id="inscription" class="inscription" name="inscription" value="Sign up" disabled>
				</p>
			</div>
			<p style="color:red"><?php echo $message .'<br/>'?></p>
			<?php
				echo $retour;
			?>
			<a href="en_DiagHealth_accueil.php">home</a>
		</form>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    	<script src="../Public/JS/app.js"></script>
	</body>
</html> 