<?php
/*##################################################
 *                        PlainCodeHighlighter.class.php
 *                            -------------------
 *   begin                : January 3, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This is a manual syntax highlighter for plain code with the [highlight] 
 * tag to choose what to highlight.
 */
class PlainCodeHighlighter extends AbstractParser
{
	const HIGHLIGHTING_STYLE = 'color:#BA154C; font-weight:bold;';
	
	/**
	 * @desc Build a PlainCodeHighlighter object.
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
		$this->content = preg_replace('`\[highlight\](.*)\[/highlight\]`iSU', '<span style="' . self::HIGHLIGHTING_STYLE . '">$1</span>', $this->content);
	}
}
?>