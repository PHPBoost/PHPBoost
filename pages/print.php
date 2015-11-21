<?php
/*##################################################
*                                 print.php
*                            -------------------
*   begin                : September 13, 2008
*   copyright            : (C) 2008 Sautel Benoit
*   email                : ben.popeye@phpboost.com
*
*
 ###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
 ###################################################*/

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
	$array_auth = unserialize($page_infos['auth']);

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
