<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 29
 * @since       PHPBoost 1.6 - 2008 09 13
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

require_once('../kernel/begin.php');

require_once('pages_defines.php');

//Titre de l'article à afficher en version imprimable
$encoded_title = retrieve(GET, 'title', '', TSTRING);
$pages_config = PagesConfig::load();

if (!empty($encoded_title)) //Si on connait son titre
{
	try {
		$page_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'pages', array('id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'redirect', 'contents'), 'WHERE encoded_title = :encoded_title', array('encoded_title' => $encoded_title));
	} catch (RowNotFoundException $e) {
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	$num_rows =!empty($page_infos['title']) ? 1 : 0;

	if ($page_infos['redirect'] > 0)
	{
		$redirect_title = stripslashes($page_infos['title']);
		$redirect_id = $page_infos['id'];
		try {
			$page_infos = PersistenceContext::get_querier()->select_single_row(PREFIX . 'pages', array('id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'redirect', 'contents'), 'WHERE id = :id', array('id' => $page_infos['redirect']));
		} catch (RowNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else
		$redirect_title = '';

	//Autorisation particulière ?
	$special_auth = !empty($page_infos['auth']);
	$array_auth = TextHelper::unserialize($page_infos['auth']);

	//Vérification de l'autorisation de voir la page
	if (($special_auth && !AppContext::get_current_user()->check_auth($array_auth, READ_PAGE)) || (!$special_auth && !AppContext::get_current_user()->check_auth($pages_config->get_authorizations(), READ_PAGE)))
		AppContext::get_response()->redirect(HOST . DIR . url('/pages/pages.php?error=e_auth'));
}

if (empty($page_infos['id']))
	exit;

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$template = new FileTemplate('framework/content/print.tpl');

$template->put_all(array(
	'PAGE_TITLE' => stripslashes($page_infos['title']) . ' - ' . GeneralConfig::load()->get_site_name(),
	'TITLE' => stripslashes($page_infos['title']),
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'CONTENT' => FormatingHelper::second_parse($page_infos['contents'])
));

$template->display();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>
