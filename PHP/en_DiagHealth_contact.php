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
						<h2 class="popup_titre">E-mail sent</h2>
						<p class="popup_msg">Your email has been sent to the site address.</p>
						<div align="center">
							<a class="popup_lien" href="en_DiagHealth_contact.php">Return</a>
							<a class="popup_lien" href="en_DiagHealth_accueil.php">Home</a>
						</div>
					</div>
				</div>
				<?php
		} else {
			$msg = "Your email address is not valid";
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
    <?php include 'en_DiagHealth_header.php'; ?>
    <body>
        <div class="container">
        	<h1 align="center">DiagHealth contact form</h1>
            <form method="post">
                <label class="label" for="mail">Email address :</label>
                <input class="input" type="email" id="mail" name="mail" placeholder="Your email address" required>
                <br>
                <label class="label" for="fname">First name :</label>
                <input class="input" type="text" id="fname" name="prenom" placeholder="Your first name" required>
                <br>
                <label class="label" for="lname">Last name :</label>
                <input class="input" type="text" id="lname" name="nom" placeholder="Your last name" required>
                <br>
                <label class="label" for="country">Status :</label>
                    <select id="country" name="statut" required>
                      <option value="">Fill in your status</option>
						<?php
							foreach($SelectChoix as $NumSelect => $Select):
							echo '<option value="'.$NumSelect.'">'.$Select.'</option>';
							endforeach;
						?>
                    </select>
                    <br>
                <label class="label" for="titre">Title :</label>
                <input class="input" type="text" id="titre" name="titre" placeholder="Email's title" required>
                <br>
                <label class="label" for="subject">Topic :</label>
                <textarea id="subject" name="subject" placeholder="Explain your problem to us." style="height:200px" required></textarea>
                <br>
                <div align="right" class="div">
                	<input type="submit" id="submit" name="envoi" value="Send">
                </div>
                <br>
                <?= $msg; ?>
          </form>
        </div>
    </body>
    <?php include 'en_DiagHealth_footer.php'; ?>
</html>



