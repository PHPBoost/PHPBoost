<?php
/*##################################################
 *                        HTMLNewsletterMail.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kevin MASSY
 *   email                : kevin.massy@phpboost.com
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class HTMLNewsletterMail extends AbstractNewsletterMail
{
	public function send_mail($subscribers, $sender, $subject, $contents)
	{
		$contents = $this->parse_contents($contents) . $this->add_unsubscribe_link();
		
		parent::send_mail($subscribers, $sender, $subject, $contents);
	}
	
	public function display_mail($subject, $contents)
	{
		return stripslashes($contents);
	}
	
	public function parse_contents($contents)
	{
		$contents = stripslashes($contents);
		$contents = $this->clean_html($contents);
		return ContentSecondParser::export_html_text($contents);
	}
	
	private function clean_html($contents)
	{
		$contents = TextHelper::htmlentities($contents, ENT_NOQUOTES);
		$contents = str_replace(array('&amp;', '&lt;', '&gt;'), array('&', '<', '>'), $contents);
		return $contents;
	}
}
?>