<?php
/*##################################################
 *                        NewsletterMailService.class.php
 *                            -------------------
 *   begin                : February 1, 2011
 *   copyright            : (C) 2011 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 * @author Kévin MASSY <soldier.weasel@gmail.com>
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

	public static function send_mail($language_type, $sender, $subject, $contents)
	{
		$contents = NewsletterMailFactory::parse_contents($language_type, $contents);
		NewsletterMailFactory::send_mail($language_type, $sender, $subject, $contents);

		self::register_archive($language_type, $title, $contents);

		//TOTO Gestion des erreurs
	}

	public static function display_mail($title, $contents)
	{
		$row = $this->querier->select_single_row(NewsletterSetup::$newsletter_table_archive, array('*'), "WHERE id = '" . $id . "'");
		return NewsletterMailFactory::display_mail($language_type, $row['title'] , $row['contents']);
	}

	private static function register_archive($language_type, $title, $contents)
	{
		$number_subscribers = self::number_subscribers();
		$title = TextHelper::strprotect($title, TextHelper::HTML_NO_PROTECT, TextHelper::ADDSLASHES_FORCE);
		$contents = TextHelper::strprotect($contents, HTML_NO_PROTECT, ADDSLASHES_FORCE);

		self::$db_querier->inject(
			"INSERT INTO " . NewsletterSetup::$newsletter_table_archive . " (title, contents, timestamp, type, subscribers)
			VALUES (:title, :contents, :timestamp, :type, :field_type, :subscribers)", array(
                'title' => $title,
                'contents' => $contents,
				'timestamp' => time(),
				'type' => $language_type,
				'subscribers' => $number_subscribers
		));
	}

	private static function number_subscribers(NewsletterMailService $newsletter_mail_service)
	{
		return self::$db_querier->count(NewsletterSetup::$newsletter_table_subscribers);
	}
}

?>