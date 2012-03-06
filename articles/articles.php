<?php
/*##################################################
 *                               articles.php
 *                            -------------------
 *   begin                : July 17, 2005
 *   copyright            : (C) 2005 Viarre Régis & (C) 2009 Maurel Nicolas
 *   email                : crowkait@phpboost.com
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
require_once('articles_begin.php');
require_once('../kernel/header.php');

$articles_categories = new ArticlesCats();
$page = retrieve(GET, 'p', 1, TUNSIGNED_INT);
$cat = retrieve(GET, 'cat', 0);
$idart = retrieve(GET, 'id', 0);	

if (!empty($idart) && isset($cat) )
{		
	$result = $Sql->query_while("SELECT a.contents, a.title, a.id, a.idcat, a.auth, a.timestamp, a.sources, a.start, a.visible, a.user_id, a.icon, a.nbr_com, m.login, m.level
		FROM " . DB_TABLE_ARTICLES . " a 
		LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = a.user_id
		WHERE a.id = '" . $idart . "'", __LINE__, __FILE__);
	$articles = $Sql->fetch_assoc($result);
	$Sql->query_close($result);
	
	$special_auth = (unserialize($articles['auth']) !== $ARTICLES_CAT[$articles['idcat']]['auth']) && ($articles['auth'] != '')  ? true : false;
	$articles['auth'] = $special_auth ? unserialize($articles['auth']) : $ARTICLES_CAT[$articles['idcat']]['auth'];

	//Niveau d'autorisation de la catégorie
	if (!isset($ARTICLES_CAT[$idartcat]) || (!$User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_READ) && !$User->check_auth($articles['auth'], AUTH_ARTICLES_READ))|| $ARTICLES_CAT[$idartcat]['visible'] == 0 ) 
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

	$tpl = new FileTemplate('articles/articles.tpl');
	
	//MAJ du compteur.
	$Sql->query_inject("UPDATE " . LOW_PRIORITY . " " . DB_TABLE_ARTICLES . " SET views = views + 1 WHERE id = " . $idart, __LINE__, __FILE__); 
	
	//On crée une pagination si il y plus d'une page.
	 
	$Pagination = new DeprecatedPagination();

	//Si l'article ne commence pas par une page on l'ajoute.
	if (substr(trim($articles['contents']), 0, 6) != '[page]')
	{
		$articles['contents'] = ' [page]&nbsp;[/page]' . $articles['contents'];
	}
	else
	{
		$articles['contents'] = ' ' . $articles['contents'];
	}
		
	//Pagination des articles.
	$array_contents = preg_split('`\[page\].+\[/page\](.*)`Us', $articles['contents'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

	//Récupération de la liste des pages.
	preg_match_all('`\[page\]([^[]+)\[/page\]`U', $articles['contents'], $array_page);
	$page_list = '<option value="1">' . $ARTICLES_LANG['select_page'] . '</option>';
	$page_list .= '<option value="1"></option>';
	$i = 1;
	
	// If tab pagination is active
	$c_tab=$articles['pagination_tab'];
	
	//Nombre de pages
	$nbr_page = count($array_page[1]);
	$nbr_page = !empty($nbr_page) ? $nbr_page : 1;
	$tpl->put_all( array(
		'TOTAL_TAB'=> count($array_page[1]),
		'C_CAROUSEL'=>count($array_page[1]) > 5 ? true : false
	));

	foreach ($array_page[1] as $page_name)
	{
		if($c_tab && $Pagination->display('articles' . url('.php?cat=' . $idartcat . '&amp;id='. $idart . '&amp;p=%d', '-' . $idartcat . '-'. $idart . '-%d+' . Url::encode_rewrite($articles['title']) . '.php'), $nbr_page, 'p', 1, 3, 11, NO_PREVIOUS_NEXT_LINKS) )
		{	
			$c_tab=true;
			$tpl->assign_block_vars('tab', array(
				'CONTENTS_TAB'=>isset($array_contents[$i]) ? FormatingHelper::second_parse($array_contents[$i]) : '',
				'ID_TAB' =>$i,
				'DISPLAY' => ( $i == 1 )? "yes" : "none",
				'STYLE' => ($i == 1)? 'style="margin-left: 1px"' : '',
				'ID_TAB_ACT' =>($i == 1)?'Active' : $i,
				'TOTAL_TAB'=> count($array_page[1]),
				'PAGE_NAME'=> Trim($page_name) == '' ? $LANG['page']." : ".$i : substr(Trim($page_name),0,8).( strlen($page_name) > 8 ? "..." : ''),
			));
		}
		else
			$c_tab=false;
			
		$selected = ($i == $page) ? 'selected="selected"' : '';
		$page_list .= '<option value="' . $i++ . '"' . $selected . '>' . $page_name . '</option>';
	}
	
	$array_sources = unserialize($articles['sources']);
	$i = 0;
	foreach ($array_sources as $sources)
	{	
		$tpl->assign_block_vars('sources', array(
			'I' => $i,
			'SOURCE' => stripslashes($sources['sources']),
			'URL' => substr($sources['url'],0,7) != "http://" ? "http://".$sources['url'] : $sources['url'],
			'INDENT'=> $i < (count($array_sources)-1) ? '-' : '',
		));
		$i++;
	}	
	
	//options
	$options=unserialize($articles['options']);
	
	$notation = new Notation();
	$notation->set_module_name('articles');
	$notation->set_notation_scale($articles_config->get_note_max());
	
	$tpl->put_all(array(
		'C_IS_ADMIN' => ($User->check_auth($ARTICLES_CAT[$idartcat]['auth'], AUTH_ARTICLES_WRITE)),
		'C_DISPLAY_ARTICLE' => true,
		'C_SOURCES'=> $i > 0 ? true : false,
		'C_TAB'=>$c_tab,
		'C_NOTE'=> $options['note'] ? true : false,
		'C_PRINT'=> $options['impr'] ? true : false,
		'C_COM'=> $options['com'] ? true : false,
		'C_AUTHOR'=> $options['author'] ? true : false,
		'C_DATE'=> $options['date'] ? true : false,
		'IDART' => $articles['id'],
		'IDCAT' => $idartcat,
		'NAME' => $articles['title'],
		'PSEUDO' => $Sql->query("SELECT login FROM " . DB_TABLE_MEMBER . " WHERE user_id = '" . $articles['user_id'] . "'", __LINE__, __FILE__),		
		'CONTENTS' => isset($array_contents[$page]) ? FormatingHelper::second_parse($array_contents[$page]) : '',
		'CAT' => $ARTICLES_CAT[$idartcat]['name'],
		'DATE' => gmdate_format('date_format_short', $articles['timestamp']),
		'PAGES_LIST' => $page_list,
		'PAGINATION_ARTICLES' => $Pagination->display('articles' . url('.php?cat=' . $idartcat . '&amp;id='. $idart . '&amp;p=%d', '-' . $idartcat . '-'. $idart . '-%d+' . Url::encode_rewrite($articles['title']) . '.php'), $nbr_page, 'p', 1, 3, 11, NO_PREVIOUS_NEXT_LINKS),
		'PAGE_NAME' => (isset($array_page[1][($page-1)]) && $array_page[1][($page-1)] != '&nbsp;') ? $array_page[1][($page-1)] : '',
		'PAGE_PREVIOUS_ARTICLES' => ($page > 1 && $page <= $nbr_page && $nbr_page > 1) ? '<a href="' . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=' . ($page - 1), 'articles-' . $idartcat . '-' . $idart . '-' . ($page - 1) . '+' . Url::encode_rewrite($articles['title']) . '.php') . '">&laquo; ' . $LANG['previous_page'] . '</a><br />' . $array_page[1][($page-2)] : '',
		'PAGE_NEXT_ARTICLES' => ($page > 0 && $page < $nbr_page && $nbr_page > 1) ? '<a href="' . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=' . ($page + 1), 'articles-' . $idartcat . '-' . $idart . '-' . ($page + 1) . '+' . Url::encode_rewrite($articles['title']) . '.php') . '">' . $LANG['next_page'] . ' &raquo;</a><br />' . $array_page[1][$page] : '',
		'COM' => '<a href="'. PATH_TO_ROOT .'/articles/articles' . url('.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;com=0', '-' . $idartcat . '-' . $idart . '+' . Url::encode_rewrite($articles['title']) . '.php?com=0') .'">'. CommentsService::get_number_and_lang_comments('articles', $idart) . '</a>',
		'KERNEL_NOTATION' => NotationService::display_active_image($notation),
		'L_DELETE' => $LANG['delete'],
		'L_EDIT' => $LANG['edit'],
		'L_SUBMIT' => $LANG['submit'],
		'L_WRITTEN' =>  $LANG['written_by'],
		'L_ON' => $LANG['on'],
		'L_DATE' => $LANG['date'],
		'L_COM' => $LANG['com'],
		'L_PRINTABLE_VERSION' => $LANG['printable_version'],
		'L_SOURCE'=>$ARTICLES_LANG['source'],
		'L_ALERT_DELETE_ARTICLE' => $ARTICLES_LANG['alert_delete_article'],
		'L_SUMMARY' => $ARTICLES_LANG['summary'],
		'L_ERASE'=>$LANG['erase'],
		'L_MAIL_ARTICLES'=>$ARTICLES_LANG['mail_articles'],
		'L_INFO'=>$LANG['info'],
		'U_PROFILE' => UserUrlBuilder::profile($articles['user_id'])->absolute(),
		'U_ARTICLES_LINK'=> url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart, 'articles-' . $idartcat . '-' . $idart .  Url::encode_rewrite($articles['title']) . '.php' . "'"),
		'U_ONCHANGE_ARTICLE' => "'" . url('articles.php?cat=' . $idartcat . '&amp;id=' . $idart . '&amp;p=\' + this.options[this.selectedIndex].value', 'articles-' . $idartcat . '-' . $idart . '-\'+ this.options[this.selectedIndex].value + \'+' . Url::encode_rewrite($articles['title']) . '.php' . "'"),
		'U_PRINT_ARTICLE' => url('print.php?id=' . $idart),
		'U_ARTICLES_EDIT' =>url('management.php?edit=' . $idart),
		'U_ARTICLES_DEL' =>url('management.php?del=' . $idart . '&amp;token=' . $Session->get_token()),
	));

	//Affichage commentaires.
	if (isset($_GET['com']))
	{
		$comments_topic = new CommentsTopic();
		$comments_topic->set_module_id('articles');
		$comments_topic->set_id_in_module($idart);
		$comments_topic->set_url(new Url('/articles/articles?cat=' . $idartcat . '&amp;id=' . $idart . '&com=0'));
		$Template->put_all(array(
			'COMMENTS' => CommentsService::display($comments_topic)->render()
		));
	}	
	$tpl->display();
}
else
{
	$modulesLoader = AppContext::get_extension_provider_service();
	$module_name = 'articles';
	$module = $modulesLoader->get_provider($module_name);
	if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
	{
		echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
	}
	elseif (!$no_alert_on_error) 
	{
		//TODO Gestion de la langue
		$controller = new UserErrorController(LangLoader::get_message('error', 'errors'), 
            'Le module <strong>' . $module_name . '</strong> n\'a pas de fonction get_home_page!', UserErrorController::FATAL);
        DispatchManager::redirect($controller);
	}
}
			
require_once('../kernel/footer.php'); 

?>