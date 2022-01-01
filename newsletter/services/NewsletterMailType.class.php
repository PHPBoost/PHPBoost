<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 24
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

interface NewsletterMailType
{
	/**
	 * @desc This function send mail
	 * @param instance of NewsletterMailService $newsletter_mail_service.
	 */
	public function send_mail($subscribers, $sender, $subject, $content);

	/**
	 * @desc This function displayed mail
	 * @param instance of NewsletterMailService $newsletter_mail_service.
	 */
	public function display_mail($subject, $content);

	/**
	 * @desc This function parse content mail
	 * @param instance of NewsletterMailService $newsletter_mail_service.
	 */
	public function parse_content($content);
}
?>
