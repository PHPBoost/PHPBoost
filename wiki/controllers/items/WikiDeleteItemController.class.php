<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2023 01 09
 * @since       PHPBoost 6.0 - 2022 11 18
 */

class WikiDeleteItemController extends DefaultModuleController
{
    public function execute(HTTPRequestCustom $request)
    {
        AppContext::get_session()->csrf_get_protect();

        $this->item = $this->get_item($request);
        $this->check_authorizations();

        if($request->get_int('content_id') == 0)
        {
            foreach($this->get_item_contents($request) as $content)
            {
                PersistenceContext::get_querier()->delete(WikiSetup::$wiki_contents_table, 'WHERE item_id = :id', ['id' => $this->item->get_id()]);
            }
        }
        WikiService::delete($this->item->get_id(), $request->get_int('content_id'));

        if (!WikiAuthorizationsService::check_authorizations()->write() && WikiAuthorizationsService::check_authorizations()->contribution())
            ContributionService::generate_cache();

        WikiService::clear_cache();
        HooksService::execute_hook_action('delete', self::$module_id, $this->item->get_properties());

        AppContext::get_response()->redirect(
            (
                $request->get_url_referrer() && !TextHelper::strstr($request->get_url_referrer(), WikiUrlBuilder::display($this->item->get_category()->get_id(), $this->item->get_category()->get_rewrited_name(), $this->item->get_id(), $this->item->get_rewrited_title())->rel())
                    ? $request->get_url_referrer()
                    : WikiUrlBuilder::home()
            ),
            $request->get_int('content_id') === 0
                ? StringVars::replace_vars($this->lang['wiki.message.success.delete'], ['title' => $this->item->get_title()])
                : StringVars::replace_vars($this->lang['wiki.message.success.delete.content'], ['content' => $request->get_int('content_id'),'title' => $this->item->get_title()])
        );
    }

    private function check_authorizations()
    {
        if (!$this->item->is_authorized_to_delete())
        {
            $error_controller = PHPBoostErrors::user_not_authorized();
            DispatchManager::redirect($error_controller);
        }
        if (AppContext::get_current_user()->is_readonly())
        {
            $error_controller = PHPBoostErrors::user_in_read_only();
            DispatchManager::redirect($error_controller);
        }
    }

    private function get_item(HTTPRequestCustom $request)
    {
        $id = $request->get_getint('id', 0);
        if (!empty($id))
        {
            try {
                return WikiService::get_item($id);
            } catch (RowNotFoundException $e) {
                $error_controller = PHPBoostErrors::unexisting_page();
                DispatchManager::redirect($error_controller);
            }
        }
    }

    private function get_item_contents(HTTPRequestCustom $request)
    {
        $id = $request->get_getint('id', 0);
        if (!empty($id))
        {
            try {
                return WikiService::get_item_content($id);
            } catch (RowNotFoundException $e) {
                $error_controller = PHPBoostErrors::unexisting_page();
                DispatchManager::redirect($error_controller);
            }
        }
    }
}
?>
