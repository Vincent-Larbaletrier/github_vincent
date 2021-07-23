<!-- Page d'Acceuil -->

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

	$_SESSION['page'] = "accueil";

	$recherchePrenom = $db -> prepare('SELECT prenom FROM users where idUtilisateur=:idUtilisateur');
	$recherchePrenom -> execute(array('idUtilisateur' => $_SESSION['idUtilisateur']));
	$resultatPrenom = $recherchePrenom -> fetch();

	$_SESSION['prenom'] = $resultatPrenom['prenom'];

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Page d'accueil</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_accueil.css">
	</head>
	<?php include 'DiagHealth_header_accueil.php'; ?>
	<body>
		<h1>Bienvenue<?php if(isset($_SESSION['role'])) {
			echo ' ' .$_SESSION['prenom'];
		}
		else {
			echo '';
		}?>.</h1>
		<!-- Création du block de gauche -->
		<div class="leftbox">
			<!-- Création du diaporama -->
			<section id="slideshow">
				<div class="container">
					<div class="slide">
						<figure>
							<img src="../Public/Images/Accueil2.jpg" class="image" alt=""/>
							<figcaption>Destiné aux conducteurs d'engins de chantier</figcaption>
						</figure>
						<figure>
							<img src="../Public/Images/test_cardiac.jpg" class="image" alt=""/>
							<figcaption>Une kyrielle de tests</figcaption>
						</figure>
						<figure>
							<img src="../Public/Images/suivi_resultats.jpg" class="image" alt=""/>
							<figcaption>Un suivi des résultats</figcaption>

						</figure>
						<figure>
							<img src="../Public/Images/equipe.png" class="image" alt=""/>
							<figcaption>Une équipe à votre écoute</figcaption>
						</figure>
					</div>
				</div>
				<!-- Timeline du diaporama -->
				<span id="timeline"></span>
			</section>
		</div>
		<!-- Création du block de droite -->
		<div class="rightbox">
			<!-- Création du block de texte -->
			<div class="highbox">
				<div class="description" align="justify">
					<p class="description">
						<h3>Bienvenue sur notre site <i class="far fa-smile-beam"></i></h3>
						<p style="text-indent: 20px">
							DiagHealth est une start-up proposant des tests psychotechniques s'adressant aux conducteurs d'engins de chantier. Ces tests permettent d'évaluer les aptitudes psychotechniques de ces conducteurs en se basant sur plusieurs thèmes :
						</p>
						<ul>
							<li>Troubles auditifs</li>
							<li>Niveau de stress</li>
							<li>Réflexes</li>
						</ul>
						<p>
							Ce site permet alors aux utilisateurs de consulter leurs résultats.
						</p> 
						<p>
							Si vous avez des questions, n'hésitez-pas à consulter notre FAQ ou poser directement votre question dans notre forum ou encore contacter notre équipe ; nous sommes à votre écoute.
						</p>
					</p>
				</div>
			</div>
			<!-- Création du block contenant le bouton -->
			<div class="downbox">
				<div class="bouton">
					<p>
	   					<a href="DiagHealth_accueil_plus.php">en savoir plus</a>
	 				</p>
				</div>
			</div>	
		</div>
		<?php include 'DiagHealth_footer.php'; ?>	
	</body>
</html>