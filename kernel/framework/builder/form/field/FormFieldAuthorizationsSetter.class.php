<?php
/*##################################################
 *                    FormFieldAuthorizationsSetter.class.php
 *                            -------------------
 *   begin                : March, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * 
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @package {@package}
 */
class FormFieldAuthorizationsSetter extends AbstractFormField
{
	public function __construct($id, AuthorizationsSettings $value, array $field_options = array())
	{
		parent::__construct($id, '', $value, $field_options);
	}

	/**
	 * @return string The html code for the free field.
	 */
	public function display()
	{
		$template = $this->get_template_to_use();

		$this->assign_common_template_variables($template);

		foreach ($this->get_value()->get_actions() as $action)
		{
			$template->assign_block_vars('actions', array(
				'ID' => 'auth' . $action->get_bit(),
				'HTML_ID' => $this->get_html_id() . 'auth' . $action->get_bit(),
				'BIT' => $action->get_bit(),
        		'LABEL' => $action->get_label(),
        		'DESCRIPTION' => $action->get_description(),
        		'AUTH_FORM' => Authorizations::generate_select($action->get_bit(), $action->build_auth_array(), array(), $this->get_html_id() . $action->get_bit(), $this->is_disabled())
			));
		}

		return $template;
	}

	protected function get_default_template()
	{
		return new FileTemplate('framework/builder/form/FormFieldAuthorizationsSetter.tpl');
	}

	public function retrieve_value()
	{
		$request = AppContext::get_request();
		foreach ($this->get_value()->get_actions() as $action)
		{
			if ($request->has_parameter('groups_auth' . $this->get_html_id() . $action->get_bit()))
			{
				$roles_auths = self::get_action_auth($action, $request->get_array('groups_auth' . $this->get_html_id() . $action->get_bit(), array()));
				$roles_auths = self::clean_groups_auths($roles_auths);
				if ($request->has_parameter('members_auth' . $this->get_html_id() . $action->get_bit()))
				{
					$member_auths = self::get_action_auth($action, $request->get_array('members_auth' . $this->get_html_id() . $action->get_bit(), array()));
					foreach (self::clean_members_auths($member_auths) as $member => $auth)
					{
						$roles_auths[$member] = $auth;
					}
				}
				$roles = new RolesAuthorizations($roles_auths);
			}
			else
			{
				$roles = new RolesAuthorizations();
			}
			$action->set_roles_auths($roles);
		}
	}

	private static function get_action_auth(ActionAuthorization $action, array $values)
	{
		$auth_array = array();
		foreach ($values as $role_htlm_id)
		{
			$role = substr($role_htlm_id, strlen($action->get_bit()) - 1);
			$auth_array[$role_htlm_id] = 1;
		}
		return $auth_array;
	}

	private static function clean_groups_auths(array $auths)
	{
		$g_auths = array();
		foreach ($auths as $role => $auth)
		{
			if ($role[0] == 'r' || $role != '0')
			{
				$g_auths[$role] = $auth;
			}
		}
		return $g_auths;
	}

	private static function clean_members_auths(array $auths)
	{
		$m_auths = array();
		foreach ($auths as $member_id => $auth)
		{
			if ($member_id != 0)
			{
				$m_auths['m' . $member_id] = $auth;
			}
		}
		return $m_auths;
	}
}
?>