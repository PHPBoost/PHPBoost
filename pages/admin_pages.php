<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 23
 * @since       PHPBoost 1.6 - 2007 08 09
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

require_once('../admin/admin_begin.php');
load_module_lang('pages');
define('TITLE', $LANG['administration']);
require_once('../admin/admin_header.php');

include_once('pages_begin.php');
include_once('pages_functions.php');

$request = AppContext::get_request();

$update = $request->get_postvalue('update', false);

$tpl = new FileTemplate('pages/admin_pages.tpl');

if ($update)  //Mise à jour
{
	$pages_config->set_authorizations(Authorizations::build_auth_array_from_form(READ_PAGE, EDIT_PAGE, READ_COM));
	$pages_config->set_count_hits_activated(retrieve(POST, 'count_hits', false));
	$pages_config->set_comments_activated(retrieve(POST, 'comments_activated', false));
	$pages_config->set_left_column_disabled(retrieve(POST, 'left_column_disabled', false));
	$pages_config->set_right_column_disabled(retrieve(POST, 'right_column_disabled', false));

	PagesConfig::save();

	###### Régénération du cache #######
	PagesCategoriesCache::invalidate();

	$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('message.success.config', 'status-messages-common'), MessageHelper::SUCCESS, 4));
}

//Configuration des authorisations
$config_authorizations = $pages_config->get_authorizations();

$tpl->put_all(array(
	'HITS_CHECKED'              => ($pages_config->get_count_hits_activated() == true) ? 'checked="checked"' : '',
	'COM_CHECKED'               => ($pages_config->get_comments_activated() == true) ? 'checked="checked"' : '',
	'SELECT_READ_PAGE'          => Authorizations::generate_select(READ_PAGE, $config_authorizations),
	'SELECT_EDIT_PAGE'          => Authorizations::generate_select(EDIT_PAGE, $config_authorizations),
	'SELECT_READ_COM'           => Authorizations::generate_select(READ_COM, $config_authorizations),
	'L_READ_COM'                => $LANG['pages_auth_read_com'],
	'L_EDIT_PAGE'               => $LANG['pages_auth_edit'],
	'L_READ_PAGE'               => $LANG['pages_auth_read'],
	'L_SELECT_NONE'             => $LANG['select_none'],
	'L_SELECT_ALL'              => $LANG['select_all'],
	'L_EXPLAIN_SELECT_MULTIPLE' => $LANG['explain_select_multiple'],
	'L_AUTH'                    => $LANG['pages_auth'],
	'L_COUNT_HITS_EXPLAIN'      => $LANG['pages_count_hits_explain'],
	'L_COUNT_HITS'              => $LANG['pages_count_hits_activated'],
	'L_PAGES'                   => $LANG['pages'],
	'L_UPDATE'                  => $LANG['update'],
	'L_RESET'                   => $LANG['reset'],
	'L_COMMENTS_ACTIVATED'      => $LANG['pages_comments_activated'],
	'L_PAGES_CONGIG'            => $LANG['pages_config'],
	'L_PAGES_MANAGEMENT'        => $LANG['pages_management'],
	'HIDE_LEFT_COLUMN_CHECKED'  => ($pages_config->is_left_column_disabled() == true) ? 'checked="checked"' : '',
	'HIDE_RIGHT_COLUMN_CHECKED' => ($pages_config->is_right_column_disabled() == true) ? 'checked="checked"' : '',
	'L_HIDE_LEFT_COLUMN'        => StringVars::replace_vars(LangLoader::get_message('config.hide_left_column', 'admin-common'), array('module' => "pages")),
	'L_HIDE_RIGHT_COLUMN'       => StringVars::replace_vars(LangLoader::get_message('config.hide_right_column', 'admin-common'), array('module' => "pages"))
));

$tpl->display();

require_once('../admin/admin_footer.php');

?>
