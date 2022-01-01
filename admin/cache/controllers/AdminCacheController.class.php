<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 04
 * @since       PHPBoost 2.0 - 2008 08 05
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminCacheController extends DefaultAdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$this->build_form();

		if ($this->submit_button->has_been_submited() && $this->form->validate())
		{
			$this->handle_submit();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.process.success'], MessageHelper::SUCCESS, 5));
		}

		$this->view->put('CONTENT', $this->form->display());

		return new AdminCacheMenuDisplayResponse($this->view, $this->lang['admin.cache']);
	}

	protected function build_form()
	{
		$form = new HTMLForm(__CLASS__);

		$fieldset = new FormFieldsetHTML('cache', $this->lang['admin.cache']);
		$form->add_fieldset($fieldset);

		$fieldset->add_field(new FormFieldHTML('explain', $this->lang['admin.cache.data.description'],
			array('class' => 'full-field')
		));

		$this->submit_button = new FormButtonSubmit($this->lang['admin.clear.cache'], 'button');
		$form->add_button($this->submit_button);

		$this->form = $form;
	}

	protected function handle_submit()
	{
		AppContext::get_cache_service()->clear_cache();
		HtaccessFileCache::regenerate();
		NginxFileCache::regenerate();
	}
}
?>
