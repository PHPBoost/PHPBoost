<?php
/*##################################################
 *                                alert.php
 *                            -------------------
 *   begin                : August 7, 2006
 *   copyright          : (C) 2006 Viarre Régis / Sautel Benoît
 *   email                : crowkait@phpboost.com / ben.popeye@phpboost.com
 *
 *  
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/begin.php'); 
include_once('../forum/lang/' . $CONFIG['lang'] . '/forum_' . $CONFIG['lang'] . '.php'); //Chargement de la langue du module.
include_once('../forum/forum_auth.php');

$alert = !empty($_GET['id']) ? numeric($_GET['id']) : '';	
$alert_post = !empty($_POST['id']) ? numeric($_POST['id']) : '';	

$topic_id = (!empty($alert)) ? $alert : $alert_post;
$topic = $sql->query_array('forum_topics', 'idcat', 'title', "WHERE id = '" . $topic_id . "'", __LINE__, __FILE__);

$cat_name = !empty($CAT_FORUM[$topic['idcat']]['name']) ? $CAT_FORUM[$topic['idcat']]['name'] : '';
$topic_name = !empty($topic['title']) ? $topic['title'] : '';
$speed_bar = array(
	$CONFIG_FORUM['forum_name'] => 'index.php' . SID,
	$cat_name => 'forum' . transid('.php?id=' . $topic['idcat'], '-' . $topic['idcat'] . '+' . url_encode_rewrite($cat_name) . '.php'),
	$topic['title'] => 'topic' . transid('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php'),
	$LANG['alert_topic'] => ''
);
define('TITLE', $LANG['title_forum'] . ' - ' . $LANG['alert_topic']);
define('ALTERNATIVE_CSS', 'forum');
include_once('../includes/header.php');

//Accès au module.
if( !$groups->check_auth($SECURE_MODULE['forum'], ACCESS_MODULE) )
{
	$errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}

if( empty($alert) && empty($alert_post) || empty($topic['idcat']) ) 
{
	header('location:' . HOST . DIR . '/forum/index' . transid('.php'));  
	exit;
}

if( !$session->check_auth($session->data, 0) ) //Si c'est un invité
{
    $errorh->error_handler('e_auth', E_USER_REDIRECT); 
	exit;
}  
	
$template->set_filenames(array(
	'forum_alert' => '../templates/' . $CONFIG['theme'] . '/forum/forum_alert.tpl'
));
		
//On fait un formulaire d'alerte
if( !empty($alert) && empty($alert_post) )
{
	//On vérifie qu'une alerte sur le même sujet n'ait pas été postée
	$nbr_alert = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_alerts WHERE idtopic = '" . $alert ."'", __LINE__, __FILE__);
	if( empty($nbr_alert) ) //On affiche le formulaire
	{
		$template->assign_vars(array(
			'L_ALERT' => $LANG['alert_topic'],
			'L_ALERT_EXPLAIN' => $LANG['alert_modo_explain'],
			'L_ALERT_TITLE' => $LANG['alert_title'],
			'L_ALERT_CONTENTS' => $LANG['alert_contents'],
			'L_REQUIRE_TEXT' => $LANG['require_text'],
			'L_REQUIRE_TITLE' => $LANG['require_title']
		));
			
		include_once('../includes/bbcode.php');	
		$template->assign_var_from_handle('BBCODE', 'bbcode');
		
		$template->assign_block_vars('alert_form', array(
			'TITLE' => $topic_name,
			'ID_ALERT' => $alert,
		));	
	}
	else //Une alerte a déjà été postée
	{
		$template->assign_vars(array(
			'L_ALERT' => $LANG['alert_topic'],
			'L_BACK_TOPIC' => $LANG['alert_back'],
			'URL_TOPIC' => 'topic' . transid('.php?id=' . $alert, '-' . $alert . '-' . url_encode_rewrite($topic_name) . '.php')
		));	
		
		$template->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
}

//Si on enregistre une alerte
if( !empty($alert_post) )
{
	$template->assign_vars(array(
		'L_ALERT' => $LANG['alert_topic'],
		'L_BACK_TOPIC' => $LANG['alert_back'],
		'URL_TOPIC' => 'topic' . transid('.php?id=' . $alert_post, '-' . $alert_post . '-' . url_encode_rewrite($topic_name) . '.php')
	));
	
	//On vérifie qu'une alerte sur le même sujet n'ait pas été postée
	$nbr_alert = $sql->query("SELECT COUNT(*) FROM ".PREFIX."forum_alerts WHERE idtopic = '" . $alert_post ."'", __LINE__, __FILE__);
	if( empty($nbr_alert) ) //On enregistre
	{
		$alert_title = !empty($_POST['title']) ? securit($_POST['title']) : '';
		$alert_contents = !empty($_POST['contents']) ? parse($_POST['contents']) : '';
		
		$idcat = $sql->query("SELECT idcat FROM ".PREFIX."forum_topics WHERE id = '" . $alert_post . "'", __LINE__, __FILE__);
		$sql->query_inject("INSERT INTO ".PREFIX."forum_alerts (idcat,idtopic,title,contents,user_id,status,idmodo,timestamp) VALUES('" . $idcat . "', '" . $alert_post . "', '" . $alert_title . "', '" . $alert_contents . "', '" . $session->data['user_id'] . "', 0, 0, '" . time() . "')", __LINE__, __FILE__);
		
		$template->assign_block_vars('alert_confirm', array(
			'MSG' => str_replace('%title', $topic_name, $LANG['alert_success'])
		));
			
		## Mise à jour du fichier de mise en cache ##
		$cache->generate_module_file('forum');
	}
	else //Une alerte a déjà été postée
	{
		$template->assign_block_vars('alert_confirm', array(
			'MSG' => $LANG['alert_topic_already_done']
		));
	}
	
}
$template->pparse('forum_alert');	

include('../includes/footer.php');

?>