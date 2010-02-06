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
	private $test = 'This is a list of {CONTENT}
<ul class="bb_ul">
# START elements #
<li>{elements.NAME}</li>
# END elements #
</ul>';

	private $fruits = array('apple', 'pear', 'banana');

	private $result_tpl = 'Non cached: {NON_CACHED_TIME} s - Cached: {CACHED_TIME} s';

	public function execute(HTTPRequest $request)
	{
		$tpl = new CachedStringTemplate($this->result_tpl);

		$bench_non_cached = new Bench();
		$bench_non_cached->start();
		$this->run_non_cached_parsing();
		$bench_non_cached->stop();
		$tpl->assign_vars(array('NON_CACHED_TIME' => $bench_non_cached->to_string(5)));

		$bench_cached = new Bench();
		$bench_cached->start();
		$this->run_cached_parsing();
		$bench_cached->stop();
		$tpl->assign_vars(array('CACHED_TIME' => $bench_cached->to_string(5)));

		return new SiteDisplayResponse($tpl);
	}

	private function assign_template(Template $tpl)
	{
		$tpl->assign_vars(array('CONTENT' => 'fruits:'));

		foreach ($this->fruits as $fruit)
		{
			$tpl->assign_block_vars('elements', array('NAME' => $fruit));
		}
	}

	private function run_cached_parsing()
	{
		for ($i = 0; $i < 1000; $i++)
		{
			$tpl = new CachedStringTemplate($this->test);
			$this->assign_template($tpl);
			$tpl->to_string();
		}
	}

	private function run_non_cached_parsing()
	{
		for ($i = 0; $i < 1000; $i++)
		{
			$tpl = new StringTemplate($this->test);
			$this->assign_template($tpl);
			$tpl->to_string();
		}
	}
}
?>
