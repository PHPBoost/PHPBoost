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
if( defined('PHPBOOST') !== true)  
{		
	//On genère l'entête xml.
	header("Content-Type: text/xml");

	require_once('../includes/begin.php'); 
	require_once('../download/download_begin.php');
	require_once('../includes/header_no_display.php');
	
	$Template->Set_filenames(array('rss' => '../templates/' . $CONFIG['theme'] . '/rss.tpl'));

	$Template->Assign_vars(array(
		'DATE' => gmdate_format('date_format_tiny'),
		'TITLE_RSS' => $LANG['xml_download_desc'],
		'HOST' => HOST,	
		'DESC' => $LANG['xml_download_desc'],
		'LANG' => $LANG['xml_lang']
	));

	$Cache->Load_file('download');
	
	$result = $Sql->Query_while("SELECT a.id, a.idcat, a.title, a.contents, a.timestamp 
	FROM ".PREFIX."download a
	LEFT JOIN ".PREFIX."download_cat aa ON aa.id = a.idcat
	WHERE a.visible = 1
	ORDER BY a.timestamp DESC
	" . $Sql->Sql_limit(0, $CONFIG_DOWNLOAD['nbr_file_max']), __LINE__, __FILE__);
	while ($row = $Sql->Sql_fetch_assoc($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'];

		//On convertit les accents en entitées normales, puis on remplace les caractères non supportés en xml.
		$contents = htmlspecialchars(html_entity_decode(strip_tags($row['contents'])));

		$Template->Assign_block_vars('rss', array(
			'LINK' => HOST . DIR . '/download/download' . $rewrited_title,
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
	$Cache->Load_file('download');
	global $CONFIG_DOWNLOAD;
	
	$RSS_flux = array();
	$result = $Sql->Query_while("SELECT a.id, a.idcat, a.title, a.timestamp 
	FROM ".PREFIX."download a
	LEFT JOIN ".PREFIX."download_cat aa ON aa.id = a.idcat
	WHERE a.visible = 1 AND aa.visible
	ORDER BY a.timestamp DESC
	" . $Sql->Sql_limit(0, $CONFIG_DOWNLOAD['nbr_file_max']), __LINE__, __FILE__);
	while ($row = mysql_fetch_array($result))
	{ 
		//On encode l'url pour un éventuel rewriting, c'est une opération assez gourmande
		$rewrited_title = ($CONFIG['rewrite'] == 1) ? '-' . $row['idcat'] . '-' . $row['id'] .  '+' . url_encode_rewrite($row['title']) . '.php' : '.php?cat=' . $row['idcat'] . '&amp;id=' . $row['id'];
		$link = HOST . DIR . '/download/download' . $rewrited_title;
		
		//Variable utilisé pour la récupération du flux par le lecteur rss.
		$RSS_flux[] = array($row['title'], $link, gmdate_format('date_format_tiny', $row['timestamp']));
	}
	$Sql->Close($result);
}

?>