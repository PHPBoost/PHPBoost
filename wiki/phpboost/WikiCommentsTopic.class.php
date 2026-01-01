<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 11 27
 * @since       PHPBoost 3.0 - 2012 04 09
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class WikiCommentsTopic extends CommentsTopic
{
    private $item;

    public function __construct(?WikiItem $item = null)
    {
        parent::__construct('wiki');
        $this->item = $item;
    }

    public function get_authorizations()
    {
        $authorizations = new CommentsAuthorizations();
        $authorizations->set_authorized_access_module(WikiAuthorizationsService::check_authorizations($this->get_item()->get_id_category())->read());
        return $authorizations;
    }

    public function is_displayed()
    {
        return $this->get_item()->is_published();
    }

    private function get_item()
    {
        if ($this->item === null)
        {
            $this->item = WikiService::get_item($this->get_id_in_module());
        }
        return $this->item;
    }
}
?>
