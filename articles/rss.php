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
if( defined('PHPBOOST') !== true)  
{		
	//On genère l'entête xml.
	header("Content-Type: text/xml");

	require_once('../includes/begin.php'); 
	require_once('../articles/articles_begin.php');
	require_once('../includes/header_no_display.php');
	
	$Template->Set_filenames(array('rss' => '../templates/' . $CONFIG['theme'] . '/rss.tpl'));

	$Template->Assign_vars(array(
		'DATE' => gmdate_format('date_format_rss'),
		'TITLE_RSS' => $LANG['xml_articles_desc'],
		'HOST' => HOST,	
		'DESC' => $LANG['xml_articles_desc'],
		'LANG' => $LANG['xml_lang']
	));
	
	//Catégories non autorisées.
	$unauth_cats_sql = array();
	foreach($CAT_ARTICLES as $idcat => $key)
	{
		if( $CAT_ARTICLES[$idcat]['aprob'] == 1 )
		{
			if( !$Member->Check_auth($CAT_ARTICLES[$idcat]['auth'], READ_CAT_ARTICLES) )
			{
				$clause_level = !empty($g_idcat) ? ($CAT_ARTICLES[$idcat]['level'] == ($CAT_ARTICLES[$g_idcat]['level'] + 1)) : ($CAT_ARTICLES[$idcat]['level'] == 0);
				if( $clause_level )
					$unauth_cats_sql[] = $idcat;
			}
		}
	}
	$clause_unauth_cats = (count($unauth_cats_sql) > 0) ? " AND gc.id NOT IN (" . implode(', ', $unauth_cats_sql) . ")" : '';
	
	$result = $Sql->Query_while("SELECT a.id, a.idcat, a.title, a.contents, a.timestamp 
	FROM ".PREFIX."articles a
	LEFT JOIN ".PREFIX."articles_cats ac ON ac.id = a.idcat
	WHERE a.visible = 1 AND ac.aprob = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%'
	ORDER BY a.timestamp DESC
	" . $Sql->Sql_limit(0, $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
	while ($row = $Sql->Sql_fetch_assoc($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'];

		//On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
		$contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));

		$Template->Assign_block_vars('rss', array(
			'LINK' => HOST . DIR . '/articles/articles' . $rewrited_title,
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
	$Cache->Load_file('articles');
	global $CONFIG_ARTICLES;
	
	$RSS_flux = array();
	$result = $Sql->Query_while("SELECT a.id, a.idcat, a.title, a.timestamp 
	FROM ".PREFIX."articles a
	LEFT JOIN ".PREFIX."articles_cats ac ON ac.id = a.idcat
	WHERE a.visible = 1 AND ac.aprob = 1 AND ac.auth LIKE '%s:3:\"r-1\";i:1;%'
	ORDER BY a.timestamp DESC
	" . $Sql->Sql_limit(0, $CONFIG_ARTICLES['nbr_articles_max']), __LINE__, __FILE__);
	while ($row = mysql_fetch_array($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'];
		$link = HOST . DIR . '/articles/articles' . $rewrited_title;
		
		//Variable utilisé pour la récupération du flux par le lecteur rss.
		$RSS_flux[] = array($row['title'], $link, gmdate_format('date_format_tiny', $row['timestamp']));
	}
	$Sql->Close($result);
}

?>