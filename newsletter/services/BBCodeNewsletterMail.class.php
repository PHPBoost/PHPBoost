<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 24
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class BBCodeNewsletterMail extends AbstractNewsletterMail
{
	public function __construct()
	{
		parent::__construct();
	}

	public function send_mail($subscribers, $sender, $subject, $content)
	{
		$mail_content = '<html><head><title>' . $subject . '</title></head><body>';
		$mail_content .= $this->parse_content($content). $this->add_unsubscribe_link();
		$mail_content .= '</body></html>';

		parent::send_mail($subscribers, $sender, $subject, $mail_content);
	}

	public function display_mail($subject, $content)
	{
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'. $content;
	}

	public function parse_content($content)
	{
		return ContentSecondParser::export_html_text(FormatingHelper::second_parse($content));
	}
}
?>
