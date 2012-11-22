<?php
/*##################################################
*                               faq.php
*                            -------------------
*   begin                : November 10, 2007
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

//if the category doesn't exist or is not visible
if (!array_key_exists($id_faq, $FAQ_CATS) || (array_key_exists($id_faq, $FAQ_CATS) && $id_faq > 0 && !$FAQ_CATS[$id_faq]['visible']))
{
	$controller = PHPBoostErrors::unexisting_category();
    DispatchManager::redirect($controller);
}

$TITLE = FaqUrlBuilder::get_title($id_faq);
define('TITLE', $faq_config->get_faq_name() . ($id_faq > 0 ? ' - ' . $TITLE : ''));

$id_cat_for_bread_crumb = $id_faq;
include_once('faq_bread_crumb.php');
include_once('../kernel/header.php');

//checking authorization
if (!$auth_read)
{
	$error_controller = PHPBoostErrors::user_not_authorized();
	DispatchManager::redirect($error_controller);
}

$modulesLoader = AppContext::get_extension_provider_service();
$module = $modulesLoader->get_provider('faq');
if ($module->has_extension_point(HomePageExtensionPoint::EXTENSION_POINT))
{
	echo $module->get_extension_point(HomePageExtensionPoint::EXTENSION_POINT)->get_home_page()->get_view()->display();
}

include_once('../kernel/footer.php'); 

?>