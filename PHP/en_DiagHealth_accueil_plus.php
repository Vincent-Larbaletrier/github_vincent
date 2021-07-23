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
		<title>Main page (plus version)</title>
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_accueil_plus.css">
	</head>
	<?php include 'en_DiagHealth_header.php'; ?>
	<body>
		<div class="accueil">
			<!-- Block de titre -->
			<div class="presentation_titre">
				<h1>About us</h1>
			</div>
			<!-- Block de contenu -->
			<div class="presentation_texte">
				<p style="text-indent: 20px" align="justify">
					We are a six students team who thought about this project.
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
					We created DiagHealth to enable building site machinery drivers to test their abilities with different psychotechnic tests. This permits the employer to check every employee's abilities, which guarantees that he has the required capabilities to work. Every employee can also check his health abilities through this website.
				</p>
			</div>
			<!-- Block de titre -->
			<div class="services_titre">
				<h1>Our services</h1>
			</div>
			<!-- Block de contenu -->
			<div class="services_texte" align="center">
				<p style="text-indent: 20px" align="justify">
					We offer you many tests divided into three categories. Each category is detailed just below.
				</p>
				<!-- Panneaux -->
				<div class="panel" align="center">
					<h4>Hearing problems</h4>
					<img class="img_troubles_auditifs" src="../Public/Images/troubles_auditifs.svg">
					<p style="text-indent: 20px" align="justify">
						These are problems that affect the way the brain processes with information the individual heard, but also problems due to a lack of hearing abilities. To detect theses problems, DiagHealth offers you two tests. <br>
						A first one, called "tone recognition", permits an individual to reproduce a sound (or note), with a more or less accurate frequency. <br> <br>
						The second test permits the determination of the hearing level of an individual, which means the minimum and maximum values that the individual can hear.  
					</p>
				</div>
				<div class="panel">
					<h4>Stress level</h4>
					<img class="img_niveau_de_stress" src="../Public/Images/niveau_de_stress.svg">
					<p style="text-indent: 20px" align="justify">
					
					In humans, stress is a reaction of the body generally due to physical or nervous shock. The origins of a feeling of stress are multiple and differ from one situation to another. Additionally, stress can manifest itself in a number of ways that vary from individual to individual. 
					However, some signs are more common and DiagHealth offers services to detect them. Indeed, we offer a first heart rate measurement test. Under stress, the heart rate tends to vary; DiagHealth then proposes to measure the heart rate of an individual to monitor his state of health.
					A second test is also offered by our start-up, it is the measurement of body temperature. We are able to measure the temperature of the skin to detect and prevent abnormalities. 
					</p>
				</div>
				<div class="panel">
					<h4>Reflexes</h4>
					<img class="img_reflexes" src="../Public/Images/reflexes2.png">
					<p style="text-indent: 20px" align="justify">
						A reflexe is a sudden muscular raction to a certain stimulation. DiagHealth can evaluate two types of reflexes : the time an individual needs to react to a light pulse, and the time he needs to react to a sound. <br> <br>
						These tests permit individuals to estimate their reaction time to an unexpected visual or sound stimulation. The results are then analysed to establish a diagnosis about the individual's health abilities. 
					</p>
				</div>
			</div>
		</div>
		<?php include 'en_DiagHealth_footer.php'; ?>		
	</body>
</html>