<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2025 04 13
 * @since       PHPBoost 3.0 - 2012 01 21
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiSearchable extends DefaultSearchable
{
    public function __construct()
    {
        parent::__construct('wiki');

        $this->table_name = WikiSetup::$wiki_articles_table;

        $this->has_second_table = true;
        $this->second_table_name = WikiSetup::$wiki_contents_table;
        $this->second_table_label = 'item_content';
        $this->second_table_foreign_id = 'item_id';

        $this->field_id = 'content_id';
        $this->field_rewrited_title = 'rewrited_title';
        $this->field_content = 'item_content.content';

        $this->has_summary = true;
        $this->field_summary = 'item_content.summary';

        $this->field_published = 'published';

        $this->has_validation_period = true;
        $this->field_validation_start_date = 'publishing_start_date';
        $this->field_validation_end_date = 'publishing_end_date';
    }
}
?>
