<?php
/*##################################################
 *                             formating_helper.inc.php
 *                            -------------------
 *   begin                : Januar 21, 2010
 *   copyright            : (C) 2010 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @desc Parses a string with several default parameters. This methods exists to lighten the number of lines written.
 * @param string $content Content to parse
 * @param string[] $forbidden_tags List of the forbidden formatting tags
 * @param bool $addslashes if true, the parsed string will be escaped.
 * @return string The parsed string.
 */
function strparse($content, $forbidden_tags = array(), $addslashes = true)
{
	$parser = ContentFormattingMetaFactory::get_default_parser();

	//On assigne le contenu à interpréter. Il supprime les antislashes d'échappement seulement si ils ont été ajoutés par magic_quotes
	$parser->set_content($content, MAGIC_QUOTES);

	//Si il y a des balises interdites, on lui signale
	if (!empty($forbidden_tags))
	{
		$parser->set_forbidden_tags($forbidden_tags);
	}
	//Au travail maintenant !
	$parser->parse();

	//Renvoie le résultat. Echappe par défaut les caractères critiques afin d'être envoyé en base de données
	return $parser->get_content($addslashes);
}

/**
 * @desc Unparses a string with several default parameters. This methods exists to lighten the number of lines written.
 * @param string $content Content to unparse
 * @param string[] $forbidden_tags List of the forbidden formatting tags
 * @param bool $addslashes if true, the unparsed string will be escaped.
 * @return string The unparsed string.
 * @see ContentFormattingUnparser
 */
function unparse($content)
{
	$parser = ContentFormattingMetaFactory::get_default_unparser();
	$parser->set_content($content, FormattingParser::DONT_STRIP_SLASHES);
	$parser->parse();

	return $parser->get_content(FormattingParser::DONT_ADD_SLASHES);
}

/**
 * @desc Second parses a string with several default parameters. This methods exists to lighten the number of lines written.
 * @param string $content Content to second parse
 * @param string[] $forbidden_tags List of the forbidden formatting tags
 * @param bool $addslashes if true, the second parsed string will be escaped.
 * @return string The second parsed string.
 * @see ContentSecondParser
 */
function second_parse($content)
{
	$parser = ContentFormattingMetaFactory::get_default_second_parser();
	$parser->set_content($content, FormattingParser::DONT_STRIP_SLASHES);
	$parser->parse();

	return $parser->get_content(FormattingParser::DONT_ADD_SLASHES);
}

/**
 * @desc Second parses relative urls to absolute urls.
 * @param string $url Url to second parse
 * @return string The second parsed url.
 * @see Url
 */
function second_parse_url($url)
{
	$Url = new Url($url);
	return $Url->absolute();
}

?>
