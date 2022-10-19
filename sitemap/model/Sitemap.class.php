<?php
/**
 * Describes the map of the site. Can be exported according to any text form by using a template configuration.
 * A site map contains some links, some link sections and some module maps (which also contain links and sections).
 * @copyright   &copy; 2005-2022 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Benoit SAUTEL <ben.popeye@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 12 16
 * @since       PHPBoost 3.0 - 2009 02 03
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class Sitemap
{
	//Who will see the site map?
	/**
	* The site map will be seen by every body, only the public elements must appear
	*/
	const AUTH_PUBLIC = false;
	/**
	 * The site map is for the current user. It must contain only what the user can see, but it can be private.
	 */
	const AUTH_USER = true;

	//In which context will be used the site map?
	/**
	* It will be a page of the site containing the site map
	*/
	const USER_MODE = true;
	/**
	 * It will be for the search engines (sitemap.xml), all the pages which don't need to be present in the search engines results
	 * can be forgotten in that case.
	 */
	const SEARCH_ENGINE_MODE = false;

	//Actualization frequencies
	const FREQ_ALWAYS = 'always';
	const FREQ_HOURLY = 'hourly';
	const FREQ_DAILY = 'daily';
	const FREQ_WEEKLY = 'weekly';
	const FREQ_MONTHLY = 'monthly';
	const FREQ_YEARLY = 'yearly';
	const FREQ_NEVER = 'never';
	const FREQ_DEFAULT = self::FREQ_MONTHLY;

	//Link priority
	const PRIORITY_MAX = '1';
	const PRIORITY_HIGH = '0.75';
	const PRIORITY_AVERAGE = '0.5';
	const PRIORITY_LOW = '0.25';
	const PRIORITY_MIN = '0';

	/**
	 * @var SitemapElement[] Elements contained by the site map
	 */
	private $elements = array();
	/**
	 * @var string name of the site
	 */
	private $site_name = '';

	/**
	 * @desc Builds a Sitemap object with its elements
	 * @param SitemapElement[] $elements List of the elements it contains
	 */
	public function __construct($site_name = '', $elements = null)
	{
		if (is_array($elements))
		{
			$this->elements = $elements;
		}
		$this->set_site_name($site_name);
	}

	/**
	 * @desc Returns the name of the site
	 * @return string name
	 */
	public function get_site_name()
	{
		return $this->site_name;
	}

	/**
	 * @desc Sets the name of the site. The default value is the name of the site taken from the site configuration.
	 * @param string $site_name name of the site
	 */
	public function set_site_name($site_name)
	{
		if (!empty($site_name))
		{
			$this->site_name = $site_name;
		}
		elseif (empty($this->site_name))
		{
			$general_config = GeneralConfig::load();
			$this->site_name = $general_config->get_site_name();
		}
	}

	/**
	 * @desc Adds an element to the elements list of the Sitemap
	 * @param SitemapElement $element The element to add
	 */
	public function add($element)
	{
		$this->elements[] = $element;
	}

	/**
	 * @desc Exports a Sitemap. You will be able to use the following variables into the templates used to export:
	 * <ul>
	 * 	<li>C_SITE_MAP which is a condition indicating if it's a site map (useful if you want to use a sigle template
	 * for the whole export configuration)</li>
	 * 	<li>SITE_NAME which contains the name of the site</li>
	 * 	<li>A loop "element" in which the code of each element is in the variable CODE</li>
	 * </ul>
	 * @param SitemapExportConfig $export_config Export configuration
	 * @return Template The exported code of the Sitemap
	 */
	public function export(SitemapExportConfig  $export_config)
	{
		//We get the stream in which we are going to write
		$template = $export_config->get_site_map_stream();

		$template->put_all(array(
			'C_SITE_MAP' => true,

            'SITE_NAME' => TextHelper::htmlspecialchars($this->site_name, ENT_QUOTES)
		));

		//Let's export all the element it contains
		foreach ($this->elements as $element)
		{
			$template->assign_block_vars('element', array(), array(
				'ELEMENT' => $element->export($export_config)
			));
		}

		return $template;
	}

	/**
	 * @desc Builds the whole sitemap
	 * @param int $mode USER_MODE ou SEARCH_ENGINE_MODE, it depends on if you want to show it to a user in particular or to anybody
	 * @param int $auth_mode AUTH_GUEST or AUTH_USERS, it depends if you want to display only the public pages or also the private ones.
	 */
	public function build($mode = self::SEARCH_ENGINE_MODE, $auth_mode = self::AUTH_PUBLIC)
	{
		$this->build_kernel_map($mode, $auth_mode);
		$this->build_modules_maps($auth_mode);
	}

	/**
	 * @desc Adds to the site map all maps of the installed modules
	 * @param int $auth_mode AUTH_GUEST or AUTH_USERS, it depends if you want to display only the public pages or also the private ones.
	 */
	private function build_modules_maps($auth_mode = self::AUTH_PUBLIC)
	{
		$providers = AppContext::get_extension_provider_service()->get_providers(SitemapExtensionPoint::EXTENSION_POINT);
		foreach (ModulesManager::get_activated_modules_map_sorted_by_localized_name() as $id => $module)
		{
			if (array_key_exists($module->get_id(), $providers))
			{
				$sitemap_provider = $providers[$module->get_id()]->sitemap();
				if ($sitemap_provider !== false)
				{
					if ($auth_mode == self::AUTH_PUBLIC)
					{
						$module_map = $sitemap_provider->get_public_sitemap();
					}
					else
					{
						$module_map = $sitemap_provider->get_user_sitemap();
					}
					$this->add($module_map);
				}
			}
		}
	}

	/**
	 * @desc Adds to the site map all the kernel links.
	 * @param int $mode USER_MODE ou SEARCH_ENGINE_MODE, it depends on if you want to show it to a user in particular or to anybody
	 * @param int $auth_mode AUTH_GUEST or AUTH_USERS, it depends if you want to display only the public pages or also the private ones.
	 */
	private function build_kernel_map($mode = self::USER_MODE, $auth_mode = self::AUTH_PUBLIC)
	{
		$lang = LangLoader::get_all_langs();

		//We consider the kernel as a module
		$kernel_map = new ModuleMap(new SitemapLink($lang['common.home'], new Url(Environment::get_home_page())));

		//The site description
		$kernel_map->set_description(nl2br(GeneralConfig::load()->get_site_description()));

		//All the links which not need to be present in the search engine results.
		if ($mode == self::USER_MODE)
		{
			if (AppContext::get_current_user()->check_auth(UserAccountsConfig::load()->get_auth_read_members(), UserAccountsConfig::AUTH_READ_MEMBERS_BIT))
			{
				$kernel_map->add(new SitemapLink($lang['user.members.list'], UserUrlBuilder::home()));
			}

			//Member space
			if ($auth_mode == self::AUTH_USER && AppContext::get_current_user()->check_level(User::MEMBER_LEVEL))
			{
				//We create a section for that
				$member_space_section = new SitemapSection(new SitemapLink($lang['user.dashboard'],
				UserUrlBuilder::profile(AppContext::get_current_user()->get_id())));

				//Profile edition
				$member_space_section->add(new SitemapLink($lang['user.profile.edit'],
				UserUrlBuilder::edit_profile(AppContext::get_current_user()->get_id())));

				//Private messaging
				$member_space_section->add(new SitemapLink($lang['user.private.messaging'],
				UserUrlBuilder::personnal_message(AppContext::get_current_user()->get_id())));

				//Contribution panel
				$member_space_section->add(new SitemapLink($lang['user.contribution.panel'],
				UserUrlBuilder::contribution_panel()));

				//Administration panel
				if (AppContext::get_current_user()->check_level(User::ADMINISTRATOR_LEVEL))
				{
					$member_space_section->add(new SitemapLink($lang['user.admin.panel'],
					UserUrlBuilder::administration()));
				}

				//We add it to the kernel map
				$kernel_map->add($member_space_section);
			}
		}

		//The kernel map is added to the site map
		$this->add($kernel_map);
	}
}
?>
