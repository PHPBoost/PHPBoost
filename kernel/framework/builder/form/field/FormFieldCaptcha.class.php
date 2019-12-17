<?php
/**
 * This class manage captcha validation fields to avoid bot spam.
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 10 02
 * @since       PHPBoost 3.0 - 2010 01 11
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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

		$field_options = $this->is_enabled() && $this->captcha->is_visible() ? array('required' => true) : array();
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
		$this->captcha->set_form_id($this->get_form_id());

		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		$template->put_all(array(
			'C_IS_ENABLED' => $this->is_enabled(),
			'C_IS_VISIBLE' => $this->captcha->is_visible(),
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
