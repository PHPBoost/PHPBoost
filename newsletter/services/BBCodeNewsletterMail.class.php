<?php
/*##################################################
 *                        BBCodeNewsletterMail.class.php
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
class BBCodeNewsletterMail extends AbstractNewsletterMail
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function send_mail($subscribers, $sender, $subject, $contents)
	{
		$mail_contents = '<html><head><title>' . $subject . '</title></head><body>';
		$mail_contents .= $this->parse_contents($contents). $this->add_unsubscribe_link();
		$mail_contents .= '</body></html>';
		
		parent::send_mail($subscribers, $sender, $subject, $mail_contents);
	}
	
	public function display_mail($subject, $contents)
	{
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'. $contents;
	}
	
	public function parse_contents($contents)
	{
		return ContentSecondParser::export_html_text(FormatingHelper::second_parse($contents));
	}
}
?>