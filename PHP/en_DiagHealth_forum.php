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
	// requete vers la BDD
	$idforum_req = $db -> prepare('SELECT idForum FROM forum_sujets ORDER BY dateCreation DESC');
	$idforum_req -> execute();
	$idforum_fetch = $idforum_req -> fetch();
	$idforum_fetchAll = $idforum_req -> fetchAll();
	// ID max de la table forum
	$numero_sujet = $idforum_fetch['idForum'];
	// Nb d'éléments dans la table forum
	$nb_sujet = count($idforum_fetchAll) + 1;
	// recherche favoris
	$favoris_sujet_req = $db -> prepare('SELECT * FROM favoris where idUtilisateur=:idUtilisateur');
	$favoris_sujet_req -> execute(array('idUtilisateur' => $_SESSION['idUtilisateur']));
	$dataFavorisSujet = $favoris_sujet_req -> fetchAll();

	if (isset ($_POST['creer'])) {

		$idAuteur = $_SESSION['idUtilisateur'];
		$titre = html_entity_decode(addslashes($_POST['titre']));
		$reponse = 0;
		$message = html_entity_decode(addslashes($_POST['message']));
		// création du sujet dans la BDD
		$creation_sujet = "INSERT INTO forum_sujets(titre,nbReponses,idAuteur) VALUES ('$titre','$reponse','$idAuteur')";
		$db -> exec($creation_sujet);
		// recherche l'ID du sujet créé
		$idsujet = $db -> prepare('SELECT idForum FROM forum_sujets ORDER BY dateCreation DESC');
		$idsujet -> execute();
		$id_sujet = $idsujet -> fetch();

		$sujet = $id_sujet['idForum'];
		// création de la première réponse du sujet créé
		$creation_reponse = "INSERT INTO forum_reponses(message,sujet,idAuteur) VALUES ('$message','$sujet','$idAuteur')";
		$db -> exec($creation_reponse);

		header("Refresh:0");
	}

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
	<body>
		<h1>DiagHealth forum</h1>
		<div class="nb_sujets_style">
			<p class="nb_sujets">There is currently <?php echo $nb_sujet ?> 
			<?php if($nb_sujet <= 1) {
				echo ' ' .'sujet';
			} else {
				echo ' ' .'sujets';
			} ?> in the forum.</p>
		</div>
		<div class="nouveau_post">
			<?php 
				if(!isset($_SESSION['role'])) {
            		?><a class="nouveau_post_bouton" href="en_DiagHealth_connexion.php"><i class="fas fa-plus"></i>  Create a new post</a><?php
          		}
              	if(isset($_SESSION['role'])) {
            			?><a class="nouveau_post_bouton" href="#nouveau_sujet" onclick="over('nouveau_sujet')"><i class="fas fa-plus"></i>  Create a new post</a><?php
        		}
        	?>		
		</div>
		<div class="choix">
			<div id="section_forum" onclick="over('sujets'); out('favoris'); colorActive('section_forum'); colorDisabled('section_favoris')">Forum</div>
			<div id="section_favoris" onclick="over('favoris'); out('sujets'); colorActive('section_favoris'); colorDisabled('section_forum')">Favorites</div>
		</div>
		<!-- Affichage forum -->
		<div id="sujets" style="display: block;">	
			<table class="affichage_forum_table">
				<?php
				while ($numero_sujet != 0) {
					// recherche des sujets du forum
					$idsujet = $db -> prepare('SELECT idForum, titre, dateCreation, nbReponses, idAuteur FROM forum_sujets where idForum=:idForum');
					$idsujet -> execute(array('idForum' => $numero_sujet));
					$dataForum = $idsujet -> fetch();
					// recherche des auteurs de chaque sujets
					$idauteur_req = $db -> prepare('SELECT idUtilisateur, nom, prenom FROM users where idUtilisateur=:idUtilisateur');
					$idauteur_req -> execute(array('idUtilisateur' => $dataForum['idAuteur']));
					$auteur = $idauteur_req -> fetch();

					sscanf($dataForum['dateCreation'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);

					if ($dataForum['titre'] != '') {
						?>
						<tr class="affichage_forum_tr">
							<td class="reponses">
								<p class="nb_reponses"><?php echo $dataForum['nbReponses']?></p>
								<?php 
								if ($dataForum['nbReponses'] <= 1) {
									?><p>answer</p><?php
								}
								else {
									?><p>answer</p><?php
								}
							?>	
							</td>
							<td class="titre">
								<div class="titre_style">
									<?php echo '<a style="color:black" href="en_DiagHealth_forum_sujet.php?id_sujet=' , $dataForum['idForum'], '">' , $dataForum['titre'], '</a>'; ?>
								</div>
							</td>
							<td class="complements">
								<p class="auteur">by <?php echo $auteur['prenom'] .' ' .$auteur['nom'] .' at ' .$jour , '/' , $mois , '/' , $annee?>
								<?php
								for ($k = 0; $k <= count($dataFavorisSujet)-1; $k++) {
									// vérifie si sujet est suivi
									if($dataFavorisSujet[$k][1] == $_SESSION['idUtilisateur'] && $dataFavorisSujet[$k][2] == $dataForum['idForum']) {
										?>
										(follow <i class="fas fa-check"></i>)
										<?php
									}
								}
								?>
								</p>
							</td>
						</tr>
						<tr class="espace_affichage"></tr>
						<?php
					}

					$numero_sujet = $numero_sujet - 1;	
				}
			?>
			</table>
		</div>
		<!-- Affichage messages suivis -->
		<div id="favoris" style="display: none;">
			<div class="disconnect">
				<?php 
				if(!isset($_SESSION['role'])) {
				echo "You must log in to access this feature";
				}
				?>	
			</div>
			<table class="affichage_forum_table">
				<?php
				// recherche de l'ID max des favoris
				$idfavoris = $db -> prepare('SELECT idFavoris FROM favoris ORDER BY idFavoris DESC');
				$idfavoris -> execute();
				$idfavoris_fetch = $idfavoris -> fetch();

				$idfavoris_max = $idfavoris_fetch['idFavoris'];

				while($idfavoris_max > 0) {
					// recherche des favoris
					$favoris_req = $db -> prepare('SELECT idUtilisateur, idForum FROM favoris where idFavoris="'.$idfavoris_max.'"');
					$favoris_req -> execute();
					$dataFavoris = $favoris_req -> fetch();

					if($dataFavoris['idUtilisateur'] == $_SESSION['idUtilisateur']) {
						// recherche des sujets suivis du forum
						$idsujet = $db -> prepare('SELECT idForum, titre, dateCreation, nbReponses, idAuteur FROM forum_sujets where idForum=:idForum');
						$idsujet -> execute(array('idForum' => $dataFavoris['idForum']));
						$dataFavorisForum = $idsujet -> fetch();
						// recherche des auteurs de chaque sujets
						$idauteur_req = $db -> prepare('SELECT idUtilisateur, nom, prenom FROM users where idUtilisateur=:idUtilisateur');
						$idauteur_req -> execute(array('idUtilisateur' => $dataFavorisForum['idAuteur']));
						$auteur = $idauteur_req -> fetch();

						sscanf($dataFavorisForum['dateCreation'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);

						if ($dataFavorisForum['titre'] != '') {
							?>
							<tr class="affichage_forum_tr">
								<td class="reponses">
									<p class="nb_reponses"><?php echo $dataFavorisForum['nbReponses']?></p>
									<?php 
									if ($dataFavorisForum['nbReponses'] <= 1) {
										?><p>answer</p><?php
									}
									else {
										?><p>answer</p><?php
									}
								?>	
								</td>
								<td class="titre">
									<div class="titre_style">
										<?php echo '<a style="color:black" href="en_DiagHealth_forum_sujet.php?id_sujet=' , $dataFavorisForum['idForum'], '">' , $dataFavorisForum['titre'], '</a>'; ?>
									</div>
								</td>
								<td class="complements">
									<p class="auteur">by <?php echo $auteur['prenom'] .' ' .$auteur['nom'] .' at ' .$jour , '/' , $mois , '/' , $annee?>
									<?php
									for ($k = 0; $k <= count($dataFavorisSujet)-1; $k++) {
										// vérifie si sujet est suivi
										if($dataFavorisSujet[$k][1] == $_SESSION['idUtilisateur'] && $dataFavorisSujet[$k][2] == $dataFavorisForum['idForum']) {
											?>
											(follow <i class="fas fa-check"></i>)
											<?php
										}
									}
									?>
									</p>
								</td>
							</tr>
							<tr class="espace_affichage"></tr>
							<?php
						}
					}

					$idfavoris_max = $idfavoris_max - 1;
				}
			?>
			</table>
		</div>
		<?php
    	if(isset($_SESSION['role'])) {
    	?>
	    	<!-- Affichage création nouveau sujet -->
	    	<div id="nouveau_sujet" style="display: none;">
				<h2>Creation of a new post</h2>
				<form method="post">
					<table class="nouveau_sujet_table">
						<tr class="nouveau_sujet_tr">
							<td class="nouveau_sujet_titre">
								Topic title :
							</td>
							<td class="nouveau_sujet_contenu">
								<input type="text" name="titre" class="insert_titre" placeholder=" Insert the topic title" required>
							</td>
						</tr>
						<tr class="espace_nouveau"></tr>
						<tr class="nouveau_sujet_tr">
							<td class="nouveau_sujet_titre">
								Main message of the subject :
							</td>
							<td class="nouveau_sujet_contenu">
								<textarea name="message" class="insert_msg" placeholder=" Insert your message" required></textarea>
							</td>
						</tr>
						<tr class="nouveau_sujet_tr">
							<td><td align="right">
								<input type="submit" class="post" name="creer" value="Create a new topic">
							</td>
						</tr>
					</table>
				</form>
				<a class="retour" href="DiagHealth_forum.php">Cancel</a>
			</div>
			<?php 
		} 
		?>
		<a id="top" href="#section_forum"><img class="top" src="../Public/Images/top.jpg"></a>
	</body>
	<?php include 'en_DiagHealth_footer.php'; ?>
</html>