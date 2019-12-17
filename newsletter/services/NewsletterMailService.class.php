<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 30
 * @since       PHPBoost 3.0 - 2011 02 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
*/

class NewsletterMailService
{
	private static $db_querier;
	const TEXT_LANGUAGE = 'text';
	const BBCODE_LANGUAGE = 'bbcode';
	const HTML_LANGUAGE = 'html';

	public static function __static()
	{
		self::$db_querier = PersistenceContext::get_querier();
	}

	public static function send_mail($language_type, $id_cat, $sender, $subject, $contents)
	{
		$contents = NewsletterMailFactory::parse_contents($language_type, $contents);
		NewsletterMailFactory::send_mail($language_type, $sender, $subject, $contents);

		self::register_archive($language_type, $title, $contents, $id_cat);

		//TOTO Gestion des erreurs
	}

	public static function display_mail($language_type, $id, $title, $contents)
	{
		$row = self::$db_querier->select_single_row(NewsletterSetup::$newsletter_table_archive, array('*'), "WHERE id = '" . $id . "'");
		return NewsletterMailFactory::display_mail($language_type, $row['title'] , $row['contents']);
	}

	private static function register_archive($language_type, $title, $contents, $id_cat)
	{
		$number_subscribers = self::number_subscribers($id_cat);
		$title = TextHelper::strprotect($title, TextHelper::HTML_NO_PROTECT, TextHelper::ADDSLASHES_FORCE);
		$contents = TextHelper::strprotect($contents, HTML_NO_PROTECT, ADDSLASHES_FORCE);

		self::$db_querier->inject(
			"INSERT INTO " . NewsletterSetup::$newsletter_table_archive . " (id_cat, title, contents, timestamp, type, subscribers)
			VALUES (:id_cat, :title, :contents, :timestamp, :type, :field_type, :subscribers)", array(
				'id_cat' => $id_cat,
				'title' => $title,
				'contents' => $contents,
				'timestamp' => time(),
				'type' => $language_type,
				'subscribers' => 0
		));
	}

	public static function number_subscribers($id_cat)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers, 'WHERE id = '. $id_cat .'');
	}
}
?>
