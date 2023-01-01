<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2020 12 24
 * @since       PHPBoost 3.0 - 2011 02 01 11
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class NewsletterConfig extends AbstractConfigData
{
	const MAIL_SENDER             = 'mail_sender';
	const NEWSLETTER_NAME         = 'newsletter_name';
	const DEFAULT_CONTENT         = 'default_content';
	const STREAMS_NUMBER_PER_PAGE = 'streams_number_per_page';
	const AUTHORIZATIONS          = 'authorizations';

	public function get_mail_sender()
	{
		return $this->get_property(self::MAIL_SENDER);
	}

	public function set_mail_sender($sender)
	{
		$this->set_property(self::MAIL_SENDER, $sender);
	}

	public function get_newsletter_name()
	{
		return $this->get_property(self::NEWSLETTER_NAME);
	}

	public function set_newsletter_name($name)
	{
		$this->set_property(self::NEWSLETTER_NAME, $name);
	}

	public function get_default_content()
	{
		return $this->get_property(self::DEFAULT_CONTENT);
	}

	public function set_default_content($value)
	{
		$this->set_property(self::DEFAULT_CONTENT, $value);
	}

	public function get_streams_number_per_page()
	{
		return $this->get_property(self::STREAMS_NUMBER_PER_PAGE);
	}

	public function set_streams_number_per_page($value)
	{
		$this->set_property(self::STREAMS_NUMBER_PER_PAGE, $value);
	}

	public function get_authorizations()
	{
		return $this->get_property(self::AUTHORIZATIONS);
	}

	public function set_authorizations(Array $auth)
	{
		$this->set_property(self::AUTHORIZATIONS, $auth);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return array(
			self::MAIL_SENDER => MailServiceConfig::load()->get_default_mail_sender(),
			self::NEWSLETTER_NAME => '',
			self::DEFAULT_CONTENT => '',
			self::STREAMS_NUMBER_PER_PAGE => 25,
			self::AUTHORIZATIONS => array('r1' => 63, 'r0' => 35, 'r-1' => 35)
		);
	}

	/**
	 * Returns the configuration.
	 * @return NewsletterConfig
	 */
	public static function load()
	{
		return ConfigManager::load(__CLASS__, 'newsletter', 'config');
	}

	/**
	 * Saves the configuration in the database. Has it become persistent.
	 */
	public static function save()
	{
		ConfigManager::save('newsletter', self::load(), 'config');
	}
}
?>
