<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 10 03
 * @since       PHPBoost 4.1 - 2015 02 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ForumHomeController extends DefaultModuleController
{
	private $category;

	public function execute(HTTPRequestCustom $request)
	{
		$this->build_view();

		return $this->generate_response();
	}

	private function build_view()
	{
		self::$module_id = 'forum';
		global $nbr_msg_not_read, $top_view, $bottom_view;

		$id_get = (int)retrieve(GET, 'id', 0);
		$categories_cache = CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache();

		try {
			$this->category = $categories_cache->get_category($id_get);
		} catch (CategoryNotFoundException $e) {
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		//Vérification des autorisations d'accès.
		if (!ForumAuthorizationsService::check_authorizations($id_get)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}

		require_once(PATH_TO_ROOT . '/forum/forum_begin.php');
		require_once(PATH_TO_ROOT . '/forum/forum_tools.php');

		//Affichage des sous-catégories de la catégorie.
		$display_cat = !empty($id_get);

		//Vérification des autorisations.
		$authorized_categories = CategoriesService::get_authorized_categories($id_get, true, self::$module_id);

		//Calcul du temps de péremption, ou de dernière vue des messages par à rapport à la configuration.
		$max_time_msg = forum_limit_time_msg();

		$is_guest = AppContext::get_current_user()->get_id() == -1;
		$total_topic = 0;
		$total_msg = 0;
		$i = 0;

		//On liste les catégories et sous-catégories.
		$result = PersistenceContext::get_querier()->select('SELECT
			c.id AS cid, c.id_parent, c.name, c.rewrited_name, c.description AS subname, c.url, c.thumbnail, c.icon, c.color, c.last_topic_id, c.status AS cat_status,
			t.id AS tid, t.id_category, t.title, t.last_timestamp, t.last_user_id, t.last_msg_id, t.nbr_msg AS t_nbr_msg, t.display_msg, t.status,
			m.user_id, m.display_name as login, m.level as user_level, m.user_groups,
			v.last_view_id
		FROM ' . ForumSetup::$forum_cats_table . ' c
		LEFT JOIN ' . ForumSetup::$forum_topics_table . ' t ON t.id = c.last_topic_id
		LEFT JOIN ' . ForumSetup::$forum_view_table . ' v ON v.user_id = :user_id AND v.idtopic = t.id
		LEFT JOIN ' . DB_TABLE_MEMBER . ' m ON m.user_id = t.last_user_id
		WHERE ' . ($display_cat ? 'c.id_parent = :id_cat AND ' : '') . 'c.id IN :authorized_categories
		ORDER BY c.id_parent, c.c_order', array(
			'id_cat' => $id_get,
			'user_id' => AppContext::get_current_user()->get_id(),
			'authorized_categories' => $authorized_categories
		));

		$this->view->put('C_THUMBNAILS_DISPLAYED', $this->config->are_thumbnails_displayed());

		$categories = array();
		while ($row = $result->fetch())
		{
			$category = $categories_cache->get_category($row['cid']);
			$elements_number = $category->get_elements_number();

			$categories[$row['cid']] = $row;
			$categories[$row['cid']]['nbr_topic'] = $elements_number['topics_number'];
			$categories[$row['cid']]['nbr_msg'] = $elements_number['messages_number'];
		}
		$result->dispose();

		if (!$display_cat)
			$categories = parentChildSort_r('cid', 'id_parent', $categories);

		$display_sub_cats = false;
		$is_sub_forum = array();
		foreach ($categories as $row)
		{
			$this->view->assign_block_vars('forums_list', array());
			if ($row['id_parent'] == Category::ROOT_CATEGORY && $i > 0 && $display_sub_cats) //Fermeture de la catégorie racine.
			{
				$this->view->assign_block_vars('forums_list.endcats', array(
				));
			}
			$i++;

			if ($row['id_parent'] == Category::ROOT_CATEGORY) //Si c'est une catégorie
			{
				$this->view->assign_block_vars('forums_list.cats', array(
					'C_HAS_THUMBNAIL'      => !empty($row['thumbnail']),
					'C_HAS_CATEGORY_ICON'  => !empty($row['icon']),
					'C_HAS_CATEGORY_COLOR' => !empty($row['color']),

					'CATEGORY_ID'        => $row['cid'],
					'CATEGORY_NAME'      => $row['name'],
					'CATEGORY_PARENT_ID' => $row['id_parent'],
					'CATEGORY_ICON'      => $row['icon'],
					'CATEGORY_COLOR'     => $row['color'],
					'REWRITED_NAME'      => $row['rewrited_name'],

					'U_CATEGORY'           => ForumUrlBuilder::display_category($row['cid'], $row['rewrited_name'])->rel(),
					'U_CATEGORY_THUMBNAIL' => Url::to_rel($categories_cache->get_category($row['cid'])->get_thumbnail()),
				));
				$display_sub_cats = $row['cat_status'] == ForumCategory::STATUS_UNLOCKED;
			}
			else //On liste les sous-catégories
			{
				if (in_array($row['id_parent'], $is_sub_forum))
					$is_sub_forum[] = $row['cid'];

				if (($display_sub_cats || !empty($id_get)) && !in_array($row['cid'], $is_sub_forum))
				{
					if ($display_cat) //Affichage des forums d'une catégorie, ajout de la catégorie.
					{
						$this->view->assign_block_vars('forums_list.cats', array(
							'C_HAS_THUMBNAIL'     => !empty($this->category->get_thumbnail()->rel()),
							'C_HAS_CATEGORY_ICON' => !empty($this->category->get_icon()),

							'CATEGORY_ID'        => $this->category->get_id(),
							'CATEGORY_NAME'      => $this->category->get_name(),
							'CATEGORY_PARENT_ID' => $this->category->get_id_parent(),
							'CATEGORY_SUB_ORDER' => $this->category->get_order(),
							'CATEGORY_ICON'      => $this->category->get_icon(),
							'REWRITED_NAME'      => $this->category->get_rewrited_name(),

							'U_CATEGORY'           => PATH_TO_ROOT . '/forum/' . url('index.php?id=' . $this->category->get_id(), 'cat-' . $this->category->get_id() . '-' . $this->category->get_rewrited_name() . '.php'),
							'U_CATEGORY_THUMBNAIL' => Url::to_rel($this->category->get_thumbnail()),
						));
						$display_cat = false;
					}

					$subforums = '';
					$this->view->put_all(array(
						'C_FORUM_ROOT_CAT'  => false,
						'C_FORUM_CHILD_CAT' => true,
						'C_END_S_CATS'      => false
					));

					$children = CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_children($row['cid']);
					if ($children)
					{
						foreach ($children as $id => $child) //Listage des sous forums.
						{
							if ($child->get_id_parent() == $row['cid'] && ForumAuthorizationsService::check_authorizations($child->get_id())->read()) //Sous forum distant d'un niveau au plus.
							{
								$is_sub_forum[] = $child->get_id();
								$link = $child->get_url() ? '<a href="' . $child->get_url() . '" class="forum-subform-element offload">' : '<a href="forum' . url('.php?id=' . $child->get_id(), '-' . $child->get_id() . '-' . $child->get_rewrited_name() . '.php') . '" class="forum-subform-element offload">';
								$subforums .= !empty($subforums) ? ', ' . $link . $child->get_name() . '</a>' : $link . $child->get_name() . '</a>';
							}
						}
					}

					if (!empty($row['last_topic_id']))
					{
						//Si le dernier message lu est présent on redirige vers lui, sinon on redirige vers le dernier posté.
						if (!empty($row['last_view_id'])) //Calcul de la page du last_view_id réalisé dans topic.php
						{
							$last_msg_id = $row['last_view_id'];
							$last_page = 'idm=' . $row['last_view_id'] . '&amp;';
							$last_page_rewrite = '-0-' . $row['last_view_id'];
						}
						else
						{
							$last_msg_id = $row['last_msg_id'];
							$last_page = ceil($row['t_nbr_msg'] / $this->config->get_number_messages_per_page());
							$last_page_rewrite = ($last_page > 1) ? '-' . $last_page : '';
							$last_page = ($last_page > 1) ? 'pt=' . $last_page . '&amp;' : '';
						}

						$last_topic_title = (($this->config->is_message_before_topic_title_displayed() && $row['display_msg']) ? $this->config->get_message_before_topic_title() : '') . ' ' . $row['title'];
						$row['login'] = !empty($row['login']) ? $row['login'] : $this->lang['user.guest'];
						$last_group_color = User::get_group_color($row['user_groups'], $row['user_level']);
					}
					else
					{
						$row['last_timestamp'] = $last_group_color = '';
					}

					//Vérifications des topics Lu/non Lus.
					$topic_icon = 'fa-announce';
					$blink = false;
					if (!$is_guest)
					{
						if ($row['last_view_id'] != $row['last_msg_id'] && $row['last_timestamp'] >= $max_time_msg) //Nouveau message (non lu).
						{
							$topic_icon = $topic_icon . '-new'; //Image affiché aux visiteurs.
							$blink = true;
						}
					}
					$topic_icon .= ($row['cat_status'] == ForumCategory::STATUS_LOCKED) ? '-lock' : '';

					$total_topic += $row['nbr_topic'];
					$total_msg += $row['nbr_msg'];

					$last_msg_date = new Date($row['last_timestamp'], Timezone::SERVER_TIMEZONE);

					$this->view->assign_block_vars('forums_list.subcats', array_merge(
						Date::get_array_tpl_vars($last_msg_date, 'LAST_MESSAGE_DATE'), array(
						'C_BLINK'                 => $blink,
						'C_SUBFORUMS'             => !empty($subforums),
						'C_LAST_USER_GROUP_COLOR' => !empty($last_group_color),
						'C_LAST_TOPIC_MSG'        => !empty($row['last_topic_id']),
						'C_HAS_THUMBNAIL'         => !empty($row['thumbnail']),
						'C_HAS_CATEGORY_ICON'     => !empty($row['icon']),
						'C_HAS_CATEGORY_COLOR'    => !empty($row['color']),
						'C_LAST_MESSAGE_GUEST'    => ($row['last_user_id']) != '-1',

						'TOPIC_ICON'            => $topic_icon,
						'CATEGORY_ID'           => $row['cid'],
						'CATEGORY_NAME'         => $row['name'],
						'CATEGORY_PARENT_ID'  	=> $row['id_parent'],
						'CATEGORY_ICON'         => $row['icon'],
						'CATEGORY_COLOR'        => $row['color'],
						'REWRITED_NAME'         => $row['rewrited_name'],
						'DESCRIPTION'           => FormatingHelper::second_parse($row['subname']),
						'SUBFORUMS'             => $subforums,
						'TOPICS_NUMBER'         => $row['nbr_topic'],
						'MESSAGES_NUMBER'       => $row['nbr_msg'],
						'LAST_TOPIC_TITLE'      => !empty($row['last_topic_id']) ? stripslashes($last_topic_title) : '',
						'LAST_USER_LOGIN'       => $row['login'],
						'LAST_USER_LEVEL'       => UserService::get_level_class($row['user_level']),
						'LAST_USER_GROUP_COLOR' => $last_group_color,

						'U_LAST_TOPIC'         => PATH_TO_ROOT . "/forum/topic" . url('.php?id=' . $row['tid'], '-' . $row['tid'] . '-' . Url::encode_rewrite($row['title']) . '.php'),
						'U_LAST_MESSAGE'       => !empty($row['last_topic_id']) ? PATH_TO_ROOT . "/forum/topic" . url('.php?' . $last_page . 'id=' . $row['tid'], '-' . $row['tid'] . $last_page_rewrite . '-' . Url::encode_rewrite($row['title']) . '.php') . '#m' . $last_msg_id : '',
						'U_LAST_USER_PROFILE'  => UserUrlBuilder::profile($row['last_user_id'])->rel(),
						'U_LINK'               => Url::to_rel($row['url']),
						'U_CATEGORY_THUMBNAIL' => Url::to_rel($categories_cache->get_category($row['cid'])->get_thumbnail()),
						'U_CATEGORY'           => ForumUrlBuilder::display_forum($row['cid'], $row['rewrited_name'])->rel(),
					)));
				}
			}
		}

		if ($i > 0) //Fermeture de la catégorie racine.
		{
			$this->view->assign_block_vars('forums_list', array(
			));
			$this->view->assign_block_vars('forums_list.endcats', array(
			));
		}

		$site_path = GeneralConfig::get_default_site_path();
		if (GeneralConfig::load()->get_module_home_page() == self::$module_id)
		{
			list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online("AND s.location_script = '". $site_path ."/forum/' OR s.location_script = '". $site_path ."/forum/index.php' OR s.location_script = '". $site_path ."/index.php' OR s.location_script = '". $site_path ."/'");
		}
		else
		{
			$where = "AND s.location_script LIKE '%". $site_path ."/forum/%'";
			if (!empty($id_get))
			{
				$where = "AND s.location_script LIKE '%". $site_path . url('/forum/index.php?id=' . $id_get, '/forum/cat-' . $id_get . ($this->category !== false && $id_get != Category::ROOT_CATEGORY ? '-' . $this->category->get_rewrited_name() : '') . '.php') ."'";
			}
			list($users_list, $total_admin, $total_modo, $total_member, $total_visit, $total_online) = forum_list_user_online($where);
		}

		//Liste des catégories.
		$search_category_children_options = new SearchCategoryChildrensOptions();
		$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
		$categories_tree = CategoriesService::get_categories_manager(self::$module_id)->get_select_categories_form_field('cats', '', $id_get, $search_category_children_options);
		$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
		$method->setAccessible(true);
		$categories_tree_options = $method->invoke($categories_tree);
		$cat_list = '';
		foreach ($categories_tree_options as $option)
		{
			if ($option->get_raw_value())
			{
				$cat = CategoriesService::get_categories_manager(self::$module_id)->get_categories_cache()->get_category($option->get_raw_value());
				if (!$cat->get_url())
					$cat_list .= $option->display()->render();
			}
		}

		$vars_tpl = array(
			'C_TOTAL_POST'     => true,
			'C_USER_CONNECTED' => AppContext::get_current_user()->check_level(User::MEMBER_LEVEL),
			'C_NO_USER_ONLINE' => (($total_online - $total_visit) == 0),

			'FORUM_NAME'            => $this->config->get_forum_name(),
			'C_SEVERAL_MESSAGES'    => $total_msg > 1,
			'MESSAGES_NUMBER'       => $total_msg,
			'C_SEVERAL_TOPICS'      => $total_topic > 1,
			'TOPICS_NUMBER'         => $total_topic,
			'TOTAL_ONLINE'          => $total_online,
			'ONLINE_USERS_LIST'     => $users_list,
			'ADMINISTRATORS_NUMBER' => $total_admin,
			'MODERATORS_NUMBER'     => $total_modo,
			'MEMBERS_NUMBER'        => $total_member,
			'GUESTS_NUMBER'         => $total_visit,
			'SELECT_CAT'            => !empty($id_get) ? $cat_list : '', //Retourne la liste des catégories, avec les vérifications d'accès qui s'imposent.

			'U_ONCHANGE'     => PATH_TO_ROOT ."/forum/" . url("index.php?id=' + this.options[this.selectedIndex].value + '", "forum-' + this.options[this.selectedIndex].value + '.php"),
			'U_ONCHANGE_CAT' => PATH_TO_ROOT ."/forum/" . url("/index.php?id=' + this.options[this.selectedIndex].value + '", "cat-' + this.options[this.selectedIndex].value + '.php"),

			'L_USER'    => ($total_online > 1) ? $this->lang['user.users'] : $this->lang['user.user'],
			'L_ADMIN'   => ($total_admin > 1) ? $this->lang['user.administrators'] : $this->lang['user.administrator'],
			'L_MODO'    => ($total_modo > 1) ? $this->lang['user.moderators'] : $this->lang['user.moderator'],
			'L_MEMBER'  => ($total_member > 1) ? $this->lang['user.members'] : $this->lang['user.member'],
			'L_GUEST'   => ($total_visit > 1) ? $this->lang['user.guests'] : $this->lang['user.guest'],
		);

		$this->view->put_all($vars_tpl);
		$top_view->put_all($vars_tpl);
		$bottom_view->put_all($vars_tpl);

		$this->view->put('FORUM_TOP', $top_view);
		$this->view->put('FORUM_BOTTOM', $bottom_view);

		return $this->view;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function get_template_to_use()
	{
		return new FileTemplate('forum/forum_index.tpl');
	}

	private function generate_response()
	{
		$response = new SiteDisplayResponse($this->view);
		$graphical_environment = $response->get_graphical_environment();
		$graphical_environment->set_page_title($this->config->get_forum_name(), $this->category !== false && !empty($this->category) && $this->category->get_id() != Category::ROOT_CATEGORY ? $this->category->get_name() : '');

		$description = $this->category !== false ? $this->category->get_description() : '';
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['forum.root.description.seo'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->category !== false && $this->category->get_name() && $this->category->get_id() != Category::ROOT_CATEGORY ? ' ' . $this->lang['category.category'] . ' ' . $this->category->get_name() : '');

		$graphical_environment->get_seo_meta_data()->set_description($description);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(ForumUrlBuilder::home());

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->config->get_forum_name(), ForumUrlBuilder::home());

		if ($this->category !== false && $this->category->get_id() != Category::ROOT_CATEGORY)
			$breadcrumb->add($this->category->get_name(), Url::to_rel(url('/forum/index.php?id=' . $this->category->get_id(), '/forum/cat-' . $this->category->get_id() . '-' . $this->category->get_rewrited_name() . '.php')));

		return $response;
	}

	public static function get_view()
	{
		$object = new self(self::$module_id);
		$object->build_view();
		return $object->view;
	}
}
?>
