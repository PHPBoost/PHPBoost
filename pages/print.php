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
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
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

//Titre de l'article à afficher en version imprimable
$encoded_title = retrieve(GET, 'title', '', TSTRING);

//Requêtes préliminaires utiles par la suite
if( !empty($encoded_title) ) //Si on connait son titre
{
	$page_infos = $Sql->Query_array("pages", 'id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'nbr_com', 'redirect', 'contents', "WHERE encoded_title = '" . $encoded_title . "'", __LINE__, __FILE__);
	$num_rows =!empty($page_infos['title']) ? 1 : 0;
	if( $page_infos['redirect'] > 0 )
	{
		$redirect_title = $page_infos['title'];
		$redirect_id = $page_infos['id'];
		$page_infos = $Sql->Query_array("pages", 'id', 'title', 'auth', 'is_cat', 'id_cat', 'hits', 'count_hits', 'activ_com', 'nbr_com', 'redirect', 'contents', "WHERE id = '" . $page_infos['redirect'] . "'", __LINE__, __FILE__);
	}
	else
		$redirect_title = '';
}

if( empty($page_infos['id']) )
	exit;

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$template = new Template('print.tpl');

$template->assign_vars(array(
	'PAGE_TITLE' => $page_infos['title'] . ' - ' . $CONFIG['site_name'],
	'TITLE' => $page_infos['title'],
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'CONTENT' => second_parse($page_infos['contents'])
));

$template->parse();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>