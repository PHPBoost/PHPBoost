<?php
/*##################################################
*                               action.php
*                            -------------------
*   begin                : December 1, 2007
*   copyright          : (C) 2007 Sautel Benoit
*   email                : ben.popeye@phpboost.com
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
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*
###################################################*/

include_once('../includes/begin.php');
include_once('faq_begin.php');


$faq_del_id = !empty($_GET['del']) ? numeric($_GET['del']) : 0;
$down = !empty($_GET['down']) ? numeric($_GET['down']) : 0;
$up = !empty($_GET['up']) ? numeric($_GET['up']) : 0;
$id_question = !empty($_POST['id_question']) ? numeric($_POST['id_question']) : 0;
$entitled = !empty($_POST['entitled']) ? securit($_POST['entitled']) : '';
$answer = !empty($_POST['answer']) ? parse($_POST['answer']) : '';
$new_id_cat = !empty($_POST['id_cat']) ? numeric($_POST['id_cat']) : 0;
$id_after = !empty($_POST['after']) ? numeric($_POST['after']) : 0;
//Properties of the category
$cat_properties = !empty($_GET['cat_properties']);
$id_cat = !empty($_POST['id_faq']) ? numeric($_POST['id_faq']) : 0;
$display_mode = !empty($_POST['display_mode']) ? numeric($_POST['display_mode']) : 0;
$global_auth = !empty($_POST['global_auth']);
$cat_name = !empty($_POST['cat_name']) ? securit($_POST['cat_name']) : '';
$description = !empty($_POST['description']) ? parse($_POST['description']) : '';

$target = !empty($_POST['target']) ? numeric($_POST['target']) : 0;
$move_question = !empty($_POST['move_question']);

