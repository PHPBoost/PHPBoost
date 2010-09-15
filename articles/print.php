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
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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
require_once('articles_begin.php');
$Cache->load('articles');

//Article title to display in print version
$idart = retrieve(GET, 'id', '', TSTRING);
if ($idart > 0)
{
	$articles = $Sql->query_array(DB_TABLE_ARTICLES, '*', "WHERE visible = 1 AND id = '" . $idart . "'", __LINE__, __FILE__);

	$idartcat = $articles['idcat'];

	//category level authorization
	if (!isset($ARTICLES_CAT[$idartcat]) || !$User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ) || $ARTICLES_CAT[$idartcat]['visible'] == 0)
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}

	if (empty($articles['id']))
	{
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            $LANG['e_unexist_articles']);
        DispatchManager::redirect($controller);
	}
}

if (empty($articles['title']))
	AppContext::get_response()->redirect(url('articles.php'));

$array_sources = unserialize($articles['sources']);
$sources='';

if(count($array_sources) != 0)
{
	$i = 0;
	$sources = "<br /><br /><hr /><div><b> sources : </b>";
	foreach ($array_sources as $value)
	{	
		$url=substr($value['url'],0,7) != "http://" ? "http://".$value['url'] : $value['url'];
		$sources .='<a href="'.$url.'">'.$value['sources'].'</a>&nbsp;'.(($i < (count($array_sources)-1) )? '- ' : ''.'');
		$i++;
	}	
	$sources .="</div>";
}

require_once(PATH_TO_ROOT . '/kernel/header_no_display.php');
$tpl = new FileTemplate('framework/content/print.tpl');

$contents = preg_replace('`\[page\](.*)\[/page\]`', '<h2>$1</h2>', $articles['contents']);

$tpl->assign_vars(array(
	'PAGE_TITLE' => $articles['title'] . ' - ' . GeneralConfig::load()->get_site_name(),
	'TITLE' => $articles['title'],
	'L_XML_LANGUAGE' => $LANG['xml_lang'],
	'CONTENT' => FormatingHelper::second_parse($contents).$sources,
));

$tpl->display();

require_once(PATH_TO_ROOT . '/kernel/footer_no_display.php');
?>