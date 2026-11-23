<?php
/**
 * @package     PHPBoost
 * @subpackage  Module\tree-links
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 11 23
 * @since       PHPBoost 4.1 - 2013 11 15
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class ModuleLink
{
	protected string $name;
	protected Url|string $url;
	protected string $css_class;
	protected $sub_link = [];
	protected $visibility = true;

	public function __construct(string $name, Url|string $url, bool$visibility = true, string $css_class = '')
	{
		$this->name = $name;
		$this->visibility = $visibility;
        $this->css_class = $css_class;
		$this->set_url($url);
	}

	public function set_name(string $name): void
	{
		$this->name = $name;
	}

	public function get_name(): string
	{
		return $this->name;
	}

	public function set_css_class(string $css_class)
	{
		$this->css_class = $css_class;
	}

	public function get_css_class(): string
	{
		return $this->css_class;
	}

	public function has_css_class(): bool
	{
		return !empty($this->css_class);
	}

	public function set_url(Url|string $url): void
	{
		if (!($url instanceof Url))
        {
            $url = new Url($url);
        }
        $this->url = $url;
	}

	public function get_url(): Url|string
	{
		return $this->url;
	}

	public function add_sub_link(ModuleLink $sub_link): void
	{
		$this->sub_link[] = $sub_link;
	}

	public function get_sub_link(): array
	{
		return $this->sub_link;
	}

	public function has_sub_link(): bool
	{
		return !empty($this->sub_link);
	}

	public function set_visibility(bool $visibility): void
	{
		$this->visibility = $visibility;
	}

	public function is_visible(): bool
	{
		return (bool)$this->visibility;
	}

	public function is_active(): bool
	{
		return Url::is_current_url($this->get_url()->relative(), true);
	}

	public function export(): Template
	{
		$tpl = new FileTemplate('framework/module/module_actions_link.tpl');

		$tpl->put_all([
			'C_HAS_SUB_LINK'  => $this->has_sub_link(),
			'C_IS_ACTIVE'     => $this->is_active(),
			'C_HAS_CSS_CLASS' => $this->has_css_class(),
			'NAME'            => $this->get_name(),
			'CSS_CLASS'       => $this->get_css_class(),
			'FULLNAME'        => LangLoader::get_message('menu.link.to', 'menu-lang') . $this->get_name(),
			'U_LINK'          => $this->get_url()->rel(),
		]);

		foreach ($this->get_sub_link() as $element)
		{
			if ($element->is_visible())
			{
				$tpl->assign_block_vars('element', [], [
					'ELEMENT' => $element->export()
				]);
			}
		}

		return $tpl;
	}
}
?>
