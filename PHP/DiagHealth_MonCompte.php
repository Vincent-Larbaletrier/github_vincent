<?php 
	
	session_start();

	include 'DiagHealth_database.php';
	global $db;

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

if($_SESSION['connexion'] != '1') {
	header('Location: DiagHealth_connexion.php');
}

else if($_SESSION['connexion'] == '1') {

	$_SESSION['page'] = "MonCompte";

    $msg = '';

    $req_centre = $db -> prepare('SELECT idCentre FROM centre ORDER BY dateCreation DESC');
	$req_centre -> execute();
	$fetch_centre = $req_centre -> fetch();
	// id du centre le plus élevé
	$id_max_centre = $fetch_centre['idCentre'];

    $rechercheinfo = $db -> prepare('SELECT * FROM users WHERE idUtilisateur=:idUtilisateur');
	$rechercheinfo -> execute(array('idUtilisateur' => $_SESSION['idUtilisateur']));
	$dataCompte = $rechercheinfo -> fetch();

	sscanf($dataCompte['dateCreation'], "%4s-%2s-%2s %2s:%2s:%2s", $annee, $mois, $jour, $heure, $minute, $seconde);
	$dataCompte['dateCreation'] = $jour ."/" .$mois ."/" .$annee;

	sscanf($dataCompte['dateNaissance'], "%4s-%2s-%2s", $annee, $mois, $jour);
	$dateNaissance = $jour ."/" .$mois ."/" .$annee;

	$requeteCentre = $db -> prepare('SELECT idCentre, ville, code, map FROM centre where idCentre="'.$dataCompte['centre'].'"');
	$requeteCentre -> execute();
	$infoCentre = $requeteCentre -> fetch();

	if($infoCentre['ville'] == '') {
		$affichageCentre = "Vous n'appartenez à aucun centre";
	} else {
		$affichageCentre = $infoCentre['ville'];
	}

	if(isset($_FILES['pp']) AND !empty($_FILES['pp']['name'])) {
		$taillemax = 2097152;
		$extV = array('jpg', 'jpeg', 'gif', 'png');
		if($_FILES['pp']['size'] <= $taillemax) {
			$extU = strtolower(substr(strrchr($_FILES['pp']['name'], '.'), 1));
			if(in_array($extU, $extV)) {
				$chemin = "users/pp/" .$_SESSION['idUtilisateur']. "." .$extU;
				$resultat = move_uploaded_file($_FILES['pp']['tmp_name'], $chemin);
				if($resultat) {
					$updatepp = $db -> prepare('UPDATE users SET pp = :pp where idUtilisateur = :idUtilisateur');
					$updatepp -> execute(array(
						'pp' => $_SESSION['idUtilisateur'].'.'.$extU,
						'idUtilisateur' => $_SESSION['idUtilisateur']
					));

					header("Refresh:0");

				}
				else {
					$msg = "Une erreur d'importation est survenue.";
				}
			}
			else {
				$msg = "L'image n'est pas au bon format";
			}
		}
		else {
			$msg = "L'image est trop volumineuse";
		}
	}

	if(isset($_POST['validation'])) {
		$Nom = html_entity_decode(addslashes($_POST['nom']));
		$Prenom = html_entity_decode(addslashes($_POST['prenom']));
		$Mdp_before = $_POST['mdp_before'];
		$Mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
		$Centre = $_POST['centre'];
		$Naissance = $_POST['dateN'];
		$Adresse = html_entity_decode(addslashes($_POST['adresse']));
		$Tel = $_POST['tel'];

		if(empty($Centre)) {
			$Centre = $dataCompte['centre'];
		}

		if(empty($Naissance)) {
			$Naissance = $dataCompte['dateNaissance'];
		}

		if(!empty($Nom) || !empty($Prenom)) {
			if(empty($Mdp_before) || empty($Mdp)) {
				$update_profil = 'UPDATE users SET nom="'.$Nom.'", prenom="'.$Prenom.'", centre="'.$Centre.'", dateNaissance="'.$Naissance.'", adresse="'.$Adresse.'", tel="'.$Tel.'" WHERE idUtilisateur="'.$_SESSION['idUtilisateur'].'"';
				$db-> exec($update_profil);

				header("Refresh:0");
			}
			else {
				$MdpBon = password_verify($Mdp_before, $dataCompte['mdp']);

				if($MdpBon) {
					$update_profil = 'UPDATE users SET nom="'.$Nom.'", prenom="'.$Prenom.'", mdp="'.$Mdp.'", centre="'.$Centre.'", dateNaissance="'.$Naissance.'", adresse="'.$Adresse.'", tel="'.$Tel.'" WHERE idUtilisateur="'.$_SESSION['idUtilisateur'].'"';
					$db-> exec($update_profil);

					header("Refresh:0");
				} 
				else {
					$msg = "Le mot de passe n'est pas le bon.";
				}
			}
		}
	}

	if(isset($_POST['ajout'])){
		if($dataCompte['centre'] != '0') {
			if($dataCompte['testDemande'] == '0') {
				$ajout_demande ='UPDATE users SET testDemande=1, dateDemande=CURRENT_TIMESTAMP WHERE idUtilisateur="'.$dataCompte['idUtilisateur'].'"';
				$db-> exec($ajout_demande);

				header("Refresh:0");
			}
			else {
				$msg = "Vous êtes déjà en attente d'un test.";
			}
		}
		else {
			$msg = "Vous devez appartenir à un centre de test pour effectuer une demande.";
		}
	}

?>


<!DOCTYPE html>
<html>
	<head>
		<title>Mon compte</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_MonCompte.css">
	</head>
	<?php include 'DiagHealth_header.php'; ?>
	<body>
		<div class="responsive"></div>
		<div id="photo" style="background-image: url(../Public/Images/fond0.jpg);" align="center">
			<div class="border" align="center">
				<img id="pp" src="users/pp/<?= $_SESSION['pp']; ?>">
			</div>
		</div>
		<div id="parcourir" align="center">
			<form method="post" enctype="multipart/form-data">
				<input type="file" class="modif_pp" name="pp" required> <br>
				<input type="submit" id="modif_pp" name="modif_pp" value="Modifier ma photo de profil">
			</form>
		</div>
		<h1> Mon compte/mes informations personnelles </h1>
		<div id="tableau" align="center">
			<form method="post">
				<table>
					<tr>
						<th>Nom :</th>
						<td>
							<div id="nom"><?= $dataCompte['nom']; ?></div>
							<div id="nom_modif" style="display: none;"><input type="text" id="nom_mod" oninput="verifNomModif()" onblur="verifNomModif()" name="nom" value='<?= $dataCompte['nom']; ?>' required></div>
							<a id="msg_erreur_nom" class="msg_erreur" style="display: none;"></a>
						</td>
						<td class="mod">
							<div id="nom_btn">
								<a onclick="over('nom_modif'); out('nom'); over('nom_annuler'); out('nom_btn');" class="bouton"><i class="far fa-edit"></i> Modifier</a>
							</div>
							<div id="nom_annuler" style="display: none;">
								<a onclick="out('nom_modif'); over('nom'); out('nom_annuler'); over('nom_btn');" class="annuler_modif">Annuler les modifications</a>
							</div>
						</td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Prénom :</th>
						<td>
							<div id="prenom"><?= $dataCompte['prenom']; ?></div>
							<div id="prenom_modif" style="display: none;"><input type="text" id="prenom_mod" oninput="verifPrenomModif()" onblur="verifPrenomModif()" name="prenom" value='<?= $dataCompte['prenom']; ?>' required></div>
							<a id="msg_erreur_prenom" class="msg_erreur" style="display: none;"></a>
						</td>
						<td class="mod">
							<div id="prenom_btn">
								<a onclick="over('prenom_modif'); out('prenom'); over('prenom_annuler'); out('prenom_btn');" class="bouton"><i class="far fa-edit"></i> Modifier</a>
							</div>
							<div id="prenom_annuler" style="display: none;">
								<a onclick="out('prenom_modif'); over('prenom'); out('prenom_annuler'); over('prenom_btn');" class="annuler_modif">Annuler les modifications</a>
							</div>
						</td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Sexe :</th>
						<td>
							<div id="sexe"><?php if($dataCompte['genre'] == 0) {echo "Femme";} else {echo "Homme";} ?></div>
						</td>
						<td class="mod"></td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Mail :</th>
						<td>
							<div id="mail"><?= $dataCompte['mail']; ?></div>
						</td>
						<td class="mod"></td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Mot de passe :</th>
						<td>
							<div id="mdp"><?= "**********"; ?></div>
							<div id="mdp_conf" style="display: none;"><label>Entrez votre mot de passe actuel</label><input type="password" name="mdp_before"></div>
							<div id="mdp_modif" style="display: none;"><label>Entrez le nouveau mot de passe</label><input type="password" name="mdp"></div>
						</td>
						<td class="mod">
							<div id="mdp_btn">
								<a onclick="over('mdp_modif'); over('mdp_conf'); out('mdp'); over('mdp_annuler'); out('mdp_btn');" class="bouton"><i class="far fa-edit"></i> Modifier</a>
							</div>
							<div id="mdp_annuler" style="display: none;">
								<a onclick="out('mdp_modif'); out('mdp_conf'); over('mdp'); out('mdp_annuler'); over('mdp_btn');" class="annuler_modif">Annuler les modifications</a>
							</div>
						</td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Centre de test :</th>
						<td>
							<div id="centre"><?= $affichageCentre; ?></div>
							<div id="centre_modif" style="display: none;">
								<input type="radio" name="centre" value="0"><a class="lien_map">Aucun</a><br>
								<?php 
									while ($id_max_centre > 0) {
										$requeteCentre = $db -> prepare('SELECT idCentre, ville, code, map FROM centre where idCentre="'.$id_max_centre.'"');
										$requeteCentre -> execute();
										$dataCentre = $requeteCentre -> fetch();

										if($dataCentre['ville'] != '') {
										?>
										<input type="radio" name="centre" value='<?= $dataCentre['idCentre'] ?>'><a class="lien_map" href="<?= $dataCentre['map'] ?>" target="_blank"><?= $dataCentre['ville'] .' (' .$dataCentre['code'] .')'?></a><br>
										<?php
										}
										$id_max_centre = $id_max_centre - 1;
									}
								?>
							</div>
						</td>
						<td class="mod">
							<div id="centre_btn">
								<a onclick="over('centre_modif'); out('centre'); over('centre_annuler'); out('centre_btn');" class="bouton"><i class="far fa-edit"></i> Modifier</a>
							</div>
							<div id="centre_annuler" style="display: none;">
								<a onclick="out('centre_modif'); over('centre'); out('centre_annuler'); over('centre_btn');" class="annuler_modif">Annuler les modifications</a>
							</div>
						</td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Date de naissance :</th>
						<td>
							<div id="dateN"><?= $dateNaissance; ?></div>
							<div id="dateN_modif" style="display: none;">
								<?php $date = date("Y-m-d"); ?>
								<input type="date" max="<?= $date; ?>" name="dateN" value='<?= $dataCompte['dateNaissance']; ?>'>
							</div>
						</td>
						<td class="mod">
							<div id="dateN_btn">
								<a onclick="over('dateN_modif'); out('dateN'); over('dateN_annuler'); out('dateN_btn');" class="bouton"><i class="far fa-edit"></i> Modifier</a>
							</div>
							<div id="dateN_annuler" style="display: none;">
								<a onclick="out('dateN_modif'); over('dateN'); out('dateN_annuler'); over('dateN_btn');" class="annuler_modif">Annuler les modifications</a>
							</div>
						</td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Adresse :</th>
						<td>
							<div id="adresse"><?= $dataCompte['adresse']; ?></div>
							<div id="adresse_modif" style="display: none;"><input type="text" name="adresse" value='<?= $dataCompte['adresse']; ?>'></div>
						</td>
						<td class="mod">
							<div id="adresse_btn">
								<a onclick="over('adresse_modif'); out('adresse'); over('adresse_annuler'); out('adresse_btn');" class="bouton"><i class="far fa-edit"></i> Modifier</a>
							</div>
							<div id="adresse_annuler" style="display: none;">
								<a onclick="out('adresse_modif'); over('adresse'); out('adresse_annuler'); over('adresse_btn');" class="annuler_modif">Annuler les modifications</a>
							</div>
						</td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Numéro de téléphone :</th>
						<td>
							<div id="tel"><?= $dataCompte['tel']; ?></div>
							<div id="tel_modif" style="display: none;"><input id="tel_mod" type="text" oninput="verifTelModif()" onblur="verifTelModif()" name="tel" value='<?= $dataCompte['tel']; ?>'></div>
							<a id="msg_erreur_tel" class="msg_erreur" style="display: none;"></a>
						</td>
						<td class="mod">
							<div id="tel_btn">
								<a onclick="over('tel_modif'); out('tel'); over('tel_annuler'); out('tel_btn');" class="bouton"><i class="far fa-edit"></i> Modifier</a>
							</div>
							<div id="tel_annuler" style="display: none;">
								<a onclick="out('tel_modif'); over('tel'); out('tel_annuler'); over('tel_btn');" class="annuler_modif">Annuler les modifications</a>
							</div>
						</td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Rôle :</th>
						<td>
							<div id="role"><?= $dataCompte['role']; ?></div>
						</td>
						<td class="mod"></td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Demande de test :</th>
						<td>
							<?php sscanf($dataCompte['dateDemande'], "%4s-%2s-%2s", $annee, $mois, $jour);
							$dataCompte['dateDemande'] = $jour ."/" .$mois ."/" .$annee; ?>
							<div id="test_demande"><?php if($dataCompte['testDemande'] == 0) {echo "Aucune demande de test en attente";} else {echo "Demande de test en attente le : " .$dataCompte['dateDemande'] .".";} ?></div>
						</td>
						<td class="mod"></td>
					</tr>
					<tr class="espace"></tr>
					<tr>
						<th>Date de création du compte :</th>
						<td>
							<div id="dateC"><?= $dataCompte['dateCreation']; ?></div>
						</td>
						<td class="mod"></td>
					</tr>
					<tr class="espace"></tr>		
				</table>
				<div class="sauvegarder" align="right">
					<a class="save"><i class="far fa-save"></i><input type="submit" class="save_submit" name="validation" value="Enregistrer les modifications"></a>
				</div>
				<div class="msg"><?= $msg; ?></div>
			</form>
			<div id="bouton">
				<form id="ajouter" name="ajouter" method="post">
					<input type="submit" class="ajout" name="ajout" onclick="return confirmDemande()" value="Demander un test">
				</form>
			</div>
		</div>	
	</body>
	<?php include 'DiagHealth_footer.php'; ?>
</html>
<?php 
}
?>	