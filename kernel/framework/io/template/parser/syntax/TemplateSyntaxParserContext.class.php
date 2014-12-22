<?php
/*##################################################
 *                      TemplateSyntaxParserContext.class.php
 *                            -------------------
 *   begin                : October 02 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : horn@phpboost.com
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

class TemplateSyntaxParserContext
{
	private $loops = array();
	
	public function enter_loop($name)
	{
		$this->loops[] = $name;
	}
	
	public function exit_loop()
	{
		array_pop($this->loops);
	}
	
	public function is_in_loop($name)
	{
		return in_array($name, $this->loops);
	}
	
	public function loops_scopes()
	{
		return $this->loops;
	}
}

?>