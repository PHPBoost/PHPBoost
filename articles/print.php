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
require_once('articles_constants.php'); 

$Cache->load('articles');

//Titre de l'article à afficher en version imprimable
$idart = retrieve(GET, 'id', '', TSTRING);
if ($idart > 0)
{
	$articles = $Sql->query_array(DB_TABLE_ARTICLES, '*', "WHERE visible = 1 AND id = '" . $idart . "'", __LINE__, __FILE__);
	
	$idartcat = $articles['idcat'];
	
	//Niveau d'autorisation de la catégorie
	if (!isset($ARTICLES_CAT[$idartcat]) || !$User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ) || $ARTICLES_CAT[$idartcat]['visible'] == 0) 
		$Errorh->handler('e_auth', E_USER_REDIRECT);
	
	if (empty($articles['id']))
		$Errorh->handler('e_unexist_articles', E_USER_REDIRECT);
}

if (empty($articles['title']))
	exit;

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');

$template = new Template('framework/content/print.tpl');

$contents = preg_replace('`\[page\](.*)\[/page\]`', '<h2>$1</h2>', $articles['contents']);

$template->assign_vars(array(
	'PAGE_TITLE' => $articles['title'] . ' - ' . $CONFIG['site_name'],
	'TITLE' => $articles['title'],
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'CONTENT' => second_parse($contents)
));

$template->parse();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>