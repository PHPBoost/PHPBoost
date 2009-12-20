<?php
/*##################################################
 *                               admin_extend_field.php
 *                            -------------------
 *   begin                : June 16, 2007
 *   copyright            : (C) 2007 Viarre Régis
 *   email                : crowkait@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
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

require_once('../admin/admin_begin.php');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

$id = retrieve(GET, 'id', 0);
$del = !empty($_GET['del']) ? true : false;
$top = retrieve(GET, 'top', 0);
$bottom = retrieve(GET, 'bot', 0);
	
$Template->set_filenames(array(
	'admin_extend_field'=> 'admin/admin_extend_field.tpl'
));
	
if ($del && !empty($id))
{
	$field_name = $Sql->query("SELECT field_name FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
	if (!empty($field_name)) 
	{
		$Sql->query_inject("DELETE FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
		$Sql->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " DROP " . $field_name, __LINE__, __FILE__);
	}
	redirect(HOST . SCRIPT);
}
elseif (!empty($_POST['valid']))
{
	$name = retrieve(POST, 'name', '');
	$contents = nl2br(retrieve(POST, 'contents', '', TSTRING));
	$field = retrieve(POST, 'field', 0);
	$required = retrieve(POST, 'required', 0);
	$possible_values = retrieve(POST, 'possible_values', '');
	$default_values = retrieve(POST, 'default_values', '');
	
	$regex_type = retrieve(POST, 'regex_type', 0);
	if (empty($regex_type))
		$regex = retrieve(POST, 'regex1', 0);
	else
		$regex = retrieve(POST, 'regex2', '');

	$array_field = array(
		1 => 'VARCHAR(255) NOT NULL DEFAULT = \'\'', 
		2 => 'TEXT NOT NULL', 
		3 => 'TEXT NOT NULL', 
		4 => 'TEXT NOT NULL', 
		5 => 'TEXT NOT NULL', 
		6 => 'TEXT NOT NULL'
	);
	
	if (!empty($name) && !empty($field))
	{
		function rewrite_field($field)
		{
			$field = strtolower($field);
			$field = url_encode_rewrite($field);
			$field = str_replace('-', '_', $field);
			return 'f_' . $field;
		}
		$field_name = rewrite_field($name);
		
		$check_name = $Sql->query("SELECT COUNT(*) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE field_name = '" . $field_name . "' AND id <> '" . $id . "'", __LINE__, __FILE__);
		if (empty($check_name)) 
		{
			$new_field_name = $field_name . ' ' . $array_field[$field];
			$previous_name = $Sql->query("SELECT field_name FROM " . DB_TABLE_MEMBER_EXTEND_CAT . " WHERE id = '" . $id . "'", __LINE__, __FILE__);
			if ($previous_name != $field_name)
				$Sql->query_inject("ALTER TABLE " . DB_TABLE_MEMBER_EXTEND . " CHANGE " . $previous_name . " " . $new_field_name, __LINE__, __FILE__);
			$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET name = '" . $name . "', field_name = '" . $field_name . "', contents = '" . $contents . "', field = '" . $field . "', possible_values = '" . $possible_values . "', default_values = '" . $default_values . "', required = '" . $required . "', regex = '" . $regex . "' WHERE id = '" . $id . "'", __LINE__, __FILE__);
			
			redirect('/admin/admin_extend_field.php');
		}
		else
			redirect('/admin/admin_extend_field_add.php?error=exist_field#errorh');
	}
	else
		redirect('/admin/admin_extend_field.php?id=' . $id . '&error=incomplete#errorh');
}
elseif ((!empty($top) || !empty($bottom)) && !empty($id)) //Monter/descendre.
{
	if (!empty($top))
	{	
		$idmoins = ($top - 1);
			
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class = 0 WHERE class='" . $top . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class=" . $top . " WHERE class = '" . $idmoins . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class=" . $idmoins . " WHERE class = 0", __LINE__, __FILE__);
		
		redirect(HOST . SCRIPT . '#e' . $id);
	}
	elseif (!empty($bottom))
	{
		$idplus = ($bottom + 1);
			
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class = 0 WHERE class = '" . $bottom . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class = " . $bottom . " WHERE class = '" . $idplus . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class = " . $idplus . " WHERE class = 0", __LINE__, __FILE__);
			
		redirect(HOST . SCRIPT . '#e' . $id);
	}
}
elseif (!empty($id))
{	
	$extend_field = $Sql->query_array(DB_TABLE_MEMBER_EXTEND_CAT, "id", "name", "contents", "field", "possible_values", "default_values", "required", "regex", "WHERE id = '" . $id . "'", __LINE__, __FILE__);
	
	$regex_checked = 2;
	$predef_regex = false;
	if (is_numeric($extend_field['regex']) && $extend_field['regex'] >= 0 && $extend_field['regex'] <= 5)
	{
		$regex_checked = 1;
		$predef_regex = true;
	}
		
	$Template->assign_vars(array(
		'C_FIELD_EDIT' => true,
		'ID' => $extend_field['id'],
		'NAME' => $extend_field['name'],
		'CONTENTS' => str_replace('<br />', '', $extend_field['contents']),
		'POSSIBLE_VALUES' => $extend_field['possible_values'],
		'DEFAULT_VALUES' => $extend_field['default_values'],
		'REGEX' => (!$predef_regex) ? $extend_field['regex'] : '',
		'REGEX1_CHECKED' => ($regex_checked == 1) ? 'checked="checked"' : '',
		'REGEX2_CHECKED' => ($regex_checked == 2) ? 'checked="checked"' : '',
		'DISABLED' => ($extend_field['field'] > 2) ? ' disabled="disabled"' : '',
	));

	//Gestion erreur.
	$get_error = retrieve(GET, 'error', '');
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif ($get_error == 'exist_field')
		$Errorh->handler($LANG['e_exist_field'], E_USER_NOTICE);
	
	$array_field = array(
		1 => $LANG['short_text'],
		2 => $LANG['long_text'], 
		3 => $LANG['sel_uniq'], 
		4 => $LANG['sel_mult'], 
		5 => $LANG['check_uniq'],
		6 => $LANG['check_mult']
	);
	$option_field = '';
	foreach ($array_field as $key => $value)
	{
		$selected = ($key == $extend_field['field']) ? 'selected="selected"' : '';
		$option_field .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	}
	
	$array_regex = array(
		1 => $LANG['figures'],
		2 => $LANG['letters'], 
		3 => $LANG['figures_letters'], 
		4 => $LANG['mail'], 
		5 => $LANG['website']
	);
	
	$selected = (!$predef_regex) ? 'selected="selected"' : ''; 
	$option_regex = '<option value="0" ' . $selected . '>--</option>';
	foreach ($array_regex as $key => $value)
	{
		$selected = ($key == $extend_field['regex']) ? 'selected="selected"' : '';
		$option_regex .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
	}
	
	$Template->assign_vars(array(
		'REGEX' => (!$predef_regex) ? $extend_field['regex'] : '',
		'OPTION_REGEX' => $option_regex,
		'OPTION_FIELD' => $option_field,
		'REQUIRED_FIELD_ENABLE' => $extend_field['required'] ? 'checked="checked"' : '',
		'REQUIRED_FIELD_DISABLE' => !$extend_field['required'] ? 'checked="checked"' : '',
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_DEFAULT_FIELD_VALUE' => $LANG['default_field_possible_values'],
		'L_EXTEND_FIELD_MANAGEMENT' => $LANG['extend_field_management'],
		'L_EXTEND_FIELD_ADD' => $LANG['extend_field_add'],
		'L_EXTEND_FIELD' => $LANG['extend_field'],
		'L_EXTEND_FIELD_EDIT' => $LANG['extend_field_edit'],
		'L_REQUIRE' => $LANG['require'],
		'L_NAME' => $LANG['name'],
		'L_TYPE' => $LANG['type'],
		'L_DESC' => $LANG['description'],		
		'L_REQUIRED_FIELD' => $LANG['require_field'],
		'L_REQUIRED_FIELD_EXPLAIN' => $LANG['require_field_explain'],
		'L_REQUIRED' => $LANG['required'],
		'L_NOT_REQUIRED' => $LANG['not_required'],
		'L_SHORT_TEXT' => $LANG['short_text'],
		'L_LONG_TEXT' => $LANG['long_text'],
		'L_SEL_UNIQ' => $LANG['sel_uniq'],
		'L_SEL_MULT' => $LANG['sel_mult'],
		'L_CHECK_UNIQ' => $LANG['check_uniq'],
		'L_CHECK_MULT' => $LANG['check_mult'],
		'L_PERSO_REGEXP' => $LANG['personnal_regex'],
		'L_PREDEF_REGEXP' => $LANG['predef_regexp'],
		'L_FIGURES' => $LANG['figures'],
		'L_LETTERS' => $LANG['letters'],
		'L_FIGURES_LETTERS' => $LANG['figures_letters'],
		'L_MAIL' => $LANG['mail'],
		'L_WEBSITE' => $LANG['website'],
		'L_POSSIBLE_VALUES' => $LANG['possible_values'],
		'L_POSSIBLE_VALUES_EXPLAIN' => $LANG['possible_values_explain'],
		'L_DEFAULT_VALUE' => $LANG['default_values'],
		'L_DEFAULT_VALUE_EXPLAIN' => $LANG['default_values_explain'],
		'L_REGEX' => $LANG['regex'],
		'L_REGEX_EXPLAIN' => $LANG['regex_explain'],
		'L_RESET' => $LANG['reset'],
		'L_UPDATE' => $LANG['update'],
	));
}
else
{
	$Template->set_filenames(array(
		'admin_extend_field'=> 'admin/admin_extend_field.tpl'
	));
	
	$Template->assign_vars(array(
		'C_FIELD_MANAGEMENT' => true,
		'L_EXTEND_FIELD_MANAGEMENT' => $LANG['extend_field_management'],
		'L_EXTEND_FIELD_ADD' => $LANG['extend_field_add'],
		'L_EXTEND_FIELD' => $LANG['extend_field'],
		'L_NAME' => $LANG['name'],
		'L_UPDATE' => $LANG['update'],
		'L_REQUIRED' => $LANG['required'],
		'L_POSITION' => $LANG['position'],
		'L_DELETE' => $LANG['delete'],
		'L_UPDATE' => $LANG['update'],
	));
	
	$min_cat = $Sql->query("SELECT MIN(class) FROM " . PREFIX . "member_extend_cat WHERE display = 1", __LINE__, __FILE__);
	$max_cat = $Sql->query("SELECT MAX(class) FROM " . PREFIX . "member_extend_cat WHERE display = 1", __LINE__, __FILE__);
	
	$result = $Sql->query_while("SELECT id, class, name, required
	FROM " . PREFIX . "member_extend_cat
	WHERE display = 1
	ORDER BY class", __LINE__, __FILE__);
	while ($row = $Sql->fetch_assoc($result))
	{
		//Si on atteint le premier ou le dernier id on affiche pas le lien inaproprié.
		$top_link = $min_cat != $row['class'] ? '<a href="admin_extend_field.php?top=' . $row['class'] . '&amp;id=' . $row['id'] . '" title="">
		<img src="../templates/' . get_utheme() . '/images/admin/up.png" alt="" title="" /></a>' : '';
		$bottom_link = $max_cat != $row['class'] ? '<a href="admin_extend_field.php?bot=' . $row['class'] . '&amp;id=' . $row['id'] . '" title="">
		<img src="../templates/' . get_utheme() . '/images/admin/down.png" alt="" title="" /></a>' : '';

		$Template->assign_block_vars('field', array(
			'ID' => $row['id'],
			'NAME' => $row['name'],
			'L_REQUIRED' => $row['required'] ? $LANG['yes'] : $LANG['no'],
			'TOP' => $top_link,
			'BOTTOM' => $bottom_link
		));		
	}
	$Sql->query_close($result);
}

$Template->pparse('admin_extend_field');		
	
require_once('../admin/admin_footer.php');

?>