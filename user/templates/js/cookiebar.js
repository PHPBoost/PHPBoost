$(function(){
	showCookieBar();
});

function showCookieBar() {

	var L_BUTTON = "";

	//Cas 1 : NOTRACKING : Utilisation uniquement des cookies techniques (login / préférences / stats anonymes)
	//Cas 2 : TRACKING : Utilisation d'outils types google-analytics, publicité, liens RS
	if ( COOKIEBAR_TRACKING_MODE == 'notracking') {
		L_BUTTON = '<button type="button" class="cookiebar-button cookiebar-button-understand" id="cookiebar-button-allowed">' + L_COOKIEBAR_UNDERSTAND + '</button>';
	}
	else {
		L_BUTTON = '<button type="button" class="cookiebar-button cookiebar-button-allowed" id="cookiebar-button-allowed">' + L_COOKIEBAR_ALLOWED + '</button><button type="button" class="cookiebar-button cookiebar-button-declined" id="cookiebar-button-declined">' + L_COOKIEBAR_DECLINED + '</button>';
	}

	if (getCookie('pbt-cookiebar-viewed') == "")
	{
		//On ajoute la cookiebar uniquement si elle n'existe pas. On cherche si une id #cookiebar-container existe.
		if ($('#cookiebar-container').length < 1 )
		{
			$('body').prepend('<div class="cookiebar-container" id="cookiebar-container"><div class="cookiebar-content" id="cookiebar-content">' + L_COOKIEBAR_CONTENT + '</div><div class="cookiebar-actions">' + L_BUTTON + ' <span class="cookiebar-more"><a href="' + U_COOKIEBAR_ABOUTCOOKIE + '" title="' + L_COOKIEBAR_MORE_TITLE + '">' + L_COOKIEBAR_MORE + '</a></span></div></div>')
		}

		//Si cookie accepté on sauvegarde le choix
		$('#cookiebar-button-allowed').on('click', function(e){
			e.preventDefault();
			$('#cookiebar-container').fadeOut();
			sendCookie('pbt-cookiebar-viewed', 1, COOKIEBAR_DURATION);
			sendCookie('pbt-cookiebar-choice', 1, COOKIEBAR_DURATION);
			showChangeChoice();
		});

		//Si cookie refusé on sauvegarde le choix
		$('#cookiebar-button-declined')..on('click', function(e){
			e.preventDefault();
			$('#cookiebar-container').fadeOut();
			sendCookie('pbt-cookiebar-viewed', 1, COOKIEBAR_DURATION);
			sendCookie('pbt-cookiebar-choice', -1, COOKIEBAR_DURATION);
			showChangeChoice();
		});
	} else {
		showChangeChoice();
	}
}

function showChangeChoice() {
	if (getCookie('pbt-cookiebar-viewed') == 1)
	{
		$('#cookiebar-change-choice').append('<button type="button" onclick="changeCookieBarChoice();" title="' + L_COOKIEBAR_CHANGE_CHOICE + '" id="cookiebar-change-choice-link" class="cookiebar-change-choice-link">' + L_COOKIEBAR_CHANGE_CHOICE + '</button>');
	}
}

//Supprime les cookies de la cookiebar.
function changeCookieBarChoice() {
	eraseCookie('pbt-cookiebar-viewed');
	eraseCookie('pbt-cookiebar-choice');
	$('#cookiebar-change-choice-link').remove();
	showCookieBar();
}