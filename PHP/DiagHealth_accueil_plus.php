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

  $_SESSION['page'] = "accueil_plus";

 ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Page d'accueil plus</title>
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_accueil_plus.css">
	</head>
	<?php include 'DiagHealth_header.php'; ?>
	<body>
		<div class="accueil">
			<!-- Block de titre -->
			<div class="presentation_titre">
				<h1>Qui sommes-nous ?</h1>
			</div>
			<!-- Block de contenu -->
			<div class="presentation_texte">
				<p style="text-indent: 20px" align="justify">
					Nous sommes une équipe composée de six étudiants à l'origine de ce projet.
				</p>
				<!-- Photos équipe -->
				<div class="photo_equipe" align="center">
					<img class="photo" src="../Public/Images/yassir.png">
					<h5>Yassir EL-KOBI</h5>
				</div>
				<div class="photo_equipe" align="center">
					<img class="photo" src="../Public/Images/anaelle.jpg">
					<h5>Anaëlle JASMIN</h5>
				</div>
				<div class="photo_equipe" align="center">
					<img class="photo" src="../Public/Images/vincent.jpg">
					<h5>Vincent LARBALETRIER</h5>
				</div>
				<div class="photo_equipe" align="center">
					<img class="photo" src="../Public/Images/pacome.jpg">
					<h5>Pacôme LE BRIS</h5>
				</div>
				<div class="photo_equipe" align="center">
					<img class="photo" src="../Public/Images/baptiste.jpg">
					<h5>Baptiste MARCHAND</h5>
				</div>
				<div class="photo_equipe" align="center">
					<img class="photo" src="../Public/Images/lena.jpg">
					<h5>Léna PETRUCCIOLI</h5>
				</div>
				<p style="text-indent: 20px" align="justify">
					Nous avons créé DiagHealth pour permettre à des conducteurs d'engins de chantier de tester leurs capacités sur différents tests psychotechniques. Cela permet un suivi des capacités respectives de chaque employé garantissant une aptitude au travail de ces derniers à l'employeur, ainsi qu'un suivi médical pour les employés. 
				</p>
			</div>
			<!-- Block de titre -->
			<div class="services_titre">
				<h1>Nos services</h1>
			</div>
			<!-- Block de contenu -->
			<div class="services_texte" align="center">
				<p style="text-indent: 20px" align="justify">
					Nous proposons plusieurs services divisés en trois grandes catégories. Les caractéristiques de chaque catégorie sont décrites ci-dessous.
				</p>
				<!-- Panneaux -->
				<div class="panel" align="center">
					<h4>Troubles auditifs</h4>
					<img class="img_troubles_auditifs" src="../Public/Images/troubles_auditifs.svg">
					<p style="text-indent: 20px" align="justify">
						Il s'agit des troubles qui affectent la manière dont le cerveau traite l'information auditive, mais également des cas de pertes d'auditions et de surdité. Pour détecter ces troubles, DiagHealth propose deux tests. <br>
						Un premier appelé "reconnaissance de tonalité", qui permet d'évaluer la capacité d'un individu à reproduire un son (ou une note) à une fréquence plus ou moins précise. <br> <br>
						Le second test quand à lui, permet de déterminer le seuil de perception auditive d'un individu, c'est-à-dire, les valeurs de fréquences maximale et minimale que peut percevoir l'individu.  
					</p>
				</div>
				<div class="panel">
					<h4>Niveau de stress</h4>
					<img class="img_niveau_de_stress" src="../Public/Images/niveau_de_stress.svg">
					<p style="text-indent: 20px" align="justify">
						Chez l'humain, le stress est une réaction de l'organisme généralement due à un choc physique ou nerveux. Les origines d'un sentiment de stress sont multiples et diffèrent d'une situation à l'autre. De plus, le stress peut se manifester de plusieurs manières qui varient d'un individu à l'autre. Néanmoins, certains signes sont plus fréquents et DiagHealth propose des services permettant de les détecter. <br> <br>
						En effet, nous proposons un premier test de mesure de fréquence cardiaque. En situation de stress le rythme cardiaque a tendance à varier ; DiagHealth propose alors de mesurer le rythme cardiaque d'un individu pour suivre son état de santé. <br> <br>
						Un second test est également proposé par notre start-up, il s'agit de la mesure de la température corporelle. Nous sommes à même de mesurer la température de la peau pour détecter et prévenir des anomalies.  
					</p>
				</div>
				<div class="panel">
					<h4>Réflexes</h4>
					<img class="img_reflexes" src="../Public/Images/reflexes2.png">
					<p style="text-indent: 20px" align="justify">
						Un réflexe est une réponse musculaire involontaire à un stimulus. DiagHealth propose d'évaluer deux types de réflexes : le temps de réaction à un signal lumineux et le temps de réaction à un signal sonore. <br> <br>
						Ces tests permettent de mesurer le temps de réaction de chaque individu à un stimulus visuel ou sonore inattendu. Les résultats sont ensuite analysés pour établir un diagnostic sur l'état de santé de l'individu. 
					</p>
				</div>
			</div>
		</div>
		<?php include 'DiagHealth_footer.php'; ?>		
	</body>
</html>