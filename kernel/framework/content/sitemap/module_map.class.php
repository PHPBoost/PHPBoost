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
 * @package content
 * @subpackage sitemap
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc The ModuleMap class represents the map of a module. It has a description
 * (generally the module description) and contains some elements which can be
 * some simple links or some sections (which can match the categories for example).
 */
class ModuleMap extends SiteMapSection
{
    /**
     * @desc Builds a ModuleMap object
     * @param SiteMapLink $link Link associated to the root of the module
     */
    function ModuleMap($link)
    {
        //We build the parent object
        parent::SiteMapSection($link);
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
     * @desc Sets the description of the module
     * @param string $description Description of the module
     */
    function set_description($description)
    {
        $this->description = $description;
    }

    /**
     * @desc Exports the sitemap (according to a configuration of templates).
     * In your template, you will be able to use the following variables:
     * <ul>
     * 	<li>MODULE_NAME which contains the name of the module</li>
     *  <li>MODULE_DESCRIPTION which contains the description of the module</li>
     *  <li>MODULE_URL which contains the URL of the module root page</li>
     *  <li>DEPTH which is the depth of the module map in the sitemap (generally 1).
     *  It might be usefull to apply different CSS styles to each level of depth.</li>
     *  <li>LINK_CODE which contains the code of the link associated to the module root exported with the same configuration.</li>
     *  <li>C_MODULE_MAP which is a boolean whose value is true, this will enable you to use a single template for the whole export configuration</li>
     *  <li>The loop "element" for which the variable CODE contains the code of each sub element of the module (for example categories)</li>
     *  </ul>
     * @param SiteMapExportConfig $export_config export configuration
     */
    function export(&$export_config)
    {
        //We get the stream in which we are going to write
        $template = $export_config->get_module_map_stream();

        $template->assign_vars(array(
			'MODULE_NAME' => htmlspecialchars($this->get_name(), ENT_QUOTES),
			'MODULE_DESCRIPTION' => $this->description,
            'MODULE_URL' => !empty($this->link) ? $this->link->get_url() : '',
		    'DEPTH' => $this->depth,
            'LINK_CODE' => is_object($this->link) ? $this->link->export($export_config) : '',
            'C_MODULE_MAP' => true
        ));

        //We export all the elements contained by the module map
        foreach ($this->elements as $element)
        {
            $template->assign_block_vars('element', array(
				'CODE' => $element->export($export_config)
            ));
        }
        return $template->parse(Template::TEMPLATE_PARSER_STRING);
    }

    ## Private elements ##
    /**
    * @var string Description of the module
    */
    var $description;
}

?>
