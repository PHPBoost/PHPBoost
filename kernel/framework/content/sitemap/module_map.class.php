<?php
/*##################################################
 *                            module_map.class.php
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

import('content/sitemap/site_map_section');

/**
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc The ModuleMap class represents the map of a module. It has a description 
 * (generally the module description) and contains some elements which can be 
 * some simple links or some sections (which can match the categories for example).
 */
class ModuleMap extends SitemapSection
{
    /**
     * @desc Build a ModuleMap object
     * @param $name Name of the module
     * @param $depth Depth of the module in the elements tree of the site map (default value: 1)
     */
    function ModuleMap($name = '', $depth = 1)
    {
        //We build the parent object
        parent::SiteMapSection($name, $depth);
    }

    /**
     * @desc Return the module description
     * @return string
     */
    function get_description()
    {
        return $this->description;
    }

    /**
     * @desc Set the description of the module
     * @param $description string Description of the module
     */
    function set_description($description)
    {
        $this->description = $description;
    }

    //Exports the sitemap (according to a configuration of templates). It returns a string
    function export(&$export_config)
    {
        //We get the stream in which we are going to write
        $template = $export_config->get_module_map_stream();

        $template->assign_vars(array(
			'MODULE_NAME' => htmlspecialchars($this->name, ENT_QUOTES),
			'MODULE_DESCRIPTION' => $this->description,
		    'DEPTH' => $this->depth,
            'C_MODULE_MAP' => true
        ));

        //We export all the elements contained by the module map
        foreach ($this->elements as $element)
        {
            $template->assign_block_vars('children', array(
				'CHILD_CODE' => $element->export($export_config)
            ));
        }
        return $template->parse(TEMPLATE_STRING_MODE);
    }

    ## Private elements ##
    /**
    * @var string Description of the module
    */
    var $description;
}

?>
