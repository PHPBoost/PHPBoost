<?php
/*##################################################
 *		                        ContentSharingActionsMenuLink.class.php
 *                            -------------------
 *   begin                : January 30, 2018
 *   copyright            : (C) 2018 Kévin MASSY
 *   email                : kevin.massy@phpboost.com
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

/**
 * @author Kevin MASSY <kevin.massy@phpboost.com>
 */
class ContentSharingActionsMenuLink
{
	private $id;
	private $name;
	private $url;
	private $image_render_html;
	private $tpl;
	private $onclick_tag;
	private $kernel_element;

	public function __construct($id, $name, Url $url, $image_render_html, Template $tpl = null, $onclick_tag = 'javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=700\');return false;', $kernel_element = false)
	{
		$this->id = $id;
		$this->name = $name;
		$this->url = $url;
		$this->image_render_html = $image_render_html;
		$this->onclick_tag = $onclick_tag;
		$this->kernel_element = $kernel_element;

		if ($tpl instanceof Template)
		{
			$this->tpl = $tpl;
		}
		else
		{
			$this->tpl = new FileTemplate('framework/content/share/ContentSharingActionsMenuLink.tpl');
		}
	}
	
	public function get_id()
	{
		return $this->id;
	}

	public function get_name()
	{
		return $this->name;
	}
	
	public function get_url()
	{
		return $this->url;
	}

	public function get_image_render_html()
	{
		return $this->image_render_html;
	}

	public function get_onclick_tag()
	{
		return $this->onclick_tag;
	}

	public function export()
	{
		$this->tpl->put_all(array(
			'C_ONCLICK_TAG'   	=> !empty($this->onclick_tag),
			'ID'              	=> $this->id,
			'U_LINK'          	=> $this->get_url()->rel(),
			'NAME'            	=> (!$this->kernel_element ? LangLoader::get_message('share_on', 'user-common') . ' ' : '') . $this->name,
			'IMG_RENDER_HTML' 	=> $this->image_render_html,
			'ONCLICK_TAG' 		=> $this->onclick_tag,
		));

		return $this->tpl;
	}
}
?>