<?php
/***************************************************************************
 *                               rss.php
 *                            -------------------
 *   begin                : September 09, 2005
 *   copyright          : (C) 2005 CrowkaiT
 *   email                : crowkait@phpboost.com
 *
 *  
 *
 ***************************************************************************
 Gestion des flux rss.
***************************************************************************/

//Affichage du contenu au format rss 2.0.
if( defined('PHP_BOOST') !== true)  
{		
	//On genère l'entête xml.
	header("Content-Type: text/xml");

	require_once('../includes/begin.php'); 
	require_once('../news/news_begin.php'); 
	require_once('../includes/header_no_display.php');

	$Cache->Load_file('news'); //Requête des configuration générales (new), $CONFIG_NEWS variable globale.

	$Template->Set_filenames(array('rss' => '../templates/' . $CONFIG['theme'] . '/rss.tpl'));

	$Template->Assign_vars(array( 
		'DATE' => gmdate_format('date_format_tiny'),
		'TITLE_RSS' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
		'HOST' => HOST,	
		'DESC' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
		'LANG' => $LANG['xml_lang']	
	));

	$result = $Sql->Query_while("SELECT id, title, contents, timestamp 
	FROM ".PREFIX."news 
	WHERE visible = 1 
	ORDER BY timestamp DESC 
	" . $Sql->Sql_limit(0, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
	while ($row = $Sql->Sql_fetch_assoc($result))
	{ 
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?id=' . $row['id'];
		$link = HOST . DIR . '/news/news' . $rewrited_title;
		
		//On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
		$contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
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
	global $Sql, $LANG, $CONFIG, $Cache;	
	$Cache->Load_file('news');
	global $CONFIG_NEWS;
	
	$RSS_flux = array();
	$result = $Sql->Query_while("SELECT id, title, timestamp 
	FROM ".PREFIX."news 
	WHERE visible = 1 
	ORDER BY timestamp DESC 
	" . $Sql->Sql_limit(0, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
	while ($row = mysql_fetch_array($result))
	{ 
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?id=' . $row['id'];
		$link = HOST . DIR . '/news/news' . $rewrited_title;
		
		//Variable utilisé pour la récupération du flux par le lecteur rss.
		$RSS_flux[] = array($row['title'], $link, gmdate_format('date_format_tiny', $row['timestamp']));
	}
	$Sql->Close($result);
}

?>