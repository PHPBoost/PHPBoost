<?php
/**
 * @copyright   &copy; 2005-2025 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2022 04 14
 * @since       PHPBoost 4.0 - 2013 11 08
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class WikiUntrackItemController extends DefaultModuleController
{
    public function execute(HTTPRequestCustom $request)
    {
        $item_id = $request->get_getint('id', 0);
        $current_user_id = AppContext::get_current_user()->get_id();

        if (!empty($item_id))
        {
            $this->get_item($item_id);

            $this->check_authorizations();

            WikiService::untrack_item($item_id, $current_user_id);
            WikiService::clear_cache();

            $category = $this->item->get_category();

            AppContext::get_response()->redirect($request->get_url_referrer() ? $request->get_url_referrer() : WikiUrlBuilder::display($category->get_id(), $category->get_rewrited_name(), $item_id, $this->item->get_rewrited_title()));
        }
        else
        {
            $error_controller = PHPBoostErrors::unexisting_page();
            DispatchManager::redirect($error_controller);
        }
    }

    private function get_item($id)
    {
        try {
            $this->item = WikiService::get_item($id);
        } catch (RowNotFoundException $e) {
            $error_controller = PHPBoostErrors::unexisting_page();
            DispatchManager::redirect($error_controller);
        }
    }

    private function check_authorizations()
    {
        if (AppContext::get_current_user()->is_readonly())
        {
            $error_controller = PHPBoostErrors::user_in_read_only();
            DispatchManager::redirect($error_controller);
        }
    }
}
?>
