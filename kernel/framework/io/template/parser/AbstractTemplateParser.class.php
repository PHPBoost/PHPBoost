<?php
/*##################################################
 *                       AbstractTemplateParser.class.php
 *                            -------------------
 *   begin                : June 18 2009
 *   copyright            : (C) 2009 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
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
 * @package {@package}
 * @desc This is an abstract implementation of the {@link TemplateParser} interface.
 * @author Loic Rouchon <loic.rouchon@phpboost.com>
 */
abstract class AbstractTemplateParser implements TemplateParser
{
	protected $content;

	/**
	 * {@inheritdoc}
	 */
	public function parse($content)
	{
		$this->content = $content;
		$this->do_parse();
		return $this->content;
	}
	
	abstract protected function do_parse();
	
	protected function clean()
	{
		$this->content = preg_replace(
		array('`# START [\w\.]+ #(.*)# END [\w\.]+ #`s', '`# START [\w\.]+ #`', '`# END [\w\.]+ #`', '`{[\w\.]+}`'),
		array('', '', '', ''),
		$this->content
		);
	}

	protected function get_getvar_method_name($varname)
	{
		$method = 'var';
		$tiny_varname = $varname;

		$split_index = strpos($varname, '_');

		if ($split_index > 0)
		{
			$prefix = substr($varname, 0, $split_index);
			$tiny_var = substr($varname, $split_index + 1);
			switch ($prefix)
			{
				case 'L':
					$method = 'lang_var';
					$tiny_varname =& $tiny_var;
					break;
				case 'E':
					$method = 'htmlescaped_var';
					$tiny_varname =& $tiny_var;
					break;
				case 'J':
					$method = 'js_var';
					$tiny_varname =& $tiny_var;
					break;
				case 'EL':
					$method = 'htmlescaped_lang_var';
					$tiny_varname =& $tiny_var;
					break;
				case 'JL':
					$method = 'js_lang_var';
					$tiny_varname =& $tiny_var;
					break;
				default:
					break;
			}
		}

		return array('method' => 'get_' . $method, 'varname' => $tiny_varname);
	}
}
?>