<?php
/**
 * This is a syntax highlighter for the PHPBoost template syntax.
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 11 14
 * @since       PHPBoost 3.0 - 2010 01 03
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class TemplateHighlighter extends AbstractParser
{
	const TPL_BRACES_STYLE = 'color:#7F3300;';
	const TPL_VARIABLE_STYLE = 'color:#FF6600; font-weight: bold;';
	const TPL_NESTED_VARIABLE_STYLE = 'color:#8F5211;';
	const TPL_SHARP_STYLE = 'color:#9915AF; font-weight: bold;';
	const TPL_KEYWORD_STYLE = 'color:#000066; font-weight: bold;';

	/**
	 * Build a TemplateHighlighter object.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Highlights the code. It uses the geshi HTML syntax highlighter and then it highlights the specific template syntax.
	 * @param int $line_number GESHI_NO_LINE_NUMBERS => no line numbers, GESHI_NORMAL_LINE_NUMBERS line numbers.
	 * @param bool $inline_code true if it's a sigle line code, otherwise false.
	 */
	public function parse($line_number = GESHI_NO_LINE_NUMBERS, $inline_code = false)
	{
		//The template language of PHPBoost contains HTML. We first ask to highlight the html code.
		require_once(PATH_TO_ROOT . '/kernel/lib/php/geshi/geshi.php');

		$geshi = new GeSHi($this->content, 'html');

		if ($line_number) //Affichage des numéros de lignes.
		{
			$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
		}

		//GeSHi must not put any div or pre tag before and after the content
		if ($inline_code)
		{
			$geshi->set_header_type(GESHI_HEADER_NONE);
		}

		$this->content = $geshi->parse_code();

		//Now we highlight the specific syntax of PHPBoost templates

		//Conditionnal block
		$this->content = preg_replace('`# IF ( NOT)? ((?:\w+\.)*)(\w+) #`iu', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">IF$1</span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$2</span><span style="' . self::TPL_VARIABLE_STYLE . '">$3</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);
		$this->content = preg_replace('`# ELSEIF ( NOT)? ((?:\w+\.)*)(\w+) #`iu', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">ELSEIF$1</span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$2</span><span style="' . self::TPL_VARIABLE_STYLE . '">$3</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);
		$this->content = str_replace('# ELSE #', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">ELSE</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);
		$this->content = str_replace('# ENDIF #', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">ENDIF</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);

		//Loops
		$this->content = preg_replace('`# START ((?:\w+\.)*)(\w+) #`iu', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">START</span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_VARIABLE_STYLE . '">$2</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);
		$this->content = preg_replace('`# END ((?:\w+\.)*)(\w+) #`iu', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">END</span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_VARIABLE_STYLE . '">$2</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);

		//Inclusions
		$this->content = preg_replace('`# INCLUDE ((?:\w+\.)*)([\w]+) #`u', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">INCLUDE </span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_VARIABLE_STYLE . '">$2</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);

		//Simple variable
		$this->content = preg_replace('`{([\w]+)}`iu', '<span style="' . self::TPL_BRACES_STYLE . '">{</span><span style="' . self::TPL_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_BRACES_STYLE . '">}</span>', $this->content);
		//Loop variable
		$this->content = preg_replace('`{((?:[\w]+\.)+)([\w]+)}`iu', '<span style="' . self::TPL_BRACES_STYLE . '">{</span><span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_VARIABLE_STYLE . '">$2</span><span style="' . self::TPL_BRACES_STYLE . '">}</span>', $this->content);

		if ($inline_code)
		{
			$this->content = '<pre style="display:inline; font-color:courier new;">' . $this->content . '</pre>';
		}
	}
}
?>
