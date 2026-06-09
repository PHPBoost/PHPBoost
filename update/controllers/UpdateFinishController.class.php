<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2010 10 04
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class UpdateFinishController extends UpdateController
{
    public function execute(HTTPRequestCustom $request): mixed
    {
        parent::load_lang($request);

        // Retry .htaccess and nginx cache regeneration here, now that all module
        // tables exist. The first attempt in generate_cache() may have been skipped
        // due to missing category tables (UrlUpdater queries them during url_mappings()).
        if (ServerEnvironmentConfig::load()->is_url_rewriting_enabled()) {
            try { HtaccessFileCache::regenerate(); } catch (Exception $e) {}
            try { NginxFileCache::regenerate(); } catch (Exception $e) {}
        }

        $view = new FileTemplate('update/finish.tpl');
        return $this->create_response($view);
    }

    private function create_response(View $view): UpdateDisplayResponse
    {
        $step_title = $this->lang['update.step.list.end'];
        $response   = new UpdateDisplayResponse(5, $step_title, $view);
        return $response;
    }
}
