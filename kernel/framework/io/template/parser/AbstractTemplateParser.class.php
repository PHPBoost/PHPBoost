<?php
/**
 * This is an abstract implementation of the {@link TemplateParser} interface.
 * @package     IO
 * @subpackage  Template\parser
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2016 11 15
 * @since       PHPBoost 3.0 - 2009 06 18
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
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
		array('`# START [\w\.]+ #(.*)# END [\w\.]+ #`su', '`# START [\w\.]+ #`', '`# END [\w\.]+ #`', '`{[\w\.]+}`'),
		array('', '', '', ''),
		$this->content
		);
	}

	protected function get_getvar_method_name($varname)
	{
		$method = 'var';
		$tiny_varname = $varname;

		$split_index = TextHelper::strpos($varname, '_');

		if ($split_index > 0)
		{
			$prefix = TextHelper::substr($varname, 0, $split_index);
			$tiny_var = TextHelper::substr($varname, $split_index + 1);
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
