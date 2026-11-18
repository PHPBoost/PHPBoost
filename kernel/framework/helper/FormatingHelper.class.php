<?php
/**
 * Formating helper
 * @package     Helper
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 10 24
 * @since       PHPBoost 3.0 - 2010 01 21
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class FormatingHelper
{
	const NO_EDITOR_UNPARSE = false;

	/**
	 * Parses a string with several default parameters. This methods exists to lighten the number of lines written.
	 * @param mixed $content Content to parse
	 * @param array $forbidden_tags List of the forbidden formatting tags
	 * @param bool $addslashes if true, the parsed string will be escaped.
	 * @return string The parsed string.
	 */
	public static function strparse(mixed $content, array $forbidden_tags = [], bool $addslashes = true): string
	{
		$parser = AppContext::get_content_formatting_service()->get_default_parser();
		if ($parser === null)
		{
			return $addslashes ? addslashes($content) : $content;
		}

		// We assign the content to be interpreted. It removes escape backslashes only if they were added by magic_quotes.
		$parser->set_content($content);

		// Any prohibited tags are reported
		if (!empty($forbidden_tags) && method_exists($parser, 'set_forbidden_tags'))
		{
			$parser->set_forbidden_tags($forbidden_tags);
		}
		$parser->parse();

		// Returns the result. By default, escapes critical characters in order to be sent to the database
		$result = $parser->get_content();
		if ($addslashes)
		{
			$result = addslashes($result);
		}
		return $result;
	}

	/**
	 * Unparses a string with several default parameters. This methods exists to lighten the number of lines written.
	 * @param string $content Content to unparse
	 * @return string The unparsed string.
	 * @see ContentFormattingUnparser
	 */
	public static function unparse(mixed $content): string
	{
		$parser = AppContext::get_content_formatting_service()->get_default_unparser();
		if ($parser === null)
		{
			return $content;
		}
		$parser->set_content(TextHelper::stripslashes($content));
		$parser->parse();

		return $parser->get_content();
	}

	/**
	 * Second parses a string with several default parameters. This methods exists to lighten the number of lines written.
	 * @param string $content Content to second parse
	 * @return string The second parsed string.
	 * @see ContentSecondParser
	 */
	public static function second_parse(mixed $content): string
	{
		$parser = AppContext::get_content_formatting_service()->get_default_second_parser();
        if ($parser === null)
        {
            return $content;
        }
		$parser->set_content($content);
		$parser->parse();

		return $parser->get_content();
	}

	/**
	 * Second parses relative urls to absolute urls.
	 * @param string $url Url to second parse
	 * @return string The second parsed url.
	 * @see Url
	 */
	public static function second_parse_url(string $url): string
	{
		$Url = new Url($url);
		return $Url->absolute();
	}
}
?>
