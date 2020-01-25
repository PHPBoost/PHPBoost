<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 01 25
 * @since       PHPBoost 2.0 - 2008 08 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCSSCacheController extends AdminController
{
	/**
	 * @var HTMLForm
	 */
	private $form;
	/**
	 * @var FormButtonSubmit
	 */
	private $submit_button;

	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->build_form();

		$tpl = new StringTemplate('# INCLUDE MSG # # INCLUDE FORM #');
		$tpl->add_lang($this->lang);

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->handle_submit();
			$tpl->put('MSG', MessageHelper::display(LangLoader::get_message('process.success', 'status-messages-common'), MessageHelper::SUCCESS, 5));
		}

		$tpl->put('FORM', $this->form->display());

		return new AdminCacheMenuDisplayResponse($tpl, $this->lang['css_cache']);
	}

	private function init()
	{
		$this->lang = LangLoader::get('admin-cache-common');
	}

	protected function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTMLHeading('css_cache', $this->lang['css_cache']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldHTML('explain', $this->lang['explain_css_cache'],
			array('class' => 'full-field')
		));

		$this->submit_button = new FormButtonSubmit($this->lang['clear_cache'], 'button');
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	protected function handle_submit()
	{
		AppContext::get_cache_service()->clear_css_cache();
	}
}
?>
