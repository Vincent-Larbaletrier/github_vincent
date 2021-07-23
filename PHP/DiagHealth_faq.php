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

	include 'DiagHealth_database.php';
	global $db;

	$idfaq_req = $db -> prepare('SELECT idFAQ FROM faq ORDER BY dateCreation DESC');
	$idfaq_req -> execute();
	$idfaq_fetch = $idfaq_req -> fetch();
	
	$max_faq = $idfaq_fetch['idFAQ'];

	$numero_faq = 0;

	$_SESSION['page'] = "faq";

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
		<h1>Bienvenue sur notre FAQ</h1>
		<div id="faq">	
				<?php
				while ($numero_faq < $max_faq + 1) {
					$idfaq = $db -> prepare('SELECT idFAQ, question, reponse FROM faq where idFAQ=:idFAQ');
					$idfaq -> execute(array('idFAQ' => $numero_faq));
					$datafaq = $idfaq -> fetch();

					if ($datafaq['question'] != '') {
						?>
						<a class="question" onclick="over(<?=$datafaq['idFAQ']?>)">
								<?php echo $datafaq['question']; ?>
						</a>
						<div class="espace1"></div>
						<div class="reponse" id="<?=$datafaq['idFAQ']?>" style="display:none;" onclick="out(<?=$datafaq['idFAQ']?>)">
								<?php echo $datafaq['reponse']; ?>
						</div>
						<div class="espace2"></div>
						<?php
						$numero_faq = $numero_faq + 1;
					}
					else {
						$numero_faq = $numero_faq + 1;
					}	
				}
				?>
		</div>
		<?php
        	if(isset($_SESSION['role'])) {
       			if($_SESSION['role'] == 'Administrateur') {
        			?><a class="version_admin" href="DiagHealth_faq_admin.php"><i class="fas fa-pen-square"></i>  Ajouter/modifier la FAQ</a><?php
     			}
     		}
    	?>
		<a id="top" href="#faq"><img class="top" src="../Public/Images/top.jpg"></a>
	</body>
	<?php include 'DiagHealth_footer.php'; ?>
</html>