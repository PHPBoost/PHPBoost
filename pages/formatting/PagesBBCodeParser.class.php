<?php
/*##################################################
 *                          PagesBBCodeParser.class.php
 *                            -------------------
 *   begin                : May 29 2016
 *   copyright            : (C) 2016 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 * @desc Converts the PHPBoost BBCode language to the XHTML language which is stocked in
 * the database and can be displayed nearly directly.
 * It parses only the authorized tags (defined in the parent class which is ContentFormattingParser) and the pages link tag.
 */
class PagesBBCodeParser extends BBCodeParser
{
	/**
	 * @desc Builds a WikiBBCodeParser object
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @desc Parses the parser content from BBCode to XHTML, including the pages link tag.
	 * @return void You will find the result by using the get_content method
	 */
	public function parse()
	{
		//On supprime d'abord toutes les occurences de balises CODE que nous réinjecterons à la fin pour ne pas y toucher
		if (!in_array('code', $this->forbidden_tags))
		{
			$this->pick_up_tag('code', '=[A-Za-z0-9#+-]+(?:,[01]){0,2}');
		}
		
		$this->content = preg_replace('`\[link=([a-z0-9+#-_]+)\](.+)\[/link\]`isU', '[url=/pages/$1]$2[/url]', $this->content);
		
		parent::parse();
	}
}
?>
