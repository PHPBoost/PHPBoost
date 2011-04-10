<?php
/*##################################################
 *                                link.php
 *                            -------------------
 *   begin                : July 06, 2006
 *   copyright            : (C) 2006 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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

if (defined('PHPBOOST') !== true)	exit;

function newsletter_mini($position, $block)
{
    $tpl = new FileTemplate('newsletter/newsletter_mini.tpl');

    MenuService::assign_positions_conditions($tpl, $block);

	$lang = LangLoader::get('newsletter_common', 'newsletter');
	
    $tpl->put_all(array(
    	'SUBSCRIBE' => $lang['newsletter.subscribe_newsletters'],
    	'UNSUBSCRIBE' => $lang['newsletter.unsubscribe_newsletters'],
    	'USER_MAIL' => AppContext::get_user()->get_email(),
    	'L_NEWSLETTER' => $lang['newsletter'],
    	'L_SUBMIT' => $lang['newsletter.submit'],
    	'L_ARCHIVES' => $lang['newsletter.archives']
    ));

    return $tpl->render();
}
?>