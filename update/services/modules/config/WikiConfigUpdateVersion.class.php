<?php
/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Julien BRISWALTER <j1.seth@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 04 13
 * @since       PHPBoost 5.1 - 2018 12 19
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
 * @contributor Max KODER <maxkoder@phpboost.com>
*/

class WikiConfigUpdateVersion extends ConfigUpdateVersion
{
	public function __construct()
	{
		parent::__construct('wiki');
	}

	protected function build_new_config()
	{
		$old_config = $this->get_old_config();

		if ($old_config)
		{
			$config = WikiConfig::load();
			$config->set_property('authorizations', $this->build_authorizations($old_config->get_property('authorizations')));
			$this->save_new_config('wiki-config', $config);

			return true;
		}
		return false;
	}

	private function build_authorizations($old_auth_array)
	{
        $build_auth = [];
		$auth_translation = [
			// Old auth => New auth
			0x01 => 2,  // Create article => WRITE_AUTHORIZATIONS
			0x02 => 16, // Create Category => CATEGORIES_MANAGEMENT_AUTHORIZATIONS
			0x04 => 32, // Restaure Archive => MANAGE_ARCHIVES_AUTHORIZATIONS
			0x08 => 32, // Delete Archive => MANAGE_ARCHIVES_AUTHORIZATIONS
			0x10 => 2,  // Edit => WRITE_AUTHORIZATIONS
			0x20 => 8,  // Delete => MODERATION_AUTHORIZATIONS
			0x40 => 8,  // Rename => MODERATION_AUTHORIZATIONS
			0x80 => 8,  // Redirect => MODERATION_AUTHORIZATIONS
			0x100 => 2, // Move => => WRITE_AUTHORIZATIONS
			0x200 => 8, // Statut => MODERATION_AUTHORIZATIONS
			// 0x400 => Comments are managed by kernel comments
			0x800 => 8, // Restrictions => MODERATION_AUTHORIZATIONS
			0x1000 => 1 // Read => READ_AUTHORIZATIONS
		];

		foreach ($old_auth_array as $level => $auth) {
			$bits = 0x00;
			if ($level === 'r1' || $level === 'r0') {
				// We add Contribution Auth for members and admins
				$bits = 0x04;
			}
			foreach ($auth_translation as $old_auth => $new_auth)
			{
				if (($auth & $old_auth) && !($bits & $new_auth)) {
					$bits += $new_auth;
				}

			}
			$build_auth[$level] = $bits;
		}
		return $build_auth;
	}
}
?>
