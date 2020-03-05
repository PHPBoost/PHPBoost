<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2020 03 05
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxBBCodeController extends ModuleController
{
	private $view;
	private $common_lang;
	private $lang;

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->common_lang = LangLoader::get('common', 'sandbox');
		$this->lang = LangLoader::get('bbcode', 'sandbox');
		$this->view = new FileTemplate('sandbox/SandboxBBCodeController.tpl');
		$this->view->add_lang($this->common_lang);
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		if (ModulesManager::is_module_installed('wiki')  && ModulesManager::is_module_activated('wiki'))
		{
			// Condition de présence du module retourne true
			$c_wiki = true;

			include_once('../wiki/wiki_functions.php');

			//On crée le menu des paragraphes et on enregistre le menu
			$contents = wiki_parse("
				-- " . $this->lang['bbcode.paragraph'] . " 1 --
				" . $this->common_lang['lorem.short.content'] . "
				--- " . $this->lang['bbcode.paragraph'] . " 1.1 ---
				" . $this->common_lang['lorem.short.content'] . "
				---- " . $this->lang['bbcode.paragraph'] . " 1.1.1 ----
				" . $this->common_lang['lorem.short.content'] . "
				----- " . $this->lang['bbcode.paragraph'] . " 1.1.1.1 -----
				" . $this->common_lang['lorem.short.content'] . "
				------ " . $this->lang['bbcode.paragraph'] . " 1.1.1.1.1 ------
				" . $this->common_lang['lorem.short.content'] . "
				------ " . $this->lang['bbcode.paragraph'] . " 1.1.1.1.2 ------
				" . $this->common_lang['lorem.short.content'] . "
				-----  " . $this->lang['bbcode.paragraph'] . " 1.1.1.2 -----
				" . $this->common_lang['lorem.short.content'] . "
				---- " . $this->lang['bbcode.paragraph'] . " 1.1.2 ----
				" . $this->common_lang['lorem.short.content'] . "

				--- " . $this->lang['bbcode.paragraph'] . " 1.2 ---
				" . $this->common_lang['lorem.short.content'] . "

				-- " . $this->lang['bbcode.paragraph'] . " 2 --
				" . $this->common_lang['lorem.short.content'] . "
				-- " . $this->lang['bbcode.paragraph'] . " 3 --
				" . $this->common_lang['lorem.short.content'] . "
			");

			$this->view->assign_block_vars('wikimenu', array(
				'MENU' => wiki_display_menu(wiki_explode_menu($contents))
			));

			$this->view->put('WIKI_CONTENTS', FormatingHelper::second_parse(wiki_no_rewrite($contents)));
		} else {
			// la condition de présence du module retourne false
			$c_wiki = false;
		}

		$this->view->put_all(array(
			'C_WIKI'          => $c_wiki,
			'SANDBOX_SUBMENU' => self::get_submenu(),
			'TYPOGRAPHY'      => self::build_typography_view(),
			'BLOCKS'          => self::build_blocks_view(),
			'CODE'            => self::build_code_view(),
			'LIST'            => self::build_list_view(),
			'TABLE'           => self::build_table_view(),
		));
	}

	private function get_submenu()
	{
		$submenu_lang = LangLoader::get('submenu', 'sandbox');
		$submenu_tpl = new FileTemplate('sandbox/SandboxSubMenu.tpl');
		$submenu_tpl->add_lang($submenu_lang);
		return $submenu_tpl;
	}

	private function build_typography_view()
	{
		$typo_tpl = new FileTemplate('sandbox/pagecontent/bbcode/typography.tpl');
		$typo_tpl->add_lang($this->lang);
		$typo_tpl->add_lang($this->common_lang);
		return $typo_tpl;
	}

	private function build_blocks_view()
	{
		$blocks_tpl = new FileTemplate('sandbox/pagecontent/bbcode/blocks.tpl');
		$blocks_tpl->add_lang($this->lang);
		$blocks_tpl->add_lang($this->common_lang);
		return $blocks_tpl;
	}

	private function build_code_view()
	{
		$code_tpl = new FileTemplate('sandbox/pagecontent/bbcode/code.tpl');
		$code_tpl->add_lang($this->lang);
		$code_tpl->add_lang($this->common_lang);
		return $code_tpl;
	}

	private function build_list_view()
	{
		$list_tpl = new FileTemplate('sandbox/pagecontent/bbcode/list.tpl');
		$list_tpl->add_lang($this->lang);
		$list_tpl->add_lang($this->common_lang);
		return $list_tpl;
	}

	private function build_table_view()
	{
		$table_tpl = new FileTemplate('sandbox/pagecontent/bbcode/table.tpl');
		$table_tpl->add_lang($this->lang);
		$table_tpl->add_lang($this->common_lang);
		return $table_tpl;
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
		$graphical_environment->set_page_title($this->common_lang['title.bbcode'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.bbcode']);

		return $response;
	}
}
?>
