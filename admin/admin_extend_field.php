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
	$extended_field = new ExtendedField();
	$extended_field->set_id($id);
	
	ExtendedFieldsService::delete($extended_field);
	
	AppContext::get_response()->redirect(HOST . SCRIPT);
}
elseif (!empty($_POST['valid']) && !empty($id))
{
	$regex_type = retrieve(POST, 'regex_type', 0);
	$regex = empty($regex_type) ? retrieve(POST, 'regex1', 0) : retrieve(POST, 'regex2', '');
	
	$extended_field = new ExtendedField();
	$extended_field->set_id($id);
	$extended_field->set_name(retrieve(POST, 'name', ''));
	$extended_field->set_field_name(ExtendedField::rewrite_field_name(retrieve(POST, 'name', '')));
	$extended_field->set_position($Sql->query("SELECT MAX(class) + 1 FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "", __LINE__, __FILE__));
	$extended_field->set_content(retrieve(POST, 'contents', '', TSTRING));
	$extended_field->set_field_type(retrieve(POST, 'field', 0));
	$extended_field->set_possible_values(retrieve(POST, 'possible_values', ''));
	$extended_field->set_default_values(retrieve(POST, 'default_values', ''));
	$extended_field->set_is_required(retrieve(POST, 'required', 0));
	$extended_field->set_display(retrieve(POST, 'display', 0));
	$extended_field->set_regex($regex);
	$extended_field->set_authorization(Authorizations::auth_array_simple(ExtendedField::AUTHORIZATION, $extended_field->get_id()));
	
	ExtendedFieldsService::update($extended_field);
	
	AppContext::get_response()->redirect('/admin/admin_extend_field.php');
}
elseif ((!empty($top) || !empty($bottom)) && !empty($id)) //Monter/descendre.
{
	if (!empty($top))
	{	
		$idmoins = ($top - 1);
			
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class = 0 WHERE class='" . $top . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class=" . $top . " WHERE class = '" . $idmoins . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class=" . $idmoins . " WHERE class = 0", __LINE__, __FILE__);
		
		ExtendFieldsCache::invalidate();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '#e' . $id);
	}
	elseif (!empty($bottom))
	{
		$idplus = ($bottom + 1);
			
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class = 0 WHERE class = '" . $bottom . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class = " . $bottom . " WHERE class = '" . $idplus . "'", __LINE__, __FILE__);
		$Sql->query_inject("UPDATE " . DB_TABLE_MEMBER_EXTEND_CAT . " SET class = " . $idplus . " WHERE class = 0", __LINE__, __FILE__);
			
		ExtendFieldsCache::invalidate();
		
		AppContext::get_response()->redirect(HOST . SCRIPT . '#e' . $id);
		
	}
}
elseif (!empty($id))
{	
	$get_error = retrieve(GET, 'error', TSTRING);
	if ($get_error == 'incomplete')
		$Errorh->handler($LANG['e_incomplete'], E_USER_NOTICE);
	elseif ($get_error == 'exist_field')
		$Errorh->handler($LANG['e_exist_field'], E_USER_NOTICE);
		
	$extend_field = ExtendFieldsCache::load()->get_extend_field($id);

	$regex_checked = 2;
	$predef_regex = false;
	if (is_numeric($extend_field['regex']))
	{
		$regex_checked = 1;
		$predef_regex = true;
	}

	$Template->put_all(array(
		'C_FIELD_EDIT' => true,
		'ID' => $extend_field['id'],
		'NAME' => $extend_field['name'],
		'CONTENTS' => str_replace('<br />', '', $extend_field['contents']),
		'POSSIBLE_VALUES' => $extend_field['possible_values'],
		'DEFAULT_VALUES' => $extend_field['default_values'],
		'REGEX' => ($predef_regex) ? $extend_field['regex'] : '',
		'REGEX1_CHECKED' => ($regex_checked == 1) ? 'checked="checked"' : '',
		'REGEX2_CHECKED' => ($regex_checked == 2) ? 'checked="checked"' : '',
		'DISABLED' => ($extend_field['field'] > 2) ? ' disabled="disabled"' : '',
		'DISPLAY' => $extend_field['display'] ? 'checked="checked"' : '',
		'NOT_DISPLAY' => $extend_field['display'] ? '' : 'checked="checked"',
		'AUTH' => Authorizations::generate_select(ExtendedField::AUTHORIZATION, $extend_field['auth'], array(2 => true), $extend_field['id'])
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
	
	$Template->put_all(array(
		'REGEX' => (!$predef_regex) ? $extend_field['regex'] : '',
		'OPTION_REGEX' => $option_regex,
		'OPTION_FIELD' => $option_field,
		'REQUIRED_FIELD_ENABLE' => $extend_field['required'] ? 'checked="checked"' : '',
		'REQUIRED_FIELD_DISABLE' => !$extend_field['required'] ? 'checked="checked"' : '',
		'L_AUTH' => $LANG['authorizations'],
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
		'L_REQUIRED_FIELD_EXPLAIN' => $LANG['required_field_explain'],
		'L_REQUIRED' => $LANG['required'],
		'L_NOT_REQUIRED' => $LANG['not_required'],
		'L_DISPLAY' => $LANG['display'],
		'L_YES' => $LANG['yes'],
		'L_NO' => $LANG['no'],
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
	
	$Template->put_all(array(
		'C_FIELD_MANAGEMENT' => true,
		'L_EXTEND_FIELD_MANAGEMENT' => $LANG['extend_field_management'],
		'L_EXTEND_FIELD_ADD' => $LANG['extend_field_add'],
		'L_EXTEND_FIELD' => $LANG['extend_field'],
		'L_NAME' => $LANG['name'],
		'L_UPDATE' => $LANG['update'],
		'L_REQUIRED' => $LANG['required'],
		'L_POSITION' => $LANG['position'],
		'L_DISPLAY' => $LANG['display'],
		'L_DELETE' => $LANG['delete'],
		'L_UPDATE' => $LANG['update'],
	));
	
	$extend_field = ExtendFieldsCache::load()->get_extend_fields();
	
	$min_cat = $Sql->query("SELECT MIN(class) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "", __LINE__, __FILE__);
	$max_cat = $Sql->query("SELECT MAX(class) FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "", __LINE__, __FILE__);

	foreach($extend_field as $id => $row)
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
			'L_DISPLAY' => $row['display'] ? $LANG['yes'] : $LANG['no'],
			'TOP' => $top_link,
			'BOTTOM' => $bottom_link
		));	
	}

}

$Template->pparse('admin_extend_field');		
	
require_once('../admin/admin_footer.php');

?>