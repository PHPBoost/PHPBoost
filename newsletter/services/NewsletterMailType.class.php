<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2011 02 01
*/

interface NewsletterMailType
{
	/**
	 * @desc This function send mail
	 * @param instance of NewsletterMailService $newsletter_mail_service.
	 */
	public function send_mail($subscribers, $sender, $subject, $contents);

	/**
	 * @desc This function displayed mail
	 * @param instance of NewsletterMailService $newsletter_mail_service.
	 */
	public function display_mail($subject, $contents);

	/**
	 * @desc This function parse contents mail
	 * @param instance of NewsletterMailService $newsletter_mail_service.
	 */
	public function parse_contents($contents);
}
?>
