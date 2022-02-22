/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 07 01
 * @since       PHPBoost 5.0 - 2016 09 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

$(function(){
	showCookieBar();
});

function showCookieBar() {

	var L_BUTTON = "";

	//Case 1 : NO TRACKING : Only technical cookies (login / preferences / anonymous stats)
	//Case 2 : TRACKING : cookies for tracking systems (google-analytics, advertising, social networks links, and so on...)
	if ( COOKIEBAR_TRACKING_MODE == 'notracking') {
		L_BUTTON = '<button type="button" class="button submit grouped-element" id="cookiebar-button-allowed">' + L_COOKIEBAR_UNDERSTAND + '</button>';
	}
	else {
		L_BUTTON = '<button type="submit" class="button bgc success grouped-element" id="cookiebar-button-allowed">' + L_COOKIEBAR_ALLOWED + '</button><button type="submit" class="button bgc error grouped-element" id="cookiebar-button-declined">' + L_COOKIEBAR_DECLINED + '</button>';
	}

	if (getCookie('pbt-cookiebar-viewed') == "")
	{
		// Add cookies bar if it doesn't exist
		if ($('#cookiebar-container').length < 1 )
		{
			$('body').prepend('<div class="cookiebar-container" id="cookiebar-container"><div class="cookiebar-content" id="cookiebar-content">' + L_COOKIEBAR_CONTENT + '</div><div class="cookiebar-actions grouped-inputs">' + L_BUTTON + '<a class="button small grouped-element offload cookiebar-question" href="' + U_COOKIEBAR_ABOUTCOOKIE + '" aria-label="' + L_COOKIEBAR_MORE + '"><i class="fa fa-question"></i></a></div></div>');
		}

		// If cookie is accepted then save this choice
		$('#cookiebar-button-allowed').on('click', function(e){
			e.preventDefault();
			$('#cookiebar-container').fadeOut();
			sendCookie('pbt-cookiebar-viewed', 1, COOKIEBAR_DURATION);
			sendCookie('pbt-cookiebar-choice', 1, COOKIEBAR_DURATION);
			showChangeChoice();
		});

		// If cookie is not accepted then save this choice
		$('#cookiebar-button-declined').on('click', function(e){
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
		$('#cookiebar-change-choice').append('<button type="button" onclick="changeCookieBarChoice();" id="cookiebar-change-choice-link" class="cookiebar-change-choice-link">' + L_COOKIEBAR_CHANGE_CHOICE + '</button>');
	}
}

// Delete cookies from cookiebar.
function changeCookieBarChoice() {
	eraseCookie('pbt-cookiebar-viewed');
	eraseCookie('pbt-cookiebar-choice');
	$('#cookiebar-change-choice-link').remove();
	showCookieBar();
}
