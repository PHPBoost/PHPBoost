<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 01 12
 * @since       PHPBoost 5.2 - 2020 12 05
*/

class WebMemberItemsController extends DefaultModuleController
{
	private $member;
	private $authorized_categories;

	protected function get_template_to_use()
	{
		return new FileTemplate('web/WebSeveralItemsController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->init();
		$this->check_authorizations();

        $this->view->put('C_MEMBER_ITEMS', true);

		if ($this->member === null)
            $this->build_members_listing_view();
        else
            $this->build_view($request);

		return $this->generate_response($request);
	}

	private function init()
	{
        $this->member = AppContext::get_request()->get_getint('user_id', 0) ? UserService::get_user(AppContext::get_request()->get_getint('user_id', 0)) : null;
		$this->authorized_categories = CategoriesService::get_authorized_categories(Category::ROOT_CATEGORY, $this->config->are_descriptions_displayed_to_guests());
    }

	private function build_members_listing_view()
	{
		$now = new Date();
        $result = PersistenceContext::get_querier()->select('SELECT web.*, member.*
            FROM ' . WebSetup::$web_table . ' web
            LEFT JOIN ' . DB_TABLE_MEMBER . ' member ON member.user_id = web.author_user_id
            WHERE id_category IN :authorized_categories
            AND (published = 1 OR (published = 2 AND publishing_start_date < :timestamp_now AND (publishing_end_date > :timestamp_now OR publishing_end_date = 0)))
            ORDER BY member.display_name, web.creation_date DESC', [
                'authorized_categories' => $this->authorized_categories,
                'timestamp_now' => $now->get_timestamp()
            ]
        );

        $this->view->put_all([
            'C_MEMBERS_LIST' => true,
            'C_ITEMS' => false
        ]);

        $contributors = [];
        if ($result->get_rows_count() > 0)
        {
            foreach ($result as $smallad)
            {
                $contributors[] = $smallad['author_user_id'];
            }
            $this->view->put('C_MEMBERS', count($contributors) > 0);

            foreach (array_unique($contributors) as $user_id)
            {
                $user = UserService::get_user($user_id);
                if ($user)
                {
                    $this->view->assign_block_vars('users', [
                        'C_AVATAR' => UserService::get_avatar($user) || UserAccountsConfig::load()->is_default_avatar_enabled(),
                        'USER_NAME' => $user->get_display_name(),
                        'U_USER' => WebUrlBuilder::display_member_items($user->get_id())->rel(),
                        'U_AVATAR' => UserService::get_avatar($user)
                    ]);
                }
            }
        }
    }

