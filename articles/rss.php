<?php
/***************************************************************************
 *                               rss.php
 *                            -------------------
 *   begin                : September 09, 2005
 *   copyright          : (C) 2005 Viarre Régis
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
	@include_once('../articles/lang/' . $userdata['user_lang'] . '/articles_' . $userdata['user_lang'] . '.php');
	define('TITLE', $LANG['title_articles']);
	include_once('../includes/header_no_display.php');
	
	$template->set_filenames(array('rss' => '../templates/' . $CONFIG['theme'] . '/rss.tpl'));

	$template->assign_vars(array(
		'VERSION' => 'PHPBoost ' . $CONFIG['version'],  
		'DATE' => date($LANG['date_format'] . ' \a\t H:m:s', time()),
		'TITLE_RSS' => $LANG['xml_articles_desc'],
		'HOST' => HOST,	
		'DESC' => $LANG['xml_articles_desc'],
		'LANG' => $LANG['xml_lang']
	));

	$cache->load_file('articles');
	define('READ_CAT_ARTICLES', 0x01);
	
	//Catégories non autorisées.
	$unauth_cats_sql = array();
	foreach($CAT_ARTICLES as $idcat => $key)
	{
		if( $CAT_ARTICLES[$idcat]['aprob'] == 1 )
		{
			if( !$groups->check_auth($CAT_ARTICLES[$idcat]['auth'], READ_CAT_ARTICLES) )
			{
				$clause_level = !empty($g_idcat) ? ($CAT_ARTICLES[$idcat]['level'] == ($CAT_ARTICLES[$g_idcat]['level'] + 1)) : ($CAT_ARTICLES[$idcat]['level'] == 0);
				if( $clause_level )
					$unauth_cats_sql[] = $idcat;
			}
		}
	}
	$clause_unauth_cats = (count($unauth_cats_sql) > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';
	
	$result = $sql->query_while("SELECT a.id, a.idcat, a.title, a.contents, a.timestamp 
	FROM ".PREFIX."articles AS a
	LEFT JOIN ".PREFIX."articles_cats AS ac ON ac.id = a.idcat
	WHERE a.visible = 1 AND ac.aprob = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%'
	ORDER BY a.timestamp DESC
	" . $sql->sql_limit(0, $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
	while ($row = $sql->sql_fetch_assoc($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?idcat=' . $row['idcat'] . '&amp;id=' . $row['id'];

		//On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
		$contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));

		$template->assign_block_vars('rss', array(
			'LINK' => HOST . DIR . '/articles/articles' . $rewrited_title,
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
	$cache->load_file('articles');
	global $CONFIG_ARTICLES;
	
	$RSS_flux = array();
	$result = $sql->query_while("SELECT a.id, a.idcat, a.title, a.timestamp 
	FROM ".PREFIX."articles AS a
	LEFT JOIN ".PREFIX."articles_cats AS ac ON ac.id = a.idcat
	WHERE a.visible = 1 AND ac.aprob = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%'
	ORDER BY a.timestamp DESC
	" . $sql->sql_limit(0, $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
	while ($row = mysql_fetch_array($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?idcat=' . $row['idcat'] . '&amp;id=' . $row['id'];
		$link = HOST . DIR . '/articles/articles' . $rewrited_title;
		
		//Variable utilisé pour la récupération du flux par le lecteur rss.
		$RSS_flux[] = array($row['title'], $link, date($LANG['date_format_rss'], $row['timestamp']));
	}
	$sql->close($result);
}

?>