<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2023 12 05
 * @since       PHPBoost 3.0 - 2011 10 08
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Pierre Pelisset <ppelisset@hotmail.fr>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class GalleryModuleMiniMenu extends ModuleMiniMenu
{
	public function get_default_block()
	{
		return self::BLOCK_POSITION__RIGHT;
	}

	public function get_menu_id()
	{
		return 'module-mini-gallery';
	}

	public function get_menu_title()
	{
		return LangLoader::get_message('gallery.random.items', 'common', 'gallery');
	}

	public function get_formated_title()
	{
		return LangLoader::get_message('gallery.module.title', 'common', 'gallery');
	}

	public function is_displayed()
	{
		return CategoriesAuthorizationsService::check_authorizations(Category::ROOT_CATEGORY, 'gallery')->read();
	}

	public function get_menu_content()
	{
		$view = new FileTemplate('gallery/gallery_mini.tpl');
		$view->add_lang(LangLoader::get_all_langs('gallery'));

		MenuService::assign_positions_conditions($view, $this->get_block());

		//Chargement de la langue du module.
		$config = GalleryConfig::load();

		$array_random_pics = GalleryMiniMenuCache::load()->get_pictures();

		$i = 0;

		//Affichage des miniatures disponibles
		$array_pics_mini = 'var array_pics_mini = new Array();' . "\n";
		list($nbr_pics, $sum_height, $sum_width, $scoll_mode, $height_max, $width_max) = array(0, 0, 0, 0, 142, 142);
		if (isset($array_random_pics) && $array_random_pics !== array())
		{
			$gallery_mini = array();
			shuffle($array_random_pics); //On mélange les éléments du tableau.

			//Vérification des autorisations.
			$break = 0;
			foreach ($array_random_pics as $array_pics_info)
			{
				if (isset($array_pics_info['id_category']) && CategoriesAuthorizationsService::check_authorizations($array_pics_info['id_category'], 'gallery')->read())
				{
					$gallery_mini[] = $array_pics_info;
					$break++;
				}
				if ($break == $config->get_pics_number_in_mini())
					break;
			}

			//Aucune photo ne correspond, on fait une requête pour vérifier.
			if (count($gallery_mini) == 0)
			{
				$array_random_pics = array();
				$result = PersistenceContext::get_querier()->select("SELECT g.id, g.name, g.path, g.width, g.height, g.id_category, gc.auth
				FROM " . GallerySetup::$gallery_table . " g
				LEFT JOIN " . GallerySetup::$gallery_cats_table . " gc on gc.id = g.id_category
				WHERE g.aprob = 1
				ORDER BY RAND()
				LIMIT " . $config->get_pics_number_in_mini());
				while($row = $result->fetch())
				{
					$array_random_pics[] = $row;
				}

				//Vérification des autorisations.
				$break = 0;
				foreach ($array_random_pics as $key => $array_pics_info)
				{
					if (CategoriesAuthorizationsService::check_authorizations($array_pics_info['id_category'], 'gallery')->read())
					{
						$gallery_mini[] = $array_pics_info;
						$break++;
					}
					if ($break == $config->get_pics_number_in_mini())
						break;
				}
			}

			$view->put_all(array(
				'C_FADE'              => false,
				'C_VERTICAL_SCROLL'   => false,
				'C_HORIZONTAL_SCROLL' => false,
				'C_STATIC'            => false
			));

			switch ($config->get_scroll_type())
			{
				case GalleryConfig::STATIC_SCROLL :
					$view->put('C_FADE', true);
					break;
				case GalleryConfig::VERTICAL_DYNAMIC_SCROLL :
					$view->put('C_VERTICAL_SCROLL', true);
					break;
				case GalleryConfig::HORIZONTAL_DYNAMIC_SCROLL :
					$view->put('C_HORIZONTAL_SCROLL', true);
					break;
				case GalleryConfig::NO_SCROLL :
					$view->put('C_STATIC', true);
					break;
			}

			$Gallery = new Gallery();

			foreach ($gallery_mini as $key => $row)
			{
				//Si la miniature n'existe pas (cache vidé) on regénère la miniature à partir de l'image en taille réelle.
				if (!is_file(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']))
					$Gallery->Resize_pics(PATH_TO_ROOT . '/gallery/pics/' . $row['path']); //Redimensionnement + création miniature

				// On recupère la hauteur et la largeur de l'image.
				if ($row['width'] == 0 || $row['height'] == 0)
					list($row['width'], $row['height']) = @getimagesize(PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path']);
				if ($row['width'] == 0 || $row['height'] == 0)
					list($row['width'], $row['height']) = array(142, 142);

				$view->assign_block_vars('pics_mini', array(
					'ID'          => $row['id'],
					'U_THUMBNAIL' => TPL_PATH_TO_ROOT . '/gallery/pics/thumbnails/' . $row['path'],
					'NAME'        => $row['name'],
					'HEIGHT'      => $row['height'],
					'WIDTH'       => $row['width'],
					'U_ITEM'      => TPL_PATH_TO_ROOT . '/gallery/gallery' . url('.php?cat=' . $row['id_category'] . '&amp;id=' . $row['id'], '-' . $row['id_category'] . '-' . $row['id'] . '.php')
				));

				$sum_height += $row['height'] + 5;
				$sum_width += $row['width'] + 5;

				$i++;

				if ($config->get_scroll_type() == GalleryConfig::NO_SCROLL)
					break;
			}
		}

		$view->put_all(array(
            'C_NO_ITEM' => $i == 0,

			'SCROLL_DELAY'  => $config->get_mini_pics_speed()*1000,
			'ARRAY_PICS'    => $array_pics_mini,
			'HEIGHT_DIV'    => $config->get_mini_max_height(),
			'SUM_HEIGHT'    => $sum_height + 10,
			'HIDDEN_HEIGHT' => $config->get_mini_max_height() + 10,
			'WIDTH_DIV'     => $config->get_mini_max_width(),
			'SUM_WIDTH'     => $sum_width + 30,
			'HIDDEN_WIDTH'  => ($config->get_mini_max_width() * 3) + 30,
		));
		return $view->render();
	}
}
?>
