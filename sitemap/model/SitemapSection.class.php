<?php
/**
 * This class represents a section of a site map.
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 2.0 - 2008 06 16
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

class SitemapSection extends SitemapElement
{
	/**
	 * @var SitemapLink Link associated to the section
	 */
	protected $link;
	/**
	 * @var SitemapElement[] List of the elements contained by the module map
	 */
	protected $elements = array();

	/**
	 * @desc Builds a SitemapSection object
	 * @param SitemapLink $link The link associated to the section
	 */
	public function __construct(SitemapLink $link)
	{
		$this->set_link($link);
	}

	public function get_name()
	{
		return $this->link->get_name();
	}

	/**
	 * @desc Returns the link associated to the section
	 * @return SitemapLink the link
	 */
	public function get_link()
	{
		return $this->link;
	}

	/**
	 * @desc Sets the link associated to the section
	 * @param SitemapLink $link the link
	 */
	public function set_link(SitemapLink  $link)
	{
		$this->link = $link;
	}
	/**
	 * @desc Sets the depth of the element
	 * @warning the description is not protected for XML displaying (but usefulless in sitemap.xml)
	 * @param int $depth The depth of the element
	 */
	public function set_depth($depth)
	{
		parent::set_depth($depth);
		//We set the depth to all the sections contained by the module map
		foreach ($this->elements as $element)
		{
			$element->set_depth($depth + 1);
		}
	}

	/**
	 * @desc Adds an elemement to the section
	 * @param SitemapElement $element element to add
	 */
	public function add($element)
	{
		//We assign to the element its depth
		$element->set_depth($this->depth + 1);
		//We add the element to the list
		$this->elements[] = $element;
	}

	/**
	 * @desc Exports the section according to the given configuration. You will use the following template variables:
	 * <ul>
	 * 	<li>SECTION_NAME which contains the name of the section</li>
	 * 	<li>SECTION_URL which contains the URL of the link associated to the section</li>
	 * 	<li>DEPTH which contains the depth of the section in the site map tree (useful for CSS classes names)</li>
	 * 	<li>LINK_CODE which contains the code got by the associated link export</li>
	 * 	<li>C_SECTION, boolean meaning that it's a section (useful if you want to use a sigle template for the whole export configuration)</li>
	 * 	<li>A loop "element" containing evert element of the section (their code is available in the CODE variable of the loop)</li>
	 * </ul>
	 * @param SitemapExportConfig $export_config Export configuration
	 * @return Template the exported section
	 */
	public function export(SitemapExportConfig  $export_config)
	{
		//We get the stream in which we are going to write
		$template = $export_config->get_section_stream();

		$template->put_all(array(
			'SECTION_NAME' => TextHelper::htmlspecialchars($this->get_name(), ENT_QUOTES),
            'SECTION_URL' => !empty($this->link) ? $this->link->get_url() : '',
		    'DEPTH' => $this->depth,
            'C_SECTION' => true
		));

		if ($this->link != null)
		{
			$template->put('LINK', $this->link->export($export_config));
		}

		foreach ($this->elements as $element)
		{
			$template->assign_block_vars('element', array(), array(
				'ELEMENT' => $element->export($export_config)
			));
		}
		return $template;
	}
}

?>
