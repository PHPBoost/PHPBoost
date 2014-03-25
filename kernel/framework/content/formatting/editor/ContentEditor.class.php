<?php
/*##################################################
 *                         	ContentEditor.class.php
 *                            -------------------
 *   begin                : July 5 2008
 *   copyright            : (C) 2008 Régis Viarre
 *   email                : crowkait@phpboost.com
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
 * @author Régis Viarre <crowkait@phpboost.com>
 * @desc Abstract class for editors content.
 */
abstract class ContentEditor
{
    protected $template = null;
    protected $forbidden_tags = array();
    protected $identifier = 'contents';

    public function __construct()
    {
        $content_formatting_config = ContentFormattingConfig::load();
        $this->forbidden_tags = $content_formatting_config->get_forbidden_tags();
    }

    /**
	 * @desc Set the forbidden tags
	 * @param array List of forbidden tags.
	 */
    public function set_forbidden_tags($forbidden_tags)
    {
        $this->forbidden_tags = $forbidden_tags;
    }

    /**
	 * @desc Get the fordidden tags.
	 * @return array List of forbidden tags.
	 */
    public function get_forbidden_tags()
    {
        return $this->forbidden_tags;
    }

    /**
	 * @desc Set the html identifier of the textarea field which contain the content to edit.
	 * @param string The html identifier.
	 */
    public function set_identifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
	 * @desc Set an alternative template for the editor.
	 * @param Template $template.
	 */
    public function set_template($template)
    {
        $this->template = $template;
    }

    /**
	 * @desc Get the template used for the editor.
	 * @return Template The template
	 */
    public function get_template()
    {
        return $this->template;
    }
}
?>