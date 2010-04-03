<?php
/*##################################################
 *                       SandboxHomeController.class.php
 *                            -------------------
 *   begin                : February 10, 2010
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

class SandboxHomeController extends ModuleController
{
	private static $tpl_src = '<h1>Sandbox parts</h1>
	<ul class="bb_ul">
	# START parts #
		<li><a href="{parts.URL}">{parts.NAME}</a>
	# END parts #
	</ul>';

	private static $links = array('Form builder' => '?url=/form', 'Table builder' => '?url=/table', 'String template bencher' => '?url=/template', 'Mail sender' => '?url=/mail');

	public function execute(HTTPRequest $request)
	{
		$tpl = new StringTemplate(self::$tpl_src);

		foreach (self::$links as $name => $url)
		{
			$tpl->assign_block_vars('parts', array(
				'URL' => $url,
				'NAME' => $name
			));
		}

		return new SiteDisplayResponse($tpl);
	}
}
?>
