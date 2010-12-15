<?php
/*##################################################
 *                               admin_extend_field_add.php
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

if (!empty($_POST['valid'])) //Insertion du nouveau champs.
{
	$regex_type = retrieve(POST, 'regex_type', 0);
	$regex = empty($regex_type) ? retrieve(POST, 'regex1', 0) : retrieve(POST, 'regex2', '');
	
	$extended_field = new ExtendedField();
	$extended_field->set_name(retrieve(POST, 'name', ''));
	$extended_field->set_field_name(ExtendedField::rewrite_field_name(retrieve(POST, 'name', '')));
	$extended_field->set_position($Sql->query("SELECT MAX(position) + 1 FROM " . DB_TABLE_MEMBER_EXTEND_CAT . "", __LINE__, __FILE__));
	$extended_field->set_description(retrieve(POST, 'contents', '', TSTRING));
	$extended_field->set_field_type(retrieve(POST, 'field', 0));
	$extended_field->set_possible_values(retrieve(POST, 'possible_values', ''));
	$extended_field->set_default_values(retrieve(POST, 'default_values', ''));
	$extended_field->set_is_required(retrieve(POST, 'required', 0));
	$extended_field->set_display(retrieve(POST, 'display', 0));
	$extended_field->set_regex($regex);
	$extended_field->set_authorization(Authorizations::auth_array_simple(ExtendedField::AUTHORIZATION, 'auth'));
	
	ExtendedFieldsService::add($extended_field);

	AppContext::get_response()->redirect('/admin/admin_extend_field.php');
}
else
{
	$Template->set_filenames(array(
		'admin_extend_field_add'=> 'admin/admin_extend_field_add.tpl'
	));
	
	$Template->put_all(array(
		'AUTH' => Authorizations::generate_select(ExtendedField::AUTHORIZATION, array('r-1' => 1, 'r0' => 1, 'r1' => 1, 'r2' => 1), array(2 => true), 'auth'),
		'L_AUTH' => $LANG['authorizations'],
		'L_REQUIRE_NAME' => $LANG['require_title'],
		'L_DEFAULT_FIELD_VALUE' => $LANG['default_field_possible_values'],
		'L_EXTEND_FIELD_MANAGEMENT' => $LANG['extend_field_management'],
		'L_EXTEND_FIELD_ADD' => $LANG['extend_field_add'],
		'L_EXTEND_FIELD' => $LANG['extend_field'],
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
		'L_SUBMIT' => $LANG['submit'],
	));
	
	$Template->pparse('admin_extend_field_add');		
}

require_once('../admin/admin_footer.php');

?>