<?php
header('Content-type: text/html; charset=iso-8859-15');

include_once('../includes/begin.php');
define('TITLE', 'Ajax forum');
include_once('../includes/header_no_display.php');
include_once('../forum/forum_auth.php');

if( !empty($_GET['warning_moderation_panel'])  || !empty($_GET['punish_moderation_panel']) ) //Recherche d'un membre
{
	$login = !empty($_POST['login']) ? securit(utf8_decode($_POST['login'])) : '';
	$login = str_replace('*', '%', $login);
	if( !empty($login) )
	{
		$i = 0;
		$result = $sql->query_while("SELECT user_id, login FROM ".PREFIX."member WHERE login LIKE '" . $login . "%'", __LINE__, __FILE__);
		while( $row = $sql->sql_fetch_assoc($result) )
		{
			if( !empty($_GET['warning_moderation_panel']) )
				echo '<a href="moderation_forum.php?action=warning&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />';
			elseif( !empty($_GET['punish_moderation_panel']) )
				echo '<a href="moderation_forum.php?action=punish&amp;id=' . $row['user_id'] . '">' . $row['login'] . '</a><br />';
			
			$i++;
		}
		
		if( $i == 0 ) //Aucun membre trouvé.
			echo $LANG['no_result'];
	}
	else
	{
		echo $LANG['no_result'];
	}
	
	$sql->sql_close(); //Fermeture de mysql*/
}
elseif( !empty($_GET['del']) ) //Suppression d'un message.
{
	//Variable GET.
	$id_get = !empty($_GET['id']) ? numeric($_GET['id']) : '';

	$msg = $sql->query_array('forum_msg', 'user_id', 'idtopic', 'contents', "WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
	$msg['user_id'] = (int)$msg['user_id'];
					
	//On cherche l'id du topic dans lequel on supprime pour pouvoir choper l'id de la catégorie
	$idcat_topic = $sql->query("SELECT idcat FROM ".PREFIX."forum_topics WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
	if( $groups->check_auth($CAT_FORUM[$idcat_topic]['auth'], EDIT_CAT_FORUM) || $session->data['user_id'] === $msg['user_id'] )
	{
		####################Récupération des infos pour suppression####################
		//On cherche l'id du premier et du dernier messages du topic afin de voir si on n'est pas en train d'en supprimer l'un ou l'autre
		$id_first = $sql->query("SELECT first_msg_id FROM ".PREFIX."forum_topics WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
		$id_last = $sql->query("SELECT last_msg_id FROM ".PREFIX."forum_topics WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
		
		####################Execution suivant l'id du msg####################
		if( $id_first != $id_get ) //Suppression d'un message.
		{
			//On compte le nombre de messages du topic avant l'id supprimé.
			$nbr_msg = $sql->query("SELECT COUNT(*) as compt FROM ".PREFIX."forum_msg WHERE idtopic = '" . $msg['idtopic'] . "' AND id < '" . $id_get . "'", __LINE__, __FILE__);	
			//On supprime le message demandé.
			$sql->query_inject("DELETE FROM ".PREFIX."forum_msg WHERE id='" . $id_get . "'", __LINE__, __FILE__);
			//On retire un msg au membre.
			$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."member SET user_msg = user_msg - 1 WHERE user_id='" . $msg['user_id'] . "'", __LINE__, __FILE__);
			//On met à jour la table forum_topics.
			$sql->query_inject("UPDATE ".PREFIX."forum_topics SET nbr_msg = nbr_msg - 1 WHERE id = '" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			//On retranche d'un messages la catégorie concernée.
			$sql->query_inject("UPDATE ".PREFIX."forum_cats SET nbr_msg = nbr_msg - 1 WHERE id_left <= '" . $CAT_FORUM[$idcat_topic]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_topic]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_topic]['level'] . "'", __LINE__, __FILE__);
			//Récupération du message suivant celui supprimé afin de rediriger vers la bonne ancre.
			$previous_msg_id = $sql->query("SELECT id FROM ".PREFIX."forum_msg WHERE idtopic='" . $msg['idtopic'] . "' AND id < '" . $id_get . "' ORDER BY timestamp DESC " . $sql->sql_limit(0, 1), __LINE__, __FILE__);
			//Mise à jour du dernier message lu par les membres.
			$sql->query_inject("UPDATE ".PREFIX."forum_view SET last_view_id='" . $previous_msg_id . "' WHERE last_view_id='" . $id_get . "'", __LINE__, __FILE__);
						
			if( $id_last == $id_get ) //On met à jour le dernier message posté dans la liste des topics.
			{
				//On cherche les infos à propos de l'avant dernier message afin de mettre la table forum_topics à jour.
				$id_before_last = $sql->query_array('forum_msg', 'user_id', 'timestamp', "WHERE id='" . $previous_msg_id . "'", __LINE__, __FILE__);	
				$sql->query_inject("UPDATE ".PREFIX."forum_topics SET last_user_id = '" . $id_before_last['user_id'] . "', last_msg_id='" . $previous_msg_id . "', last_timestamp = '" . $id_before_last['timestamp'] . "' WHERE id='" . $msg['idtopic'] . "'", __LINE__, __FILE__);
				//Récupération du timestamp du dernier message de la catégorie.		
				$max_timestamp = $sql->query("SELECT MAX(last_timestamp) as max_timestamp FROM ".PREFIX."forum_topics WHERE idcat='" . $idcat_topic . "'", __LINE__, __FILE__); 
				$last_topic_id = $sql->query("SELECT id FROM ".PREFIX."forum_topics WHERE last_timestamp = '" . $max_timestamp . "'", __LINE__, __FILE__);
				//On met maintenant a jour le last_topic_id dans les catégories.
				$sql->query_inject("UPDATE ".PREFIX."forum_cats SET last_topic_id = '" . $last_topic_id . "' WHERE id_left <= '" . $CAT_FORUM[$idcat_topic]['id_left'] . "' AND id_right >= '" . $CAT_FORUM[$idcat_topic]['id_right'] ."' AND level <= '" . $CAT_FORUM[$idcat_topic]['level'] . "'", __LINE__, __FILE__);
			}
			
			###On marque le message comme lu ###
			//On va chercher les infos sur le topic	
			$topic = !empty($msg['idtopic']) ? $sql->query_array('forum_topics', 'last_msg_id', 'last_timestamp', "WHERE id='" . $msg['idtopic'] . "'", __LINE__, __FILE__) : '';
				
			//Message(s) dans le topic non lu ( non prise en compte des topics trop vieux (x semaine) ou déjà lus).
			$last_view_forum = $sql->query("SELECT last_view_forum FROM ".PREFIX."member_extend WHERE user_id = '" . $session->data['user_id'] . "'", __LINE__, __FILE__);
			$max_time = $last_view_forum > (time() - ($CONFIG_FORUM['view_time']*3600)) ? $last_view_forum : (time() - ($CONFIG_FORUM['view_time']*3600));
			if( $session->data['user_id'] !== -1 && $topic['last_timestamp'] >= $max_time )
			{
				$check_view_id = $sql->query("SELECT last_view_id FROM ".PREFIX."forum_view WHERE user_id='" . $session->data['user_id'] . "' AND idtopic='" . $msg['idtopic'] . "'", __LINE__, __FILE__);
				
				if( !empty($check_view_id) && $check_view_id != $topic['last_msg_id'] ) 
				{
					$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_topics SET nbr_views = nbr_views + 1 WHERE id='" . $msg['idtopic'] . "'", __LINE__, __FILE__);
					$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_view SET last_view_id='" . $topic['last_msg_id'] . "', timestamp='" . time() . "' WHERE idtopic='" . $msg['idtopic'] . "' AND user_id='" . $session->data['user_id'] . "'", __LINE__, __FILE__);
				}
				elseif( empty($check_view_id) )
				{			
					$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_topics SET nbr_views= nbr_views + 1 WHERE id='" . $msg['idtopic'] . "'", __LINE__, __FILE__);
					$sql->query_inject("INSERT ".LOW_PRIORITY." INTO ".PREFIX."forum_view (idtopic,last_view_id,user_id,timestamp) VALUES('" . $msg['idtopic'] . "', '" . $topic['last_msg_id'] . "', '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);			
				}
				else
				{
					$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_topics SET nbr_views = nbr_views + 1 WHERE id='" . $msg['idtopic'] . "'", __LINE__, __FILE__);
				}
			}
			else
			{
				$sql->query_inject("UPDATE ".LOW_PRIORITY." ".PREFIX."forum_topics SET nbr_views = nbr_views + 1 WHERE id='" . $msg['idtopic'] . "'", __LINE__, __FILE__);
			}
			
			//Insertion de l'action dans l'historique.
			if( $msg['user_id'] != $session->data['user_id'] ) $sql->query_inject("INSERT INTO ".PREFIX."forum_history (action,user_id,timestamp) VALUES(0, '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
			
			//Regénération du flux rss.
			include_once('../includes/rss.class.php');
			$rss = new Rss('forum/rss.php');
			$rss->cache_path('../cache/');
			$rss->generate_file('javascript', 'rss_forum');
			$rss->generate_file('php', 'rss2_forum');
		}
	}	
}

?>