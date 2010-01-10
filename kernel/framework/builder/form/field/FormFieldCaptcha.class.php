<?php
/*##################################################
 *                             field_captcha_field.class.php
 *                            -------------------
 *   begin                : September 19, 2009
 *   copyright            : (C) 2009 Viarre Régis
 *   email                : crowkait@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manage captcha validation fields to avoid bot spam.
 * @package builder
 * @subpackage form
 */
class FormFieldCaptcha implements AbstractFormField
{
	/**
	 * @var Captcha
	 */
	private $captcha = '';

	/**
	 * @param $field_id string The html field identifier
	 * @param $captcha Captcha The captcha object
	 * @param $field_options array Field's options
	 */
	public function __construct(Captcha $captcha = null)
	{
		global $LANG;
		parent::__construct('captcha', $LANG['verif_code'], false, array('required' => true));
	}
	
	public function retrieve_value()
	{
		$request = AppContext::get_request();
		if ($request->has_parameter($this->get_html_id()))
		{
			$this->captcha->
			$this->set_value($request->get_value($this->get_html_id()));
		}
	}

	/**
	 * @return string The html code for the free field.
	 */
	public function display()
	{
		$this->captcha->save_user();

		$template = new Template('framework/builder/form/FormFieldCaptcha.tpl');
			
		$template->assign_vars(array(
			'NAME' => $this->name,
			'ID' => $this->id,
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'CAPTCHA_INSTANCE' => $this->captcha->get_instance(),
			'CAPTCHA_WIDTH' => $this->captcha->get_width(),
			'CAPTCHA_HEIGHT' => $this->captcha->get_height(),
			'CAPTCHA_FONT' => $this->captcha->get_font(),
			'CAPTCHA_DIFFICULTY' => $this->captcha->get_difficulty(),
			'CAPTCHA_ONBLUR' => $onblur ? 'onblur="' . implode(';', $validations) . $this->on_blur . '" ' : ''
		));

		return $template;
	}
}

?>