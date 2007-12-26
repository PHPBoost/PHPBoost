<?php
/***************************************************************************
 *                               rss.php
 *                            -------------------
 *   begin                : April 02, 2007
 *   copyright          : (C) 2007 Viarre Régis
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
	@include_once('../download/lang/' . $userdata['user_lang'] . '/download_' . $userdata['user_lang'] . '.php');
	define('TITLE', $LANG['title_download']);
	include_once('../includes/header_no_display.php');
	
	$template->set_filenames(array('rss' => '../templates/' . $CONFIG['theme'] . '/rss.tpl'));

	$template->assign_vars(array(
		'VERSION' => 'PHPBoost ' . $CONFIG['version'],  
		'DATE' => date($LANG['date_format'] . ' \a\t H:m:s', time()),
		'TITLE_RSS' => $LANG['xml_download_desc'],
		'HOST' => HOST,	
		'DESC' => $LANG['xml_download_desc'],
		'LANG' => $LANG['xml_lang']
	));

	$cache->load_file('download');
	
	$result = $sql->query_while("SELECT a.id, a.idcat, a.title, a.contents, a.timestamp 
	FROM ".PREFIX."download AS a
	LEFT JOIN ".PREFIX."download_cat AS aa ON aa.id = a.idcat
	WHERE a.visible = 1 AND aa.aprob = 1 AND aa.secure = -1
	ORDER BY a.timestamp DESC
	" . $sql->sql_limit(0, $CONFIG_DOWNLOAD['nbr_file_max']), __LINE__, __FILE__);
	while ($row = $sql->sql_fetch_assoc($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?idcat=' . $row['idcat'] . '&amp;id=' . $row['id'];

		//On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
		$contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));

		$template->assign_block_vars('rss', array(
			'LINK' => HOST . DIR . '/download/download' . $rewrited_title,
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
	$cache->load_file('download');
	global $CONFIG_DOWNLOAD;
	
	$RSS_flux = array();
	$result = $sql->query_while("SELECT a.id, a.idcat, a.title, a.timestamp 
	FROM ".PREFIX."download AS a
	LEFT JOIN ".PREFIX."download_cat AS aa ON aa.id = a.idcat
	WHERE a.visible = 1 AND aa.aprob = 1 AND aa.secure = -1
	ORDER BY a.timestamp DESC
	" . $sql->sql_limit(0, $CONFIG_DOWNLOAD['nbr_file_max']), __LINE__, __FILE__);
	while ($row = mysql_fetch_array($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?idcat=' . $row['idcat'] . '&amp;id=' . $row['id'];
		$link = HOST . DIR . '/download/download' . $rewrited_title;
		
		//Variable utilisé pour la récupération du flux par le lecteur rss.
		$RSS_flux[] = array($row['title'], $link, date($LANG['date_format_rss'], $row['timestamp']));
	}
	$sql->close($result);
}

?>