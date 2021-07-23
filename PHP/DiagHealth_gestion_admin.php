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

if($_SESSION['connexion'] != '1' || $_SESSION['role'] != 'Administrateur') {
	header('Location: DiagHealth_connexion.php');
}

else if($_SESSION['connexion'] == '1' && $_SESSION['role'] == 'Administrateur') {

	include 'DiagHealth_database.php';
	global $db;

	$_SESSION['page'] = "gestion_admin";

	$id_attente_req = $db -> prepare('SELECT * FROM inscription_attentes ORDER BY idAttente DESC ');
	$id_attente_req -> execute();
	$id_max_attente = $id_attente_req -> fetch();

	$attente_req = $db -> prepare('SELECT * FROM inscription_attentes');
	$attente_req -> execute();
	$dataAttente = $attente_req -> fetchAll();

	$id_centre_req = $db -> prepare('SELECT idCentre FROM centre ORDER BY dateCreation DESC ');
	$id_centre_req -> execute();
	$id_max_centre = $id_centre_req -> fetch();

	$maxCentre = $id_max_centre['idCentre'];
	$maxCentreBis = $id_max_centre['idCentre'];

	$critereChoisi = array('Numéro de compte','Nom','Prénom','Adresse mail','Centre de tests');

	$message = "";

	for($j = 0; $j < $id_max_attente['idAttente']+1; ++$j) {

		if(isset($_POST[$j .'_rejeter'])) {

			$suppression_attente = "DELETE FROM inscription_attentes where idAttente = '".$j."'";
			$db -> exec($suppression_attente);

			header("Refresh:0");
		}

		if(isset($_POST[$j .'_ajouter'])) {

			$deplacement_req = $db -> prepare('SELECT * FROM inscription_attentes where idAttente = :idAttente');
			$deplacement_req -> execute(array('idAttente' => $j));
			$deplacement = $deplacement_req -> fetch();

			$Numgenre = $deplacement['genre'];
			$Nom = $deplacement['nom'];
			$Prenom = $deplacement['prenom'];
			$Mail = $deplacement['mail'];
			$Mdp = $deplacement['mdp'];
			$Naissance = $deplacement['dateNaissance'];
			$Tel = $deplacement['tel'];
			$Role = $deplacement['role'];
			$pp = $deplacement['pp'];
			$Centre = $deplacement['centre'];
			$Adresse = $deplacement['adresse'];

			$ajout_membre = "INSERT INTO users(genre,nom,prenom,mail,mdp,dateNaissance,tel,role,pp,centre,adresse,testDemande) VALUES ('$Numgenre','$Nom','$Prenom','$Mail','$Mdp','$Naissance','$Tel','$Role','$pp','$Centre','$Adresse',0)";
			$db -> exec($ajout_membre);

			$suppression_attente = "DELETE FROM inscription_attentes where idAttente = '".$j."'";
			$db -> exec($suppression_attente);

			header("Refresh:0");
		}
	}

	if(isset($_POST['inscription'])) {
		// définition des variables
		$Genre = $_POST['genre'];
		$Role = $_POST['role'];
		$Nom = html_entity_decode(addslashes($_POST['nom']));
		$Prenom = html_entity_decode(addslashes($_POST['prenom']));
		$Centre = $_POST['centre'];
		$Mail = $_POST['mail'];
		$Mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
		$Cmdp = $_POST['cmdp'];
		$Naissance = $_POST['date'];
		// attribution pp par défaut en fonction du genre
		if($Genre == 0) {
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
						// insertion dans la BDD
						$insert_user = "INSERT INTO users(genre,nom,prenom,mail,mdp,dateNaissance,tel,role,pp,centre,adresse,testDemande) VALUES ('$Genre','$Nom','$Prenom','$Mail','$Mdp','$Naissance','$Tel','$Role','$pp','$Centre','$Adresse',0)";
						$db -> exec($insert_user);
						// popup de réussite
						?>
						<div id="overlay">
							<div id="popup">
								<h2 class="popup_titre">Inscription réussie</h2>
								<p class="popup_msg">Le membre à bien été ajouté.</p>
								<div align="center">
									<a class="popup_lien" href="DiagHealth_gestion_admin.php">Retour</a>
									<a class="popup_lien" href="DiagHealth_accueil.php">Accueil</a>
								</div>
							</div>
						</div>
						<?php 
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

	if(isset($_POST['validation'])) {
        if ($_POST['delete'] == []) {
			header("Refresh:0");
		}
		else {
			foreach ($_POST['delete'] as $valeur) {
	            $suppression_centre = "DELETE FROM centre where idCentre='".$valeur."'";
				$db -> exec($suppression_centre);
			}
			header("Refresh:0");
		}
    }
	// annuler suppression
	else if(isset($_POST['annulation'])) {
        header("Refresh:0");
    }

    if (isset ($_POST['ajout_centre'])) {

		$ville = html_entity_decode(addslashes($_POST['ville']));
		$code = html_entity_decode(addslashes($_POST['code']));
		$map = html_entity_decode(addslashes($_POST['map']));
		
		$creation_centre = "INSERT INTO centre(ville,code,map) VALUES ('$ville','$code','$map')";
		$db -> exec($creation_centre);

		header("Refresh:0");

	}

	$res = '';

?>



<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Gestion des utilisateurs</title>
		<script src="https://kit.fontawesome.com/d8ae1e748c.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="../Public/CSS/DiagHealth_base.css">
	    <link rel="stylesheet" href="../Public/CSS/DiagHealth_gestion.css">
	</head>
	<?php include 'DiagHealth_header.php'; ?>
	<body>
		<h1>Bienvenue <?= $_SESSION['prenom']; ?> sur votre compte administrateur</h1>
		<div id="inscription_attente">
			<table class="attente_table">
				<tr class="attente_tr">
					<th class="attente_td">Nom</th>
					<th class="attente_td">Prénom</th>
					<th class="attente_td">Mail</th>
					<th class="attente_td">Rôle</th>
					<th class="attente_td">Actions</th>
				</tr>
				<?php
				for($i = 0; $i < $id_max_attente['idAttente']; ++$i) {
					if($dataAttente[$i][0] != '') {
						?>
						<tr class="attente_tr">
							<form method="post">
								<td class="attente_td"><?= $dataAttente[$i][2] ?></td>
								<td class="attente_td"><?= $dataAttente[$i][3] ?></td>
								<td class="attente_td"><a class="lien_mail" href="mailto: <?= $dataAttente[$i][4] ?>"><?= $dataAttente[$i][4] ?></a></td>
								<td class="attente_td"><?= $dataAttente[$i][6] ?></td>
								<td class="attente_td">
									<input type="submit" style="background-image: url(../Public/Images/times_circle.svg);" onclick="return confirmSuppression()" id="rejeter" value="" name="<?=$dataAttente[$i][0]?>_rejeter">
									<input type="submit" style="background-image: url(../Public/Images/check_circle.svg);" onclick="return confirmAjout()" id="ajouter" value="" name="<?=$dataAttente[$i][0]?>_ajouter">
								</td>
							</form>	
						</tr>
						<?php
					}
				} 
				?>
			</table>
			<br><br>	
		</div>
		<div id="ajouter_membre_banniere">
			<div class="banniere" onclick="over('ajouter_membre'); out('ajouter_membre_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">Ajouter un utilisateur</h3>
					<div class="sigle" align="right">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-right"></i></h3>
					</div>
				</div>
			</div>
		</div>
		<div id="ajouter_membre" style="display: none;">
			<div class="banniere" onclick="out('ajouter_membre'); over('ajouter_membre_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">Ajouter un utilisateur</h3>
					<div class="sigle">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-down"></i></h3>
					</div>
				</div>
			</div>
			<div class="ajouter_utilisateurs">
				<form method="post" name="formulaire">
					<table class="ajouter_utilisateurs_table">
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre">
								Civilité :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<input type="radio" name="genre" value='0' required><label>Mme.</label>
								<input type="radio" name="genre" value='1' required><label>M.</label>
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre">
								Rôle :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<input type="radio" name="role" value='Utilisateur' required><label>Utilisateur</label>
								<input type="radio" name="role" value='Gestionnaire' required><label>Gestionnaire</label>
								<input type="radio" name="role" value='Administrateur' required><label>Administrateur</label>
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre">
								Nom :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<input type="text" id="nom" name="nom" oninput="verifNom()" onblur="verifNom()" placeholder="exemple: Dupont" required>
								<a id="msg_erreur_nom" class="msg_erreur" style="display: none;"></a>
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre">
								Prénom :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<input type="text" id="prenom" name="prenom" oninput="verifPrenom()" onblur="verifPrenom()" placeholder="exemple: Martin" required>
								<a id="msg_erreur_prenom" class="msg_erreur" style="display: none;"></a>
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre"> 
								Centre de test :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<input type="radio" name="centre" value="0" required><a class="lien_map">Aucun</a><br>
								<?php 
								while ($maxCentreBis > 0) {
									$requeteCentre = $db -> prepare('SELECT idCentre, ville, code, map FROM centre where idCentre="'.$maxCentreBis.'"');
									$requeteCentre -> execute();
									$dataCentre = $requeteCentre -> fetch();

									if($dataCentre['ville'] != '') {
									?>
									<input type="radio" name="centre" value='<?= $dataCentre['idCentre'] ?>' required><a class="lien_map" href="<?= $dataCentre['map'] ?>" target="_blank"><?= $dataCentre['ville'] .' (' .$dataCentre['code'] .')'?></a><br>
									<?php
									}
									$maxCentreBis = $maxCentreBis - 1;
								}
								?>
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre">
								Identifiant (adresse mail) :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<input type="email" name="mail" id="mail" placeholder="exemple: martin.dupont@exemple.fr" required>
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre">
								Mot de passe :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<input type="password" name="mdp" id="mdp" required>
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre">
								Confirmation du mot de passe :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<input type="password" name="cmdp" id="cmdp" required oninput="verifPassword()">
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td class="ajouter_utilisateurs_td_titre">
								Date de naissance :
							</td>
							<td class="ajouter_utilisateurs_td_input">
								<?php $date = date("Y-m-d"); ?>
								<input type="date" max="<?= $date; ?>" name="date" required>
							</td>
						</tr>
						<tr class="ajouter_utilisateurs_tr">
							<td><td>
								<input type="submit" id="inscription" class="inscription" name="inscription" value="Inscrire ce membre">
							</td>
						</tr>
					</table>
				</form>
			</div>
		</div>
		<div class="espace_banniere"></div>
		<div id="recherche_banniere">
			<div class="banniere" onclick="over('recherche'); out('recherche_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">Rechercher un utilisateur</h3>
					<div class="sigle" align="right">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-right"></i></h3>
					</div>
				</div>
			</div>
		</div>
		<div id="recherche" style="display: none;">
			<div class="banniere" onclick="out('recherche'); over('recherche_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">Rechercher un utilisateur</h3>
					<div class="sigle">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-down"></i></h3>
					</div>
				</div>
			</div>
			<div class="recherche_utilisateurs">
				<h2>Recherches sur les comptes</h2>
				<p class="recherche"><label for="re">Rechercher un(des) utilisateur(s) :</label></p>
				<form method="post" action="" name="recherche">
					<label for="selection">Rechercher par :</label><br><br>
					<select id="selection" name="selection" required>
						<option value="">Sélectionner un critère</option>
						<?php
						foreach($critereChoisi as $NumCritere => $Critere):
						echo'<option value="'.$NumCritere.'">'.$Critere.'</option>';
						endforeach;
						?>
					</select>
					<input class="input_recherche" type="text" name="valeur" placeholder="Saisir une valeur">
					<input type="submit" id="recherche" class="w3-button" name="recherche" value="Rechercher">
					<br>
					<button id="bout" class="w3-button" onclick="over('selection2'); over('valeur2'); over('debout'); out('bout');">Ajouter un critère</button>
					<select id="selection2" name="selection2" style="display:none;">
						<option value="">Autre critère</option>
						<?php
						foreach($critereChoisi as $NumCritere => $Critere):
						echo'<option value="'.$NumCritere.'">'.$Critere.'</option>';
						endforeach;
						?>
					</select>
					<input class="input_recherche" id="valeur2" type="text" name="valeur2" placeholder="Saisir une valeur" style="display:none;">

					<button id="debout" class="w3-button" onclick="out('selection2'); out('valeur2'); out('debout'); over('bout');" style="display:none;">Enlever le critère</button>
					<div align="center">
						<!--
						<label for="result"> Afficher les résultats sous forme de :</label><br><br>
						<input type="checkbox" id="tableau" value="None" />
						<label for="tableau" class="bo1"> Tableaux</label>
						<input type="checkbox" id="graphe" value="None" />
						<label for="graphe" class="bo"> Graphes</label>
						-->
					</div>
				</form>
				<div id="tableau" align="center">
					<h2>Résultats de la recherche</h2>
					<table class="recherche_table">
						<tr class="recherche_tr">
							<th class="recherche_th">Numéro utilisateur</th>
							<th class="recherche_th">Nom</th>
							<th class="recherche_th">Prénom</th>
							<th class="recherche_th">Adresse mail</th>
							<th class="recherche_th">Centre de tests</th>
							<th class="recherche_th">Action</th>
						</tr>
						<?php if(isset($_POST['recherche'])) {
							//on récupère les valeurs
							$NumSelect = $_POST['selection'];
							$Select = $critereChoisi[$_POST['selection']];
							$Valeur = $_POST['valeur'];
								
							$NumSelect2 = $_POST['selection2'];
							$Select2 = $critereChoisi[$_POST['selection2']];
							$Valeur2 = $_POST['valeur2'];

							$req_id = $db -> prepare('SELECT idUtilisateur FROM users ORDER BY dateCreation DESC');
							$req_id -> execute();
							$fetch_id = $req_id -> fetch();
										// id utilisateur le plus élevé
							$id_max = $fetch_id['idUtilisateur'];


							$nb_lignes = 0;

							if($nb_lignes <= 45) {
								while ($id_max > 0) {
									$idres = $db -> prepare('SELECT * FROM users WHERE idUtilisateur="'.$id_max.'"');
									$idres -> execute();
									$dataRecherche = $idres -> fetch();
										
									$idresad = $db -> prepare('SELECT * FROM centre JOIN users ON centre.idCentre = users.centre  WHERE  centre="'.$dataRecherche['centre'].'"');
									$idresad -> execute();
									$dataRecherchead = $idresad -> fetch();
										
									if($Select == 'Numéro de compte' && $dataRecherche['idUtilisateur'] == $Valeur || $Select2 == 'Numéro de compte' && $dataRecherche['idUtilisateur'] == $Valeur2 || $Select == 'Nom'&& $dataRecherche['nom'] == $Valeur || $Select2 == 'Nom' && $dataRecherche['nom'] == $Valeur2 || $Select == 'Prénom'&& $dataRecherche['prenom']==$Valeur ||$Select2 == 'Prénom' && $dataRecherche['prenom']==$Valeur2 || $Select == 'Adresse mail'&& $dataRecherche['mail']==$Valeur ||$Select2 == 'Adresse mail' && $dataRecherche['mail']==$Valeur2 || $Select == 'Centre de tests' && $dataRecherchead['ville']==$Valeur ||$Select2 == 'Centre de tests' && $dataRecherchead['ville']==$Valeur2) {
									
										echo '<tr class="recherche_tr"><td class="recherche_th">';
										echo $dataRecherche['idUtilisateur'].'</td><td class="recherche_th">';
										echo $dataRecherche['nom'].'</td><td class="recherche_th">';
										echo $dataRecherche['prenom'].'</td><td class="recherche_th">';
										echo '<a class="lien_mail" href="mailto: ' .$dataRecherche['mail'] .'">' .$dataRecherche['mail'] .'</a></td><td class="recherche_th">';
										echo $dataRecherchead['ville'].'</td><td class="recherche_th">';
										echo '<a class="consult" href="DiagHealth_MonCompteRecherche.php?id_user=' , $dataRecherche['idUtilisateur'], '">' , "Consulter le compte", '</a></td></tr>';
								
										$nb_lignes = $nb_lignes + 1;
								
									}
									$id_max=$id_max-1;
									}
									
								}
							} 
							?>
					</table>
				</div>
			</div>
		</div>
		<div class="espace_banniere"></div>
		<div id="gestion_centre_banniere">
			<div class="banniere" onclick="over('gestion_centre'); out('gestion_centre_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">Gérer les centres de tests</h3>
					<div class="sigle" align="right">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-right"></i></h3>
					</div>
				</div>
			</div>
		</div>
		<div id="gestion_centre" style="display: none;">
			<div class="banniere" onclick="out('gestion_centre'); over('gestion_centre_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">Gérer les centres de tests</h3>
					<div class="sigle">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-down"></i></h3>
					</div>
				</div>
			</div>
			<div class="espace_banniere"></div>
			<div id="supprimer_centre_banniere">
				<div class="banniere_centre" onclick="over('supprimer_centre'); out('supprimer_centre_banniere')">
					<div class="titre_banniere">
						<h3 class="banniere_contenu">Supprimer un centre</h3>
						<div class="sigle" align="right">
							<h3 class="banniere_contenu2"><i class="fas fa-chevron-right"></i></h3>
						</div>
					</div>
				</div>
			</div>
			<div id="supprimer_centre" style="display: none;">
				<div class="banniere_centre" onclick="out('supprimer_centre'); over('supprimer_centre_banniere')">
					<div class="titre_banniere">
						<h3 class="banniere_contenu">Supprimer un centre</h3>
						<div class="sigle">
							<h3 class="banniere_contenu2"><i class="fas fa-chevron-down"></i></h3>
						</div>
					</div>
				</div>
				<div class="contenu_supprimer_centre">
					<form method="post">
						<table class="supprimer_centre_table">
							<?php
							while ($maxCentre > 0) {
								$centre_req = $db -> prepare('SELECT * FROM centre where idCentre=:idCentre');
								$centre_req -> execute(array('idCentre' => $maxCentre));
								$dataCentre = $centre_req -> fetch();
								if($dataCentre['ville'] != '') {
									?>
									<tr class="supprimer_centre_tr">
										<td class="supprimer_centre_td"><?= $dataCentre['ville']; ?></td>
										<td class="supprimer_centre_td"><input type='checkbox' name='delete[]' value=<?= $dataCentre['idCentre']; ?>></td>
									</tr>
									<?php
								}
								$maxCentre = $maxCentre - 1;
							}
							?>
							<tr class="supprimer_centre_tr">
								<td class="supprimer_centre_td"><td class="supprimer_centre_td">
									<input type="submit" id="suppression" name="validation" value="Supprimer">
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
			<div class="espace_banniere"></div>
			<div id="ajouter_centre_banniere">
				<div class="banniere_centre" onclick="over('ajouter_centre'); out('ajouter_centre_banniere')">
					<div class="titre_banniere">
						<h3 class="banniere_contenu">Ajouter un centre</h3>
						<div class="sigle" align="right">
							<h3 class="banniere_contenu2"><i class="fas fa-chevron-right"></i></h3>
						</div>
					</div>
				</div>
			</div>
			<div id="ajouter_centre" style="display: none;">
				<div class="banniere_centre" onclick="out('ajouter_centre'); over('ajouter_centre_banniere')">
					<div class="titre_banniere">
						<h3 class="banniere_contenu">Ajouter un centre</h3>
						<div class="sigle">
							<h3 class="banniere_contenu2"><i class="fas fa-chevron-down"></i></h3>
						</div>
					</div>
				</div>
				<div class="contenu_ajouter_centre">
					<form method="post">
						<table class="ajouter_centre_table">
							<tr class="ajouter_centre_tr">
								<td class="ajouter_centre_td_titre">
									Ville :
								</td>
								<td class="ajouter_centre_td_input">
									<input type="text" name="ville" placeholder="Nom de la ville" required>
								</td>
							</tr>
							<tr class="ajouter_centre_tr">
								<td class="ajouter_centre_td_titre">
									Code :
								</td>
								<td class="ajouter_centre_td_input">
									<input type="text" name="code" placeholder="Code postal de la ville" required>
								</td>
							</tr>
							<tr class="ajouter_centre_tr">
								<td class="ajouter_centre_td_titre">
									Map :
								</td>
								<td class="ajouter_centre_td_input">
									<input type="text" name="map" placeholder="Lien Google Map" required>
								</td>
							</tr>
							<tr class="ajouter_centre_tr">
							<td><td>
								<input type="submit" id="ajout_centre" class="ajout_centre" name="ajout_centre" value="Ajouter un centre de test">
							</td>
						</tr>
						</table>
					</form>
				</div>
			</div>
		</div>
	</body>
	<?php include 'DiagHealth_footer.php'; ?>
</html>

<?php
	}
?>