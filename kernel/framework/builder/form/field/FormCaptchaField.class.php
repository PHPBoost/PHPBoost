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
class FormCaptchaField extends FormField
{
	private $captcha = ''; //Captcha object
	
	/**
	 * @param $field_id string The html field identifier
	 * @param $captcha Captcha The captcha object
	 * @param $field_options array Field's options
	 */
	public function __construct($field_id, $captcha, array $field_options = array(), array $constraints = array())
	{
		global $LANG;
		
		$this->title = $LANG['verif_code'];
		$this->required_alert = $LANG['require_verif_code'];
		$this->required = true;
		
		parent::__construct($field_id . $captcha->get_instance(), $field_options, $constraints);
		$this->captcha = $captcha;
		foreach($field_options as $attribute => $value)
		{
			throw new FormBuilderException(sprintf('Unsupported option %s with field ' . __CLASS__, strtolower($attribute)));
		}
	}
	
	/**
	 * @return string The html code for the free field.
	 */
	public function display()
	{
		$this->captcha->save_user();
		
		$Template = new Template('framework/builder/forms/field_captcha.tpl');
			
		$Template->assign_vars(array(
			'ID' => $this->id,
			'L_FIELD_TITLE' => $this->title,
			'L_EXPLAIN' => $this->sub_title,
			'CAPTCHA_INSTANCE' => $this->captcha->get_instance(),
			'CAPTCHA_WIDTH' => $this->captcha->get_width(),
			'CAPTCHA_HEIGHT' => $this->captcha->get_height(),
			'CAPTCHA_FONT' => $this->captcha->get_font(),
			'CAPTCHA_DIFFICULTY' => $this->captcha->get_difficulty()
		));	
		
		return $Template->parse(Template::TEMPLATE_PARSER_STRING);
	}
}

?>