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
$cache->load_file('faq');

$faq_del_id = !empty($_GET['del']) ? numeric($_GET['del']) : 0;
$down = !empty($_GET['down']) ? numeric($_GET['down']) : 0;
$up = !empty($_GET['up']) ? numeric($_GET['up']) : 0;
$id_question = !empty($_POST['id_question']) ? numeric($_POST['id_question']) : 0;
$entitled = !empty($_POST['entitled']) ? securit($_POST['entitled']) : '';
$answer = !empty($_POST['answer']) ? parse($_POST['answer']) : '';
$new_id_cat = !empty($_POST['id_cat']) ? numeric($_POST['id_cat']) : 0;
$id_after = !empty($_POST['after']) ? numeric($_POST['after']) : 0;
//Properties of the category
$cat_properties = !empty($_GET['cat_properties']) ? true : false;
$id_cat = !empty($_POST['id_faq']) ? numeric($_POST['id_faq']) : 0;
$display_mode = !empty($_POST['display_mode']) ? numeric($_POST['display_mode']) : 0;
$global_auth = !empty($_POST['global_auth']) ? true : false;

if( $faq_del_id > 0 )
{
	$faq_infos = $sql->query_array('faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $faq_del_id . "'", __LINE__, __FILE__);
	if( !empty($faq_infos['question']) ) //If the id corresponds to a question existing in the database
	{
		$sql->query_inject("UPDATE ".PREFIX."faq SET q_order = q_order - 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order > '" . $faq_infos['q_order'] . "'", __LINE__, __FILE__); //Decrementation of the order of every question which are after
		$sql->query_inject("DELETE FROM ".PREFIX."faq WHERE id = '" . $faq_del_id . "'", __LINE__, __FILE__); //Deleting question
		header('Location:' . HOST . DIR . transid('/faq/management.php?faq=' . $faq_infos['idcat'], '', '&'));
		exit;
	}
}
elseif( $down > 0 )
{
	$faq_infos = $sql->query_array('faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $down . "'", __LINE__, __FILE__);
	if( !empty($faq_infos['question']) ) //If the id corresponds to a question existing in the database
	{
		if( $faq_infos['q_order'] < $FAQ_CATS[$faq_infos['idcat']]['num_questions'] ) //If it's not the last question we exchange it and its following
		{
			$sql->query_inject("UPDATE ".PREFIX."faq SET q_order = q_order - 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order = '" . ($faq_infos['q_order'] + 1) . "'", __LINE__, __FILE__);
			$sql->query_inject("UPDATE ".PREFIX."faq SET q_order = q_order + 1 WHERE id = '" . $down . "'", __LINE__, __FILE__);
			header('Location:' . HOST . DIR . transid('/faq/management.php?faq=' . $faq_infos['idcat'] . '#q' . $down, '', '&'));
			exit;
		}
	}
}
elseif( $up > 0 )
{
	$faq_infos = $sql->query_array('faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $up . "'", __LINE__, __FILE__);
	if( !empty($faq_infos['question']) ) //If the id corresponds to a question existing in the database
	{
		if( $faq_infos['q_order'] > 1 ) //If it's not the last question we exchange it and its following
		{
			$sql->query_inject("UPDATE ".PREFIX."faq SET q_order = q_order + 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order = '" . ($faq_infos['q_order'] - 1) . "'", __LINE__, __FILE__);
			$sql->query_inject("UPDATE ".PREFIX."faq SET q_order = q_order - 1 WHERE id = '" . $up . "'", __LINE__, __FILE__);
			header('Location:' . HOST . DIR . transid('/faq/management.php?faq=' . $faq_infos['idcat'] . '#q' . $up, '', '&'));
			exit;
		}
	}
}
//Updating or creating a question
elseif( !empty($entitled) && !empty($answer) )
{
	if( $id_question > 0 )
	{
		$faq_infos = $sql->query_array('faq', 'idcat', "WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
		$sql->query_inject("UPDATE ".PREFIX."faq SET question = '" . $entitled . "', answer = '" . $answer . "' WHERE id = '" . $id_question . "'", __LINE__, __FILE__);
		header('Location:' . HOST . DIR . transid('/faq/management.php?faq=' . $faq_infos['idcat'] . '#q' . $id_question, '', '&'));
		exit;
	}
	else
	{
		//shifting right all questions which will be after this
		$sql->query_inject("UPDATE ".PREFIX."faq SET q_order = q_order + 1 WHERE idcat = '" . $new_id_cat . "' AND q_order > '" . $id_after . "'", __LINE__, __FILE__);
		$sql->query_inject("INSERT INTO ".PREFIX."faq (idcat, q_order, question, answer, user_id, timestamp) VALUES ('" . $new_id_cat . "', '" . ($id_after + 1 ) . "', '" . $entitled . "', '" . $answer . "', '" . $session->data['user_id'] . "', '" . time() . "')", __LINE__, __FILE__);
		$cache->generate_module_file('faq');
		header('Location:' . HOST . DIR . transid('/faq/management.php?faq=' . $new_id_cat . '#q' . ($id_after + 1), '', '&'));
		exit;
	}
}
//This cat hasn't particular auth anymore
elseif( $global_auth )
{
	//Category existing into database
	if( $id_cat > 0 )
	{
		$sql->query_inject("UPDATE ".PREFIX."faq_cats SET auth = '' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
	}
	//Root : properties into cache
	else
	{
		$FAQ_CONFIG['root'] = array(
			'display_mode' => $display_mode,
			'num_questions' => $FAQ_CATS[0]['num_questions'],
			'auth' => array()
		);
		$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($FAQ_CONFIG)) . "' WHERE name = 'faq'", __LINE__, __FILE__);
	}
	$cache->generate_module_file('faq');
	header('Location:' . HOST . DIR . transid('/faq/management.php?faq=' . $id_cat, '', '&'));
	exit;
}
elseif( $cat_properties )
{
	$auth_read = isset($_POST['groups_auth1']) ? $_POST['groups_auth1'] : '';
	$auth_write = isset($_POST['groups_auth2']) ? $_POST['groups_auth2'] : '';
	$array_auth_all = $groups->return_array_auth($auth_read, $auth_write);
	$new_auth = addslashes(serialize($array_auth_all));
	$display_mode = ($display_mode <= 2 || $display_mode >= 0) ? $display_mode : 0;

	//Category existing into database
	if( $id_cat > 0 )
	{
		$sql->query_inject("UPDATE ".PREFIX."faq_cats SET display_mode = '" . $display_mode . "', auth = '" . $new_auth . "' WHERE id = '" . $id_cat . "'", __LINE__, __FILE__);
	}
	//Root : properties into cache
	else
	{
		$FAQ_CONFIG['root'] = array(
			'display_mode' => $display_mode,
			'num_questions' => $FAQ_CATS[0]['num_questions'],
			'auth' => $new_auth
		);
		$sql->query_inject("UPDATE ".PREFIX."configs SET value = '" . addslashes(serialize($FAQ_CONFIG)) . "' WHERE name = 'faq'", __LINE__, __FILE__);
	}
	$cache->generate_module_file('faq');
	header('Location:' . HOST . DIR . transid('/faq/management.php?faq=' . $id_cat, '', '&'));
	exit;
}
else
{
	header('Location:' . HOST . DIR . transid('/faq/faq.php', '', '&'));
	exit;
}

?>