<?php
/**
 * @package     IO
 * @subpackage  Template
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2017 08 27
 * @since       PHPBoost 3.0 - 2010 09 04
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class TemplateRenderingException extends Exception
{
	/**
	 * @var StringInputStream
	 */
	private $input = null;
	private $tpl_line = 0;
	private $offset = 0;
	private $error_message = 0;

	public function __construct($message, StringInputStream $input = null)
	{
		$this->error_message = $message;
		if ($input != null)
		{
			$this->input = $input;
			$this->compute_position();
		}
		parent::__construct($this->get_message());
	}

	private function get_message()
	{
		$msg = $this->error_message;
		if ($this->input != null)
		{
			$msg .= "\n" . 'line ' . $this->tpl_line . ' offset ' . $this->offset . ' near';
			$msg .= ' "...' . TextHelper::htmlspecialchars($this->input->to_string(-100, 200)) . '..."';
		}
		return $msg;
	}

	private function compute_position()
	{
		$position = $this->input->tell();
		$string = $this->input->entire_string();
		$str_to_position = TextHelper::substr($string, 0, $position);

		$this->tpl_line = TextHelper::substr_count($str_to_position, "\n") + 1;
		$last_line_index = TextHelper::strrpos($str_to_position, "\n");
		$line_content = TextHelper::substr($str_to_position, $last_line_index + 1);
		$this->offset = TextHelper::strlen($line_content);
	}
}
?>
