<?php
/*##################################################
 *                           SiteMapSection.class.php
 *                            -------------------
 *   begin                : June 16 th 2008
 *   copyright            : (C) 2008 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
 *
 *
 *
 ###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
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
 * @package content
 * @subpackage sitemap
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class represents a section of a site map.
 */
class SiteMapSection extends SiteMapElement
{
	/**
	 * @var SiteMapLink Link associated to the section
	 */
	protected $link;
	/**
	 * @var SiteMapElement[] List of the elements contained by the module map
	 */
	protected $elements = array();

	/**
	 * @desc Builds a SiteMapSection object
	 * @param SiteMapLink $link The link associated to the section
	 */
	public function __construct(SiteMapLink $link)
	{
		$this->set_link($link);
	}
	
	public function get_name()
	{
		return $this->link->get_name();
	}

	/**
	 * @desc Returns the link associated to the section
	 * @return SiteMapLink the link
	 */
	public function get_link()
	{
		return $this->link;
	}

	/**
	 * @desc Sets the link associated to the section
	 * @param SiteMapLink $link the link
	 */
	public function set_link(SiteMapLink  $link)
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
	 * @param SiteMapElement $element element to add
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
	 * @param SiteMapExportConfig $export_config Export configuration
	 * @return Template the exported section
	 */
	public function export(SiteMapExportConfig  $export_config)
	{
		//We get the stream in which we are going to write
		$template = $export_config->get_section_stream();
		 
		$template->assign_vars(array(
			'SECTION_NAME' => htmlspecialchars($this->get_name(), ENT_QUOTES),
            'SECTION_URL' => !empty($this->link) ? $this->link->get_url() : '',
		    'DEPTH' => $this->depth,
            'C_SECTION' => true
		));
		
		if (is_object($this->link))
		{
			$template->add_subtemplate('LINK', $this->link->export($export_config));
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
