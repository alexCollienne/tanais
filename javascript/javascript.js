function valid(){

		var form = document.forms['formInscription'];
		var prenom = form.elements['prenom'].value;
		var nom = form.elements['nom'].value;
		var email = form.elements['mail_inscription'].value;
		var confirm_email = form.elements['confirm_mail_inscription'].value;
		var posAt = email.indexOf("@",1);
		var periodPos = email.indexOf(".", posAt);
		var invalidChar = "/'\":;,?!";
		var password = form.elements['mdp_inscription'].value;
		var confirm_password = form.elements['confirm_mdp_inscription'].value;
		var adresse = form.elements['adresse'].value;
		var cp = form.elements['cp'].value;
		var ville = form.elements['ville'].value;
		var pays = form.elements['pays'].value;
		var tel = form.elements['tel'].value;

		if( prenom == "" || nom == "" || email == "" || password == "" || adresse == "" || cp == "" || ville == "" || pays == "" || tel == ""){
			document.forms.onsubmit = alert('Certains champs sont vides. Il faut tous les remplir. Merci.');
			return false;
		}

		for(var k=0; k<invalidChar.length; k++){
			var badChar = invalidChar.charAt(k);
			if(email.indexOf(badChar) > -1){
				document.forms.onsubmit = alert('Des caractères invalides ont été trouvé dans votre adresse mail.');
				return false;
			}
		}

		if(posAt == -1 || email.indexOf("@",posAt+1) != -1 || periodPos == -1 || periodPos+3 > email.length || email == ""){
			document.forms.onsubmit = alert('L\'adresse mail n\'est pas correct.');
			return false;
		}

		if(email != confirm_email){
			document.forms.onsubmit = alert('Les champs mails ne correspondent pas.');
			return false;
		}

		if(password != confirm_password){
			document.forms.onsubmit = alert('Les mots de passes ne correspondent pas.');
			return false;
		}

		if(!isNaN(ville)){
			document.forms.onsubmit = alert('Le nom de la ville n\'est pas correct.');
			return false;
			}

		if(!isNaN(pays)){
			document.forms.onsubmit = alert('Le nom du pays n\'est pas correct.');
			return false;
			}

		var tel2 = replace(tel, '/', '');
		var tel2 = replace(tel, '.', '');

		if(isNaN(tel2)){
			document.forms.onsubmit = alert('Veuillez vérifier le format de numéro de téléphone.');
			return false;
		}

		return true;
}

function caddie_plus(){
	document.forms['caddie'].elements['caddiePerle'].value ++;
	}

function caddie_moins(){
	if(document.forms['caddie'].elements['caddiePerle'].value != 0){
	document.forms['caddie'].elements['caddiePerle'].value --;}
	}

function confirmation(){

		var form = document.forms['confirm'];
		var nom = form.elements['nom'].value;
		var adresse = form.elements['adresse'].value;
		var cp = form.elements['cp'].value;
		var mail = form.elements['mail'].value;
		var posAt = mail.indexOf("@",1);
		var periodPos = mail.indexOf(".", posAt);
		var invalidChar = "/'\":;,?!";
		var tel = form.elements['tel'].value;
		var pays = form.elements['pays'].value;
		var check = form.check.checked;
		var ogone = document.getElementById('ogone').checked;
		var cb = document.getElementById('cb').checked;
		var paypal = document.getElementById('paypal').checked;
		var cheque = document.getElementById('cheque').checked;
		var virement = document.getElementById('virement').checked;

		if(nom == "Nom et prénom" || nom == "" || adresse == "Rue, Numéro" || adresse == "" || cp == "Code postale, Ville" || cp == "" || mail == "" || tel == "Téléphone" || tel == "" || pays == ""){
			document.forms.onsubmit = alert('Certains champs sont vides ou érronés. Il faut tous les remplir ou les corriger. Merci.');
			return false;
		}

		for(var k=0; k<invalidChar.length; k++){
			var badChar = invalidChar.charAt(k);
			if(mail.indexOf(badChar) > -1){
				document.forms.onsubmit = alert('Des caractères invalides ont été trouvé dans votre adresse mail.');
				return false;
			}
		}

		if(posAt == -1 || mail.indexOf("@",posAt+1) != -1 || periodPos == -1 || periodPos+3 > mail.length || mail == ""){
			document.forms.onsubmit = alert('L\'adresse mail n\'est pas correct.');
			return false;
		}

		
		if(!check){
			document.forms.onsubmit = alert('Vous n\'avez pas lu et approuvé les conditions générales');
			return false;
		}

		if(!ogone && !paypal && !cheque && !virement && !cb){
			document.forms.onsubmit = alert('Vous devez choisir un mode de paiement.');
			return false;
		}

		return true;
}