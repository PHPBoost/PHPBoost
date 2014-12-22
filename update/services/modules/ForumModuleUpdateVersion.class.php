<?php
/*##################################################
 *                       ForumModuleUpdateVersion.class.php
 *                            -------------------
 *   begin                : May 22, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : julienseth78@phpboost.com
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

class ForumModuleUpdateVersion extends ModuleUpdateVersion
{
	public function __construct()
	{
		parent::__construct('forum');
	}
	
	public function execute()
	{
		$this->delete_old_files();
	}
	
	private function delete_old_files()
	{
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/announce.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/announce_hot.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/announce_lock.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/announce_post.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/announce_top.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/border.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/cut.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/folder.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/forum_top.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/forum_top_l.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/forum_top_r.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/important_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/last_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/move.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_bottom.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_bottom_l.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_bottom_r.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_display.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_display2.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_display_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_top.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_top_l.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_top_r.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/msg_top_row.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/new_announce.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/new_announce_hot.gif'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/new_announce_lock.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/new_announce_post.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/new_announce_top.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/new_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/next_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/poll_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/stats.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/track_mail_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/track_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/track_pm_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/untrack_mail_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/untrack_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/untrack_pm_mini.png'));
		$file->delete();
		$file = new File(Url::to_rel('/' . $this->module_id . '/templates/images/weblink.png'));
		$file->delete();
	}
}
?>