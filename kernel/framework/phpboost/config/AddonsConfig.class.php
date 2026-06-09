<?php
/**
 * This class contains the cache data of the addons configuration.
 * Websites or Github repositories to download addons (modules, templates, langs)
 * @package     PHPBoost
 * @subpackage  Config
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.1 - 2026 03 07
*/

class AddonsConfig extends AbstractConfigData
{
    const GITHUB_TOKEN  = 'github_token';
    const MODULES_REPO  = 'modules_repo';
    const THEMES_REPO   = 'themes_repo';
    const LANGS_REPO    = 'langs_repo';
    const ADDONS_SERVER = 'addons_server';

    const DEFAULT_MODULES_REPO = [
        [
            'owner'      => 'PHPBoost',
            'repository' => 'Modules',
            'directory'  => '',
        ],
    ];
    const DEFAULT_THEMES_REPO = [
        [
            'owner'      => 'PHPBoost',
            'repository' => 'Themes',
            'directory'  => '',
        ],
    ];
    const DEFAULT_LANGS_REPO = [
        [
            'owner'      => 'PHPBoost',
            'repository' => 'Langs',
            'directory'  => '',
        ],
    ];
    const DEFAULT_ADDONS_SERVER = [
        [
            'website'   => 'PHPBoost',
            'url'       => 'https://dl.phpboost.com',
            'directory' => '',
        ]
    ];

    public function get_github_token()
    {
        return $this->get_property(self::GITHUB_TOKEN);
    }

    public function set_github_token($token)
    {
        $this->set_property(self::GITHUB_TOKEN, $token);
    }

    public function get_modules_repo()
    {
        return $this->get_property(self::MODULES_REPO);
    }

    public function set_modules_repo(array $modules_repo)
    {
        $this->set_property(self::MODULES_REPO, $modules_repo);
    }

    public function get_themes_repo()
    {
        return $this->get_property(self::THEMES_REPO);
    }

    public function set_themes_repo(array $themes_repo)
    {
        $this->set_property(self::THEMES_REPO, $themes_repo);
    }

    public function get_langs_repo()
    {
        return $this->get_property(self::LANGS_REPO);
    }

    public function set_langs_repo(array $langs_repo)
    {
        $this->set_property(self::LANGS_REPO, $langs_repo);
    }

    public function get_addons_server()
    {
        return $this->get_property(self::ADDONS_SERVER);
    }

    public function set_addons_server(array $addons_server)
    {
        $this->set_property(self::ADDONS_SERVER, $addons_server);
    }

	/**
	 * {@inheritdoc}
	 */
	public function get_default_values()
	{
		return [
            self::GITHUB_TOKEN  => '',
            self::MODULES_REPO  => self::DEFAULT_MODULES_REPO,
            self::THEMES_REPO   => self::DEFAULT_THEMES_REPO,
            self::LANGS_REPO    => self::DEFAULT_LANGS_REPO,
            self::ADDONS_SERVER => self::DEFAULT_ADDONS_SERVER,
		];
	}

	/**
	 * Loads and returns the addons cached data.
	 * @return AddonsConfig The cached data
	 */
	public static function load()
	{
		return ConfigManager::load(self::class, 'kernel', 'addons-config');
	}

	/**
	 * Invalidates the current addons cached data.
	 */
	public static function save()
	{
		ConfigManager::save('kernel', self::load(), 'addons-config');
	}
}
?>
