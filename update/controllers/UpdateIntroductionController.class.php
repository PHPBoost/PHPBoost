<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 3.0 - 2012 03 11
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @author      Arnaud GENET <elenwii@phpboost.com>
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class UpdateIntroductionController extends UpdateController
{
    public function execute(HTTPRequestCustom $request)
    {
        parent::load_lang($request);
        $view = new FileTemplate('update/introduction.tpl');
        $this->add_navigation($view);
        return $this->create_response($view);
    }

    private function create_response(Template $view): UpdateDisplayResponse
    {
        $step_title = $this->lang['update.step.introduction.title'];
        $response   = new UpdateDisplayResponse(1, $step_title, $view);
        return $response;
    }

    private function add_navigation(Template $view)
    {
        $server_configuration = new ServerConfiguration();
        if (UpdateServices::check_server()) {
            if (UpdateServices::database_config_file_checked())
            {
                $service = new UpdateServices();
                $service->generate_update_token();
                $redirect_url = UpdateUrlBuilder::update()->rel();
            }
            else
            {
                $redirect_url = UpdateUrlBuilder::database()->rel();
            }
        }
        else
        {
            $redirect_url = UpdateUrlBuilder::server_configuration()->rel();
        }

        $form = new HTMLForm('preambleForm', $redirect_url, false);

        $action_fieldset = new FormFieldsetSubmit('actions');
        $next            = new FormButtonSubmitCssImg($this->lang['update.step.next'], 'fa fa-arrow-right', 'introduction');
        $action_fieldset->add_element($next);
        $form->add_fieldset($action_fieldset);
        $view->put_all([
            'C_PUT_UNDER_MAINTENANCE' => !MaintenanceConfig::load()->is_under_maintenance(),
            'SERVER_FORM'             => $form->display(),
        ]);
    }
}
