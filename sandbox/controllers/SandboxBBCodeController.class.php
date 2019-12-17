<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2019 07 31
 * @since       PHPBoost 3.0 - 2012 05 05
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class SandboxBBCodeController extends ModuleController
{
	private $tpl;
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
		$this->tpl = new FileTemplate('sandbox/SandboxBBCodeController.tpl');
		$this->tpl->add_lang($this->common_lang);
		$this->tpl->add_lang($this->lang);
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
				-- Paragraphe 1 --
				" . $this->common_lang['lorem.short.content'] . "
				--- paragraphe 1.1 ---
				" . $this->common_lang['lorem.short.content'] . "
				---- paragraphe 1.1.1 ----
				" . $this->common_lang['lorem.short.content'] . "
				----- paragraphe 1.1.1.1 -----
				" . $this->common_lang['lorem.short.content'] . "
				------ paragraphe 1.1.1.1.1 ------
				" . $this->common_lang['lorem.short.content'] . "
				------ paragraphe 1.1.1.1.2 ------
				" . $this->common_lang['lorem.short.content'] . "
				-----  paragraphe 1.1.1.2 -----
				" . $this->common_lang['lorem.short.content'] . "
				---- paragraphe 1.1.2 ----
				" . $this->common_lang['lorem.short.content'] . "

				--- paragraphe 1.2 ---
				" . $this->common_lang['lorem.short.content'] . "

				-- Pararaphe 2 --
				" . $this->common_lang['lorem.short.content'] . "
				-- Pararaphe 3 --
				" . $this->common_lang['lorem.short.content'] . "
			");

			$this->tpl->assign_block_vars('wikimenu', array(
				'MENU' => wiki_display_menu(wiki_explode_menu($contents))
			));

			$this->tpl->put('WIKI_CONTENTS', FormatingHelper::second_parse(wiki_no_rewrite($contents)));
		} else {
			// la condition de présence du module retourne false
			$c_wiki = false;
		}

		$this->tpl->put_all(array(
			'TYPOGRAPHY' => file_get_contents('html/bbcode/typography.tpl'),
			'BLOCK' => file_get_contents('html/bbcode/block.tpl'),
			'BLOCK_CODE' => file_get_contents('html/bbcode/block-code.tpl'),
			'MEDIA' => file_get_contents('html/bbcode/media.tpl'),
			'TABLE' => file_get_contents('html/bbcode/table.tpl'),
			'C_WIKI' => $c_wiki
		));
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
		$response = new SiteDisplayResponse($this->tpl);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->common_lang['title.bbcode'], $this->common_lang['sandbox.module.title']);

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->common_lang['sandbox.module.title'], SandboxUrlBuilder::home()->rel());
		$breadcrumb->add($this->common_lang['title.bbcode'], SandboxUrlBuilder::css()->rel());

		return $response;
	}
}
?>
