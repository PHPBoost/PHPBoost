<?php
/**
 * This is a syntax highlighter for the PHPBoost template syntax.
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 01 03
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      mipel <mipel@phpboost.com>
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
	 * Highlights the code using Prism.js (client-side) for HTML base syntax,
	 * then highlights the specific PHPBoost template syntax with inline spans.
	 * @param bool $line_number Whether to show line numbers (adds line-numbers CSS class for Prism).
	 * @param bool $inline_code true if it's a single line code, otherwise false.
	 */
	public function parse($line_number = false, $inline_code = false)
	{
		// Escape HTML entities for safe display
		$this->content = TextHelper::htmlspecialchars($this->content);

		// Highlight PHPBoost template-specific syntax with inline spans

		// Conditional blocks
		$this->content = preg_replace('`# IF( NOT)? ((?:\w+\.)*)(\w+) #`iu', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">IF$1</span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$2</span><span style="' . self::TPL_VARIABLE_STYLE . '">$3</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);
		$this->content = preg_replace('`# ELSEIF( NOT)? ((?:\w+\.)*)(\w+) #`iu', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">ELSEIF$1</span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$2</span><span style="' . self::TPL_VARIABLE_STYLE . '">$3</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);
		$this->content = str_replace('# ELSE #', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">ELSE</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);
		$this->content = str_replace('# ENDIF #', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">ENDIF</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);

		// Loops
		$this->content = preg_replace('`# START ((?:\w+\.)*)(\w+) #`iu', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">START</span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_VARIABLE_STYLE . '">$2</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);
		$this->content = preg_replace('`# END ((?:\w+\.)*)(\w+) #`iu', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">END</span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_VARIABLE_STYLE . '">$2</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);

		// Inclusions
		$this->content = preg_replace('`# INCLUDE ((?:\w+\.)*)([\w]+) #`u', '<span style="' . self::TPL_SHARP_STYLE . '">#</span> <span style="' . self::TPL_KEYWORD_STYLE . '">INCLUDE </span> <span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_VARIABLE_STYLE . '">$2</span> <span style="' . self::TPL_SHARP_STYLE . '">#</span>', $this->content);

		// Simple variable
		$this->content = preg_replace('`{([\w]+)}`iu', '<span style="' . self::TPL_BRACES_STYLE . '">{</span><span style="' . self::TPL_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_BRACES_STYLE . '">}</span>', $this->content);
		// Loop variable
		$this->content = preg_replace('`{((?:[\w]+\.)+)([\w]+)}`iu', '<span style="' . self::TPL_BRACES_STYLE . '">{</span><span style="' . self::TPL_NESTED_VARIABLE_STYLE . '">$1</span><span style="' . self::TPL_VARIABLE_STYLE . '">$2</span><span style="' . self::TPL_BRACES_STYLE . '">}</span>', $this->content);

		// Wrap in a <pre><code> block — Prism.js handles client-side HTML highlighting
		$pre_class = 'precode';
		if ($inline_code)
			$pre_class .= ' precode-inline';
		if ($line_number)
			$pre_class .= ' line-numbers';

		if ($inline_code)
		{
			$this->content = '<pre class="' . $pre_class . '" style="display:inline-flex;"><code class="language-markup">' . $this->content . '</code></pre>';
		}
		else
		{
			$this->content = '<pre class="' . $pre_class . '"><code class="language-markup">' . $this->content . '</code></pre>';
		}
	}
}
?>