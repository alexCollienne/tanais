function Lien() {
	var i = document.Choix.Liste.selectedIndex;
	if (i == 0) return;
	url = document.Choix.Liste.options[i].value;
	parent.location.href = url;
}