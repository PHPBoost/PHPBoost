<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 02
 * @since       PHPBoost 3.0 - 2010 12 17
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class AdminExtendedFieldsMemberListController extends DefaultAdminController
{
	protected function get_template_to_use()
	{
	   return new FileTemplate('admin/member/AdminExtendedFieldsMemberlistController.tpl');
	}

	public function execute(HTTPRequestCustom $request)
	{
		$this->update_fields($request);

		$extended_field = ExtendedFieldsCache::load()->get_extended_fields();

		$fields_number = 0;
		foreach ($extended_field as $id => $row)
		{
			if ($row['name'] !== 'last_view_forum')
			{
				$this->view->assign_block_vars('list_extended_fields', array(
					'C_REQUIRED' => $row['required'],
					'C_DISPLAY'  => $row['display'],
					'C_FREEZE'   => $row['freeze'],

					'ID'   => $row['id'],
					'NAME' => $row['name'],

					'U_EDIT' => AdminExtendedFieldsUrlBuilder::edit($row['id'])->rel()
				));
				$fields_number++;
			}
		}

		$this->view->put_all(array(
			'C_FIELDS'         => $fields_number,
			'C_SEVERAL_FIELDS' => $fields_number > 1
		));

		return new AdminExtendedFieldsDisplayResponse($this->view, $this->lang['user.extended.fields.management']);
	}

	private function update_fields($request)
	{
		if ($request->get_value('submit', false))
		{
			$this->update_position($request);
			ExtendedFieldsCache::invalidate();
			$this->view->put('MESSAGE_HELPER', MessageHelper::display($this->lang['warning.success.position.update'], MessageHelper::SUCCESS, 5));
		}
	}

	private function update_position($request)
	{
		$fields_list = json_decode(TextHelper::html_entity_decode($request->get_value('tree')));
		foreach($fields_list as $position => $tree)
		{
			PersistenceContext::get_querier()->inject(
				"UPDATE " . DB_TABLE_MEMBER_EXTENDED_FIELDS_LIST . " SET
				position = :position
				WHERE id = :id"
				, array(
					'position' => $position,
					'id' => $tree->id,
				)
			);
		}
	}
}
?>
