<?php
/*##################################################
 *                        TemplateSyntaxChecker.class.php
 *                            -------------------
 *   begin                : June 17 2010
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
 * @subpackage template/parser
 * @desc 
 * @author Benoit sautel <ben.popeye@gmail.com>
 */
class TemplateSyntaxChecker
{
	private $context = array();
	/**
	 * @var TemplateSyntaxCheckerState
	 */
	private $state;
	private $code;
	
	public function __construct($code)
	{
		$this->code = $code;
		$this->state = new OutOfTemplateSyntaxCheckerState($this);
	}

	public function check_syntax()
	{
		for ($i = 0; $i < strlen($this->code); $i++)
		{
			$this->state->handle($this->code[$i]);
		}		
	}
	
	public function set_state(TemplateSyntaxCheckerState $state)
	{
		$this->state = $state;
	}
	
	public function push_context($context)
	{
		$this->context[] = $context;
	}
	
	public function pop_context()
	{
		return array_pop($this->context);
	}
	
	public function get_context()
	{
		return $this->context;
	}
}

?>