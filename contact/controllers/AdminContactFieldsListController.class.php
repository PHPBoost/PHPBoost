<?php
/**
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 05
 * @since       PHPBoost 4.0 - 2013 03 01
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminContactFieldsListController extends DefaultAdminModuleController
{
	protected function get_template_to_use()
	{
		return new FileTemplate('contact/AdminContactFieldsListController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->update_fields($request);

		$fields_number = 0;
		foreach ($this->config->get_fields() as $id => $properties)
		{
			$field = new ContactField();
			$field->set_properties($properties);

			$this->view->assign_block_vars('fields_list', array(
				'C_DELETE'   => $field->is_deletable(),
				'C_READONLY' => $field->is_readonly(),
				'C_DISPLAY'  => $field->is_displayed(),
				'C_REQUIRED' => $field->is_required(),
				'ID'         => $id,
				'NAME'       => $field->get_name(),
				'U_EDIT'     => ContactUrlBuilder::edit_field($id)->rel()
			));
			$fields_number++;
		}

		$this->view->put_all(array(
			'C_FIELDS'         => $fields_number,
			'C_SEVERAL_FIELDS' => $fields_number > 1
		));

		return new AdminContactDisplayResponse($this->view, $this->lang['form.fields.management']);
	}

	private function update_fields(HTTPRequestCustom $request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.position.update'], MessageHelper::SUCCESS, 5));
		}
	}

	private function update_position(HTTPRequestCustom $request)
	{
		$fields = $this->config->get_fields();
		$sorted_fields = array();

		$fields_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($fields_list as $position => $tree)
		{
			$sorted_fields[$position + 1] = $fields[$tree->id];
		}
		$this->config->set_fields($sorted_fields);

		ContactConfig::save();
	}
}
?>
