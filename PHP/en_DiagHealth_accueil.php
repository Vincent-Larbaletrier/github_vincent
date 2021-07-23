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
		<title>Home page</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_accueil.css">
	</head>
	<?php include 'en_DiagHealth_header_accueil.php'; ?>
	<body>
		<h1>Welcome<?php if(isset($_SESSION['role'])) {
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
							<figcaption>Intended for operators of construction machinery</figcaption>
						</figure>
						<figure>
							<img src="../Public/Images/test_cardiac.jpg" class="image" alt=""/>
							<figcaption>A host of tests</figcaption>
						</figure>
						<figure>
							<img src="../Public/Images/suivi_resultats.jpg" class="image" alt=""/>
							<figcaption>Monitoring of results</figcaption>

						</figure>
						<figure>
							<img src="../Public/Images/equipe.png" class="image" alt=""/>
							<figcaption>A team at your service</figcaption>
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
						<h3>Welcome to our website <i class="far fa-smile-beam"></i></h3>
						<p style="text-indent: 20px">
							DiagHealth is a start-up offering psychotechnical tests aimed at operators of construction machinery. These tests make it possible to assess the psychotechnical aptitudes of these drivers based on several themes:
						</p>
						<ul>
							<li>Hearing troubles</li>
							<li>Stress level</li>
							<li>Reflexes</li>
						</ul>
						<p>
							This site allows users to consult their own results.
						</p> 
						<p>
							If you have any questions, do not hesitate to consult our FAQ or ask your question directly in our forum or contact our team. We are listening to you.
						</p>
					</p>
				</div>
			</div>
			<!-- Création du block contenant le bouton -->
			<div class="downbox">
				<div class="bouton">
					<p>
	   					<a href="en_DiagHealth_accueil_plus.php">find out more</a>
	 				</p>
				</div>
			</div>	
		</div>
		<?php include 'en_DiagHealth_footer.php'; ?>	
	</body>
</html>