<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 06 03
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
