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

	$_SESSION['page'] = "contact";

	$SelectChoix = array('Utilisateur','Gestionnaire', 'Non inscrit');

	$msg = "";

	if(isset($_POST['envoi'])) {
		$Mail = $_POST['mail'];
		$Nom = $_POST['nom'];
		$Prenom = $_POST['prenom'];
		$Statut = $SelectChoix[$_POST['statut']];
		$Titre = $_POST['titre'];
		$Sujet = $_POST['subject'];

		if(filter_var($Mail, FILTER_VALIDATE_EMAIL)) {
			$header="MIME-Version: 1.0\r\n";
			$header.='From:"' .$Mail .'"<support@DiagHealth.com>'."\n";
			$header.='Content-Type:text/html; charset="uft-8"'."\n";
			$header.='Content-Transfer-Encoding: 8bit';
			$message='
				<html>
					<body>
						<div align="center">
							<h2>Informations de l\'émetteur : ' .$Prenom .' ' .$Nom .' (' .$Statut .')</h2>
							<p style="font-size: 20px">'.$Sujet.'</p>
						</div>
					</body>
				</html>
				';
				mail("appg7bmail@gmail.com", $Titre, $message, $header);

				?>
				<div id="overlay">
					<div id="popup">
						<h2 class="popup_titre">Mail envoyé</h2>
						<p class="popup_msg">Votre mail à bien été envoyé à l'adresse du site.</p>
						<div align="center">
							<a class="popup_lien" href="DiagHealth_contact.php">Retour</a>
							<a class="popup_lien" href="DiagHealth_accueil.php">Accueil</a>
						</div>
					</div>
				</div>
				<?php
		} else {
			$msg = "Votre adresse mail n'est pas valide";
		}
	}

?>

<!DOCTYPE html>
<html>
    <head>
        <!-- En-tête de la page -->
        <meta charset="utf-8">
        <title>Contact</title>
        <link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
        <link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_contact.css">
    </head>
    <?php include 'DiagHealth_header.php'; ?>
    <body>
        <div class="container">
        	<h1 align="center">Formulaire de contact DiagHealth</h1>
            <form method="post">
                <label class="label" for="mail">Adresse mail :</label>
                <input class="input" type="email" id="mail" name="mail" placeholder="Votre adresse mail" required>
                <br>
                <label class="label" for="fname">Prénom :</label>
                <input class="input" type="text" id="fname" name="prenom" placeholder="Votre prénom" required>
                <br>
                <label class="label" for="lname">Nom :</label>
                <input class="input" type="text" id="lname" name="nom" placeholder="Votre nom" required>
                <br>
                <label class="label" for="country">Statut :</label>
                    <select id="country" name="statut" required>
                      <option value="">Renseignez votre statut</option>
						<?php
							foreach($SelectChoix as $NumSelect => $Select):
							echo '<option value="'.$NumSelect.'">'.$Select.'</option>';
							endforeach;
						?>
                    </select>
                    <br>
                <label class="label" for="titre">Titre :</label>
                <input class="input" type="text" id="titre" name="titre" placeholder="Titre du mail" required>
                <br>
                <label class="label" for="subject">Sujet :</label>
                <textarea id="subject" name="subject" placeholder="Expliquez nous votre problème.." style="height:200px" required></textarea>
                <br>
                <div align="right" class="div">
                	<input type="submit" id="submit" name="envoi" value="Envoyer">
                </div>
                <br>
                <?= $msg; ?>
          </form>
        </div>
    </body>
    <?php include 'DiagHealth_footer.php'; ?>
</html>



