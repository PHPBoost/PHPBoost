<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 01
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxBBCodeController extends DefaultModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('sandbox/SandboxBBCodeController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		if (ModulesManager::is_module_installed('wiki')  && ModulesManager::is_module_activated('wiki'))
		{
			// return true when Wiki is on
			$c_wiki = true;

			include_once('../wiki/wiki_functions.php');

			// Create and store the paragraph menu
			$contents = wiki_parse("
				-- " . $this->lang['sandbox.bbcode.paragraph'] . " 1 --
				" . $this->lang['sandbox.lorem.short.content'] . "
				--- " . $this->lang['sandbox.bbcode.paragraph'] . " 1.1 ---
				" . $this->lang['sandbox.lorem.short.content'] . "
				---- " . $this->lang['sandbox.bbcode.paragraph'] . " 1.1.1 ----
				" . $this->lang['sandbox.lorem.short.content'] . "
				----- " . $this->lang['sandbox.bbcode.paragraph'] . " 1.1.1.1 -----
				" . $this->lang['sandbox.lorem.short.content'] . "
				------ " . $this->lang['sandbox.bbcode.paragraph'] . " 1.1.1.1.1 ------
				" . $this->lang['sandbox.lorem.short.content'] . "
				------ " . $this->lang['sandbox.bbcode.paragraph'] . " 1.1.1.1.2 ------
				" . $this->lang['sandbox.lorem.short.content'] . "
				-----  " . $this->lang['sandbox.bbcode.paragraph'] . " 1.1.1.2 -----
				" . $this->lang['sandbox.lorem.short.content'] . "
				---- " . $this->lang['sandbox.bbcode.paragraph'] . " 1.1.2 ----
				" . $this->lang['sandbox.lorem.short.content'] . "

				--- " . $this->lang['sandbox.bbcode.paragraph'] . " 1.2 ---
				" . $this->lang['sandbox.lorem.short.content'] . "

				-- " . $this->lang['sandbox.bbcode.paragraph'] . " 2 --
				" . $this->lang['sandbox.lorem.short.content'] . "
				-- " . $this->lang['sandbox.bbcode.paragraph'] . " 3 --
				" . $this->lang['sandbox.lorem.short.content'] . "
			");

			$this->view->assign_block_vars('wikimenu', array(
				'MENU' => wiki_display_menu(wiki_explode_menu($contents))
			));

			$this->view->put('WIKI_CONTENTS', FormatingHelper::second_parse(wiki_no_rewrite($contents)));
		} else {
			// return false when Wiki is off
			$c_wiki = false;
		}

		$this->view->put_all(array(
			'C_WIKI'          => $c_wiki,
			'TYPOGRAPHY'      => self::build_markup('sandbox/pagecontent/bbcode/typography.tpl'),
			'BLOCKS'          => self::build_markup('sandbox/pagecontent/bbcode/blocks.tpl'),
			'CODE'            => self::build_markup('sandbox/pagecontent/bbcode/code.tpl'),
			'LIST'            => self::build_markup('sandbox/pagecontent/bbcode/list.tpl'),
			'TABLE'           => self::build_markup('sandbox/pagecontent/bbcode/table.tpl'),
			'SANDBOX_SUBMENU' => SandboxSubMenu::get_submenu()
		));
	}

	private function build_markup($tpl)
	{
		$view = new FileTemplate($tpl);
		$view->add_lang($this->lang);
		return $view;
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
		$graphical_environment->set_page_title($this->lang['sandbox.bbcode'], $this->lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->lang['sandbox.bbcode']);

		return $response;
	}
}
?>
