<?php
/*##################################################
 *                               admin_download_management.php
 *                            -------------------
 *   begin                : July 10, 2005
 *   copyright          : (C) 2005 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/admin_begin.php');
include_once('../download/lang/' . $CONFIG['lang'] . '/download_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
define('TITLE', $LANG['administration']);
include_once('../includes/admin_header.php');

//On recupère les variables.
$id = isset($_GET['id']) ? numeric($_GET['id']) : '' ;
$id_post = isset($_POST['id']) ? numeric($_POST['id']) : '' ;
$del = !empty($_GET['delete']) ? true : false;

if( !empty($id) && !$del )
{
	$template->set_filenames(array(
		'admin_download_management' => '../templates/' . $CONFIG['theme'] . '/download/admin_download_management.tpl'
	 ));

	$row = $sql->query_array('download', '*', "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	$idcat = $row['idcat'];

	$template->assign_vars(array(
		'TITLE' => $row['title'],
		'COMPT' => !empty($row['compt']) ? $row['compt'] : 0,
		'USER_ID' => $row['user_id'],
		'CONTENTS' => unparse($row['contents']),
		'URL' => $row['url'],
		'SIZE' => $row['size'],
		'UNIT_SIZE' => $LANG['unit_megabytes'],
		'DATE' => date($LANG['date_format'], $row['timestamp']),
		'L_REQUIRE_DESC' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_EDIT_FILE' => $LANG['edit_file'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_DOWNLOAD_DATE' => $LANG['download_date'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_UNTIL' => $LANG['until'],
		'L_DESC' => $LANG['description'],
		'L_DOWNLOAD' => $LANG['download'],
		'L_SIZE' => $LANG['size'],
		'L_URL' => $LANG['url'],
		'L_TITLE' => $LANG['title'],
		'L_CATEGORY' => $LANG['category'],
		'L_REQUIRE' => $LANG['require'],
		'L_DOWNLOAD_ADD' => $LANG['download_add'],
		'L_DOWNLOAD_MANAGEMENT' => $LANG['download_management'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $LANG['download_config'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_PREVIEW' => $LANG['preview']
	));
	
	$template->assign_block_vars('download', array(
		'TITLE' => $row['title'],
		'IDURL' => $row['id'],
		'CONTENTS' => unparse($row['contents']),
		'CURRENT_DATE' => date($LANG['date_format'], $row['timestamp']),
		'DAY_RELEASE_S' => !empty($row['start']) ? date('d', $row['start']) : '',
		'MONTH_RELEASE_S' => !empty($row['start']) ? date('m', $row['start']) : '',
		'YEAR_RELEASE_S' => !empty($row['start']) ? date('Y', $row['start']) : '',
		'DAY_RELEASE_E' => !empty($row['end']) ? date('d', $row['end']) : '',
		'MONTH_RELEASE_E' => !empty($row['end']) ? date('m', $row['end']) : '',
		'YEAR_RELEASE_E' => !empty($row['end']) ? date('Y', $row['end']) : '',
		'DAY_DATE' => !empty($row['timestamp']) ? date('d', $row['timestamp']) : '',
		'MONTH_DATE' => !empty($row['timestamp']) ? date('m', $row['timestamp']) : '',
		'YEAR_DATE' => !empty($row['timestamp']) ? date('Y', $row['timestamp']) : '',
		'USER_ID' => $row['user_id'],
		'VISIBLE_WAITING' => (($row['visible'] == 2 || !empty($row['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($row['visible'] == 1 && empty($row['end'])) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($row['visible'] == 0) ? 'checked="checked"' : ''),
		'START' => ((!empty($row['start'])) ? date($LANG['date_format'], $row['start']) : ''),
		'END' => ((!empty($row['end'])) ? date($LANG['date_format'], $row['end']) : ''),
		'HOUR' => date('H', $row['timestamp']),
		'MIN' => date('i', $row['timestamp']),
		'DATE' => date($LANG['date_format'], $row['timestamp'])	
	));
	
	//Catégories.		
	$i = 0;
	$result = $sql->query_while("SELECT id, name FROM ".PREFIX."download_cat", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
		$template->assign_block_vars('download.select', array(
			'CAT' => '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>'
		));
		$i++;
	}	
	$sql->close($result);
	
	//Gestion erreur.
	$get_error = !empty($_GET['error']) ? securit($_GET['error']) : '';
	if( $get_error == 'incomplete' )
		$errorh->error_handler($LANG['e_incomplete'], E_USER_NOTICE, NO_LINE_ERROR, NO_FILE_ERROR, 'download.');
	elseif( $i == 0 ) //Aucune catégorie => alerte.	 
		$errorh->error_handler($LANG['require_cat_create'], E_USER_WARNING, NO_LINE_ERROR, NO_FILE_ERROR, 'download.');	
		
	include_once('../includes/bbcode.php');
	$template->assign_var_from_handle('BBCODE', 'bbcode');

	$template->pparse('admin_download_management'); 
}	
elseif( !empty($_POST['previs']) && !empty($id_post) )
{
	$template->set_filenames(array(
		'admin_download_management' => '../templates/' . $CONFIG['theme'] . '/download/admin_download_management.tpl'
	 ));
	 
	$title = !empty($_POST['title']) ? trim($_POST['title']) : '';
	$compt = isset($_POST['compt']) ? numeric($_POST['compt']) : 0;
	$contents = !empty($_POST['contents']) ? trim($_POST['contents']) : '';
	$user_id = !empty($_POST['user_id']) ? numeric($_POST['user_id']) : 0;
	$url = !empty($_POST['url']) ? trim($_POST['url']) : '';
	$size = !empty($_POST['size']) ? numeric($_POST['size'], 'float') : 0;
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : 0;
	$current_date = !empty($_POST['current_date']) ? trim($_POST['current_date']) : '';
	$start = !empty($_POST['start']) ? trim($_POST['start']) : 0;
	$end = !empty($_POST['end']) ? trim($_POST['end']) : 0;
	$hour = !empty($_POST['hour']) ? trim($_POST['hour']) : 0;
	$min = !empty($_POST['min']) ? trim($_POST['min']) : 0;	
	$get_visible = !empty($_POST['visible']) ? numeric($_POST['visible']) : 0;
	
	$cat = $sql->query("SELECT name FROM ".PREFIX."download_cat WHERE id = '" . $idcat . "'", __LINE__, __FILE__);
		
	$start_timestamp = strtotimestamp($start, $LANG['date_format']);
	$end_timestamp = strtotimestamp($end, $LANG['date_format']);
	$current_date_timestamp = strtotimestamp($current_date, $LANG['date_format']);
	
	$visible = 1;		
	if( $get_visible == 2 )
	{		
		if( $start_timestamp > time() )
			$visible = 2;
		else
			$start = '';
	
		if( $end_timestamp > time() && $end_timestamp > $start_timestamp )
			$visible = 2;
		else
			$end = '';
	}
	else
	{
		$start = '';
		$end = '';
	}
	
	$template->assign_block_vars('download', array(
		'IDURL' => $id_post,
		'TITLE' => stripslashes($title),
		'CONTENTS' => stripslashes($contents),
		'USER_ID' => $user_id,
		'CURRENT_DATE' => $current_date,
		'START' => ((!empty($start) && $visible == 2) ? $start : ''),
		'END' => ((!empty($end) && $visible == 2) ? $end : ''),
		'HOUR' => $hour,
		'MIN' => $min,
		'DAY_RELEASE_S' => !empty($start_timestamp) ? date('d', $start_timestamp) : '',
		'MONTH_RELEASE_S' => !empty($start_timestamp) ? date('m', $start_timestamp) : '',
		'YEAR_RELEASE_S' => !empty($start_timestamp) ? date('Y', $start_timestamp) : '',
		'DAY_RELEASE_E' => !empty($end_timestamp) ? date('d', $end_timestamp) : '',
		'MONTH_RELEASE_E' => !empty($end_timestamp) ? date('m', $end_timestamp) : '',
		'YEAR_RELEASE_E' => !empty($end_timestamp) ? date('Y', $end_timestamp) : '',
		'DAY_DATE' => !empty($current_date_timestamp) ? date('d', $current_date_timestamp) : '',
		'MONTH_DATE' => !empty($current_date_timestamp) ? date('m', $current_date_timestamp) : '',
		'YEAR_DATE' => !empty($current_date_timestamp) ? date('Y', $current_date_timestamp) : '',
		'VISIBLE_WAITING' => (($visible == 2) ? 'checked="checked"' : ''),
		'VISIBLE_ENABLED' => (($visible == 1) ? 'checked="checked"' : ''),
		'VISIBLE_UNAPROB' => (($visible == 0) ? 'checked="checked"' : '')
	));
	
	$pseudo = $sql->query("SELECT login FROM ".PREFIX."member WHERE user_id = '" . $user_id . "'", __LINE__, __FILE__);
	//Prévisualisation
	$template->assign_block_vars('download.preview', array(
		'USER_ID' => $user_id,
		'TITLE' => stripslashes($title),
		'CONTENTS' => second_parse(stripslashes(parse($contents))),
		'PSEUDO' => $pseudo,
		'DATE' => date($LANG['date_format'], time()),
		'IDURL' => $id_post,
		'IDCAT' => $idcat,
		'CAT' => $cat,
		'COMPT' => $compt,
		'MODULE_DATA_PATH' => $template->module_data_path('download')
	));
	
	$template->assign_vars(array(
		'LANG' => $CONFIG['lang'],
		'ID' => $id,
		'TITLE' => stripslashes($title),
		'CONTENTS' => stripslashes($contents),
		'URL' => stripslashes($url),
		'SIZE' => $size,
		'UNIT_SIZE' => $LANG['unit_megabytes'],
		'COMPT' => $compt,
		'L_REQUIRE_DESC' => $LANG['require_text'],
		'L_REQUIRE_TITLE' => $LANG['require_title'],
		'L_REQUIRE_URL' => $LANG['require_url'],
		'L_REQUIRE_CAT' => $LANG['require_cat'],
		'L_EDIT_FILE' => $LANG['edit_file'],
		'L_DESC' => $LANG['description'],
		'L_DATE' => $LANG['date'],
		'L_COM' => $LANG['com'],
		'L_VIEWS' => $LANG['views'],
		'L_NOTE' => $LANG['note'],
		'L_CATEGORY' => $LANG['categorie'],
		'L_DOWNLOAD_ADD' => $LANG['download_add'],
		'L_DOWNLOAD_MANAGEMENT' => $LANG['download_management'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $LANG['download_config'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
		'L_DOWNLOAD_DATE' => $LANG['download_date'],
		'L_RELEASE_DATE' => $LANG['release_date'],
		'L_IMMEDIATE' => $LANG['immediate'],
		'L_UNAPROB' => $LANG['unaprob'],
		'L_UNTIL' => $LANG['until'],
		'L_DESC' => $LANG['description'],
		'L_DOWNLOAD' => $LANG['download'],
		'L_SIZE' => $LANG['size'],
		'L_URL' => $LANG['url'],
		'L_TITLE' => $LANG['title'],
		'L_CATEGORY' => $LANG['category'],
		'L_REQUIRE' => $LANG['require'],
		'L_UPDATE' => $LANG['update'],
		'L_RESET' => $LANG['reset'],
		'L_PREVIEW' => $LANG['preview']
	));
	
	//Catégories.
	$i = 0;	
	$result = $sql->query_while("SELECT id, name FROM ".PREFIX."download_cat", __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		$selected = ($row['id'] == $idcat) ? 'selected="selected"' : '';
		$template->assign_block_vars('download.select', array(
			'CAT' => '<option value="' . $row['id'] . '" ' . $selected . '>' . $row['name'] . '</option>'
		));
		$i++;
	}
	$sql->close($result);
	
	if( $i == 0 ) //Aucune catégorie => alerte.	 
		$errorh->error_handler($LANG['require_cat_create'], E_USER_WARNING, NO_LINE_ERROR, NO_FILE_ERROR, 'download.');
		
	include_once('../includes/bbcode.php');
	$template->assign_var_from_handle('BBCODE', 'bbcode');

	$template->pparse('admin_download_management'); 
}	
elseif( !empty($_POST['valid']) && !empty($id_post) ) //inject
{
	$title = !empty($_POST['title']) ? securit($_POST['title']) : '';	
	$compt = isset($_POST['compt']) ? numeric($_POST['compt']) : '0';
	$contents = !empty($_POST['contents']) ? parse($_POST['contents']) : '';
	$url = !empty($_POST['url']) ? securit($_POST['url']) : '';
	$size = isset($_POST['size']) ? numeric($_POST['size'], 'float') : 0;
	$idcat = !empty($_POST['idcat']) ? numeric($_POST['idcat']) : '';
	$current_date = !empty($_POST['current_date']) ? trim($_POST['current_date']) : '';
	$start = !empty($_POST['start']) ? trim($_POST['start']) : 0;
	$end = !empty($_POST['end']) ? trim($_POST['end']) : 0;
	$hour = !empty($_POST['hour']) ? trim($_POST['hour']) : 0;
	$min = !empty($_POST['min']) ? trim($_POST['min']) : 0;
	$get_visible = !empty($_POST['visible']) ? numeric($_POST['visible']) : 0;
	
	//On met à jour la config de base du sondage
	if( !empty($title) && !empty($contents) && !empty($url) && !empty($idcat) )
	{
		$start_timestamp = strtotimestamp($start, $LANG['date_format']);
		$end_timestamp = strtotimestamp($end, $LANG['date_format']);
		
		$visible = 1;		
		if( $get_visible == 2 )
		{	
			if( $start_timestamp > time() )
				$visible = 2;
			elseif( $start_timestamp == 0 )
				$visible = 1;
			else //Date inférieur à celle courante => inutile.
				$start_timestamp = 0;

			if( $end_timestamp > time() && $end_timestamp > $start_timestamp && $start_timestamp != 0 )
				$visible = 2;
			elseif( $start_timestamp != 0 ) //Date inférieur à celle courante => inutile.
				$end_timestamp = 0;
		}
		elseif( $get_visible == 1 )
		{	
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		else
		{	
			$visible = 0;
			$start_timestamp = 0;
			$end_timestamp = 0;
		}
		
		$timestamp = strtotimestamp($current_date, $LANG['date_format']);
		if( $timestamp > 0 )
		{
			//Ajout des heures et minutes
			$timestamp += ($hour * 3600) + ($min * 60);
			$timestamp = ' , timestamp = \'' . $timestamp . '\'';
		}
		else
			$timestamp = ' , timestamp = \'' . time() . '\'';
		
		$sql->query_inject("UPDATE ".PREFIX."download SET title = '" . $title . "', contents = '" . $contents . "', url = '" . $url . "', size = '" . $size . "', idcat = '" . $idcat . "', visible = '" . $visible . "', start = '" .  $start_timestamp . "', end = '" . $end_timestamp . "'" . $timestamp . ", compt = '" . $compt . "' WHERE id = '" . $id_post . "'", __LINE__, __FILE__);	
		
		include_once('../includes/rss.class.php'); //Flux rss regénéré!
		$rss = new Rss('download/rss.php');
		$rss->cache_path('../cache/');
		$rss->generate_file('javascript', 'rss_download');
		$rss->generate_file('php', 'rss2_download');
		
		header('location:' . HOST . SCRIPT);
		exit;
	}
	else
	{
		header('location:' . HOST . DIR . '/download/admin_download.php?id= ' . $id_post . '&error=incomplete#errorh');
		exit;
	}
}
elseif( $del && !empty($id) ) //Suppression du fichier.
{
	//On supprime dans la bdd.
	$sql->query_inject("DELETE FROM ".PREFIX."download WHERE id = '" . $id . "'", __LINE__, __FILE__);	

	//On supprimes les éventuels commentaires associés.
	$sql->query_inject("DELETE FROM ".PREFIX."com WHERE idprov = '" . $id . "' AND script = 'download'", __LINE__, __FILE__);
	
	include_once('../includes/rss.class.php'); //Flux rss regénéré!
	$rss = new Rss('download/rss.php');
	$rss->cache_path('../cache/');
	$rss->generate_file('javascript', 'rss_download');
	$rss->generate_file('php', 'rss2_download');
	
	header('location:' . HOST . SCRIPT);
	exit;
}
else			
{	
	$template->set_filenames(array(
		'admin_download_management' => '../templates/' . $CONFIG['theme'] . '/download/admin_download_management.tpl'
	 ));

	$nbr_dl = $sql->count_table('download', __LINE__, __FILE__);
	
	//On crée une pagination si le nombre de fichier est trop important.
	include_once('../includes/pagination.class.php'); 
	$pagination = new Pagination();
		
	$template->assign_vars(array(			
		'THEME' => $CONFIG['theme'],
		'LANG' => $CONFIG['lang'],
		'PAGINATION' => $pagination->show_pagin('admin_download.php?p=%d', $nbr_dl, 'p', 25, 3),
		'L_DEL_ENTRY' => $LANG['del_entry'],
		'L_DOWNLOAD_ADD' => $LANG['download_add'],
		'L_DOWNLOAD_MANAGEMENT' => $LANG['download_management'],
		'L_DOWNLOAD_CAT' => $LANG['cat_management'],
		'L_DOWNLOAD_CONFIG' => $LANG['download_config'],
		'L_CATEGORY' => $LANG['category'],
		'L_PSEUDO' => $LANG['pseudo'],
		'L_SIZE' => $LANG['size'],
		'L_TITLE' => $LANG['title'],
		'L_APROB' => $LANG['aprob'],
		'L_UPDATE' => $LANG['update'],
		'L_DELETE' => $LANG['delete'],
		'L_DATE' => $LANG['date']
	));

	$template->assign_block_vars('list', array(
	));
	
	$result = $sql->query_while("SELECT d.id, d.idcat, d.title, d.timestamp, d.visible, d.start, d.end, d.size, dc.name, m.login 
	FROM ".PREFIX."download AS d 
	LEFT JOIN ".PREFIX."download_cat AS dc ON dc.id = d.idcat
	LEFT JOIN ".PREFIX."member AS m ON d.user_id = m.user_id
	ORDER BY d.timestamp DESC 
	" . $sql->sql_limit($pagination->first_msg(25, 'p'), 25), __LINE__, __FILE__);
	while( $row = $sql->sql_fetch_assoc($result) )
	{
		if( $row['visible'] == 2 )
			$aprob = $LANG['waiting'];			
		elseif( $row['visible'] == 1 )
			$aprob = $LANG['yes'];
		else
			$aprob = $LANG['no'];
			
		//On reccourci le lien si il est trop long pour éviter de déformer l'administration.
		$title = html_entity_decode($row['title']);
		$title = strlen($title) > 45 ? substr($title, 0, 45) . '...' : $title;

		$visible = '';
		if( $row['start'] > 0 )
			$visible .= date($LANG['date_format'], $row['start']);
		if( $row['end'] > 0 && $row['start'] > 0 )
			$visible .= ' ' . strtolower($LANG['until']) . ' ' . date($LANG['date_format'], $row['end']);
		elseif( $row['end'] > 0 )
			$visible .= $LANG['until'] . ' ' . date($LANG['date_format'], $row['end']);
		
		$template->assign_block_vars('list.download', array(
			'TITLE' => $title,
			'IDCAT' => $row['idcat'],
			'ID' => $row['id'],
			'CAT' => $row['name'],
			'PSEUDO' => !empty($row['login']) ? $row['login'] : $LANG['guest'],		
			'DATE' => date($LANG['date_format'], $row['timestamp']),
			'SIZE' => $row['size'] . ' ' . (($row['size'] >= 1) ? $LANG['unit_megabytes'] : $LANG['unit_kilobytes']),
			'APROBATION' => $aprob,
			'VISIBLE' => ((!empty($visible)) ? '(' . $visible . ')' : '')			
		));	
	
	}
	$sql->close($result);
	
	include_once('../includes/bbcode.php');
	$template->assign_var_from_handle('BBCODE', 'bbcode');
	
	$template->pparse('admin_download_management'); 
}

include_once('../includes/admin_footer.php');

?>