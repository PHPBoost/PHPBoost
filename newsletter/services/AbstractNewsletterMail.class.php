<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 24
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

abstract class AbstractNewsletterMail implements NewsletterMailType
{
	private $lang;

	public function __construct()
	{
		$this->lang = LangLoader::get('common', 'newsletter');
	}

	/**
	 * {@inheritdoc}
	 */
	public function send_mail($subscribers, $sender, $subject, $content)
	{
		foreach ($subscribers as $properties)
		{
			$mail_subscriber = !empty($properties['mail']) ? $properties['mail'] : NewsletterDAO::get_mail_for_member($properties['user_id']);

			if (!empty($mail_subscriber))
			{
				$mail = new Mail();
				$mail->set_sender($sender);
				$mail->set_subject($subject);
				$mail->set_content(StringVars::replace_vars($content, array('user_display_name' => $properties['display_name'])));

				$mail->add_recipient($mail_subscriber);

				//TODO gestion des erreurs
				AppContext::get_mail_service()->try_to_send($mail);
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function parse_content($content){}

	/**
	 * {@inheritdoc}
	 */
	public function display_mail($subject, $content)
	{
		return $content;
	}

	/**
	 * {@inheritdoc}
	 */
	public function add_unsubscribe_link()
	{
		return '<br /><br /><a href="' . NewsletterUrlBuilder::unsubscribe()->absolute() . '">' . $this->lang['unsubscribe_newsletter'] . '</a><br /><br />';
	}
}
?>
