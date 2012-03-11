<?php
/*##################################################
 *                           GalleryConfigUpdateVersion.class.php
 *                            -------------------
 *   begin                : March 8, 2012
 *   copyright            : (C) 2012 Julien BRISWALTER
 *   email                : julien.briswalter@gmail.com
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

class GalleryConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('gallery');
	}

	protected function build_new_config()
	{
		$config = $this->get_old_config();
		
		$logo_activated = ($config['activ_logo'] == 1) ? true : false;
		$title_activated = ($config['activ_title'] == 1) ? true : false;
		$comments_activated = ($config['activ_com'] == 1) ? true : false;
		$note_activated = ($config['activ_note'] == 1) ? true : false;
		$display_nbr_note = ($config['display_nbrnote'] == 1) ? true : false;
		$view_activated = ($config['activ_view'] == 1) ? true : false;
		$user_activated = ($config['activ_user'] == 1) ? true : false;
		
		$gallery_config = GalleryConfig::load();
		$gallery_config->set_width($config['width']);
		$gallery_config->set_height($config['height']);
		$gallery_config->set_width_max($config['width_max']);
		$gallery_config->set_height_max($config['height_max']);
		$gallery_config->set_weight_max($config['weight_max']);
		$gallery_config->set_quality($config['quality']);
		$gallery_config->set_transparency($config['trans']);
		$gallery_config->set_logo($config['logo']);
		$gallery_config->set_logo_activated($logo_activated);
		$gallery_config->set_d_width($config['d_width']);
		$gallery_config->set_d_height($config['d_height']);
		$gallery_config->set_nbr_columns($config['nbr_column']);
		$gallery_config->set_nbr_pics_max($config['nbr_pics_max']);
		$gallery_config->set_note_max($config['note_max']);
		$gallery_config->set_title_activated($title_activated);
		$gallery_config->set_comments_activated($comments_activated);
		$gallery_config->set_note_activated($note_activated);
		$gallery_config->set_display_nbr_note($display_nbr_note);
		$gallery_config->set_view_activated($view_activated);
		$gallery_config->set_user_activated($user_activated);
		$gallery_config->set_limit_member($config['limit_member']);
		$gallery_config->set_limit_modo($config['limit_modo']);
		$gallery_config->set_display_pics($config['display_pics']);
		$gallery_config->set_scroll_type($config['scroll_type']);
		$gallery_config->set_nbr_pics_mini($config['nbr_pics_mini']);
		$gallery_config->set_speed_mini_pics($config['speed_mini_pics']);
		$gallery_config->set_authorizations($config['global_auth']);
		GalleryConfig::save();

		return true;
	}
}
?>