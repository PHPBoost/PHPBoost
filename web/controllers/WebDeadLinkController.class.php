<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 04 14
 * @since       PHPBoost 4.1 - 2014 08 21
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WebDeadLinkController extends AbstractController
{
	private $item;

	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);

		if (!empty($id) && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
		{
			try {
				$this->item = WebService::get_item($id);
			} catch (RowNotFoundException $e) {
				$error_controller = PHPBoostErrors::unexisting_page();
				DispatchManager::redirect($error_controller);
			}
		}

		if ($this->item !== null && $this->item->is_published())
		{
			if (!PersistenceContext::get_querier()->row_exists(PREFIX . 'events', 'WHERE id_in_module=:id_in_module AND module=\'web\' AND current_status = 0', array('id_in_module' => $this->item->get_id())))
			{
				$contribution = new Contribution();
				$contribution->set_id_in_module($this->item->get_id());
				$contribution->set_entitled(StringVars::replace_vars(LangLoader::get_message('contribution.dead.link.name', 'contribution-lang'), array('link_name' => $this->item->get_title())));
				$contribution->set_fixing_url(WebUrlBuilder::edit($this->item->get_id())->relative());
				$contribution->set_description(LangLoader::get_message('contribution.dead.link.clue', 'contribution-lang'));
				$contribution->set_poster_id(AppContext::get_current_user()->get_id());
				$contribution->set_module('web');
				$contribution->set_type('alert');
				$contribution->set_auth(
					Authorizations::capture_and_shift_bit_auth(
						CategoriesService::get_categories_manager('web')->get_heritated_authorizations($this->item->get_id_category(), Category::MODERATION_AUTHORIZATIONS, Authorizations::AUTH_CHILD_PRIORITY),
						Category::MODERATION_AUTHORIZATIONS, Contribution::CONTRIBUTION_AUTH_BIT
					)
				);

				ContributionService::save_contribution($contribution);
			}

			DispatchManager::redirect(new UserContributionSuccessController());
		}
		else
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}
	}
}
?>
