<?php
/**
 * @package     Builder
 * @subpackage  Form\field
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2018 11 05
 * @since       PHPBoost 3.0 - 2010 03 01
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
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
				'AUTH_FORM' => Authorizations::generate_select($action->get_bit(), $action->build_auth_array(), array(), $this->get_html_id() . $action->get_bit(), $this->is_disabled(), $this->is_disabled(), $action->get_disabled_ranks())
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
			$role = TextHelper::substr($role_htlm_id, TextHelper::strlen($action->get_bit()) - 1);
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
