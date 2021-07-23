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

	$_SESSION['page'] = "faq_admin";

	$idfaq_req = $db -> prepare('SELECT idFAQ FROM faq ORDER BY dateCreation DESC');
	$idfaq_req -> execute();
	$idfaq_fetch = $idfaq_req -> fetch();
	
	$max_faq = $idfaq_fetch['idFAQ'];

	$numero_faq = 0;
	// ajouter une question/réponse
	if (isset ($_POST['creer'])) {

		$question = html_entity_decode(addslashes($_POST['question']));
		$reponse = html_entity_decode(addslashes($_POST['reponse']));
		
		$creation_faq = "INSERT INTO faq(question,reponse) VALUES ('$question','$reponse')";
		$db -> exec($creation_faq);

		header("Refresh:0");

	}
	// modification de questions/réponses
	if (isset ($_POST['modification'])) {

		while ($numero_faq < $max_faq + 1) {
			$mod_question = html_entity_decode(addslashes($_POST['mod_question_' .$numero_faq]));
			$mod_reponse = html_entity_decode(addslashes($_POST['mod_reponse_' .$numero_faq]));
									
			$modif_faq_question = "UPDATE faq SET question='$mod_question' where idFAQ=".$numero_faq."";
			$modif_faq_reponse = "UPDATE faq SET reponse='$mod_reponse' where idFAQ=".$numero_faq."";
			$db -> exec($modif_faq_question);
			$db -> exec($modif_faq_reponse);

			$numero_faq = $numero_faq + 1;
			
		}

	header("Refresh:0");

	}
	// valider suppression
 	if(isset($_POST['validation'])) {
        if ($_POST['delete'] == []) {
			header("Refresh:0");
		}
		else {
			foreach ($_POST['delete'] as $valeur) {
	            $suppression_faq = "DELETE FROM faq where idFAQ='".$valeur."'";
				$db -> exec($suppression_faq);
			}
			header("Refresh:0");
		}
    }
	// annuler suppression
	else if(isset($_POST['annulation'])) {
        header("Refresh:0");
    }

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>FAQ</title>
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_faq.css">
	</head>
	<?php include 'DiagHealth_header.php'; ?>
	<body>
		<div class="terminer">
			<a href="DiagHealth_faq.php" id="terminer">Terminer les modifications</a>
		</div>
		<form method="post" name="suppression">
			<div id="faq_admin">	
				<?php
				// affiche toutes les questions/réponses
				while ($numero_faq < $max_faq + 1) {
					$idfaq = $db -> prepare('SELECT idFAQ, question, reponse FROM faq where idFAQ=:idFAQ');
					$idfaq -> execute(array('idFAQ' => $numero_faq));
					$datafaq = $idfaq -> fetch();

					if ($datafaq['question'] != '') {
						?>
						<div class="contenu">
						<div id="v_<?=$datafaq['idFAQ']?>">
							<div class="question_admin">
									<?php echo $datafaq['question']; ?>
							</div>
							<div class="reponse_admin">
									<?php echo $datafaq['reponse']; ?>
							</div>
						</div>
						<div id="<?=$datafaq['idFAQ']?>" style="display:none;">
							<div class="question_admin">
								<textarea class="input_modif" type='text' name='mod_question_<?php echo $numero_faq; ?>' value='<?php echo $datafaq['question']?>'><?php echo $datafaq['question']?></textarea>
							</div>
							<div class="reponse_admin">
								<textarea class="input_modif" type='text' name='mod_reponse_<?php echo $numero_faq; ?>' value='<?php echo $datafaq['reponse']?>'><?php echo $datafaq['reponse']?></textarea>
							</div>
							<a class="annuler_modif" onclick="out(<?=$datafaq['idFAQ']?>); over('v_<?=$datafaq['idFAQ']?>');">Enlever mode modification</a>
							
						</div>
						</div>
						<div class="modif">
								<a onclick="over(<?=$datafaq['idFAQ']?>); out('v_<?=$datafaq['idFAQ']?>');"><i class="far fa-edit"></i> Modifier</a>
						</div>
						<div class="selection">
							<?php echo "<input type='checkbox' name='delete[]' value='".$datafaq['idFAQ']."'>"; ?>
						</div>
						<div class="espace2">	
						</div>
						<?php
						$numero_faq = $numero_faq + 1;
					}
					else {
						$numero_faq = $numero_faq + 1;
					}	
				}
				?>
			</div>
		<a class="modifs"><i class="far fa-save"></i><input type="submit" class="modification" name="modification" value="Valider les modifications"></a>
		<a class="suppr" onclick="over('overlay');"><i class="fas fa-trash-alt"></i> Supprimer</a>
		<!-- Supprimer un élément -->
		<div id="overlay" style="display: none;">
        	<div id="popup">
           		<h2 class="popup_titre">Suppression d'éléments</h2>
            	<p class="popup_msg">Souhaitez-vous vraiment supprimer ces éléments ?</p>
            	<div align="center">
                	<input type="submit" name="validation" class="popup_lien" value="Supprimer">	
                	<input type="submit" name="annulation" class="popup_lien" value="Annuler">  		
                </div>
            </div>
        </div>
		</form>
		<h2>Ajouter une question/réponse</h2>
		<form action="DiagHealth_faq_admin.php" method="post">
			<table>
				<tr>
					<td class="colonne1">
						Question :  
					</td>
					<td class="colonne2">
						<input type="text" name="question" class="insert_question" placeholder=" Insérez une question" required>
					</td>
				</tr>
				<tr class="espace"><tr>
				<tr>
					<td class="colonne1">
						Réponse :  
					</td>
					<td class="colonne2">
						<textarea name="reponse" class="insert_reponse" placeholder=" Insérez une réponse" required></textarea>
					</td>
				</tr>
				<tr>
					<td><td align="right">
						<input type="submit" class="ajout" name="creer" value="Ajouter">
					</td>
				</tr>
			</table>
		</form>
		<a id="top" href="#terminer"><img class="top" src="../Public/Images/top.jpg"></a>
	</body>
	<?php include 'DiagHealth_footer.php'; ?>
</html>
<?php
}	
?>	