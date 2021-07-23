$('.header_navbar_toggle').click(function (e) {
    e.preventDefault();
    $('.header_navbar').toggleClass('is-open');
})


function disableInput(idInput, valeur) {
	var input = document.getElementById(idInput);
	input.enable = valeur;
	if (valeur) {
		input.style.background = "darkorange";
		input.disabled = false;
	BSsuppr(idInput);
	} else {
		input.style.background = "#CCC";
		input.disabled = true;
	BSajoute(idInput);
	}
}

function verifPassword() {
	 var password = document.getElementById('mdp');
	 var verifpassword = document.getElementById('cmdp');

	 var msgMdp = document.getElementById("msg_erreur_mdp");

	 if (password.value != verifpassword.value) {
	 	verifpassword.style.border = 'red 2px solid';
	 	msgMdp.innerHTML = "Les mots de passes ne correspondent pas";
	 	msgMdp.style.color = "red";
	 	msgMdp.style.display = "block";
	 } else {
	 	verifpassword.style.border = 'grey 1px solid';
	 	msgMdp.innerHTML = "";
	 	msgMdp.style.display = "none";
	 }
}

function verifPrenom() {
	var prenom = document.getElementById("prenom");
	var msgPrenom = document.getElementById("msg_erreur_prenom");

	if(prenom.value == "") {
		msgPrenom.innerHTML = "Veuillez inscrire votre prénom";
		msgPrenom.style.color = "black";
		msgPrenom.style.display = "block";
		prenom.focus();
	} else {
		if(!prenom.value.match(/^[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s\-]+$/)){
			prenom.style.color = "red";
			msgPrenom.innerHTML = "Ton prénom contient des caractères interdits";
			msgPrenom.style.color = "red";
			msgPrenom.style.display = "block";
			prenom.focus();
		} else {
			prenom.style.color = "black";
			msgPrenom.innerHTML = "";
			msgPrenom.style.display = "none";
		}
	}
}

function verifPrenomModif() {
	var prenom = document.getElementById("prenom_mod");
	var msgPrenom = document.getElementById("msg_erreur_prenom");

	if(prenom.value == "") {
		msgPrenom.innerHTML = "Veuillez inscrire votre prénom";
		msgPrenom.style.color = "black";
		msgPrenom.style.display = "block";
		prenom.focus();
	} else {
		if(!prenom.value.match(/^[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s\-]+$/)){
			prenom.style.color = "red";
			msgPrenom.innerHTML = "Ton prénom contient des caractères interdits";
			msgPrenom.style.color = "red";
			msgPrenom.style.display = "block";
			prenom.focus();
		} else {
			prenom.style.color = "black";
			msgPrenom.innerHTML = "";
			msgPrenom.style.display = "none";
		}
	}
}

function verifNom() {
	var nom = document.getElementById("nom");
	var msgNom = document.getElementById("msg_erreur_nom");

	if(nom.value == "") {
		msgNom.innerHTML = "Veuillez inscrire votre nom";
		msgNom.style.color = "black";
		msgNom.style.display = "block";
		nom.focus();
	} else {
		if(!nom.value.match(/^[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s\-]+$/)){
			nom.style.color = "red";
			msgNom.innerHTML = "Ton nom contient des caractères interdits";
			msgNom.style.color = "red";
			msgNom.style.display = "block";
			nom.focus();
		} else {
			nom.style.color = "black";
			msgNom.innerHTML = "";
			msgNom.style.display = "none";
		}
	}
}

function verifNomModif() {
	var nom = document.getElementById("nom_mod");
	var msgNom = document.getElementById("msg_erreur_nom");

	if(nom.value == "") {
		msgNom.innerHTML = "Veuillez inscrire votre nom";
		msgNom.style.color = "black";
		msgNom.style.display = "block";
		nom.focus();
	} else {
		if(!nom.value.match(/^[a-zA-Zàáâãäåçèéêëìíîïðòóôõöùúûüýÿ\s\-]+$/)){
			nom.style.color = "red";
			msgNom.innerHTML = "Ton nom contient des caractères interdits";
			msgNom.style.color = "red";
			msgNom.style.display = "block";
			nom.focus();
		} else {
			nom.style.color = "black";
			msgNom.innerHTML = "";
			msgNom.style.display = "none";
		}
	}
}

function verifTel() {
	var tel = document.getElementById("tel");
	var msgTel = document.getElementById("msg_erreur_tel");

	if(tel.value == "") {
		msgTel.style.color = "black";
		msgTel.style.display = "none";
	} else {
		if(!tel.value.match(/^[0-9\s]+$/)){
			tel.style.color = "red";
			msgTel.innerHTML = "Ton numéro de téléphone contient des caractères interdits";
			msgTel.style.color = "red";
			msgTel.style.display = "block";
			tel.focus();
		} else {
			tel.style.color = "black";
			msgTel.innerHTML = "";
			msgTel.style.display = "none";
		}
	}
}

function verifTelModif() {
	var tel = document.getElementById("tel_mod");
	var msgTel = document.getElementById("msg_erreur_tel");

	if(tel.value == "") {
		msgTel.style.color = "black";
		msgTel.style.display = "none";
	} else {
		if(!tel.value.match(/^[0-9\s]+$/)){
			tel.style.color = "red";
			msgTel.innerHTML = "Ton numéro de téléphone contient des caractères interdits";
			msgTel.style.color = "red";
			msgTel.style.display = "block";
			tel.focus();
		} else {
			tel.style.color = "black";
			msgTel.innerHTML = "";
			msgTel.style.display = "none";
		}
	}
}

function over(variable) {
	document.getElementById(variable).style.display = "block";
}

function out(variable) {
	document.getElementById(variable).style.display = "none"; 
}

function colorActive(variable) {
	document.getElementById(variable).style.background = "gold";
	document.getElementById(variable).style.border = "gold 2px solid";
}

function colorDisabled(variable) {
	document.getElementById(variable).style.background = "darkorange";
	document.getElementById(variable).style.border = "gold 2px solid";
}

function RedirectionJavascript(){
  document.location.href="DiagHealth_forum.php"; 
}

function confirmSuppression() {
	var msg_confirmation = confirm("Supprimer cette demande ?");
	if (msg_confirmation) {
		return true;
	}
	else {
		return false;
	}
}

function confirmAjout() {
	var msg_confirmation = confirm("Accepter cette demande ?");
	if (msg_confirmation) {
		return true;
	}
	else {
		return false;
	}
}

function confirmDemande() {
	var msg_confirmation = confirm("Etes-vous sûr de demander à faire un test ? Une notification sera envoyée à un gestionnaire de votre centre de tests.");
	if (msg_confirmation) {
		return true;
	}
	else {
		return false;
	}
}

function confirmDelete() {
	var msg_confirmation = confirm("Voulez-vous vraiment supprimer ce membre ?");
	if (msg_confirmation) {
		return true;
	}
	else {
		return false;
	}
}


