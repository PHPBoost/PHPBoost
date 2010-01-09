<?php
/*##################################################
 *                       BBCodeParserFactory.class.php
 *                            -------------------
 *   begin                : December 20, 2000
 *   copyright            : (C) 2009 Benoit Sautel
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
 * @package content
 * @subpackage formatting/factory
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is a factory which generates every formatting element corresponding
 * to the BBCode formatting syntax.
 */
class BBCodeParserFactory extends AbstractContentFormattingFactory
{
	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/content/formatting/factory/ContentFormattingFactory#get_parser()
	 */
	public function get_parser()
	{
		$parser = new BBCodeParser();
		$parser->set_forbidden_tags($this->get_forbidden_tags());
		$parser->set_html_auth($this->get_html_auth());
		return $parser;
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/content/formatting/factory/ContentFormattingFactory#get_unparser()
	 */
	public function get_unparser()
	{
		return new BBCodeUnparser();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/content/formatting/factory/ContentFormattingFactory#get_second_parser()
	 */
	public function get_second_parser()
	{
		return new ContentSecondParser();
	}

	/**
	 * (non-PHPdoc)
	 * @see kernel/framework/content/formatting/factory/ContentFormattingFactory#get_editor()
	 */
	public function get_editor()
	{
		$editor = new BBCodeEditor();
		$editor->set_forbidden_tags($this->get_forbidden_tags());
		return $editor;
	}
}

?>
