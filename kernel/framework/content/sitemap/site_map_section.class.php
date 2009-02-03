<?php
/*##################################################
 *                           sitemapsection.class.php
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

import('content/sitemap/site_map_element');

/**
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class represents a section of a site map.
 */
class SitemapSection extends SiteMapElement
{
    /**
     * @desc Set the depth of the element
     * @warning the description is not protected for XML displaying (but usefulless in sitemap.xml)
     * @param $depth
     */
    function set_depth($depth)
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
     * @param $element SiteMapElement element to add
     */
    function add($element)
    {
        //We assign to the element its depth
        $element->set_depth($this->depth + 1);
        //We add the element to the list
        $this->sub_sections[] = $element;
    }

    /**
     * @desc Exports the section according to the given configuration
     * @param $export_config SiteMapExportConfig Export configuration
     * @return string the exported section
     */
    function export(&$export_config)
    {
        //We get the stream in which we are going to write
        $template = $export_config->get_section_stream();

        /* What to do with that ?

        if (is_string($this->section_name))
        $template->assign_vars(array(
        'C_SECTION_NAME_IS_STRING' => !empty($this->section_name),
        'SECTION_NAME' => $this->section_name,
        'DEPTH' => $depth
        ));
        elseif (is_object($this->section_name) && strtolower(get_class($this->section_name)) == 'sitemap_link')
        $template->assign_vars(array(
        'C_SECTION_NAME_IS_LINK' => true,
        'LINK_CODE' => $this->section_name->export($export_config),
        'DEPTH' => $depth
        ));
        */

        foreach ($this->sub_sections as $sub_section)
        {
            $template->assign_block_vars('children', array(
				'CHILD_CODE' => $sub_section->export($export_config)
            ));
        }
        return $template->parse(TEMPLATE_STRING_MODE);
    }

    ## Private elements ##
    /**
    * @var SiteMapElement[] List of the elements contained by the module map
    */
    var $elements = array();

    /**
     * @var SiteMapElement link of the section
     */
    var $link;
}

?>
