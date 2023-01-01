<?php
/**
 * This is a manual syntax highlighter for plain code with the [highlight]
 * tag to choose what to highlight.
 * @package     Content
 * @subpackage  Formatting\parser
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2014 12 22
 * @since       PHPBoost 3.0 - 2010 01 03
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
*/

class PlainCodeHighlighter extends AbstractParser
{
	const HIGHLIGHTING_STYLE = 'color:#BA154C; font-weight:bold;';

	/**
	 * Build a PlainCodeHighlighter object.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * {@inheritdoc}
	 */
	public function parse()
	{
		$this->content = preg_replace('`\[highlight\](.*)\[/highlight\]`iSuU', '<span style="' . self::HIGHLIGHTING_STYLE . '">$1</span>', $this->content);
	}
}
?>
