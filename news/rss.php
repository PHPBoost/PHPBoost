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

	include_once('../includes/begin.php'); 
	//Chargement de la langue du module.
	@include_once('../news/lang/' . $CONFIG['lang'] . '/news_' . $CONFIG['lang'] . '.php');
	define('TITLE', $LANG['title_news']);
	include_once('../includes/header_no_display.php');

	$cache->load_file('news'); //Requête des configuration générales (new), $CONFIG_NEWS variable globale.

	$template->set_filenames(array('rss' => '../templates/' . $CONFIG['theme'] . '/rss.tpl'));

	$template->assign_vars(array(
		'VERSION' => 'PHPBoost ' . $CONFIG['version'],  
		'DATE' => date($LANG['date_format'] . ' \a\t H:m:s', time()),
		'TITLE_RSS' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
		'HOST' => HOST,	
		'DESC' => $LANG['xml_news_desc'] . ' ' . $CONFIG['server_name'],
		'LANG' => $LANG['xml_lang']	
	));

	$result = $sql->query_while("SELECT id, title, contents, timestamp 
	FROM ".PREFIX."news 
	WHERE visible = 1 
	ORDER BY timestamp DESC 
	" . $sql->sql_limit(0, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
	while ($row = $sql->sql_fetch_assoc($result))
	{ 
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?id=' . $row['id'];
		$link = HOST . DIR . '/news/news' . $rewrited_title;
		
		//On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
		$contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));
		$template->assign_block_vars('rss', array(
			'LINK' => $link,
			'TITLE' => htmlspecialchars(html_entity_decode($row['title'])),
			'DESC' => ( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents,
			'DATE' => date('r', $row['timestamp']) //Conversion de la date au format rss 2.0.
		));
	}
	$sql->close($result);
	$sql->sql_close();

	$template->pparse('rss');	
}
else //Récupération directe du contenu.
{
	global $sql, $LANG, $CONFIG, $cache;	
	$cache->load_file('news');
	global $CONFIG_NEWS;
	
	$RSS_flux = array();
	$result = $sql->query_while("SELECT id, title, timestamp 
	FROM ".PREFIX."news 
	WHERE visible = 1 
	ORDER BY timestamp DESC 
	" . $sql->sql_limit(0, $CONFIG_NEWS['pagination_news']), __LINE__, __FILE__);
	while ($row = mysql_fetch_array($result))
	{ 
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-0-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?id=' . $row['id'];
		$link = HOST . DIR . '/news/news' . $rewrited_title;
		
		//Variable utilisé pour la récupération du flux par le lecteur rss.
		$RSS_flux[] = array($row['title'], $link, date($LANG['date_format_rss'], $row['timestamp']));
	}
	$sql->close($result);
}

?>