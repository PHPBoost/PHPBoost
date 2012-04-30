<?php
/*##################################################
 *                              guestbook.php
 *                            -------------------
 *   begin                : July 11, 2005
 *   copyright            : (C) 2005 Viarre Régis
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
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

require_once('../kernel/begin.php');
require_once('../guestbook/guestbook_begin.php');
require_once('../kernel/header.php');
$id_get = retrieve(GET, 'id', 0);
$guestbook = retrieve(POST, 'guestbookForm', false);

$guestbook_config = GuestbookConfig::load();
$authorizations = $guestbook_config->get_authorizations();

if (!$User->check_auth($authorizations, GuestbookConfig::AUTH_READ)) //Autorisation de lire ?
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$del = retrieve(GET, 'del', false);
if ($del && !empty($id_get)) //Suppression.
{
	$row = $Sql->query_array(PREFIX . 'guestbook', '*', "WHERE id='" . $id_get . "'", __LINE__, __FILE__);
	$row['user_id'] = (int)$row['user_id'];
	
	$has_edit_auth = $User->check_auth($authorizations, GuestbookConfig::AUTH_MODO) 
		|| ($row['user_id'] === $User->get_attribute('user_id') && $User->get_attribute('user_id') !== -1);
	if ($has_edit_auth) {
		$Session->csrf_get_protect(); //Protection csrf
	
		$Sql->query_inject("DELETE FROM " . PREFIX . "guestbook WHERE id = '" . $id_get . "'", __LINE__, __FILE__);
		$previous_id = $this->sql_querier->query("SELECT MAX(id) FROM " . PREFIX . "guestbook", __LINE__, __FILE__);
	
		GuestbookMessagesCache::invalidate();
	
		AppContext::get_response()->redirect(HOST . SCRIPT . SID2 . '#m' . $previous_id);
	}
}

$modulesLoader = AppContext::get_extension_provider_service();
$module = $modulesLoader->get_provider('guestbook');
if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
{
	echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
}

require_once('../kernel/footer.php');

?>