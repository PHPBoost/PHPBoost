<?php
/*##################################################
 *                    SandboxStringTemplateController.class.php
 *                            -------------------
 *   begin                : February 6, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

class SandboxStringTemplateController extends ModuleController
{
	private $view;
	private $lang;
	
	private $test = 'This is a list of {CONTENT}
<ul>
# START elements #
<li>{elements.NAME}</li>
# END elements #
</ul>';

	private $fruits = array('apple', 'pear', 'banana');

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		
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
			'RESULT' => StringVars::replace_vars($this->lang['string_template.result'], array('non_cached_time' => $bench_non_cached->to_string(5), 'cached_time' => $bench_cached->to_string(5), 'string_length' => strlen($this->test)))
		));
		
		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get('common', 'sandbox');
		$this->view = new StringTemplate('{RESULT}');
		$this->view->add_lang($this->lang);
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
	
	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['title.string_template'], $this->lang['module_title']);
		
		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['module_title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['title.string_template'], SandboxUrlBuilder::mail()->rel());
		
		return $response;
	}
}
?>
