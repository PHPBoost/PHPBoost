<?php
/*##################################################
 *                            BreadCrumb.class.php
 *                            -------------------
 *   begin                : February 16, 2007
 *   copyright            : (C) 2007 Sautel Benoit
 *   email                : ben.popeye@phpboost.com
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
 * @package {@package}
 * @author Benoît Sautel <ben.popeye@phpboost.com>
 * @desc This class is used to represent the bread crumb displayed on each page of the website.
 * It enables the user to locate himself in the whole site.
 * A bread crumb can look like this: Home >> My module >> First level category >> Second level category >>
 * Third level category >> .. >> My page >> Edition
 */
class BreadCrumb
{
    /**
     * @var string[string] List of the links
     */
    private $array_links = array();
    /**
     * @var SiteDisplayGraphicalEnvironment The graphical environment in which the breadcrumb is
     */
    private $graphical_environment;

    /**
     * @desc Adds a link in the bread crumb. This link will be put at the end of the list.
     * @param string $text Name of the page
     * @param string $target Link whose target is the page
     */
    public function add($text, $target = '')
    {
        if (!empty($text))
        {
        	$url = $target instanceof Url ? $target->rel() : $target;
            $this->array_links[] = array($text, $url);
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * @desc Reverses the whole list of the links. It's very useful when it's easier for you to make the list in the reverse way, at the
     * end, you only need to reverse the list and it will be ok.
     */
    public function reverse()
    {
        $this->array_links = array_reverse($this->array_links);
    }

    /**
     * @desc Removes the last link of the list
     */
    public function remove_last()
    {
        array_pop($this->array_links);
    }

    /**
     * @desc Displays the bread crumb.
     */
    public function display(Template $tpl)
    {
        if (empty($this->array_links))
        {
            $this->add($this->get_page_title(), REWRITED_SCRIPT);
        }
		
        $tpl->put_all(array(
			'START_PAGE' => TPL_PATH_TO_ROOT . '/',
			'L_INDEX' 	 => LangLoader::get_message('home', 'main')
        ));
        
        $output = array_slice($this->array_links, -1, 1);
        foreach ($this->array_links as $key => $array)
        {
            $tpl->assign_block_vars('link_bread_crumb', array(
            	'C_CURRENT' => $output[0] == $array,
				'URL' 	=> $array[1],
				'TITLE' => $array[0]
            ));
        }
    }

    /**
     * @desc Removes all the existing links.
     */
    public function clean()
    {
        $this->array_links = array();
    }
    
    public function get_links()
    {
    	return $this->array_links;
    }
    
    /**
     * Sets the reference to the parent graphical environment
     * @param SiteDisplayGraphicalEnvironment $env The parent environment
     */
    public function set_graphical_environment(SiteDisplayGraphicalEnvironment $env)
    {
    	$this->graphical_environment = $env;
    }
    
    private function get_page_title()
    {
    	return $this->graphical_environment->get_page_title();
    }
}
?>