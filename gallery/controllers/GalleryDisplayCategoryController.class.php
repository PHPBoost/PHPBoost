<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 10 28
 * @since       PHPBoost 4.1 - 2015 02 04
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor mipel <mipel@phpboost.com>
 * @contributor janus57 <janus57@janus57.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GalleryDisplayCategoryController extends ModuleController
{
	private $lang;
	private $view;

	private $category;
	private $db_querier;

	public function __construct()
	{
		$this->db_querier = PersistenceContext::get_querier();
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->check_authorizations();

		$this->init();

		$this->build_view();

		return $this->generate_response();
	}

	private function init()
	{
		$this->lang = LangLoader::get_all_langs('gallery');
		$this->view = new FileTemplate('gallery/gallery.tpl');
		$this->view->add_lang($this->lang);
	}

	private function build_view()
	{
		global $Bread_crumb;

		$g_idpics = (int)retrieve(GET, 'id', 0);
		$g_views  = (bool)retrieve(GET, 'views', false);
		$g_notes  = (bool)retrieve(GET, 'notes', false);
		$g_sort   = retrieve(GET, 'sort', '');
		$g_sort   = !empty($g_sort) ? 'sort=' . $g_sort : '';

		//Récupération du mode d'ordonnement.
		if (preg_match('`([a-z]+)_([a-z]+)`u', $g_sort, $array_match))
		{
			$g_type = $array_match[1];
			$g_mode = $array_match[2];
		}
		else
			list($g_type, $g_mode) = array('name', 'asc');

		$comments_topic = new GalleryCommentsTopic();
		$config = GalleryConfig::load();
		$comments_config = CommentsConfig::load();
		$content_management_config = ContentManagementConfig::load();
		$category = $this->get_category();

		$subcategories = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_children($category->get_id(), CategoriesService::get_authorized_categories($category->get_id(), true, 'gallery'));

		$elements_number = $category->get_elements_number();
		$nbr_pics = $elements_number['pics_aprob'];

		if ($category->get_id() == Category::ROOT_CATEGORY)
		{
			foreach ($subcategories as $cat)
			{
				$elements_number = $cat->get_elements_number();
				$nbr_pics += $elements_number['pics_aprob'];
			}
		}

		$Gallery = new Gallery();

		$total_cat = count($subcategories);

		//On crée une pagination si le nombre de catégories est trop important.
		$page = AppContext::get_request()->get_getint('p', 1);
		$pagination = new ModulePagination($page, $total_cat, $config->get_categories_number_per_page());
		$pagination->set_url(new Url('/gallery/gallery.php?p=%d&amp;cat=' . $category->get_id() . '&amp;id=' . $g_idpics . '&amp;' . $g_sort));

		if ($pagination->current_page_is_empty() && $page > 1)
		{
			$error_controller = PHPBoostErrors::unexisting_page();
			DispatchManager::redirect($error_controller);
		}

		//Colonnes des catégories.
		$nbr_column_cats = ($total_cat > $config->get_columns_number()) ? $config->get_columns_number() : $total_cat;
		$nbr_column_cats = !empty($nbr_column_cats) ? $nbr_column_cats : 1;
		$column_width_cats = floor(100/$nbr_column_cats);

		//Colonnes des images.
		$nbr_column_pics = ($nbr_pics > $config->get_columns_number()) ? $config->get_columns_number() : $nbr_pics;
		$nbr_column_pics = !empty($nbr_column_pics) ? $nbr_column_pics : 1;
		$column_width_pics = floor(100/$nbr_column_pics);

		$is_admin = AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL);
		$is_modo = CategoriesAuthorizationsService::check_authorizations($category->get_id())->moderation();

		$module_data_path = $this->view->get_pictures_data_path();
		$rewrite_title = Url::encode_rewrite($category->get_name());

		##### Catégorie disponibles #####
		$nbr_cat_displayed = 0;
		if ($total_cat > 0 && empty($g_idpics))
		{
			$this->view->put('C_GALLERY_CATS', true);

			foreach ($subcategories as $id => $cat)
			{
				$nbr_cat_displayed++;

				if ($nbr_cat_displayed > $pagination->get_display_from() && $nbr_cat_displayed <= ($pagination->get_display_from() + $pagination->get_number_items_per_page()))
				{
					$category_thumbnail = $cat->get_thumbnail()->rel();
					$elements_number = $cat->get_elements_number();

					$this->view->assign_block_vars('sub_categories_list', array(
						'C_CATEGORY_THUMBNAIL' => !empty($category_thumbnail),
						'C_SEVERAL_ITEMS'      => $elements_number['pics_aprob'] > 1,
						'CATEGORY_ID'          => $category->get_id(),
						'CATEGORY_NAME'        => $cat->get_name(),
						'CATEGORY_PARENT_ID'   => $cat->get_id_parent(),
						'ITEMS_NUMBER'         => sprintf($elements_number['pics_aprob']),
						'U_CATEGORY_THUMBNAIL' => $category_thumbnail,
						'U_CATEGORY'           => GalleryUrlBuilder::get_link_cat($cat->get_id(), $cat->get_name())
					));
				}
			}
		}

		$category_description = FormatingHelper::second_parse($category->get_description());
		$this->view->put_all(array(
			'C_ROOT_CATEGORY'            => $category->get_id() == Category::ROOT_CATEGORY,
			'C_CATEGORY_DESCRIPTION'     => $category_description,
			'C_SUB_CATEGORIES'           => $nbr_cat_displayed > 0,
			'C_SUBCATEGORIES_PAGINATION' => $pagination->has_several_pages(),
			'CATEGORY_NAME'              => $category->get_name(),
			'CATEGORY_PARENT_ID'  		 => $category->get_id_parent(),
			'CATEGORY_SUB_ORDER'   		 => $category->get_order(),

			'ID_CATEGORY'              => $category->get_id(),
			'DISPLAY_MODE'             => $config->get_pics_enlargement_mode(),
			'SUBCATEGORIES_PAGINATION' => $pagination->display(),
			'ARRAY_JS'                 => '',
			'JS_ITEMS_NUMBER'          => 0,
			'MAX_START'                => 0,
			'START_THUMB'              => 0,
			'COLUMNS_NUMBER'           => $config->get_columns_number(),
			'CATEGORY_DESCRIPTION'     => $category_description,
			'ITEMS_NUMBER'             => $nbr_pics,

			'U_EDIT_CATEGORY'    => $category->get_id() == Category::ROOT_CATEGORY ? GalleryUrlBuilder::configuration()->rel() : CategoriesUrlBuilder::edit($category->get_id(), 'gallery')->rel(),
			'U_BEST_VIEWS'       => PATH_TO_ROOT . '/gallery/gallery' . url('.php?views=1&amp;cat=' . $category->get_id(), '-' . $category->get_id() . '.php?views=1'),
			'U_BEST_NOTES'       => PATH_TO_ROOT . '/gallery/gallery' . url('.php?notes=1&amp;cat=' . $category->get_id(), '-' . $category->get_id() . '.php?notes=1'),
			'U_ASC'              => PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $category->get_id() . '&amp;sort=' . $g_type . '_' . 'asc', '-' . $category->get_id() . '.php?sort=' . $g_type . '_' . 'asc'),
			'U_DESC'             => PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $category->get_id() . '&amp;sort=' . $g_type . '_' . 'desc', '-' . $category->get_id() . '.php?sort=' . $g_type . '_' . 'desc'),
			'U_SORT_BY_NAME'     => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=name_desc&amp;cat=' . $category->get_id(), '-' . $category->get_id() . '+' . $rewrite_title . '.php?sort=name_desc'),
			'U_SORT_BY_DATE'     => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=date_desc&amp;cat=' . $category->get_id(), '-' . $category->get_id() . '+' . $rewrite_title . '.php?sort=date_desc'),
			'U_SORT_BY_VIEWS'    => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=views_desc&amp;cat=' . $category->get_id(), '-' . $category->get_id() . '+' . $rewrite_title . '.php?sort=views_desc'),
			'U_SORT_BY_NOTES'    => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=notes_desc&amp;cat=' . $category->get_id(), '-' . $category->get_id() . '+' . $rewrite_title . '.php?sort=notes_desc'),
			'U_SORT_BY_COMMENTS' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?sort=com_desc&amp;cat=' . $category->get_id(), '-' . $category->get_id() . '+' . $rewrite_title . '.php?sort=com_desc'),
		));

		##### Affichage des photos #####
		if ($nbr_pics > 0)
		{
			switch ($g_type)
			{
				case 'name' :
					$sort_type = 'g.name';
					break;
				case 'date' :
					$sort_type = 'g.timestamp';
					break;
				case 'views' :
					$sort_type = 'g.views';
					break;
				case 'notes' :
					$sort_type = 'notes.average_notes';
					break;
				case 'com' :
					$sort_type = 'com.comments_number';
					break;
				default :
					$sort_type = 'g.timestamp';
			}
			switch ($g_mode)
			{
				case 'desc' :
					$sort_mode = 'DESC';
					break;
				case 'asc' :
					$sort_mode = 'ASC';
					break;
				default:
					$sort_mode = 'DESC';
			}
			$g_sql_sort = ' ORDER BY ' . $sort_type . ' ' . $sort_mode;
			if ($g_views)
				$g_sql_sort = ' ORDER BY g.views DESC';
			elseif ($g_notes)
				$g_sql_sort = ' ORDER BY notes.average_notes DESC';

			$this->view->put('C_GALLERY_PICS', true);

			//Affichage d'une photo demandée.
			if (!empty($g_idpics))
			{
				$info_pics = array();
				try {
					$info_pics = $this->db_querier->select_single_row_query("SELECT g.*, m.display_name, m.user_groups, m.level, notes.average_notes, notes.notes_number, note.note
					FROM " . GallerySetup::$gallery_table . " g
					LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
					LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
					LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = g.id AND notes.module_name = 'gallery'
					LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = g.id AND note.module_name = 'gallery' AND note.user_id = :user_id
					WHERE g.id_category = :id_category AND g.id = :id AND g.aprob = 1
					" . $g_sql_sort, array(
						'user_id' => AppContext::get_current_user()->get_id(),
						'id_category' => $category->get_id(),
						'id' => $g_idpics
					));
				} catch (RowNotFoundException $e) {}

				if ($info_pics && !empty($info_pics['id']))
				{
					$Bread_crumb->add(stripslashes($info_pics['name']), PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $info_pics['id_category'] . '&amp;id=' . $info_pics['id'], '-' . $info_pics['id_category'] . '-' . $info_pics['id'] . '.php'));

					//Affichage miniatures.
					$id_previous = 0;
					$id_next = 0;
					$nbr_pics_display_before = floor(($nbr_column_pics - 1)/2); //Nombres de photos de chaque côté de la miniature de la photo affichée.
					$nbr_pics_display_after = ($nbr_column_pics - 1) - floor($nbr_pics_display_before);
					list($i, $reach_pics_pos, $pos_pics, $thumbnails_before, $thumbnails_after, $start_thumbnails, $end_thumbnails) = array(0, false, 0, 0, 0, $nbr_pics_display_before, $nbr_pics_display_after);
					$array_pics = array();
					$array_js = 'var array_pics = new Array();';
					$result = $this->db_querier->select("SELECT g.id, g.id_category, g.path, g.name
					FROM " . GallerySetup::$gallery_table . " g
					WHERE g.id_category = :id_category AND g.aprob = 1
					" . $g_sql_sort, array(
						'id_category' => $category->get_id()
					));
					while ($row = $result->fetch())
					{
						//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
						if (!file_exists(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']))
							$Gallery->Resize_pics(PATH_TO_ROOT . '/gallery/pics/' . $row['path']); //Redimensionnement + création miniature

						//Affichage de la liste des miniatures sous l'image.
						$array_pics[] = array(
							'C_CURRENT_ITEM' => $row['id'] == $g_idpics,
							'HEIGHT'         => ($config->get_mini_max_height() + 16),
							'ID'             => $i,
							'URL'            => 'gallery' . url('.php?cat=' . $row['id_category'] . '&amp;id=' . $row['id'] . '&amp;sort=' . $g_sort, '-' . $row['id_category'] . '-' . $row['id'] . '.php?sort=' . $g_sort) . '#pics_max',
							'NAME'           => stripslashes($row['name']),
							'PATH'           => $row['path']
						);

						if ($row['id'] == $g_idpics)
						{
							$reach_pics_pos = true;
							$pos_pics = $i;
						}
						else
						{
							if (!$reach_pics_pos)
							{
								$thumbnails_before++;
								$id_previous = $row['id'];
							}
							else
							{
								$thumbnails_after++;
								if (empty($id_next))
									$id_next = $row['id'];
							}
						}
						$array_js .= 'array_pics[' . $i . '] = new Array();' . "\n";
						$array_js .= 'array_pics[' . $i . '][\'link\'] = \'' . GalleryUrlBuilder::get_link_item($row['id_category'],$row['id']) . '#pics_max' . "';\n";
						$array_js .= 'array_pics[' . $i . '][\'path\'] = \'' . $row['path'] . "';\n";
						$i++;
					}
					$result->dispose();

					$activ_note = ($content_management_config->module_notation_is_enabled('gallery') && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL) );
					if ($activ_note)
					{
						//Affichage notation.
						$notation = new Notation();
						$notation->set_module_name('gallery');
						$notation->set_id_in_module($info_pics['id']);
						$notation->set_notes_number($info_pics['notes_number']);
						$notation->set_average_notes($info_pics['average_notes']);
						$notation->set_user_already_noted(!empty($info_pics['note']));
					}

					if ($thumbnails_before < $nbr_pics_display_before)
						$end_thumbnails += $nbr_pics_display_before - $thumbnails_before;
					if ($thumbnails_after < $nbr_pics_display_after)
						$start_thumbnails += $nbr_pics_display_after - $thumbnails_after;

					$html_protected_name = $info_pics['name'];

					$comments_topic->set_id_in_module($info_pics['id']);
					$comments_topic->set_url(new Url('/gallery/gallery.php?cat='. $category->get_id() .'&id=' . $g_idpics . '&com=0'));

					//Liste des catégories.
					$search_category_children_options = new SearchCategoryChildrensOptions();
					$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
					$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
					$categories_tree = CategoriesService::get_categories_manager('gallery')->get_select_categories_form_field($info_pics['id'] . 'cat', '', $info_pics['id_category'], $search_category_children_options);
					$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
					$method->setAccessible(true);
					$categories_tree_options = $method->invoke($categories_tree);
					$cat_list = '';
					foreach ($categories_tree_options as $option)
					{
						$cat_list .= $option->display()->render();
					}

					$group_color = User::get_group_color($info_pics['user_groups'], $info_pics['level']);

					$date = new Date($info_pics['timestamp'], Timezone::SERVER_TIMEZONE);

					$info = new SplFileInfo($info_pics['path']);
					$extension = $info->getExtension();

					//Affichage de l'image et de ses informations.
					$this->view->put_all(array_merge(
						Date::get_array_tpl_vars($date,'date'),
						array(
							'C_GALLERY_PICS_MAX'      => true,
							'C_GALLERY_PICS_MODO'     => $is_modo,
							'C_AUTHOR_DISPLAYED'      => $config->is_author_displayed(),
							'C_VIEWS_COUNTER_ENABLED' => $config->is_views_counter_enabled(),
							'C_TITLE_ENABLED'         => $config->is_title_enabled(),
							'C_COMMENTS_ENABLED'      => $comments_config->module_comments_is_enabled('gallery'),
							'C_NOTATION_ENABLED'      => $content_management_config->module_notation_is_enabled('gallery'),
							'C_AUTHOR_EXISTS'         => !empty($info_pics['display_name']),
							'C_AUTHOR_GROUP_COLOR'    => !empty($group_color),
							'C_LEFT_THUMBNAILS'       => (($pos_pics - $start_thumbnails) > 0),
							'C_RIGHT_THUMBNAILS'      => (($pos_pics - $start_thumbnails) <= ($i - 1) - $nbr_column_pics),
							'C_DISPLAY_STATUS'        => $info_pics['aprob'] == 1,

							'ID'                  => $info_pics['id'],
							'NAME'                => !empty(stripslashes($info_pics['name'])) ? stripslashes($info_pics['name']) : $info_pics['path'],
							'AUTHOR_DISPLAY_NAME' => $info_pics['display_name'],
							'AUTHOR_LEVEL_CLASS'  => UserService::get_level_class($info_pics['level']),
							'AUTHOR_GROUP_COLOR'  => $group_color,
							'VIEWS_NUMBER'        => ($info_pics['views'] + 1),
							'DIMENSION'           => $info_pics['width'] . ' x ' . $info_pics['height'],
							'SIZE'                => NumberHelper::round($info_pics['weight']/1024, 1),
							'COMMENTS'            => CommentsService::get_number_and_lang_comments('gallery', $info_pics['id']),
							'COMMENTS_NUMBER'     => CommentsService::get_comments_number('gallery', $info_pics['id']),
							'KERNEL_NOTATION'     => $activ_note ? NotationService::display_active_image($notation) : '',
							'COLSPAN'             => min(($i + 2), ($config->get_columns_number() + 2)),
							'CATEGORIES_LIST'     => $cat_list,
							'RENAME'              => $html_protected_name,
							'RENAME_CUT'          => $html_protected_name,
							'ARRAY_JS'            => $array_js,
							'JS_ITEMS_NUMBER'     => ($i - 1),
							'MAX_START'           => ($i - 1) - $nbr_column_pics,
							'START_THUMB'         => (($pos_pics - $start_thumbnails) > 0) ? ($pos_pics - $start_thumbnails) : 0,
							'END_THUMB'           => ($pos_pics + $end_thumbnails),

							'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($info_pics['user_id'])->rel(),
							'U_DELETE'         => url('gallery.php?del=' . $info_pics['id'] . '&amp;token=' . AppContext::get_session()->get_token() . '&amp;cat=' . $category->get_id()),
							'U_MOVE'           => url('gallery.php?id=' . $info_pics['id'] . '&amp;token=' . AppContext::get_session()->get_token() . '&amp;move=\' + this.options[this.selectedIndex].value'),
							'U_PREVIOUS'       => ($pos_pics > 0) ? GalleryUrlBuilder::get_link_item($category->get_id(),$id_previous) : '',
							'U_NEXT'           => ($pos_pics < ($i - 1)) ? GalleryUrlBuilder::get_link_item($category->get_id(),$id_next) : '',
							'U_COMMENTS'       => GalleryUrlBuilder::get_link_item($info_pics['id_category'],$info_pics['id'],0,$g_sort) .'#comments-list',
							'U_ITEM_MAX'       => 'show_pics.php?id=' . $info_pics['id'] . '&amp;cat=' . $info_pics['id_category'] . '&amp;ext=.' . $extension,
						)
					));

					//Affichage de la liste des miniatures sous l'image.
					$i = 0;
					foreach ($array_pics as $pics)
					{
						if ($i >= ($pos_pics - $start_thumbnails) && $i <= ($pos_pics + $end_thumbnails))
						{
							$this->view->assign_block_vars('list_preview_pics', $pics);
						}
						$i++;
					}

					//Commentaires
					if (AppContext::get_request()->get_getint('com', 0) == 0 && $comments_config->module_comments_is_enabled('gallery'))
					{
						$this->view->put_all(array(
							'COMMENTS' => CommentsService::display($comments_topic)->render()
						));
					}
				}
			}
			else
			{
				$sort = retrieve(GET, 'sort', '');

				//On crée une pagination si le nombre de photos est trop important.
				$page = AppContext::get_request()->get_getint('pp', 1);
				$pagination = new ModulePagination($page, GalleryService::count('WHERE id_category = :id_category AND aprob = 1', array('id_category' => $category->get_id())), $config->get_pics_number_per_page());
				$pagination->set_url(new Url('/gallery/gallery.php?pp=%d' . (!empty($sort) ? '&amp;sort=' . $sort : '') . '&amp;cat=' . $category->get_id()));

				if ($pagination->current_page_is_empty() && $page > 1)
				{
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}

				$this->view->put_all(array(
					'C_CONTROLS'              => $is_modo,
					'C_NAME_DISPLAYED'        => $config->is_title_enabled(),
					'C_AUTHOR_DISPLAYED'      => $config->is_author_displayed(),
					'C_VIEWS_COUNTER_ENABLED' => $config->is_views_counter_enabled(),
					'C_COMMENTS_ENABLED'      => $comments_config->module_comments_is_enabled('gallery'),
					'C_NOTATION_ENABLED'      => $content_management_config->module_notation_is_enabled('gallery'),
					'C_PAGINATION'            => $pagination->has_several_pages(),

					'PAGINATION' => $pagination->display(),
				));

				$is_connected = AppContext::get_current_user()->check_level(User::MEMBER_LEVEL);
				$j = 0;
				$result = $this->db_querier->select("SELECT
					g.id, g.id_category, g.name, g.path, g.timestamp, g.aprob, g.width, g.height, g.user_id, g.views, g.aprob,
					m.display_name, m.user_groups, m.level,
					notes.average_notes, notes.notes_number, note.note
				FROM " . GallerySetup::$gallery_table . " g
				LEFT JOIN " . DB_TABLE_MEMBER . " m ON m.user_id = g.user_id
				LEFT JOIN " . DB_TABLE_COMMENTS_TOPIC . " com ON com.id_in_module = g.id AND com.module_id = 'gallery'
				LEFT JOIN " . DB_TABLE_AVERAGE_NOTES . " notes ON notes.id_in_module = g.id AND notes.module_name = 'gallery'
				LEFT JOIN " . DB_TABLE_NOTE . " note ON note.id_in_module = g.id AND note.module_name = 'gallery' AND note.user_id = :user_id
				WHERE g.id_category = :id_category AND g.aprob = 1
				" . $g_sql_sort . "
				LIMIT :number_items_per_page OFFSET :display_from", array(
					'user_id' => AppContext::get_current_user()->get_id(),
					'id_category' => $category->get_id(),
					'number_items_per_page' => $pagination->get_number_items_per_page(),
					'display_from' => $pagination->get_display_from()
				));

				$this->view->put('C_ITEMS', $result->get_rows_count() > 0);

				while ($row = $result->fetch())
				{
					//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
					if (!file_exists(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']))
						$Gallery->Resize_pics(PATH_TO_ROOT . '/gallery/pics/' . $row['path']); //Redimensionnement + création miniature

					$info = new SplFileInfo($row['path']);
					$extension = $info->getExtension();

					$onclick = '';
					//Affichage de l'image en grand.
					if ($config->get_pics_enlargement_mode() == GalleryConfig::FULL_SCREEN) //Ouverture en popup plein écran.
					{
						$display_link = 'show_pics.php?id=' . $row['id'] . '&amp;cat=' . $row['id_category'] . '&amp;ext=.' . $extension;
					}
					elseif ($config->get_pics_enlargement_mode() == GalleryConfig::POPUP) //Ouverture en popup simple.
					{
						$onclick = 'increment_view(' . $row['id'] . ');display_pics_popup(\'' . PATH_TO_ROOT . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['id_category']) . '\', \'' . $row['width'] . '\', \'' . $row['height'] . '\');return false;';
						$display_link = '';
					}
					elseif ($config->get_pics_enlargement_mode() == GalleryConfig::RESIZE) //Ouverture en agrandissement simple.
					{
						$onclick = 'increment_view(' . $row['id'] . ');display_pics(' . $row['id'] . ', \'' . PATH_TO_ROOT . '/gallery/show_pics' . url('.php?id=' . $row['id'] . '&amp;cat=' . $row['id_category']) . '\');return false;';
						$display_link = '';
					}
					else //Ouverture nouvelle page.
					{
						$onclick = true;
						$display_link = url('gallery.php?cat=' . $row['id_category'] . '&amp;id=' . $row['id'], 'gallery-' . $row['id_category'] . '-' . $row['id'] . '.php') . '#pics_max';
					}

					//Liste des catégories.
					$search_category_children_options = new SearchCategoryChildrensOptions();
					$search_category_children_options->add_authorizations_bits(Category::READ_AUTHORIZATIONS);
					$search_category_children_options->add_authorizations_bits(Category::WRITE_AUTHORIZATIONS);
					$categories_tree = CategoriesService::get_categories_manager('gallery')->get_select_categories_form_field($row['id'] . 'cat', '', $row['id_category'], $search_category_children_options);
					$method = new ReflectionMethod('AbstractFormFieldChoice', 'get_options');
					$method->setAccessible(true);
					$categories_tree_options = $method->invoke($categories_tree);
					$cat_list = '';
					foreach ($categories_tree_options as $option)
					{
						$cat_list .= $option->display()->render();
					}

					$notation = new Notation();
					$notation->set_module_name('gallery');
					$notation->set_id_in_module($row['id']);
					$notation->set_notes_number( $row['notes_number']);
					$notation->set_average_notes($row['average_notes']);
					$notation->set_user_already_noted(!empty($row['note']));

					$group_color = User::get_group_color($row['user_groups'], $row['level']);

					$comments_topic->set_id_in_module($row['id']);

					$html_protected_name = $row['name'];

					$this->view->assign_block_vars('pics_list', array(
						'C_APPROVED' => $row['aprob'] == 1,
						'C_AUTHOR_EXISTS' => !empty($row['display_name']),
						'C_AUTHOR_GROUP_COLOR' => !empty($group_color),
						'C_SEVERAL_VIEWS' => $row['views'] > 1,
						'C_NEW_CONTENT' => ContentManagementConfig::load()->module_new_content_is_enabled_and_check_date('gallery', $row['timestamp']),

						'ID' => $row['id'],
						'PATH' => $row['path'],
						'NAME' => !empty(stripslashes($row['name'])) ? stripslashes($row['name']) : $row['path'],
						'AUTHOR_DISPLAY_NAME' => $row['display_name'],
						'AUTHOR_LEVEL_CLASS' => UserService::get_level_class($row['level']),
						'AUTHOR_GROUP_COLOR' => $group_color,
						'VIEWS_NUMBER' => $row['views'],
						'KERNEL_NOTATION' => $content_management_config->module_notation_is_enabled('gallery') && $is_connected ? NotationService::display_active_image($notation) : NotationService::display_static_image($notation),
						'CATEGORIES_LIST' => $cat_list,
						'ONCLICK' => $onclick,
						'RENAME' => $html_protected_name,
						'RENAME_CUT' => $html_protected_name,
						'COMMENTS' => CommentsService::get_number_and_lang_comments('gallery', $row['id']),

						'U_AUTHOR_PROFILE' => UserUrlBuilder::profile($row['user_id'])->rel(),
						'U_ITEM' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $row['id_category'] . '&amp;id=' . $row['id'], '-' . $row['id_category'] . '-' . $row['id'] . '.php'),
						'U_PICTURE' => 'show_pics.php?id=' . $row['id'] . '&amp;cat=' . $row['id_category'] . '&amp;ext=.' . $extension,
						'U_DELETE' => url('gallery.php?del=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token() . '&amp;cat=' . $category->get_id()),
						'U_MOVE' => url('gallery.php?id=' . $row['id'] . '&amp;token=' . AppContext::get_session()->get_token() . '&amp;move=\' + this.options[this.selectedIndex].value'),
						'U_DISPLAY' => $display_link,
						'U_COMMENTS' => PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $row['id_category'] . '&amp;id=' . $row['id'] . '&amp;com=0', '-' . $row['id_category'] . '-' . $row['id'] . '.php?com=0') . '#comments-list',
					));
				}
				$result->dispose();

				//Création des cellules du tableau si besoin est.
				while (!is_int($j/$nbr_column_pics))
				{
					$this->view->assign_block_vars('end_table', array(
						'COLUMN_WIDTH_PICS' => $column_width_pics,
						'C_DISPLAY_TR_END' => (is_int(++$j/$nbr_column_pics))
					));
				}
			}
		}
	}

	private function get_category()
	{
		if ($this->category === null)
		{
			$id = AppContext::get_request()->get_getint('cat', 0);
			if (!empty($id))
			{
				try {
					$this->category = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_category($id);
				} catch (CategoryNotFoundException $e) {
					$error_controller = PHPBoostErrors::unexisting_page();
					DispatchManager::redirect($error_controller);
				}
			}
			else
			{
				$this->category = CategoriesService::get_categories_manager('gallery')->get_categories_cache()->get_category(Category::ROOT_CATEGORY);
			}
		}
		return $this->category;
	}

	private function check_authorizations()
	{
		$id_cat = $this->get_category()->get_id();
		if (!CategoriesAuthorizationsService::check_authorizations($id_cat)->read())
		{
			$error_controller = PHPBoostErrors::user_not_authorized();
			DispatchManager::redirect($error_controller);
		}
	}

	private function generate_response()
	{
		$page = AppContext::get_request()->get_getint('p', 1);
		$response = new SiteDisplayResponse($this->view);

		$graphical_environment = $response->get_graphical_environment();

		if ($this->get_category()->get_id() != Category::ROOT_CATEGORY)
			$graphical_environment->set_page_title($this->get_category()->get_name(), $this->lang['gallery.module.title'], $page);
		else
			$graphical_environment->set_page_title($this->lang['gallery.module.title'], '', $page);

		$description = $this->get_category()->get_description();
		if (empty($description))
			$description = StringVars::replace_vars($this->lang['gallery.seo.description.root'], array('site' => GeneralConfig::load()->get_site_name())) . ($this->get_category()->get_id() != Category::ROOT_CATEGORY ? ' ' . LangLoader::get_message('category.category', 'category-lang') . ' ' . $this->get_category()->get_name() : '');
		$graphical_environment->get_seo_meta_data()->set_description($description, $page);
		$graphical_environment->get_seo_meta_data()->set_canonical_url(GalleryUrlBuilder::display_category($this->get_category()->get_id(), $this->get_category()->get_rewrited_name(), $page));

		$breadcrumb = $graphical_environment->get_breadcrumb();
		$breadcrumb->add($this->lang['gallery.module.title'], GalleryUrlBuilder::home());

		$categories = array_reverse(CategoriesService::get_categories_manager('gallery')->get_parents($this->get_category()->get_id(), true));
		foreach ($categories as $id => $category)
		{
			if ($category->get_id() != Category::ROOT_CATEGORY)
				$breadcrumb->add($category->get_name(), GalleryUrlBuilder::display_category($category->get_id(), $category->get_rewrited_name(), ($category->get_id() == $this->get_category()->get_id() ? $page : 1)));
		}

		return $response;
	}

	public static function get_view()
	{
		$object = new self();
		$object->init();
		$object->check_authorizations();
		$object->build_view();
		return $object->view;
	}
}
?>