if( $faq_del_id > 0 )
{
	$faq_infos = $Sql->Query_array('faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $faq_del_id . "'", __LINE__, __FILE__);
	$id_cat_for_speed_bar = $faq_infos['idcat'];
	include('faq_speed_bar.php');
	if( $auth_write )
	{
		if( !empty($faq_infos['question']) ) //If the id corresponds to a question existing in the database
		{
			$Sql->Query_inject("UPDATE ".PREFIX."faq SET q_order = q_order - 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order > '" . $faq_infos['q_order'] . "'", __LINE__, __FILE__); //Decrementation of the order of every question which are after
			$Sql->Query_inject("DELETE FROM ".PREFIX."faq WHERE id = '" . $faq_del_id . "'", __LINE__, __FILE__);			 //Updating number of subcategories
			if( $faq_infos['idcat'] != 0 )
			{
				include_once('faq_cats.class.php');
				$faq_cats = new FaqCats();
				$Sql->Query_inject("UPDATE ".PREFIX."faq_cats SET num_questions = num_questions - 1 WHERE id IN (" . implode(', ', $faq_cats->Compute_parent_cats_id($faq_infos['idcat'], ADD_THIS_CATEGORY_IN_LIST)) . ")", __LINE__, __FILE__);
			}
			
			$Cache->Generate_module_file('faq');
			redirect(HOST . DIR . transid('/faq/management.php?faq=' . $faq_infos['idcat'], '', '&'));
		}
	}
	else
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
}
elseif( $down > 0 )
{
	$faq_infos = $Sql->Query_array('faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $down . "'", __LINE__, __FILE__);
	
	$num_questions = $Sql->Query("SELECT COUNT(*) FROM ".PREFIX."faq WHERE idcat = '" . $faq_infos['idcat'] . "'", __LINE__, __FILE__);
	$id_cat_for_speed_bar = $faq_infos['idcat'];
	include('faq_speed_bar.php');
	if( $auth_write && !empty($faq_infos['question']) ) //If the id corresponds to a question existing in the database
	{
		if( $faq_infos['q_order'] < $num_questions ) //If it's not the last question we exchange it and its previous neighboor
		{
			$Sql->Query_inject("UPDATE ".PREFIX."faq SET q_order = q_order - 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order = '" . ($faq_infos['q_order'] + 1) . "'", __LINE__, __FILE__);
			$Sql->Query_inject("UPDATE ".PREFIX."faq SET q_order = q_order + 1 WHERE id = '" . $down . "'", __LINE__, __FILE__);
			redirect(HOST . DIR . transid('/faq/management.php?faq=' . $faq_infos['idcat'] . '#q' . ($faq_infos['q_order'] + 1), '', '&'));
		}
	}
	else
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
}
elseif( $up > 0 )
{
	$faq_infos = $Sql->Query_array('faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $up . "'", __LINE__, __FILE__);
	$id_cat_for_speed_bar = $faq_infos['idcat'];
	include('faq_speed_bar.php');
	if( $auth_write && !empty($faq_infos['question']) ) //If the id corresponds to a question existing in the database
	{
		if( $faq_infos['q_order'] > 1 ) //If it's not the first question we exchange it and its following
		{
			$Sql->Query_inject("UPDATE ".PREFIX."faq SET q_order = q_order + 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order = '" . ($faq_infos['q_order'] - 1) . "'", __LINE__, __FILE__);
			$Sql->Query_inject("UPDATE ".PREFIX."faq SET q_order = q_order - 1 WHERE id = '" . $up . "'", __LINE__, __FILE__);
			redirect(HOST . DIR . transid('/faq/management.php?faq=' . $faq_infos['idcat'] . '#q' . ($faq_infos['q_order'] - 1), '', '&'));
		}
	}
	else
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
}
//Updating or creating a question
elseif( !empty($entitled) && !empty($answer) )
{
	if( $id_question > 0 )
	{
		$faq_infos = $Sql->Query_array('faq', 'idcat', 'q_order', "WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
		$id_cat_for_speed_bar = $faq_infos['idcat'];
		include('faq_speed_bar.php');
		if( $auth_write )//If authorized user
		{			
			$Sql->Query_inject("UPDATE ".PREFIX."faq SET question = '" . $entitled . "', answer = '" . $answer . "' WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
			redirect(HOST . DIR . transid('/faq/management.php?faq=' . $faq_infos['idcat'] . '#q' . $faq_infos['q_order'], '', '&'));
		}
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	}
	else
	{
		$id_cat_for_speed_bar = $new_id_cat;
		include('faq_speed_bar.php');
		if( $auth_write )//If authorized user
		{
			//shifting right all questions which will be after this
			$Sql->Query_inject("UPDATE ".PREFIX."faq SET q_order = q_order + 1 WHERE idcat = '" . $new_id_cat . "' AND q_order > '" . $id_after . "'", __LINE__, __FILE__);
			$Sql->Query_inject("INSERT INTO ".PREFIX."faq (idcat, q_order, question, answer, user_id, timestamp) VALUES ('" . $new_id_cat . "', '" . ($id_after + 1 ) . "', '" . $entitled . "', '" . $answer . "', '" . $Member->Get_attribute('user_id') . "', '" . time() . "')", __LINE__, __FILE__);
			
			//Updating number of subcategories
			if( $new_id_cat != 0 )
			{
				include_once('faq_cats.class.php');
				$faq_cats = new FaqCats();
				$Sql->Query_inject("UPDATE ".PREFIX."faq_cats SET num_questions = num_questions + 1 WHERE id IN (" . implode(', ', $faq_cats->Compute_parent_cats_id($new_id_cat, ADD_THIS_CATEGORY_IN_LIST)) . ")", __LINE__, __FILE__);
			}
			
			$Cache->Generate_module_file('faq');
			redirect(HOST . DIR . transid('/faq/management.php?faq=' . $new_id_cat . '#q' . ($id_after + 1), '', '&'));
		}
		else
			$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
	}
}
elseif( $cat_properties && (!empty($cat_name) || $id_cat == 0) )
{
	$id_cat_for_speed_bar = $id_cat;
	include('faq_speed_bar.php');
	if( $auth_write )
	{
		if( $global_auth )
		{
			$auth_read = isset($_POST['groups_auth1']) ? $_POST['groups_auth1'] : '';
			$auth_write = isset($_POST['groups_auth2']) ? $_POST['groups_auth2'] : '';
			$array_auth_all = $Group->Return_array_auth($auth_read, $auth_write);
			$new_auth = addslashes(serialize($array_auth_all));
		}
		else
			$new_auth = '';
			
		$display_mode = ($display_mode <= 2 || $display_mode >= 0) ? $display_mode : 0;

		//Category existing into database
		if( $id_cat > 0 )
		{
			$Sql->Query_inject("UPDATE ".PREFIX."faq_cats SET display_mode = '" . $display_mode . "', auth = '" . $new_auth . "', description = '" . $description . "', name = '" . $cat_name . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
		}
		//Root : properties into cache
		else
		{
			$FAQ_CONFIG['root'] = array(
				'display_mode' => $display_mode,
				'auth' => $FAQ_CATS[0]['auth'],
				'description' => stripslashes($description)
			);
			$Sql->Query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($FAQ_CONFIG)) . "' WHERE name = 'faq'", __LINE__, __FILE__);
		}
		$Cache->Generate_module_file('faq');
		redirect(HOST . DIR . transid('/faq/management.php?faq=' . $id_cat, '', '&'));
	}
	else
		$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
}
//Moving a question
elseif( $id_question > 0 && $move_question && $target >= 0 )
{
	//We check if new category exists
	if( array_key_exists($target, $FAQ_CATS) || $target == 0 )
	{
		$question_infos = $Sql->Query_array("faq", "*", "WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
		$id_cat_for_speed_bar = $question_infos['idcat'];
		$auth_write = $Member->Check_auth($FAQ_CONFIG['global_auth'], AUTH_WRITE);
		while( $id_cat_for_speed_bar > 0 )
		{
			$id_cat_for_speed_bar = (int)$FAQ_CATS[$id_cat_for_speed_bar]['id_parent'];
			if( !empty($FAQ_CATS[$id_cat_for_speed_bar]['auth']) )
				$auth_write = $Member->Check_auth($FAQ_CATS[$id_cat_for_speed_bar]['auth'], AUTH_WRITE);
		}
		if( $auth_write )
		{
			if( $target != $question_infos['idcat'])
			{
				$max_order = $Sql->Query("SELECT MAX(q_order) FROM ".PREFIX."faq WHERE idcat = '" . $target . "'", __LINE__, __FILE__);
				$Sql->Query_inject("UPDATE ".PREFIX."faq SET idcat = '" . $target . "', q_order = '" . ($max_order + 1) . "' WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
				$Sql->Query_inject("UPDATE ".PREFIX."faq SET q_order = q_order - 1 WHERE idcat = '" . $question_infos['idcat'] . "' AND q_order > '" . $question_infos['q_order'] . "'", __LINE__, __FILE__);
				
				//Updating number of subcategories of its old parents
				if( $question_infos['idcat'] != 0 )
				{
					include_once('faq_cats.class.php');
					$faq_cats = new FaqCats();
					$Sql->Query_inject("UPDATE ".PREFIX."faq_cats SET num_questions = num_questions - 1 WHERE id IN (" . implode(', ', $faq_cats->Compute_parent_cats_id($question_infos['idcat'], ADD_THIS_CATEGORY_IN_LIST)) . ")", __LINE__, __FILE__);
				}
				
				//Updating number of subcategories of its new parents
				if( $target != 0 )
				{
					include_once('faq_cats.class.php');
					$faq_cats = new FaqCats();
					$Sql->Query_inject("UPDATE ".PREFIX."faq_cats SET num_questions = num_questions + 1 WHERE id IN (" . implode(', ', $faq_cats->Compute_parent_cats_id($target, ADD_THIS_CATEGORY_IN_LIST)) . ")", __LINE__, __FILE__);
				}
				
				if( $question_infos['idcat'] != 0 || $target != 0 )
					$Cache->Generate_module_file('faq');
			}
			redirect(HOST . DIR . transid('/faq/management.php?faq=' . $target, '', '&'));
		}
	}
	$Errorh->Error_handler('e_auth', E_USER_REDIRECT);
}
else
	redirect(HOST . DIR . transid('/faq/faq.php', '', '&'));

?>