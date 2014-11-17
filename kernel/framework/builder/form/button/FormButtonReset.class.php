<?php
/*##################################################
 *                        FormButtonReset.class.php
 *                            -------------------
 *   begin                : February 17, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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
 * @desc
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 */
class FormButtonReset implements FormButton
{
	private $value;
	
	public function __construct($value = '')
	{
		$this->value = $value;
		
		if (empty($value))
			$this->value = LangLoader::get_message('reset', 'main');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function display()
	{
		$template = new StringTemplate('<button type="reset" value="true">{L_RESET}</button>');
		
		$template->put_all(array(
			'L_RESET' => $this->value
		));

		return $template;
	}

	public function set_form_id($form_id) {}
}
?>