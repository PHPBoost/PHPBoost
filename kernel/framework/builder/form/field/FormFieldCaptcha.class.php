<?php
/*##################################################
 *                         FormFieldCaptcha.class.php
 *                            -------------------
 *   begin                : January 11, 2010
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc This class manage captcha validation fields to avoid bot spam.
 * @package {@package}
 */
class FormFieldCaptcha extends AbstractFormField
{
	/**
	 * @var Captcha
	 */
	private $captcha = '';

	/**
	 * @param Captcha $captcha The captcha to use. If not given, a default captcha will be used.
	 */
	public function __construct($id = 'captcha')
	{
		$this->captcha = AppContext::get_captcha_service()->get_default_factory();
		
		$field_options = $this->is_enabled() ? array('required' => true) : array();
		parent::__construct($id, LangLoader::get_message('form.captcha', 'common'), false, $field_options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function retrieve_value()
	{
		$this->captcha->set_html_id($this->get_html_id());
		if ($this->is_enabled())
		{
			$this->set_value($this->captcha->is_valid());
		}
		else
		{
			$this->set_value(true);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function display()
	{
		$this->captcha->set_html_id($this->get_html_id());

		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);
		 
		$template->put_all(array(
			'C_IS_ENABLED' => $this->is_enabled(),
			'CAPTCHA' => $this->captcha->display(),
		));

		return $template;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate()
	{
		$this->retrieve_value();
		$result = $this->get_value();
		if (!$result)
		{
			$this->set_validation_error_message(LangLoader::get_message('captcha.validation_error', 'status-messages-common'));
		}
		return $result;
	}

	private function is_enabled()
	{
		return !AppContext::get_current_user()->check_level(User::MEMBER_LEVEL) && $this->captcha->is_available();
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldCaptcha.tpl');
	}
}
?>