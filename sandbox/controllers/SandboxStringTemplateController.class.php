<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxStringTemplateController extends DefaultModuleController
{
	private $test = 'This is a list of {CONTENT}
<ul>
# START elements #
<li>{elements.NAME}</li>
# END elements #
</ul>';

	private $fruits = array('apple', 'pear', 'banana');

	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxStringTemplateController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->test = str_repeat($this->test, 1);

		$bench_non_cached = new Bench();
		$bench_non_cached->start();
		$this->run_non_cached_parsing();
		$bench_non_cached->stop();

		$bench_cached = new Bench();
		$bench_cached->start();
		$this->run_cached_parsing();
		$bench_cached->stop();

		$this->view->put_all(array(
			'RESULT' => StringVars::replace_vars($this->lang['sandbox.string_template.result'], array('non_cached_time' => $bench_non_cached->to_string(5), 'cached_time' => $bench_cached->to_string(5), 'string_length' => TextHelper::strlen($this->test))),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));

		return $this->generate_response();
	}

	private function assign_template(Template $tpl)
	{
		$tpl->put('CONTENT', 'fruits:');

		foreach ($this->fruits as $fruit)
		{
			$tpl->assign_block_vars('elements', array('NAME' => $fruit));
		}
	}

	private function run_cached_parsing()
	{
		for ($i = 0; $i < 100; $i++)
		{
			$tpl = new StringTemplate($this->test);
			$this->assign_template($tpl);
			$tpl->render();
		}
	}

	private function run_non_cached_parsing()
	{
		for ($i = 0; $i < 100; $i++)
		{
			$tpl = new StringTemplate($this->test, StringTemplate::DONT_USE_CACHE);
			$this->assign_template($tpl);
			$tpl->render();
		}
	}

	private function check_authorizations()
	{
		if (!SandboxAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['sandbox.template'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.template'], SandboxUrlBuilder::email()->rel());

		return $response;
	}
}
?>
