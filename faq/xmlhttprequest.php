<?php
header('Content-type: text/html; charset=iso-8859-15');

include_once('../includes/begin.php');
define('TITLE', 'Ajax faq');
include_once('../includes/header_no_display.php');

if( $session->data['level'] === 2 ) //Admin
{			
	$cache->load_file('faq');

	$move = !empty($_GET['move']) ? trim($_GET['move']) : 0;
	$id = !empty($_GET['id']) ? numeric($_GET['id']) : 0;
	$get_parent_up = !empty($_GET['g_up']) ? numeric($_GET['g_up']) : 0;
	$get_parent_down = !empty($_GET['g_down']) ? numeric($_GET['g_down']) : 0;

	//Récupération de la catégorie d'échange.
	if( !empty($get_parent_up) )
	{
		$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats WHERE '" . $FAQ_CATS[$get_parent_up]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);
		if( !empty($switch_id_cat) )
			echo $switch_id_cat;
		else
		{	
			// Catégories parentes à supprimer.
			$list_parent_cats = '';
			$result = $sql->query_while("SELECT id 
			FROM ".PREFIX."faq_cats 
			WHERE id_left < '" . $FAQ_CATS[$get_parent_up]['id_left'] . "' AND id_right > '" . $FAQ_CATS[$get_parent_up]['id_right'] . "'", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$sql->close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			if( !empty($list_parent_cats) )
			{
				//Changement de catégorie.
				$change_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats
				WHERE id_left < '" . $FAQ_CATS[$get_parent_up]['id_left'] . "' AND level = '" . ($FAQ_CATS[$get_parent_up]['level'] - 1) . "' AND
				id NOT IN (" . $list_parent_cats . ")
				ORDER BY id_left DESC" . 
				$sql->sql_limit(0, 1), __LINE__, __FILE__);
				if( isset($FAQ_CATS[$change_cat]) )
				{	
					$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats 
					WHERE id_left > '" . $FAQ_CATS[$change_cat]['id_right'] . "'
					ORDER BY id_left" . 
					$sql->sql_limit(0, 1), __LINE__, __FILE__);
				}
				if( !empty($switch_id_cat) )
					echo 's' . $switch_id_cat;
			}
		}	
	}
	elseif( !empty($get_parent_down) )
	{
		$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats WHERE id_left - '" . $FAQ_CATS[$get_parent_down]['id_right'] . "' = 1", __LINE__, __FILE__);
		if( !empty($switch_id_cat) )
			echo $switch_id_cat;
		else
		{	
			$change_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats
			WHERE id_left > '" . $FAQ_CATS[$get_parent_down]['id_left'] . "' AND level = '" . ($FAQ_CATS[$get_parent_down]['level'] - 1) . "'
			ORDER BY id_left" . 
			$sql->sql_limit(0, 1), __LINE__, __FILE__);
			if( isset($FAQ_CATS[$change_cat]) )
			{	
				$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats 
				WHERE id_left < '" . $FAQ_CATS[$change_cat]['id_right'] . "'
				ORDER BY id_left DESC" . 
				$sql->sql_limit(0, 1), __LINE__, __FILE__);
			}
			if( !empty($switch_id_cat) )
				echo 's' . $switch_id_cat;
		}	
	}

	//Déplacement.
	if( !empty($move) && !empty($id) )
	{
		//Si la catégorie existe, et déplacement possible
		if( array_key_exists($id, $FAQ_CATS) )
		{
			//Catégories parentes de celle à supprimer.
			$list_parent_cats = '';
			$result = $sql->query_while("SELECT id 
			FROM ".PREFIX."faq_cats 
			WHERE id_left < '" . $FAQ_CATS[$id]['id_left'] . "' AND id_right > '" . $FAQ_CATS[$id]['id_right'] . "'", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				$list_parent_cats .= $row['id'] . ', ';
			}
			$sql->close($result);
			$list_parent_cats = trim($list_parent_cats, ', ');
			
			$to = 0;
			if( $move == 'up' )
			{	
				//Même catégorie
				$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats
				WHERE '" . $FAQ_CATS[$id]['id_left'] . "' - id_right = 1", __LINE__, __FILE__);		
				if( !empty($switch_id_cat) )
				{
					//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left + '" . ($FAQ_CATS[$switch_id_cat]['id_right'] - $FAQ_CATS[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right + '" . ($FAQ_CATS[$switch_id_cat]['id_right'] - $FAQ_CATS[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $FAQ_CATS[$id]['id_left'] . "' AND '" . $FAQ_CATS[$id]['id_right'] . "'", __LINE__, __FILE__);
					//On descend la catégorie cible.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left + '" . ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] + 1) . "', id_right = id_right + '" . ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $FAQ_CATS[$switch_id_cat]['id_left'] . "' AND '" . $FAQ_CATS[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
					
					//On rétablit les valeurs absolues.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);	
					
					$cache->generate_module_file('faq');
				}		
				elseif( !empty($list_parent_cats)  )
				{
					//Changement de catégorie.
					$to = $sql->query("SELECT id FROM ".PREFIX."faq_cats
					WHERE id_left < '" . $FAQ_CATS[$id]['id_left'] . "' AND level = '" . ($FAQ_CATS[$id]['level'] - 1) . "' AND
					id NOT IN (" . $list_parent_cats . ")
					ORDER BY id_left DESC" . 
					$sql->sql_limit(0, 1), __LINE__, __FILE__);
				}
			}
			elseif( $move == 'down' )
			{
				//Doit-on changer de catégorie parente ou non ?
				$switch_id_cat = $sql->query("SELECT id FROM ".PREFIX."faq_cats
				WHERE id_left - '" . $FAQ_CATS[$id]['id_right'] . "' = 1", __LINE__, __FILE__);
				if( !empty($switch_id_cat) )
				{
					//On monte la catégorie à déplacer, on lui assigne des id négatifs pour assurer l'unicité.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left - '" . ($FAQ_CATS[$switch_id_cat]['id_right'] - $FAQ_CATS[$switch_id_cat]['id_left'] + 1) . "', id_right = - id_right - '" . ($FAQ_CATS[$switch_id_cat]['id_right'] - $FAQ_CATS[$switch_id_cat]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $FAQ_CATS[$id]['id_left'] . "' AND '" . $FAQ_CATS[$id]['id_right'] . "'", __LINE__, __FILE__);
					//On descend la catégorie cible.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left - '" . ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] + 1) . "', id_right = id_right - '" . ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] + 1) . "' WHERE id_left BETWEEN '" . $FAQ_CATS[$switch_id_cat]['id_left'] . "' AND '" . $FAQ_CATS[$switch_id_cat]['id_right'] . "'", __LINE__, __FILE__);
					
					//On rétablit les valeurs absolues.
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left WHERE id_left < 0", __LINE__, __FILE__);
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_right = - id_right WHERE id_right < 0", __LINE__, __FILE__);
					
					$cache->generate_module_file('faq');
				}
				elseif( !empty($list_parent_cats)  )
				{
					//Changement de catégorie.
					$to = $sql->query("SELECT id FROM ".PREFIX."faq_cats
					WHERE id_left > '" . $FAQ_CATS[$id]['id_left'] . "' AND level = '" . ($FAQ_CATS[$id]['level'] - 1) . "'
					ORDER BY id_left" . 
					$sql->sql_limit(0, 1), __LINE__, __FILE__);
				}
			}

			if( !empty($to) ) //Changement de catégorie possible?
			{
				//On vérifie si la catégorie contient des sous catégories.
				$nbr_cat = (($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$id]['id_left'] - 1) / 2) + 1;
			
				//Sous catégories de la catégorie à supprimer.
				$list_cats = '';
				$result = $sql->query_while("SELECT id
				FROM ".PREFIX."faq_cats 
				WHERE id_left BETWEEN '" . $FAQ_CATS[$id]['id_left'] . "' AND '" . $FAQ_CATS[$id]['id_right'] . "'
				ORDER BY id_left", __LINE__, __FILE__);
				while( $row = $sql->sql_fetch_assoc($result) )
				{
					$list_cats .= $row['id'] . ', ';
				}
				$sql->close($result);
				$list_cats = trim($list_cats, ', ');
			
				//Précaution pour éviter erreur fatale, cas impossible si cohérence de l'arbre respectée.
				if( empty($list_cats) )
				{
					header('location:' . HOST . SCRIPT);
					exit;
				}
				
				//catégories parentes de la catégorie cible.
				$list_parent_cats_to = '';
				$result = $sql->query_while("SELECT id, level 
				FROM ".PREFIX."faq_cats 
				WHERE id_left <= '" . $FAQ_CATS[$to]['id_left'] . "' AND id_right >= '" . $FAQ_CATS[$to]['id_right'] . "'", __LINE__, __FILE__);
				while( $row = $sql->sql_fetch_assoc($result) )
				{
					$list_parent_cats_to .= $row['id'] . ', ';
				}
				$sql->close($result);
				$list_parent_cats_to = trim($list_parent_cats_to, ', ');
			
				if( empty($list_parent_cats_to) )
					$clause_parent_cats_to = " id = '" . $to . "'";
				else
					$clause_parent_cats_to = " id IN (" . $list_parent_cats_to . ")";
		
				########## Suppression ##########
				//On supprime virtuellement (changement de signe des bornes) les enfants.
				$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = - id_left, id_right = - id_right WHERE id IN (" . $list_cats . ")", __LINE__, __FILE__);					
				

				if( !empty($list_parent_cats) )
				{
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_right = id_right - '" . ( $nbr_cat*2) . "' WHERE id IN (" . $list_parent_cats . ")", __LINE__, __FILE__);
				}
				
				//On réduit la taille de l'arbre du nombre de catégories supprimées à partir de la position de celui-ci.
				$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left - '" . ($nbr_cat*2) . "', id_right = id_right - '" . ($nbr_cat*2) . "' WHERE id_left > '" . $FAQ_CATS[$id]['id_right'] . "'", __LINE__, __FILE__);

				########## Ajout ##########
				//On modifie les bornes droites des parents de la cible.
				$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_right = id_right + '" . ($nbr_cat*2) . "' WHERE " . $clause_parent_cats_to, __LINE__, __FILE__);

				//On augmente la taille de l'arbre du nombre de catégories supprimées à partir de la position de la catégorie cible.
				if( $FAQ_CATS[$id]['id_left'] > $FAQ_CATS[$to]['id_left']  ) //Direction catégorie source -> catégorie cible.
				{	
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . $FAQ_CATS[$to]['id_right'] . "'", __LINE__, __FILE__);						
					$limit = $FAQ_CATS[$to]['id_right'];
					$end = $limit + ($nbr_cat*2) - 1;
				}
				else
				{	
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = id_left + '" . ($nbr_cat*2) . "', id_right = id_right + '" . ($nbr_cat*2) . "' WHERE id_left > '" . ($FAQ_CATS[$to]['id_right'] - ($nbr_cat*2)) . "'", __LINE__, __FILE__);
					$limit = $FAQ_CATS[$to]['id_right'] - ($nbr_cat*2);
					$end = $limit + ($nbr_cat*2) - 1;						
				}	

				//On replace les catégories supprimées virtuellement.
				$array_sub_cats = explode(', ', $list_cats);
				$z = 0;
				for($i = $limit; $i <= $end; $i = $i + 2)
				{
					$id_left = $limit + ($FAQ_CATS[$array_sub_cats[$z]]['id_left'] - $FAQ_CATS[$id]['id_left']);
					$id_right = $end - ($FAQ_CATS[$id]['id_right'] - $FAQ_CATS[$array_sub_cats[$z]]['id_right']);
					$sql->query_inject("UPDATE ".PREFIX."faq_cats SET id_left = '" . $id_left . "', id_right = '" . $id_right . "' WHERE id = '" . $array_sub_cats[$z] . "'", __LINE__, __FILE__);
					$z++;
				}
				
				$cache->generate_module_file('faq');
			}
			
			//Génération de la liste des catégories en cache.
			$list_cats_js = '';
			$array_js = '';	
			$i = 0;
			$result = $sql->query_while("SELECT id, id_left, id_right
			FROM ".PREFIX."faq_cats 
			ORDER BY id_left", __LINE__, __FILE__);
			while( $row = $sql->sql_fetch_assoc($result) )
			{
				$list_cats_js .= $row['id'] . ', ';		
				$array_js .= 'array_cats[' . $row['id'] . '][\'id\'] = ' . $row['id'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_left\'] = ' . $row['id_left'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'id_right\'] = ' . $row['id_right'] . ";";
				$array_js .= 'array_cats[' . $row['id'] . '][\'i\'] = ' . $i . ";";
				$i++;
			}
			$sql->close($result);
			echo 'list_cats = new Array(' . trim($list_cats_js, ', ') . ');' . $array_js;
		}	
	}
}

?>