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

    $_SESSION['page'] = "forum";

	include 'DiagHealth_database.php';
	global $db;
	// recherche du nombre de réponse
	$idreponse_req = $db -> prepare('SELECT idReponse FROM forum_reponses');
	$idreponse_req -> execute();
	$idreponse_fetchAll = $idreponse_req -> fetchAll();
	// recherche de l'ID max des réponse
	$idreponse_req2 = $db -> prepare('SELECT idReponse FROM forum_reponses ORDER BY dateCreation DESC');
	$idreponse_req2 -> execute();
	$idreponse_fetch = $idreponse_req2 -> fetch();

	$nb_reponse = count($idreponse_fetchAll);

	$numero_reponse = $idreponse_fetch['idReponse'];
	// recherche du titre du sujet en cours
	$titre_sujet = $db -> prepare('SELECT titre FROM forum_sujets where idForum="'.$_GET['id_sujet'].'"');
	$titre_sujet -> execute();
	$titre = $titre_sujet -> fetch();

	$sujet = $_GET['id_sujet'];
	// recherche favoris
	$favoris_req = $db -> prepare('SELECT * FROM favoris where idUtilisateur=:idUtilisateur');
	$favoris_req -> execute(array('idUtilisateur' => $_SESSION['idUtilisateur']));
	$dataFavoris = $favoris_req -> fetchAll();

	$increment_favoris = 0;
	// parcours de tous les favoris
	for ($k = 0; $k <= count($dataFavoris)-1; $k++) {
		// vérifie si sujet est déjà suivi
		if($dataFavoris[$k][1] == $_SESSION['idUtilisateur'] && $dataFavoris[$k][2] == $_GET['id_sujet']) {
			$idFavoris = $dataFavoris[$k][0]; 
			$increment_favoris = 1;
		}
	}
	// nouvelle réponse
	if (isset ($_POST['nouvelle_reponse_btn'])) {

		$idAuteur = $_SESSION['idUtilisateur'];
		$message = html_entity_decode(addslashes($_POST['message']));
		// création nouvelle réponse
		$reponse_msg = "INSERT INTO forum_reponses(message,sujet,idAuteur) VALUES ('$message','$sujet','$idAuteur')";
		$db -> exec($reponse_msg);
		// recherche nombre de réponse au sujet
		$rep = $db -> prepare('SELECT nbReponses FROM forum_sujets where idForum="'.$_GET['id_sujet'].'"');
		$rep -> execute();
		$nb_rep = $rep -> fetch();

		$new_nb_rep = $nb_rep['nbReponses'] + 1;
		// modifie le nombre de réponse au sujet
		$modif_nb_rep = "UPDATE forum_sujets SET nbReponses='$new_nb_rep' where idForum=".$_GET['id_sujet']."";
		$db -> exec($modif_nb_rep);

		header("Refresh:0");
	}
	// parcours de toutes les réponses du sujet
	for($id_sujet_select = 1; $id_sujet_select < $idreponse_fetch['idReponse'] + 1; ++$id_sujet_select) {
		// suppression d'une réponse
		if(isset($_POST[$id_sujet_select.'_supprimer_reponse'])) {
			// supprime la réponse sélectionnée
			$suppression_reponse = "DELETE FROM forum_reponses where idReponse = '".$id_sujet_select."'";
			$db -> exec($suppression_reponse);
			// recherche nombre de réponse au sujet
			$rep = $db -> prepare('SELECT nbReponses FROM forum_sujets where idForum="'.$_GET['id_sujet'].'"');
			$rep -> execute();
			$nb_rep = $rep -> fetch();

			$new_nb_rep = $nb_rep['nbReponses'] - 1;
			// modifie le nombre de réponse au sujet
			$modif_nb_rep = "UPDATE forum_sujets SET nbReponses='$new_nb_rep' where idForum=".$_GET['id_sujet']."";
			$db -> exec($modif_nb_rep);

			header("Refresh:0");
		}
		// modification d'une réponse
		if(isset($_POST[$id_sujet_select .'_modification'])) {
			
			$nouveau_message = html_entity_decode(addslashes($_POST['modif_reponse_' .$id_sujet_select]));
			$mod = "(modifié)";
			// modifie message
			$modif_reponse = "UPDATE forum_reponses SET message='$nouveau_message', modification='$mod' where idReponse = '".$id_sujet_select."'";
			$db -> exec($modif_reponse);

			header("Refresh:0");
		}
	}
	// retirer des messages suivis
	if(isset($_POST['enlever_favoris'])) {

		if($_SESSION['connexion'] == '1') {
			// supprime des messages suivis
			$enlever_favoris = "DELETE FROM favoris where idFavoris = '".$idFavoris."'";
			$db -> exec($enlever_favoris);

			header("Refresh:0");
		}
		else {
			header('Location: en_DiagHealth_connexion.php');
		}
	}
	// ajoute au messages suivis
	if(isset($_POST['ajouter_favoris'])) {

		if($_SESSION['connexion'] == '1') {
			$idUtilisateur = $_SESSION['idUtilisateur'];
			$idForum = $_GET['id_sujet'];
			// création du suivi du message
			$ajouter_favoris = "INSERT INTO favoris(idUtilisateur,idForum) VALUES ('$idUtilisateur','$idForum')";
			$db -> exec($ajouter_favoris);

			header("Refresh:0");
		}
		else {
			header('Location: en_DiagHealth_connexion.php');
		}	
	}
	// suppression du sujet
	if(isset($_POST['supprimer_sujet'])) {
		// supprime le sujet, les réponses et les messages suivis
		$suppression_sujet = "DELETE FROM forum_sujets where idForum='".$_GET['id_sujet']."'";
		$suppression_reponse = "DELETE FROM forum_reponses where sujet='".$_GET['id_sujet']."'";
		$suppression_favoris = "DELETE FROM favoris where idForum='".$_GET['id_sujet']."'";
		$db -> exec($suppression_sujet);
		$db -> exec($suppression_reponse);
		$db -> exec($suppression_favoris);

		header('Location: en_DiagHealth_forum.php');
		
	}

	// vérifie que le sujet existe
	if (!isset($_GET['id_sujet'])) {
		header('Location: en_DiagHealth_forum.php');
	}
	else {
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Forum</title>
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_forum.css">
	</head>
	<?php include 'en_DiagHealth_header.php'; ?>
	<body id="body">
		<div id="overlay_sujet">
			<form method="post">
				<div id="popup_sujet">
					<h2 class="popup_titre">Subject deletion</h2>
					<p class="popup_msg">Are you sure you want to delete this topic ?</p>
					<div align="center">
						<input type="submit" name="supprimer_sujet" class="popup_lien" value="Delete">
						<a class="popup_lien" onclick="out('overlay_sujet')">Cancel</a>
					</div>
				</div>
			</form>
		</div>
		<h1>Topic : <?php echo $titre['titre']; ?></h1>
		<form method="post">
			<table class="reponse_table">
				<?php
				for ($i = 1; $i <= $numero_reponse; $i++) {
					// recherche des réponses
					$reponse = $db -> prepare('SELECT idReponse, message, dateCreation, sujet, idAuteur, modification FROM forum_reponses where idReponse=:idReponse');
					$reponse -> execute(array('idReponse' => $i));
					$dataReponse = $reponse -> fetch();
					// recherche des auteurs des réponses
					$idauteur_req = $db -> prepare('SELECT idUtilisateur, nom, prenom FROM users where idUtilisateur=:idUtilisateur');
					$idauteur_req -> execute(array('idUtilisateur' => $dataReponse['idAuteur']));
					$auteur = $idauteur_req -> fetch();
					// si la réponse correspond au sujet
					if ($dataReponse['sujet'] == $_GET['id_sujet']) {

						sscanf($dataReponse['dateCreation'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
						?>
						<tr class="reponse_tr">
							<td class="auteur_reponse">
								<?php echo $auteur['prenom'], ' ', $auteur['nom'];
								echo '<br />';
								echo 'From ', $jour , '/' , $mois , '/' , $annee , ' at ' , $heure , ':' , $minute, ' ', $dataReponse['modification']; ?>
							</td>
							<td id="<?=$dataReponse['idReponse']?>_lecture" class="message_lecture">
								<?php echo $dataReponse['message']; ?>	
							</td>
							<?php if($_SESSION['role'] == 'Administrateur' || $auteur['idUtilisateur'] == $_SESSION['idUtilisateur']) {
								?>
								<td id="<?=$dataReponse['idReponse']?>_modifs" class="message_modifs" style="display: none;">
									<textarea class="input_modification" type='text' name='modif_reponse_<?php echo $i; ?>' value='<?php echo $dataReponse['message']?>'><?php echo $dataReponse['message']?></textarea>
								</td>
								<?php
							}
							?>
							<td class="gestion_tr" >
								<?php if($_SESSION['role'] == 'Administrateur' || $auteur['idUtilisateur'] == $_SESSION['idUtilisateur']) {
									?>
									<div class="option" id="<?=$dataReponse['idReponse']?>_lecture_btn">
										<a class="modifier" onclick="over('<?=$dataReponse['idReponse']?>_modifs'); out('<?=$dataReponse['idReponse']?>_lecture'); over('<?=$dataReponse['idReponse']?>_modifs_btn'); out('<?=$dataReponse['idReponse']?>_lecture_btn')"><i class="far fa-edit"></i> Edit</a>
										<a class="supprimer" onclick="over('<?=$dataReponse['idReponse']?>_overlay_reponse')"><i class="fas fa-trash-alt"></i> Delete</a>
									</div>
									<div class="option" style="display: none;" id="<?=$dataReponse['idReponse']?>_modifs_btn">
										<a class="annuler" onclick="out('<?=$dataReponse['idReponse']?>_modifs'); over('<?=$dataReponse['idReponse']?>_lecture'); out('<?=$dataReponse['idReponse']?>_modifs_btn'); over('<?=$dataReponse['idReponse']?>_lecture_btn')"><i class="far fa-minus-square"></i> Cancel modification</a>
										<input type="submit" class="valider_modification" name="<?=$dataReponse['idReponse']?>_modification" value="Edit">
									</div>
								<?php
								}
								?>
							</td>
						</tr>
						<div class="overlay_reponse" id="<?=$dataReponse['idReponse']?>_overlay_reponse">
							<div class="popup_reponse">
								<h2 class="popup_titre">Deleting a response</h2>
								<p class="popup_msg">Are you sure you want to delete this answer ?</p>
								<div align="center">
									<form method="post">
										<input type="submit" name="<?=$dataReponse['idReponse']?>_supprimer_reponse" class="popup_lien" value="Delete">
									</form>
									<a class="popup_lien" onclick="out('<?=$dataReponse['idReponse']?>_overlay_reponse')">Cancel</a>
								</div>
							</div>
						</div>
						<tr class="espace_reponse"></tr>
						<?php
					}
				}
				?>
			</table>
		</form>
		<div class="repondre_style">
			<?php if(isset($_SESSION['role'])) {
				?>
				<a class="repondre" href="#nouvelle_reponse" onclick="over('nouvelle_reponse')">Answer</a>
				<?php
			}
			else {
				?>
				<a class="repondre" href="en_DiagHealth_connexion.php">Answer</a>
				<?php
			}
			?>
		</div>
		<?php
	}
	?>
	<div class="retour_style">
		<a class="retour" href="en_DiagHealth_forum.php">Return</a>
	</div>
	<div id="nouvelle_reponse" style="display: none;">
		<h2>Answer to topic</h2>
		<form method="post">
			<table class="nouvelle_reponse_table">
				<tr class="nouvelle_reponse_tr">
					<td class="titre_rep">
						Answer :
					</td>
					<td>
						<textarea name="message" class="insert_rep" placeholder=" Enter your answer" required></textarea>
					</td>
				</tr>
				<tr>
					<td><td align="right">
						<input type="submit" class="nouvelle_reponse_btn" name="nouvelle_reponse_btn" value="Answer">
					</td>
				</tr>
			</table>
		</form>
		<a class="retour" onclick="out('nouvelle_reponse')">Cancel</a>
	
</div>
		
		<?php if(isset($_SESSION['role'])) {
			if($_SESSION['role'] == 'Administrateur') { 
				?>
				<a class="suppression" onclick="over('overlay_sujet')"><i class="fas fa-trash-alt"></i> Delete items</a>
				<?php
			}
		}
	?>
	<form method="post">
		<?php

			for ($k = 0; $k <= count($dataFavoris)-1; $k++) {

				if($dataFavoris[$k][1] == $_SESSION['idUtilisateur'] && $dataFavoris[$k][2] == $_GET['id_sujet']) {
					?> 
					<div class="fav">
						<input type="submit" id="enlever_favoris" class="suivre" name="enlever_favoris" value="Stop following the topic">
					</div>
					 <?php
				}
			}

			if($increment_favoris == 0) {
				?>
				<div class="fav">
					 <input type="submit" id="ajouter_favoris" class="suivre" name="ajouter_favoris" value="Follow the topic">
				</div>
				 <?php
			}
		?>
	</form>
	</body>
	<?php include 'en_DiagHealth_footer.php'; ?>
</html>