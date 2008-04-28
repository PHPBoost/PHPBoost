<?php
/*##################################################
 *                               rss.php
 *                            -------------------
 *   begin                : May 20 2007
 *   copyright          : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
 ***************************************************************************
 Gestion des flux rss.
***************************************************************************/

//Affichage du contenu au format rss 2.0.

if( !defined('PHPBOOST') )  
{
	//On genère l'entête xml.
	header("Content-Type: text/xml");
	
	require_once('../kernel/begin.php');
	$cat = !empty($_GET['cat']) ? numeric($_GET['cat']) : 0;
	//Chargement de la langue du module.
	load_module_lang('wiki');
	define('TITLE', $LANG['wiki_rss']);
	require_once('../kernel/header_no_display.php');
	
	$Cache->Load_file('wiki');	
	
	$Template->Set_filenames(array('rss'=> 'rss.tpl'));

	if( $cat > 0 && array_key_exists($cat, $_WIKI_CATS) )//Catégorie
	{
		$desc = sprintf($LANG['wiki_rss_cat'], html_entity_decode($_WIKI_CATS[$cat]['name']));
		$where = "AND a.id_cat = '" . $cat . "'";
	}
	else //Sinon derniers messages
	{
		$desc = sprintf($LANG['wiki_rss_last_articles'], (!empty($_WIKI_CONFIG['wiki_name']) ? html_entity_decode($_WIKI_CONFIG['wiki_name']) : $LANG['wiki']));
		$where = "";
	}
	
	$Template->Assign_vars(array(  
		'DATE' => gmdate_format('date_format_tiny'),
		'TITLE_RSS' => (!empty($_WIKI_CONFIG['wiki_name']) ? html_entity_decode($_WIKI_CONFIG['wiki_name']) : $LANG['wiki']),
		'HOST' => HOST,	
		'DESC' => $desc,
		'LANG' => $LANG['xml_lang']	
	));
	
	$result = $Sql->Query_while("SELECT a.title, a.encoded_title, c.content, c.timestamp 
	FROM ".PREFIX."wiki_articles a
	LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id_contents
	WHERE a.redirect = 0 " . $where . "
	ORDER BY c.timestamp DESC 
	" . $Sql->Sql_limit(0, 10), __LINE__, __FILE__);
	while ($row = $Sql->Sql_fetch_assoc($result))
	{ 
		$rewrited_title = ($CONFIG['rewrite'] == 0) ? 'wiki.php?title=' . $row['encoded_title'] : $row['encoded_title'];
		$link = HOST . DIR . '/wiki/' . $rewrited_title;
		
		//On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
		$contents = htmlspecialchars(html_entity_decode(strip_tags($row['content'])));
		$contents = preg_replace('`[\n\r]{1}[\-]{2,5}[\s]+(.+)[\s]+[\-]{2,5}(<br \/>|[\n\r]){1}`U', "\n" . '$1' . "\n", "\n" . $contents . "\n");
		$Template->Assign_block_vars('rss', array(
			'LINK' => $link,
			'TITLE' => htmlspecialchars(html_entity_decode($row['title'])),
			'DESC' => ( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents,
			'DATE' => gmdate_format('r', $row['timestamp']) //Conversion de la date au format rss 2.0.
		));
	}
	$Sql->Close($result);
	$Sql->Sql_close();
	
	$Template->Pparse('rss');
}
else //Récupération directe du contenu.
{
	global $Sql, $LANG, $CONFIG;	
	
	$RSS_flux = array();
	$result = $Sql->Query_while("SELECT a.title, a.encoded_title, c.content, c.timestamp 
	FROM ".PREFIX."wiki_articles a
	LEFT JOIN ".PREFIX."wiki_contents c ON c.id_contents = a.id_contents
	WHERE a.redirect = 0
	ORDER BY c.timestamp DESC 
	" . $Sql->Sql_limit(0, 10), __LINE__, __FILE__);
	while ($row = $Sql->Sql_fetch_assoc($result))
	{ 
		$rewrited_title = ($CONFIG['rewrite'] == 0) ? 'wiki.php?title=' . $row['encoded_title'] : $row['encoded_title'];
		$link = HOST . DIR . '/wiki/' . $rewrited_title;
		
		//Variable utilisé pour la récupération du flux par le lecteur rss.
		$RSS_flux[] = array($row['title'], $link, gmdate_format('date_format_tiny', $row['timestamp']));
	}
	$Sql->Close($result);
}

?>