<?php
/*##################################################
*                               action.php
*                            -------------------
*   begin                : December 1, 2007
*   copyright            : (C) 2007 Sautel Benoit
*   email                : ben.popeye@phpboost.com
*
*
 ###################################################
*
*  This program is free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
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

include_once('../kernel/begin.php');
include_once('faq_begin.php');

$faq_categories = new FaqCats();

$faq_del_id = retrieve(GET, 'del', 0);
$down = retrieve(GET, 'down', 0);
$up = retrieve(GET, 'up', 0);
$id_question = retrieve(POST, 'id_question', 0);
$entitled = retrieve(POST, 'entitled', '');
$answer = retrieve(POST, 'answer', '', TSTRING_PARSE);
$id_cat = retrieve(POST, 'id_cat', 0);

$target = retrieve(POST, 'target', 0);
$move_question = retrieve(POST, 'move_question', false);

if ($faq_del_id > 0)
{
	//Vérification de la validité du jeton
	AppContext::get_session()->csrf_get_protect();

	$faq_infos = $Sql->query_array(PREFIX . 'faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $faq_del_id . "'");
	$id_cat_for_bread_crumb = $faq_infos['idcat'];
	include('faq_bread_crumb.php');
	if ($auth_write)
	{
		if (!empty($faq_infos['question'])) //If the id corresponds to a question existing in the database
		{
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order - 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order > '" . $faq_infos['q_order'] . "'"); //Decrementation of the order of every question which are after
			PersistenceContext::get_querier()->delete(PREFIX . 'faq', 'WHERE id=:id', array('id' => $faq_del_id));
			if ($faq_infos['idcat'] != 0)
			{
				$faq_cats = new FaqCats();
				$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = num_questions - 1 WHERE id IN (" . implode(', ', $faq_cats->build_parents_id_list($faq_infos['idcat'], ADD_THIS_CATEGORY_IN_LIST)) . ")");
			}
			
			$Cache->Generate_module_file('faq');
			AppContext::get_response()->redirect(PATH_TO_ROOT . FaqUrlBuilder::get_link_cat($faq_infos['idcat'],$FAQ_CATS[$faq_infos['idcat']]['name']));
		}
	}
	else
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif ($down > 0)
{
	$faq_infos = $Sql->query_array(PREFIX . 'faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $down . "'");
	
	$num_questions = PersistenceContext::get_querier()->count(PREFIX . 'faq', 'WHERE idcat=:idcat', array('idcat' => $faq_infos['idcat']));
	$id_cat_for_bread_crumb = $faq_infos['idcat'];
	include('faq_bread_crumb.php');
	if ($auth_write && !empty($faq_infos['question'])) //If the id corresponds to a question existing in the database
	{
		if ($faq_infos['q_order'] < $num_questions) //If it's not the last question we exchange it and its previous neighboor
		{
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order - 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order = '" . ($faq_infos['q_order'] + 1) . "'");
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order + 1 WHERE id = '" . $down . "'");
			AppContext::get_response()->redirect(PATH_TO_ROOT . FaqUrlBuilder::get_link_cat($faq_infos['idcat'],$FAQ_CATS[$faq_infos['idcat']]['name']) . '#q' . $down);
		}
	}
	else
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
elseif ($up > 0)
{
	$faq_infos = $Sql->query_array(PREFIX . 'faq', 'idcat', 'q_order', 'question', "WHERE id = '" . $up . "'");
	$id_cat_for_bread_crumb = $faq_infos['idcat'];
	include('faq_bread_crumb.php');
	if ($auth_write && !empty($faq_infos['question'])) //If the id corresponds to a question existing in the database
	{
		if ($faq_infos['q_order'] > 1) //If it's not the first question we exchange it and its following
		{
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order + 1 WHERE idcat = '" . $faq_infos['idcat'] . "' AND q_order = '" . ($faq_infos['q_order'] - 1) . "'");
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order - 1 WHERE id = '" . $up . "'");
			AppContext::get_response()->redirect(PATH_TO_ROOT . FaqUrlBuilder::get_link_cat($faq_infos['idcat'],$FAQ_CATS[$faq_infos['idcat']]['name']) . '#q' . $up);
		}
	}
	else
	{
		$error_controller = PHPBoostErrors::unexisting_page();
		DispatchManager::redirect($error_controller);
	}
}
//Updating or creating a question
elseif (!empty($entitled) && !empty($answer))
{
	if ($id_question > 0)
	{
		$faq_infos = $Sql->query_array(PREFIX . 'faq', 'idcat', 'q_order', "WHERE id = '" . $id_question . "'");
		$id_cat_for_bread_crumb = $faq_infos['idcat'];
		include('faq_bread_crumb.php');
		if ($auth_write)//If authorized user
		{
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET question = '" . $entitled . "', answer = '" . $answer . "', idcat = '" . $id_cat . "' WHERE id = '" . $id_question . "'");
			AppContext::get_response()->redirect(PATH_TO_ROOT . FaqUrlBuilder::get_link_question($faq_infos['idcat'], $id_question, $FAQ_CATS[$faq_infos['idcat']]['name']));
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
	else
	{
		$id_cat_for_bread_crumb = $id_cat;
		include('faq_bread_crumb.php');
		if ($auth_write)//If authorized user
		{
			//shifting right all questions which will be after this
			$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order + 1 WHERE idcat = '" . $id_cat . "' AND q_order > '" . $FAQ_CATS[$id_cat]['num_questions'] . "'");
			$Sql->query_inject("INSERT INTO " . PREFIX . "faq (idcat, q_order, question, answer, user_id, timestamp) VALUES ('" . $id_cat . "', '" . ($FAQ_CATS[$id_cat]['num_questions'] + 1 ) . "', '" . $entitled . "', '" . $answer . "', '" . AppContext::get_current_user()->get_id() . "', '" . time() . "')");
			
			$new_question_id = $Sql->insert_id("SELECT MAX(id) FROM " . PREFIX . "faq");
			
			//Updating number of subcategories
			if ($id_cat != 0)
			{
				$faq_cats = new FaqCats();
				$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = num_questions + 1 WHERE id IN (" . implode(', ', $faq_cats->build_parents_id_list($id_cat, ADD_THIS_CATEGORY_IN_LIST)) . ")");
			}
			
			$Cache->Generate_module_file('faq');
			
			AppContext::get_response()->redirect(PATH_TO_ROOT . FaqUrlBuilder::get_link_question($id_cat, $new_question_id, $FAQ_CATS[$id_cat]['name']));
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
}
//Moving a question
elseif ($id_question > 0 && $move_question && $target >= 0)
{
	//We check if new category exists
	if (array_key_exists($target, $FAQ_CATS) || $target == 0)
	{
		$question_infos = $Sql->query_array(PREFIX . "faq", "*", "WHERE id = '" . $id_question . "'");
		$id_cat_for_bread_crumb = $question_infos['idcat'];
		$auth_write = AppContext::get_current_user()->check_auth($faq_config->get_authorizations(), AUTH_WRITE);
		while ($id_cat_for_bread_crumb > 0)
		{
			$id_cat_for_bread_crumb = (int)$FAQ_CATS[$id_cat_for_bread_crumb]['id_parent'];
			if (!empty($FAQ_CATS[$id_cat_for_bread_crumb]['auth']))
				$auth_write = AppContext::get_current_user()->check_auth($FAQ_CATS[$id_cat_for_bread_crumb]['auth'], AUTH_WRITE);
		}
		if ($auth_write)
		{
			if ($target != $question_infos['idcat'])
			{
				$max_order = PersistenceContext::get_querier()->get_column_value(PREFIX . 'faq', 'MAX(q_order)', 'WHERE idcat=:idcat', array('id_cat' => $target));
				$Sql->query_inject("UPDATE " . PREFIX . "faq SET idcat = '" . $target . "', q_order = '" . ($max_order + 1) . "' WHERE id = '" . $id_question . "'");
				$Sql->query_inject("UPDATE " . PREFIX . "faq SET q_order = q_order - 1 WHERE idcat = '" . $question_infos['idcat'] . "' AND q_order > '" . $question_infos['q_order'] . "'");
				
				//Updating number of subcategories of its old parents
				if ($question_infos['idcat'] != 0)
				{
					$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = num_questions - 1 WHERE id IN (" . implode(', ', $faq_categories->build_parents_id_list($question_infos['idcat'], ADD_THIS_CATEGORY_IN_LIST)) . ")");
				}
				
				//Updating the number of subcategories of its new parents
				if ($target != 0)
				{
					$Sql->query_inject("UPDATE " . PREFIX . "faq_cats SET num_questions = num_questions + 1 WHERE id IN (" . implode(', ', $faq_categories->build_parents_id_list($target, ADD_THIS_CATEGORY_IN_LIST)) . ")");
				}
				
				if ($question_infos['idcat'] != 0 || $target != 0)
					$Cache->Generate_module_file('faq');
			}
			AppContext::get_response()->redirect(PATH_TO_ROOT . FaqUrlBuilder::get_link_cat($target,$FAQ_CATS[$target]['name']) . '#q' . $id_question);
		}
	}
	$error_controller = PHPBoostErrors::unexisting_page();
	DispatchManager::redirect($error_controller);
}
else
	AppContext::get_response()->redirect(HOST . DIR . url('/faq/faq.php', '', '&'));

?>