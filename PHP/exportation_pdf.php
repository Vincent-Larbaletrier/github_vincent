<?php
	session_start();

	include 'DiagHealth_database.php';
	global $db;

	$rechercheSexe = $db -> prepare('SELECT genre FROM users where idUtilisateur=:idUtilisateur');
	$rechercheSexe -> execute(array('idUtilisateur' => $_SESSION['idUtilisateur']));
	$resultatSexe = $rechercheSexe -> fetch();

	if ($resultatSexe['genre'] == '0') {
		$sexe = 'Mme.';
	}
	elseif ($resultatSexe['genre'] == '1') {
		$sexe = 'M.';
	}
	else {
		$sexe = 'Mme./M.';
	}

	include 'valeurs_donnees.php';

require('../pour_pdf/fpdf.php');

class PDF extends FPDF {

	function Header() {
	    $this->Image('../Public/Images/logo.png',2,2,20);
	    $this->SetFont('Times','B',15);
	    $this->Cell(80);
	    $this->Cell(30,10,utf8_decode('Fiche de résultats'),0,0,'C');
	    $this->Ln(20);
	}

	function Footer() {
	    $this->SetY(-15);
	    $this->SetFont('Times','IU',8);
	    $this->Cell(0,10,'DiagHealth',0,0,'C');
	}
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetLineWidth(0.3);
$pdf->SetFont('Times','B',20);
$pdf->Cell(0,8,utf8_decode('Résultats de ' .$sexe .' ' .$_SESSION['prenom'] .' ' .$_SESSION['nom']),0,1);
$pdf->Cell(0,8,utf8_decode('à la suite de différents tests psychotechniques : '),0,1);

$pdf->SetFont('Times','B',14);
$pdf->SetTextColor(255,160,25); 
$pdf->Cell(0,30,utf8_decode('Troubles auditifs'),0,1);

$pdf->SetFont('Times','',12);
$pdf->SetTextColor(0,0,0); 
$pdf->Cell(100,10,'Test',1,0,'C');
$pdf->Cell(50,10,'Score',1,1,'C');
$pdf->Cell(100,10,utf8_decode('Reconnaissance de tonalité'),1,0,'C');
$pdf->Cell(50,10,utf8_decode($reconnaissance_de_tonalite .' Hz'),1,1,'C');
$pdf->Cell(100,10,utf8_decode('Seuil de perception minimum'),1,0,'C');
$pdf->Cell(50,10,utf8_decode($seuil_min .' Hz'),1,1,'C');
$pdf->Cell(100,10,utf8_decode('Seuil de perception maximum'),1,0,'C');
$pdf->Cell(50,10,utf8_decode($seuil_max .' Hz'),1,1,'C');

$pdf->SetFont('Times','B',14); 
$pdf->SetTextColor(255,160,25);
$pdf->Cell(0,30,utf8_decode('Niveau de stress'),0,1);

$pdf->SetFont('Times','',12);
$pdf->SetTextColor(0,0,0); 
$pdf->Cell(100,10,'Test',1,0,'C');
$pdf->Cell(50,10,'Score',1,1,'C');
$pdf->Cell(100,10,utf8_decode('Température corporelle'),1,0,'C');
$pdf->Cell(50,10,utf8_decode($temperature .' °C'),1,1,'C');
$pdf->Cell(100,10,utf8_decode('Fréquence cardiaque'),1,0,'C');
$pdf->Cell(50,10,utf8_decode($freq .' Hz'),1,1,'C');

$pdf->SetFont('Times','B',14); 
$pdf->SetTextColor(255,160,25);
$pdf->Cell(0,30,utf8_decode('Réflexes'),0,1);

$pdf->SetFont('Times','',12); 
$pdf->SetTextColor(0,0,0);
$pdf->Cell(100,10,'Test',1,0,'C');
$pdf->Cell(50,10,'Score',1,1,'C');
$pdf->Cell(100,10,utf8_decode('Temps de réaction à un signal lumineux'),1,0,'C');
$pdf->Cell(50,10,utf8_decode($tps_lumiere .' ms'),1,1,'C');
$pdf->Cell(100,10,utf8_decode('Temps de réaction à un signal sonore'),1,0,'C');
$pdf->Cell(50,10,utf8_decode($tps_son .' ms'),1,1,'C');

$pdf->SetFont('Times','',10);
$pdf->Cell(0,20,utf8_decode('Ce document est personnel. Il pourra toutefois vous être demandé par votre employeur dans un cadre purement professionnel.'),0,1); 

ob_start(); 
$pdf->Output();
ob_end_flush();
?>