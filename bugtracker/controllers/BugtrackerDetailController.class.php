<?php
/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 11 14
 * @since       PHPBoost 3.0 - 2012 11 11
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class BugtrackerDetailController extends ModuleController
{
	private $lang;
	private $tpl;
	private $bug;
	private $config;
	private $current_user;

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();

		$this->check_authorizations();

		$this->build_view($request);

		return $this->build_response($this->tpl);
	}

	private function init()
	{
		$this->current_user = AppContext::get_current_user();
		$request = AppContext::get_request();
		$id = $request->get_int('id', 0);

		$this->tpl = new FileTemplate('bugtracker/BugtrackerDetailController.tpl');

		$this->lang = LangLoader::get('common', 'bugtracker');
		$this->tpl->add_lang($this->lang);
		$this->config = BugtrackerConfig::load();

		try {
			$this->bug = BugtrackerService::get_bug('WHERE id=:id', array('id' => $id));
		} catch (RowNotFoundException $e) {
			$error_controller = new UserErrorController(LangLoader::get_message('error', 'status-messages-common'), $this->lang['error.e_unexist_bug']);
			DispatchManager::redirect($error_controller);
		}
	}

	private function build_view($request)
	{
		$types = $this->config->get_types();
		$categories = $this->config->get_categories();
		$severities = $this->config->get_severities();
		$priorities = $this->config->get_priorities();
		$versions = $this->config->get_versions_detected();

		$user_assigned = $this->bug->get_assigned_to_id() && UserService::user_exists("WHERE user_id=:user_id", array('user_id' => $this->bug->get_assigned_to_id())) ? UserService::get_user($this->bug->get_assigned_to_id()) : '';
		$user_assigned_group_color = $user_assigned ? User::get_group_color($user_assigned->get_groups(), $user_assigned->get_level(), true) : '';

		$this->tpl->put_all($this->bug->get_array_tpl_vars());

		$this->tpl->put_all(array(
			'C_TYPES' 						=> $types,
			'C_CATEGORIES' 					=> $categories,
			'C_SEVERITIES' 					=> $severities,
			'C_PRIORITIES' 					=> $priorities,
			'C_VERSIONS' 					=> $versions,
			'C_EDIT_BUG'					=> BugtrackerAuthorizationsService::check_authorizations()->moderation() || $this->current_user->get_id() == $this->bug->get_assigned_to_id() || ($this->current_user->get_id() == $this->bug->get_author_user()->get_id() && $this->bug->get_author_user()->get_id() != User::VISITOR_LEVEL),
			'C_DELETE_BUG'					=> BugtrackerAuthorizationsService::check_authorizations()->moderation(),
			'C_CHANGE_STATUS'				=> BugtrackerAuthorizationsService::check_authorizations()->moderation() || $this->current_user->get_id() == $this->bug->get_assigned_to_id(),
			'C_USER_ASSIGNED_GROUP_COLOR'	=> !empty($user_assigned_group_color),
			'C_USER_ASSIGNED'				=> $user_assigned,
			'USER_ASSIGNED'					=> $user_assigned ? $user_assigned->get_display_name() : '',
			'USER_ASSIGNED_LEVEL_CLASS'		=> $user_assigned ? UserService::get_level_class($user_assigned->get_level()) : '',
			'USER_ASSIGNED_GROUP_COLOR'		=> $user_assigned_group_color,
			'U_CHANGE_STATUS'				=> BugtrackerUrlBuilder::change_status($this->bug->get_id())->rel(),
			'U_EDIT'						=> BugtrackerUrlBuilder::edit($this->bug->get_id(), 'detail')->rel(),
			'U_DELETE'						=> BugtrackerUrlBuilder::delete($this->bug->get_id(), 'unsolved')->rel(),
		));

		$comments_topic = new BugtrackerCommentsTopic();
		$comments_topic->set_id_in_module($this->bug->get_id());
		$comments_topic->set_url(BugtrackerUrlBuilder::detail_comments($this->bug->get_id() . '-' . $this->bug->get_rewrited_title()));
		$this->tpl->put('COMMENTS', $comments_topic->display());
	}

	private function check_authorizations()
	{
		if (!BugtrackerAuthorizationsService::check_authorizations()->read() || ($this->config->is_restrict_display_to_own_elements_enabled() && !BugtrackerAuthorizationsService::check_authorizations()->moderation() && $this->bug->get_author_user()->get_id() != AppContext::get_current_user()->get_id() && !$this->bug->is_fixed()))
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function build_response(View $tpl)
	{
		$body_view = BugtrackerViews::build_body_view($tpl, 'detail', $this->bug->get_id(), $this->bug->get_type());

		$response = new SiteDisplayResponse($body_view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->lang['titles.detail'] . ' #' . $this->bug->get_id(), $this->lang['bugtracker.module.title']);
		$graphical_environment->get_seo_meta_data()->set_description($this->bug->get_real_short_contents());
		$graphical_environment->get_seo_meta_data()->set_canonical_url(BugtrackerUrlBuilder::detail($this->bug->get_id() . '-' . $this->bug->get_rewrited_title()));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['bugtracker.module.title'], BugtrackerUrlBuilder::home());
		$breadcrumb->add($this->lang['titles.detail'] . ' #' . $this->bug->get_id(), BugtrackerUrlBuilder::detail($this->bug->get_id() . '-' . $this->bug->get_rewrited_title()));

		return $response;
	}
}
?>
