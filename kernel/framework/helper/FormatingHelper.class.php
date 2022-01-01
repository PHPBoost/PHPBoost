<?php
/**
 * Formating helper
 * @package     Helper
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2010 01 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class FormatingHelper
{
	const NO_EDITOR_UNPARSE = false;

	/**
	 * Parses a string with several default parameters. This methods exists to lighten the number of lines written.
	 * @param string $content Content to parse
	 * @param string[] $forbidden_tags List of the forbidden formatting tags
	 * @param bool $addslashes if true, the parsed string will be escaped.
	 * @return string The parsed string.
	 */
	public static function strparse($content, $forbidden_tags = array(), $addslashes = true)
	{
		$parser = AppContext::get_content_formatting_service()->get_default_parser();

		//On assigne le contenu à interpréter. Il supprime les antislashes d'échappement seulement si ils ont été ajoutés par magic_quotes
		$parser->set_content($content);

		//Si il y a des balises interdites, on lui signale
		if (!empty($forbidden_tags))
		{
			$parser->set_forbidden_tags($forbidden_tags);
		}
		//Au travail maintenant !
		$parser->parse();

		//Renvoie le résultat. Echappe par défaut les caractères critiques afin d'être envoyé en base de données
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
	public static function unparse($content)
	{
		$parser = AppContext::get_content_formatting_service()->get_default_unparser();
		$parser->set_content(stripslashes($content));
		$parser->parse();

		return $parser->get_content();
	}

	/**
	 * Second parses a string with several default parameters. This methods exists to lighten the number of lines written.
	 * @param string $content Content to second parse
	 * @return string The second parsed string.
	 * @see ContentSecondParser
	 */
	public static function second_parse($content)
	{
		$parser = AppContext::get_content_formatting_service()->get_default_second_parser();
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
	public static function second_parse_url($url)
	{
		$Url = new Url($url);
		return $Url->absolute();
	}
}
?>
