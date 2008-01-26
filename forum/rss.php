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
	require_once('../forum/forum_begin.php'); //Chargement de la langue du module.
	define('TITLE', $LANG['title_forum']);
	require_once('../includes/header_no_display.php');

	$template->set_filenames(array('rss' => '../templates/' . $CONFIG['theme'] . '/rss.tpl'));

	$template->assign_vars(array(
		'DATE' => gmdate_format('date_format_tiny'),
		'TITLE_RSS' => $LANG['xml_forum_desc'],
		'HOST' => HOST,	
		'DESC' => $LANG['xml_forum_desc'],
		'LANG' => $LANG['xml_lang']
	));

	$cache->load_file('forum');
	
	$cat_get = !empty($_GET['cat']) ? numeric($_GET['cat']) : 0;
	$clause_cat = !empty($cat_get) ? " AND c.id_left >= '" . $CAT_FORUM[$cat_get]['id_left'] . "' AND id_right <= '" . $CAT_FORUM[$cat_get]['id_right'] . "'" : '';
	
	$result = $sql->query_while("SELECT t.id, t.title, t.last_timestamp, msg.contents
	FROM ".PREFIX."forum_topics t
	LEFT JOIN ".PREFIX."forum_cats c ON c.id = t.idcat
	LEFT JOIN ".PREFIX."forum_msg msg ON msg.id = t.last_msg_id
	WHERE (c.auth LIKE '%s:3:\"r-1\";i:1;%' OR c.auth LIKE '%s:3:\"r-1\";i:3;%') AND c.level != 0 AND c.aprob = 1 " . $clause_cat . "
	ORDER BY t.last_timestamp DESC
	" . $sql->sql_limit(0, 10), __LINE__, __FILE__);
	while ($row = $sql->sql_fetch_assoc($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['id'] . '+' . url_encode_rewrite($row['title']) . '.php' : '.php?id=' . $row['id'];
		//On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
		$contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));

		$template->assign_block_vars('rss', array(
			'LINK' => HOST . DIR . '/forum/topic' . $rewrited_title,
			'TITLE' => htmlspecialchars(html_entity_decode($row['title'])),
			'DESC' => ( strlen($contents) > 500 ) ?  substr($contents, 0, 500) . '...[' . $LANG['next'] . ']' : $contents,
			'DATE' => gmdate_format('r', $row['last_timestamp']) //Conversion de la date au format rss 2.0.
		));
	}
	$sql->close($result);
	$sql->sql_close();

	$template->pparse('rss');	
}
else //Récupération directe du contenu.
{
	global $sql, $LANG, $CONFIG, $session;
	
	$RSS_flux = array();
	$result = $sql->query_while("SELECT t.id, t.title, t.last_timestamp, t.last_msg_id
	FROM ".PREFIX."forum_topics t
	LEFT JOIN ".PREFIX."forum_cats c ON c.id = t.idcat
	WHERE (c.auth LIKE '%s:3:\"r-1\";i:1;%' OR c.auth LIKE '%s:3:\"r-1\";i:3;%') AND c.level !=0 AND c.aprob = 1 " . $cat_get . "
	GROUP BY t.id
	ORDER BY t.last_timestamp DESC
	" . $sql->sql_limit(0, 10), __LINE__, __FILE__);
	while ($row = mysql_fetch_array($result))
	{ 
		//Variable utilisé pour la récupération du flux par le lecteur rss.
		$RSS_flux[] = array($row['title'], HOST . DIR . '/forum/topic' . transid('.php?id=' . $row['id'] . '&amp;idm=' . $row['last_msg_id'], '-' . $row['id'] . '-0-' . $row['last_msg_id'] . '+' . url_encode_rewrite($row['title'])  . '.php') . '#m' .  $row['last_msg_id'], gmdate_format('date_format_tiny', $row['last_timestamp']));
	}
	$sql->close($result);
}

?>