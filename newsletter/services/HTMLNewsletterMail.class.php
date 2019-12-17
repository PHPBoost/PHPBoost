<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 03 21
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
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
		return TextHelper::htmlspecialchars_decode(stripslashes($contents));
	}

	public function parse_contents($contents)
	{
		$contents = stripslashes($contents);
		$contents = $this->clean_html($contents);
		return ContentSecondParser::export_html_text($contents);
	}

	private function clean_html($contents)
	{
		$contents = TextHelper::htmlspecialchars($contents, ENT_NOQUOTES);
		$contents = str_replace(array('&amp;', '&lt;', '&gt;'), array('&', '<', '>'), $contents);
		return $contents;
	}
}
?>