	public function build_view(HTTPRequestCustom $request)
	{
		$now = new Date();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();

		$condition = 'WHERE id_category IN :authorized_categories
		AND author_user_id = :user_id
		AND (published = 1 OR (published = 2 AND (publishing_start_date > :timestamp_now OR (publishing_end_date != 0 AND publishing_end_date < :timestamp_now))))';
		$parameters = [
			'user_id' => $this->get_member()->get_id(),
			'authorized_categories' => $this->authorized_categories,
			'timestamp_now' => $now->get_timestamp()
        ];

		$page = $request->get_getint('page', 1);
		$pagination = $this->get_pagination($condition, $parameters, $page);

		$result = PersistenceContext::get_querier()->select('SELECT web.*, member.*, com.comments_number, notes.average_notes, notes.notes_number, note.note
		FROM '. WebSetup::$web_table .' web
		LEFT JOIN '. DB_TABLE_MEMBER .' member ON member.user_id = web.author_user_id
		LEFT JOIN ' . DB_TABLE_COMMENTS_TOPIC . ' com ON com.id_in_module = web.id AND com.module_id = \'web\'
		LEFT JOIN ' . DB_TABLE_AVERAGE_NOTES . ' notes ON notes.id_in_module = web.id AND notes.module_name = \'web\'
		LEFT JOIN ' . DB_TABLE_NOTE . ' note ON note.id_in_module = web.id AND note.module_name = \'web\' AND note.user_id = :user_id
		' . $condition . '
		ORDER BY web.privileged_partner DESC, web.update_date
		LIMIT :items_per_page OFFSET :display_from', array_merge($parameters, [
			'items_per_page' => $pagination->get_number_items_per_page(),
			'display_from' => $pagination->get_display_from()
		]));

		$this->view->put_all([
			'C_ITEMS'             => $result->get_rows_count() > 0,
			'C_SEVERAL_ITEMS'     => $result->get_rows_count() > 1,
			'C_MEMBER_ITEMS'      => true,
			'C_MY_ITEMS'          => $this->is_current_member_displayed(),
			'C_GRID_VIEW'         => $this->config->get_display_type() == WebConfig::GRID_VIEW,
			'C_LIST_VIEW'         => $this->config->get_display_type() == WebConfig::LIST_VIEW,
			'C_TABLE_VIEW'        => $this->config->get_display_type() == WebConfig::TABLE_VIEW,
			'C_CONTROLS'          => CategoriesAuthorizationsService::check_authorizations()->moderation(),
			'C_FULL_ITEM_DISPLAY' => $this->config->is_full_item_displayed(),
			'C_ENABLED_COMMENTS'  => $comments_config->module_comments_is_enabled('web'),
			'C_ENABLED_NOTATION'  => $content_management_config->module_notation_is_enabled('web'),
			'C_PAGINATION'        => $pagination->has_several_pages(),

			'CATEGORIES_PER_ROW' => $this->config->get_categories_per_row(),
			'ITEMS_PER_ROW'      => $this->config->get_items_per_row(),
			'PAGINATION'         => $pagination->display(),
			'MEMBER_NAME'        => $this->get_member()->get_display_name()
		]);

		while ($row = $result->fetch())
		{
			$item = new WebItem();
			$item->set_properties($row);

			$keywords = $item->get_keywords();
			$has_keywords = count($keywords) > 0;

			$this->view->assign_block_vars('items', array_merge($item->get_template_vars(), [
				'C_KEYWORDS' => $has_keywords
			]));

			if ($has_keywords)
				$this->build_keywords_view($keywords);
		}
		$result->dispose();
	}

	protected function get_member()
	{
		if (!$this->member && $this->member !== null)
		{
			DispatchManager::redirect(PHPBoostErrors::unexisting_element());
		}
		return $this->member;
	}

	protected function is_current_member_displayed()
	{
		return $this->member && $this->member->get_id() == AppContext::get_current_user()->get_id();
	}

	private function get_pagination($condition, $parameters, $page)
	{
		$items_number = WebService::count($condition, $parameters);

		$pagination = new ModulePagination($page, $items_number, (int)WebConfig::load()->get_items_per_page());
		$pagination->set_url(WebUrlBuilder::display_member_items($this->get_member()->get_id(), '%d'));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		return $pagination;
	}

	private function build_keywords_view($keywords)
	{
		$nbr_keywords = count($keywords);

		$i = 1;
		foreach ($keywords as $keyword)
		{
			$this->view->assign_block_vars('items.keywords', [
				'C_SEPARATOR' => $i < $nbr_keywords,
				'NAME' => $keyword->get_name(),
				'URL' => WebUrlBuilder::display_tag($keyword->get_rewrited_name())->rel(),
			]);
			$i++;
		}
	}

	private function check_authorizations()
	{
		if (!CategoriesAuthorizationsService::check_authorizations()->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response(HTTPRequestCustom $request)
	{
		$page = $request->get_getint('page', 1);
		$page_title = $this->member ? ($this->is_current_member_displayed() ? $this->lang['web.my.items'] : $this->get_member()->get_display_name()) : $this->lang['contribution.members.list'];
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($page_title, $this->lang['web.module.title'], $page);
        if ($this->member)
        {
            $graphical_environment->get_seo_meta_data()->set_description(StringVars::replace_vars($this->lang['web.seo.description.member'], ['author' => $this->get_member()->get_id()]));
            $graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display_member_items($this->get_member()->get_id(), $page));
        }
        else
        {
            $graphical_environment->get_seo_meta_data()->set_description($this->lang['contribution.members.list']);
            $graphical_environment->get_seo_meta_data()->set_canonical_url(WebUrlBuilder::display_member_items());
        }

        $breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['web.module.title'], WebUrlBuilder::home());
		$breadcrumb->add($this->lang['contribution.members.list'], WebUrlBuilder::display_member_items());
        if ($this->member)
            $breadcrumb->add($page_title, WebUrlBuilder::display_member_items($this->member ? $this->get_member()->get_id() : null, $page));

		return $response;
	}
}
?>
