<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2017 05 30
 * @since       PHPBoost 4.1 - 2013 11 15
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class ModuleLink
{
	protected $name;
	protected $url;
	protected $sub_link = array();
	protected $visibility = true;

	public function __construct($name, $url, $visibility = true)
	{
		$this->name = $name;
		$this->visibility = $visibility;
		$this->set_url($url);
	}

	public function set_name($name)
	{
		$this->name = $name;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_url($url)
	{
		if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        $this->url = $url;
	}

	public function get_url()
	{
		return $this->url;
	}

	public function add_sub_link(ModuleLink $sub_link)
	{
		$this->sub_link[] = $sub_link;
	}

	public function get_sub_link()
	{
		return $this->sub_link;
	}

	public function has_sub_link()
	{
		return !empty($this->sub_link);
	}

	public function set_visibility($visibility)
	{
		$this->visibility = $visibility;
	}

	public function is_visible()
	{
		return (bool)$this->visibility;
	}

	public function is_active()
	{
		return Url::is_current_url($this->get_url()->relative());
	}

	public function export()
	{
		$tpl = new FileTemplate('framework/module/module_actions_link.tpl');

		$tpl->put_all(array(
			'C_HAS_SUB_LINK' => $this->has_sub_link(),
			'C_IS_ACTIVE'    => $this->is_active(),
			'NAME'           => $this->get_name(),
			'FULLNAME'       => LangLoader::get_message('menu.link-to', 'user-common') . $this->get_name(),
			'U_LINK'         => $this->get_url()->rel(),
		));

		foreach ($this->get_sub_link() as $element)
		{
			if ($element->is_visible())
			{
				$tpl->assign_block_vars('element', array(), array(
					'ELEMENT' => $element->export()
				));
			}
		}

		return $tpl;
	}
}
?>
