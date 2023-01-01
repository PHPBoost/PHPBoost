<?php
/**
 * @package     Content
 * @subpackage  Share
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 23
 * @since       PHPBoost 5.1 - 2018 01 30
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
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
			'NAME'            	=> (!$this->kernel_element ? LangLoader::get_message('common.share.on', 'common-lang') . ' ' : '') . $this->name,
			'IMG_RENDER_HTML' 	=> $this->image_render_html,
			'ONCLICK_TAG' 		=> $this->onclick_tag,
		));

		return $this->tpl;
	}
}
?>
