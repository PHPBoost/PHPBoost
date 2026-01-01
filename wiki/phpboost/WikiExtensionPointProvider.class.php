<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 2.0 - 2008 02 24
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 */

class WikiExtensionPointProvider extends ItemsModuleExtensionPointProvider
{
    public function home_page()
    {
        $config = WikiConfig::load();
        if ($config->get_homepage() == WikiConfig::EXPLORER)
            return new DefaultHomePageDisplay($this->get_id(), WikiExplorerController::get_view($this->get_id()));
        elseif ($config->get_homepage() == WikiConfig::OVERVIEW)
            return new DefaultHomePageDisplay($this->get_id(), WikiIndexController::get_view($this->get_id()));
        else
            return new DefaultHomePageDisplay($this->get_id(), WikiCategoryController::get_view($this->get_id()));
    }

    public function user()
    {
        return new WikiUserExtensionPoint();
    }
}
?>
