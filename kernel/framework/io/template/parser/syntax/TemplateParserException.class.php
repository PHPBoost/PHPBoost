<?php
/*##################################################
 *                    TemplateParserException.class.php
 *                            -------------------
 *   begin                : September 04 2010
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

class TemplateParserException extends Exception
{
    /**
     * @var StringInputStream
     */
    private $input;
    private $tpl_line = 0;
    private $offset = 0;
    private $error_message = 0;
   
	public function __construct($message, StringInputStream $input)
	{
        $this->input = $input;
        $this->error_message = $message;
		$this->compute_position();
		parent::__construct($this->get_message());
	}
	
	private function get_message()
	{
		$msg = $this->error_message . '<br />';
		$msg .= 'line ' . $this->line . ' offset ' . $this->offset . ' near';
		$msg .= ' "...' . $this->input->to_string(-100, 200) . '..."';
		return $msg;
	}
	
	private function compute_position()
	{
        $position = $this->input->tell();
		$string = $this->input->entire_string();
        $str_to_position = substr($string, 0, $position);
        
		$this->tpl_line = substr_count($string, "\n", 0, $position) + 1;
        $last_line_index = strrpos($str_to_position, "\n");
		$line_content = substr($str_to_position, $last_line_index + 1);
		$this->offset = strlen($line_content);
	}
}
?>