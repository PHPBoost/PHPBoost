<?php
/*##################################################
 *                               DownloadCommentsTopic.class.php
 *                            -------------------
 *   begin                : August 24, 2014
 *   copyright            : (C) 2014 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
 *
 *
 ###################################################
 *
 * This program is a free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

 /**
 * @author Julien BRISWALTER <j1.seth@phpboost.com>
 */

class DownloadCommentsTopic extends CommentsTopic
{
	private $downloadfile;
	
	public function __construct(DownloadFile $downloadfile = null)
	{
		parent::__construct('download');
		$this->downloadfile = $downloadfile;
	}
	
	public function get_authorizations()
	{
		$authorizations = new CommentsAuthorizations();
		$authorizations->set_authorized_access_module(DownloadAuthorizationsService::check_authorizations($this->get_downloadfile()->get_id_category())->read());
		return $authorizations;
	}
	
	public function is_display()
	{
		return $this->get_downloadfile()->is_visible();
	}
	
	private function get_downloadfile()
	{
		if ($this->downloadfile === null)
		{
			$this->downloadfile = DownloadService::get_downloadfile('WHERE download.id=:id', array('id' => $this->get_id_in_module()));
		}
		return $this->downloadfile;
	}
}
?>
