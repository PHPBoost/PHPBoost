<?php
/**
 * Abstract class for editors content.
 * @package     Content
 * @subpackage  Formatting\editor
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Regis VIARRE <crowkait@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2016 10 24
 * @since       PHPBoost 3.0 - 2008 07 05
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
     * Set the forbidden tags
     * @param array List of forbidden tags.
     */
    public function set_forbidden_tags($forbidden_tags)
    {
        $this->forbidden_tags = $forbidden_tags;
    }

    /**
     * Get the fordidden tags.
     * @return array List of forbidden tags.
     */
    public function get_forbidden_tags()
    {
        return $this->forbidden_tags;
    }

    /**
     * Set the html identifier of the textarea field which contain the content to edit.
     * @param string The html identifier.
     */
    public function set_identifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * Set an alternative template for the editor.
     * @param Template $template.
     */
    public function set_template($template)
    {
        $this->template = $template;
    }

    /**
     * Get the template used for the editor.
     * @return Template The template
     */
    public function get_template()
    {
        return $this->template;
    }
}
?>
