(function($) {

	var L_MESSAGE = "";
	var L_BUTTON = "";

	//Cas 1 : NOTRACKING : Utilisation uniquement des cookies techniques (login / préférences / stats anonymes)
	//Cas 2 : TRACKING : Utilisation d'outils types google-analytics, publicité, liens RS
	if ( U_TRACKING == false) {
		L_MESSAGE = L_COOKIEBAR_NOTRACKING; 
		L_BUTTON = '<button type="button" class="cookiebar-button cookiebar-button-understand" id="cookiebar-button-allowed">' + L_COOKIEBAR_UNDERSTAND + '</button>';
	}
	else {
		L_MESSAGE = L_COOKIEBAR_TRACKING; 
		L_BUTTON = '<button type="button" class="cookiebar-button cookiebar-button-allowed" id="cookiebar-button-allowed">' + L_COOKIEBAR_ALLOWED + '</button><button type="button" class="cookiebar-button cookiebar-button-declined" id="cookiebar-button-declined">' + L_COOKIEBAR_DECLINED + '</button>';
	}
	
	if (getCookie('pbt-cookiebar-viewed') == "")
	{
		$('body').prepend('<div class="cookiebar-container" id="cookiebar-container"><div class="cookiebar-content" id="cookiebar-content">' + L_MESSAGE + '</div><div class="cookiebar-actions">' + L_BUTTON + ' <span class="cookiebar-more"><a href="' + L_COOKIEBAR_MORE_LINK + '">' + L_COOKIEBAR_MORE + '</a></span></div></div>')
		
		//Si cookie accepté on sauvegarde le choix
		$('#cookiebar-button-allowed').click(function(e){
			e.preventDefault();
			$('#cookiebar-container').fadeOut();
			sendCookie('pbt-cookiebar-viewed', 1, 12);
			sendCookie('pbt-cookiebar-choice', 1, 12);
		});

		//Si cookie refusé on sauvegarde le choix
		$('#cookiebar-button-declined').click(function(e){
			e.preventDefault();
			$('#cookiebar-container').fadeOut();
			sendCookie('pbt-cookiebar-viewed', 1, 12);
			sendCookie('pbt-cookiebar-choice', -1, 12);
		});
	}
})(jQuery);