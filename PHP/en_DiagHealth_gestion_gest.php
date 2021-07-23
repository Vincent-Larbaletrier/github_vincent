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

if($_SESSION['connexion'] != '1' || $_SESSION['role'] != 'Gestionnaire') {
	header('Location: en_DiagHealth_connexion.php');
}

else if($_SESSION['connexion'] == '1' && $_SESSION['role'] == 'Gestionnaire' && $_SESSION['centre'] != '0') {

	include 'DiagHealth_database.php';
	global $db;

	$_SESSION['page'] = "gestion_gest";

	$id_attente_req = $db -> prepare('SELECT * FROM users ORDER BY idUtilisateur DESC ');
	$id_attente_req -> execute();
	$id_max_attente = $id_attente_req -> fetch();

	$attente_req = $db -> prepare('SELECT * FROM users where testDemande = 1 ORDER BY dateDemande DESC');
	$attente_req -> execute();
	$dataAttente = $attente_req -> fetchAll();

	$gestionnaire_req = $db -> prepare('SELECT * FROM users where role = "Gestionnaire"');
	$gestionnaire_req -> execute();
	$dataGestionnaire = $gestionnaire_req -> fetchAll();

	$centre_req = $db -> prepare('SELECT * FROM centre where idCentre = "'.$_SESSION['centre'].'"');
	$centre_req -> execute();
	$dataCentre = $centre_req -> fetch();

	$critereChoisi = array('Numéro de compte','Nom','Prénom','Adresse mail');

	$message = "";

	for($j = 0; $j < $id_max_attente['idUtilisateur']+1; ++$j) {

		if(isset($_POST[$j .'_demande'])) {

			$suppression_demande = "UPDATE users SET testDemande = 0 where idUtilisateur = '".$j."'";
			$db -> exec($suppression_demande);

			header("Refresh:0");
		}
	}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>User management</title>
		<script src="https://kit.fontawesome.com/d8ae1e748c.js" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="../Public/CSS/DiagHealth_base.css">
	    <link rel="stylesheet" href="../Public/CSS/DiagHealth_gestion.css">
	</head>
	<?php include 'en_DiagHealth_header.php'; ?>
	<body>
		<h1>Welcome <?= $_SESSION['prenom']; ?> on your center manager account of <?= $dataCentre['ville']; ?></h1>
		<div id="gestionnaires">
			<h2 class="liste_gestionnaire">List of all center managers <?= $dataCentre['ville']; ?></h2>
			<table class="gestionnaires_table">
				<tr class="gestionnaires_tr">
					<th class="gestionnaires_td">Last name</th>
					<th class="gestionnaires_td">First name</th>
					<th class="gestionnaires_td">Email</th>
					<th class="gestionnaires_td">Contact information</th>
				</tr>
				<?php
				for($i = 0; $i < $id_max_attente['idUtilisateur']; ++$i) {
					if($dataGestionnaire[$i][6] == 'Gestionnaire' && $dataGestionnaire[$i][7] == $_SESSION['centre']) {
						?>
						<tr class="gestionnaires_tr">
							<td class="attente_td"><?= $dataGestionnaire[$i][2] ?></td>
							<td class="attente_td"><?= $dataGestionnaire[$i][3] ?></td>
							<td class="attente_td"><a class="lien_mail" href="mailto: <?= $dataGestionnaire[$i][4] ?>"><?= $dataGestionnaire[$i][4] ?></a></td>
							<td class="attente_td"><?= $dataGestionnaire[$i][10] ?></td>		
						</tr>
						<?php
					}
				} 
				?>
			</table>
			<br><br>	
		</div>	
		<div id="consulter_demandes_banniere">
			<div class="banniere" onclick="over('consulter_demandes'); out('consulter_demandes_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">View test requests</h3>
					<div class="sigle" align="right">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-right"></i></h3>
					</div>
				</div>
			</div>
		</div>
		<div id="consulter_demandes" style="display: none;">
			<div class="banniere" onclick="out('consulter_demandes'); over('consulter_demandes_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">View test requests</h3>
					<div class="sigle">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-down"></i></h3>
					</div>
				</div>
			</div>
			<div class="test_attente">
				<table class="attente_table">
					<tr class="attente_tr">
						<th class="attente_td">Last name</th>
						<th class="attente_td">First name</th>
						<th class="attente_td">Email</th>
						<th class="attente_td">Contact information</th>
						<th class="attente_td">Actions</th>
					</tr>
					<?php
					for($i = 0; $i < $id_max_attente['idUtilisateur']; ++$i) {
						if($dataAttente[$i][0] != '' && $dataAttente[$i][7] == $_SESSION['centre']) {
							?>
							<tr class="attente_tr">
								<form method="post">
									<td class="attente_td"><?= $dataAttente[$i][2] ?></td>
									<td class="attente_td"><?= $dataAttente[$i][3] ?></td>
									<td class="attente_td"><a class="lien_mail" href="mailto: <?= $dataAttente[$i][4] ?>"><?= $dataAttente[$i][4] ?></a></td>
									<td class="attente_td"><?= $dataAttente[$i][10] ?></td>
									<td class="attente_td">
										<input type="submit" style="background-image: url(../Public/Images/times_circle.svg);" onclick="return confirmDemande()" id="demande" value="" name="<?=$dataAttente[$i][0]?>_demande">
									</td>
								</form>	
							</tr>
							<?php
						}
					} 
					?>
				</table>	
			</div>
		</div>
		<div class="espace_banniere"></div>
		<div id="recherche_banniere">
			<div class="banniere" onclick="over('recherche'); out('recherche_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">Find a user</h3>
					<div class="sigle" align="right">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-right"></i></h3>
					</div>
				</div>
			</div>
		</div>
		<div id="recherche" style="display: none;">
			<div class="banniere" onclick="out('recherche'); over('recherche_banniere')">
				<div class="titre_banniere">
					<h3 class="banniere_contenu">Find a user</h3>
					<div class="sigle">
						<h3 class="banniere_contenu2"><i class="fas fa-chevron-down"></i></h3>
					</div>
				</div>
			</div>
			<div class="recherche_utilisateurs">
				<h2>Research on my team</h2>
				<p class="recherche"><label for="re">Search for users :</label></p>
				<form method="post" action="" name="recherche">
					<label for="selection">Search by :</label><br><br>
					<select id="selection" name="selection" required>
						<option value="">Select a criterion</option>
						<?php
						foreach($critereChoisi as $NumCritere => $Critere):
						echo'<option value="'.$NumCritere.'">'.$Critere.'</option>';
						endforeach;
						?>
					</select>
					<input class="input_recherche" type="text" name="valeur" placeholder="Enter a value">
					<input type="submit" id="recherche" class="w3-button" name="recherche" value="search">
					<br>
					<button id="bout" class="w3-button" onclick="over('selection2'); over('valeur2'); over('debout'); out('bout');">Add criteria</button>
					<select id="selection2" name="selection2" style="display:none;">
						<option value="">Other criterion</option>
						<?php
						foreach($critereChoisi as $NumCritere => $Critere):
						echo'<option value="'.$NumCritere.'">'.$Critere.'</option>';
						endforeach;
						?>
					</select>
					<input class="input_recherche" id="valeur2" type="text" name="valeur2" placeholder="Enter a value" style="display:none;">

					<button id="debout" class="w3-button" onclick="out('selection2'); out('valeur2'); out('debout'); over('bout');" style="display:none;">Remove criterion</button>
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
					<h2>Search results on your test center</h2>
					<table class="recherche_table">
						<tr class="recherche_tr">
							<th class="recherche_th">User number</th>
							<th class="recherche_th">Last name</th>
							<th class="recherche_th">First name</th>
							<th class="recherche_th">Mail address</th>
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
									$idres = $db -> prepare('SELECT * FROM users WHERE idUtilisateur="'.$id_max.'" and centre=:centre');
									$idres -> execute(array('centre' => $_SESSION['centre']));
									$dataRecherche = $idres -> fetch();
									
									
									$idresad = $db -> prepare('SELECT * FROM centre JOIN users ON centre.idCentre = users.centre  WHERE  centre="'.$dataRecherche['centre'].'"');
									$idresad -> execute();
									$dataRecherchead = $idresad -> fetch();
										
									if(($Select == 'Numéro de compte' && $dataRecherche['idUtilisateur'] == $Valeur || $Select2 == 'Numéro de compte' && $dataRecherche['idUtilisateur'] == $Valeur2 || $Select == 'Nom'&& $dataRecherche['nom'] == $Valeur || $Select2 == 'Nom' && $dataRecherche['nom'] == $Valeur2 || $Select == 'Prénom'&& $dataRecherche['prenom']==$Valeur ||$Select2 == 'Prénom' && $dataRecherche['prenom']==$Valeur2 || $Select == 'Adresse mail'&& $dataRecherche['mail']==$Valeur ||$Select2 == 'Adresse mail' && $dataRecherche['mail']==$Valeur2) && $dataRecherche['centre']==$_SESSION['centre']) {
									
										echo '<tr class="recherche_tr"><td class="recherche_th">';
										echo $dataRecherche['idUtilisateur'].'</td><td class="recherche_th">';
										echo $dataRecherche['nom'].'</td><td class="recherche_th">';
										echo $dataRecherche['prenom'].'</td><td class="recherche_th">';
										echo '<a class="lien_mail" href="mailto: ' .$dataRecherche['mail'] .'">' .$dataRecherche['mail'] .'</a></td><td class="recherche_th">';
										echo '<a class="consult" href="en_DiagHealth_MonCompteRecherche.php?id_user=' , $dataRecherche['idUtilisateur'], '">' , "Consult the account", '</a></td></tr>';
								
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
	</body>
	<?php include 'en_DiagHealth_footer.php'; ?>
</html>
<?php
	}
?>