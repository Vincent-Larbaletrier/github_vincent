<?php

	session_start();

	$ma_fonction = function(int $value, string $message, string $file, int $line) {
				switch ($value) {
					case E_USER_ERROR:
						echo 'Erreur de type : ' .$value .' à la ligne ' .$line .'<br/>';
						break;
					case E_USER_WARNING:
						echo $message .' dans le fichier ' .$file .'<br/>';
						break;
					case E_USER_NOTICE:
						echo 'Erreur E_USER_NOTICE <br/>';
						break;
					case E_NOTICE:
						echo '';
						break;
					
					default:
						echo 'Valeur erreur par defaut : ' .$value .'<br/>';
						echo 'Le problème est : ' .$message .'<br/>';
						break;
				}
	};

	// définir ma fonction comme gestionnaire d'erreur
	set_error_handler($ma_fonction);
	
if($_SESSION['connexion'] == '1' && $_SESSION['role'] == 'Utilisateur') {
	include 'DiagHealth_database.php';
	global $db;

	$_SESSION['page'] = "donnees";

	$recherchePrenom = $db -> prepare('SELECT prenom FROM users where idUtilisateur=:idUtilisateur');
	$recherchePrenom -> execute(array('idUtilisateur' => $_SESSION['idUtilisateur']));
	$resultatPrenom = $recherchePrenom -> fetch();

	$_SESSION['prenom'] = $resultatPrenom['prenom'];

	$rechercheNom = $db -> prepare('SELECT nom FROM users where idUtilisateur=:idUtilisateur');
	$rechercheNom -> execute(array('idUtilisateur' => $_SESSION['idUtilisateur']));
	$resultatNom = $rechercheNom -> fetch();

	$_SESSION['nom'] = $resultatNom['nom'];

	/*

	$reconnaissance_de_tonalite = '';
	$seuil_min = '';
	$seuil_max = '';
	$temperature = '';
	$freq = '';
	$tps_lumiere = '';
	$tps_son = '';
	
	*/
	include 'valeurs_donnees.php';

?>

<!DOCTYPE html>
<html>
<head>
	<title>Mes Données</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
	<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_donnees.css">
</head>
<?php include 'DiagHealth_header.php'; ?>
<body>
	<h1>Mes Données</h1>
	<h2>Bienvenue <?php echo $_SESSION['prenom'] ?>.</h2>
	<h3>Voici les résultats de vos tests :</h3>
	<div class="exp" align="right">
		<a href="exportation_pdf.php" class="exportation"><img src="../Public/Images/PDF.svg" class="img_pdf" width="45px"><p class="export">Exporter les résultats au format pdf</p></a>
	</div>
	<div class="troubles_auditifs">
		<a href="#TA" class="theme">Troubles auditifs</a>
		<div class="espacement"></div>
		<table>
			<tbody>
				<tr align="center">
					<td class="c1">Reconnaissance de tonalité</td>
					<td class="c2"><?php
						if (1 <= $reconnaissance_de_tonalite) {
							echo $reconnaissance_de_tonalite .' Hz';
						}
						else {
							echo '--- Hz';
						}?>
					</td>
					<td class="c3">
						<?php
							if (100 < $reconnaissance_de_tonalite) {
								?><div class="mauvais"></div>
								<p class="commentaire">Mauvais</p>
								<?php
								$score1 = 25;
							}
							elseif (100 >= $reconnaissance_de_tonalite and $reconnaissance_de_tonalite > 50) {
								?><div class="moyen"></div>
								<p class="commentaire">Moyen</p>
								<?php
								$score1 = 50;
							}
							elseif (50 >= $reconnaissance_de_tonalite and $reconnaissance_de_tonalite > 20) {
								?><div class="bon"></div>
								<p class="commentaire">Bon</p>
								<?php
								$score1 = 75;
							}
							elseif (20 >= $reconnaissance_de_tonalite and $reconnaissance_de_tonalite >= 1) {
								?><div class="excellent"></div>
								<p class="commentaire">Excellent</p>
								<?php
								$score1 = 100;
							}
							else {
								$score1 = '';
							}
						?>	
					</td>
				</tr>
			</tbody>
		</table>
		<div class="espacement"></div>
		<table>
			<tbody>
				<tr align="center">
					<td class="c1">Seuil de perception minimum</td>
					<td class="c2"><?php
						if (1 <= $seuil_min) {
							echo $seuil_min .' Hz';
						}
						else {
							echo '--- Hz';
						}?>
					</td>
					<td class="c3">
						<?php
							if (80 < $seuil_min) {
								?><div class="mauvais"></div>
								<p class="commentaire">Mauvais</p>
								<?php
								$score2 = 25;
							}
							elseif (80 >= $seuil_min and $seuil_min > 60) {
								?><div class="moyen"></div>
								<p class="commentaire">Moyen</p>
								<?php
								$score2 = 50;
							}
							elseif (60 >= $seuil_min and $seuil_min > 40) {
								?><div class="bon"></div>
								<p class="commentaire">Bon</p>
								<?php
								$score2 = 75;
							}
							elseif (40 >= $seuil_min and $seuil_min >= 1) {
								?><div class="excellent"></div>
								<p class="commentaire">Excellent</p>
								<?php
								$score2 = 100;
							}
							else {
								$score2 = '';
							}
						?>	
					</td>
				</tr>
			</tbody>
		</table>
		<div class="espacement"></div>
		<table>
			<tbody>
				<tr align="center">
					<td class="c1">Seuil de perception maximum</td>
					<td class="c2"><?php
						if (1 <= $seuil_max) {
							echo $seuil_max .' Hz';
						}
						else {
							echo '--- Hz';
						}?>
					</td>
					<td class="c3">
						<?php
							if (1 <= $seuil_max and $seuil_max < 14000) {
								?><div class="mauvais"></div>
								<p class="commentaire">Mauvais</p>
								<?php
								$score3 = 25;
							}
							elseif (14000 <= $seuil_max and $seuil_max < 16000) {
								?><div class="moyen"></div>
								<p class="commentaire">Moyen</p>
								<?php
								$score3 = 50;
							}
							elseif (16000 <= $seuil_max and $seuil_max < 18000) {
								?><div class="bon"></div>
								<p class="commentaire">Bon</p>
								<?php
								$score3 = 75;
							}
							elseif (18000 <= $seuil_max) {
								?><div class="excellent"></div>
								<p class="commentaire">Excellent</p>
								<?php
								$score3 = 100;
							}
							else {
								$score3 = '';
							}
						?>	
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="niveau_de_stress">
		<a href="#NS" class="theme">Niveau de stress</a>
		<div class="espacement"></div>
		<table>
			<tbody>
				<tr align="center">
					<td class="c1">Température corporelle</td>
					<td class="c2"><?php
						if (1 <= $temperature) {
							echo $temperature .' °C';
						}
						else {
							echo '--- °C';
						}?>
					</td>
					<td class="c3">
						<?php
							if (40 <= $temperature || $temperature < 30 and $temperature >= 1) {
								?><div class="mauvais"></div>
								<p class="commentaire">Mauvais</p>
								<?php
								$score4 = 25;
							}
							elseif (30 <= $temperature and $temperature < 33 || 39 == $temperature) {
								?><div class="moyen"></div>
								<p class="commentaire">Moyen</p>
								<?php
								$score4 = 50;
							}
							elseif (33 <= $temperature and $temperature <= 35 || 38 == $temperature) {
								?><div class="bon"></div>
								<p class="commentaire">Bon</p>
								<?php
								$score4 = 75;
							}
							elseif (36 == $temperature || 37 == $temperature) {
								?><div class="excellent"></div>
								<p class="commentaire">Excellent</p>
								<?php
								$score4 = 100;
							}
							else {
								$score4 = '';
							}
						?>	
					</td>
				</tr>
			</tbody>
		</table>
		<div class="espacement"></div>
		<table>
			<tbody>
				<tr align="center">
					<td class="c1">Fréquence cardiaque</td>
					<td class="c2"><?php
						if (1 <= $freq) {
							echo $freq .' bpm';
						}
						else {
							echo '--- bpm';
						}?>
					</td>
					<td class="c3">
						<?php
							if ($freq > 74) {
								?><div class="mauvais"></div>
								<p class="commentaire">Mauvais</p>
								<?php
								$score5 = 25;
							}
							elseif (74 >= $freq and $freq > 68) {
								?><div class="moyen"></div>
								<p class="commentaire">Moyen</p>
								<?php
								$score5 = 50;
							}
							elseif (68 >= $freq and $freq > 62) {
								?><div class="bon"></div>
								<p class="commentaire">Bon</p>
								<?php
								$score5 = 75;
							}
							elseif (62 >= $freq and $freq >= 1) {
								?><div class="excellent"></div>
								<p class="commentaire">Excellent</p>
								<?php
								$score5 = 100;
							}
							else {
								$score5 = '';
							}
						?>	
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="reflexes">
		<a href="#R" class="theme">Réflexes</a>
		<div class="espacement"></div>
		<table>
			<tbody>
				<tr align="center">
					<td class="c1">Temps de réaction à un signal lumineux</td>
					<td class="c2"><?php
						if (1 <= $tps_lumiere) {
							echo $tps_lumiere .' ms';
						}
						else {
							echo '--- ms';
						}?>
					</td>
					<td class="c3">
						<?php
							if ($tps_lumiere > 240) {
								?><div class="mauvais"></div>
								<p class="commentaire">Mauvais</p>
								<?php
								$score6 = 25;
							}
							elseif (240 >= $tps_lumiere and $tps_lumiere > 210) {
								?><div class="moyen"></div>
								<p class="commentaire">Moyen</p>
								<?php
								$score6 = 50;
							}
							elseif (210 >= $tps_lumiere and $tps_lumiere > 180) {
								?><div class="bon"></div>
								<p class="commentaire">Bon</p>
								<?php
								$score6 = 75;
							}
							elseif (180 >= $tps_lumiere and $tps_lumiere >= 1) {
								?><div class="excellent"></div>
								<p class="commentaire">Excellent</p>
								<?php
								$score6 = 100;
							}
							else {
								$score6 = '';
							}
						?>	
					</td>
				</tr>
			</tbody>
		</table>
		<div class="espacement"></div>
		<table>
			<tbody>
				<tr align="center">
					<td class="c1">Temps de réaction à un signal sonore</td>
					<td class="c2"><?php
						if (1 <= $tps_son) {
							echo $tps_son .' ms';
						}
						else {
							echo '--- ms';
						}?>
					</td>
					<td class="c3">
						<?php
							if ($tps_son > 220) {
								?><div class="mauvais"></div>
								<p class="commentaire">Mauvais</p>
								<?php
								$score7 = 25;
							}
							elseif (220 >= $tps_son and $tps_son > 180) {
								?><div class="moyen"></div>
								<p class="commentaire">Moyen</p>
								<?php
								$score7 = 50;
							}
							elseif (180 >= $tps_son and $tps_son > 140) {
								?><div class="bon"></div>
								<p class="commentaire">Bon</p>
								<?php
								$score7 = 75;
							}
							elseif (140 >= $tps_son and $tps_son >= 1) {
								?><div class="excellent"></div>
								<p class="commentaire">Excellent</p>
								<?php
								$score7 = 100;
							}
							else {
								$score7 = '';
							}
						?>	
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="espacement2"></div>
	<div class="total" align="left">
		<?php echo $_SESSION['prenom'] .' ' .$_SESSION['nom'] ?>
		<h4>Votre score</h4>
		<div align="center">
			<?php
			if ($score1 != '' and $score2 != '' and $score3 != '' and $score4 != '' and $score5 != '' and $score6 != '' and $score7 != '') {
				$score = $score1 + $score2 + $score3 + $score4 + $score5 + $score6 + $score7;
			}
			else {
				$score = '---';
			}
			if (1<= $score and $score < 150) {
				?><div class="score_mauvais"></div>
				<p class="commentaire_general">Mauvais</p>
				<p class="score"><?php echo $score; ?> / 700</p>
				<?php
			}
			elseif (150 <= $score and $score < 300) {
				?><div class="score_moyen"></div>
				<p class="commentaire_general">Moyen</p>
				<p class="score"><?php echo $score; ?> / 700</p>
				<?php
			}
			elseif (300 <= $score and $score < 500) {
				?><div class="score_bon"></div>
				<p class="commentaire_general">Bon</p>
				<p class="score"><?php echo $score; ?> / 700</p>
				<?php
			}
			elseif (500 <= $score) {
				?><div class="score_excellent"></div>
				<p class="commentaire_general">Excellent</p>
				<p class="score"><?php echo $score; ?> / 700</p>
				<?php
			}
			else {
				?>
				<p class="score"><?php echo $score; ?> / 700</p>
				<?php
			}
			?>
		</div>
	</div>
	<a href="#" id="TA" class="th">
		<h5>Troubles auditifs</h5>
		<img class="img_troubles_auditifs" src="../Public/Images/troubles_auditifs.svg">
		<p class="details">Appuyez pour plus de détails</p>
	</a>
	<a href="#" id="NS" class="th">
		<h5>Niveau de stress</h5>
		<img class="img_niveau_de_stress" src="../Public/Images/niveau_de_stress.svg">
		<p class="details">Appuyez pour plus de détails</p>
	</a>
	<a href="#" id="R" class="th">
		<h5>Réflexes</h5>
		<img class="img_reflexes" src="../Public/Images/reflexes2.png">
		<p class="details">Appuyez pour plus de détails</p>
	</a>

</body>
<?php include 'DiagHealth_footer.php'; ?>	
</html>
<?php 
}
else {
	header('Location: DiagHealth_connexion.php');
}
?>