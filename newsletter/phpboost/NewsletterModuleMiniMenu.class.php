<?php
/*##################################################
 *                          NewsletterModuleMiniMenu.class.php
 *                            -------------------
 *   begin                : October 08, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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

class NewsletterModuleMiniMenu extends ModuleMiniMenu
{    
    public function get_default_block()
    {
    	return self::BLOCK_POSITION__TOP_FOOTER;
    }

	public function display($tpl = false)
    {
    	$tpl = new FileTemplate('newsletter/newsletter_mini.tpl');
	    MenuService::assign_positions_conditions($tpl, $this->get_block());
	    
	    $lang = LangLoader::get('newsletter_common', 'newsletter');
	    $tpl->put_all(array(
	    	'SUBSCRIBE' => $lang['newsletter.subscribe_newsletters'],
	    	'UNSUBSCRIBE' => $lang['newsletter.unsubscribe_newsletters'],
	    	'USER_MAIL' => (AppContext::get_user()->get_attribute('user_mail') != '') ? AppContext::get_user()->get_attribute('user_mail') : '',
	    	'L_NEWSLETTER' => $lang['newsletter'],
	    	'L_SUBMIT' => $lang['newsletter.submit'],
	    	'L_ARCHIVES' => $lang['newsletter.archives']
	    ));
	
	    return $tpl->render();
    }
}
?>