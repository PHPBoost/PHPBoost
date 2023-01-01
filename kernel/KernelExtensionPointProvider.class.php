<?php
/**
 * @copyright   &copy; 2005-2023 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Loic ROUCHON <horn@phpboost.com>
 * @version     PHPBoost 6.0 - last update: 2021 06 23
 * @since       PHPBoost 3.0 - 2010 02 06
 * @contributor Kevin MASSY <reidlos@phpboost.com>
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
 * @contributor Sebastien LARTIGUE <babsolune@phpboost.com>
*/

class KernelExtensionPointProvider extends ExtensionPointProvider
{
	public function __construct()
	{
		parent::__construct('kernel');
	}

	public function commands()
	{
		return new CLICommandsList(array('help' => 'CLIHelpCommand', 'cache' => 'CLICacheCommand', 'htaccess' => 'CLIHtaccessCommand'));
	}

	public function url_mappings()
	{
		return new UrlMappings(array(
			new DispatcherUrlMapping('/admin/cache/index.php'),
			new DispatcherUrlMapping('/admin/config/index.php'),
			new DispatcherUrlMapping('/admin/content/index.php'),
			new DispatcherUrlMapping('/admin/errors/index.php'),
			new DispatcherUrlMapping('/admin/files/index.php'),
			new DispatcherUrlMapping('/admin/langs/index.php'),
			new DispatcherUrlMapping('/admin/maintain/index.php'),
			new DispatcherUrlMapping('/admin/member/index.php'),
			new DispatcherUrlMapping('/admin/modules/index.php'),
			new DispatcherUrlMapping('/admin/server/index.php'),
			new DispatcherUrlMapping('/admin/smileys/index.php'),
			new DispatcherUrlMapping('/admin/themes/index.php'),
			new DispatcherUrlMapping('/syndication/index.php')
		));
	}

	public function extended_field()
	{
		return new ExtendedFields(array(
			new MemberShortTextExtendedField(),
			new MemberHalfLongTextExtendedField(),
			new MemberLongTextExtendedField(),
			new MemberSimpleSelectExtendedField(),
			new MemberMultipleSelectExtendedField(),
			new MemberSimpleChoiceExtendedField(),
			new MemberMultipleChoiceExtendedField(),
			new MemberDateExtendedField(),
			new MemberUserAvatarExtendedField(),
			new MemberUserBornExtendedField(),
			new MemberUserPMToMailExtendedField(),
			new MemberUserSexExtendedField()
		));
	}

	public function content_sharing_actions_menu_links()
	{
		$config = ContentManagementConfig::load();
		$links = array();

		if ($config->is_content_sharing_email_enabled())
			$links[] = new ContentSharingActionsMenuLink('mail', LangLoader::get_message('common.share.email', 'common-lang'), new Url('mailto:?'. (defined('TITLE') ? 'subject=' . rawurlencode(TITLE) . '&' : '') . 'body=' . (rawurlencode(HOST . REWRITED_SCRIPT))), (new FileTemplate('framework/content/share/share_email_image_render.tpl'))->render(), null, '', true);

		if (AppContext::get_request()->is_mobile_device() && $config->is_content_sharing_sms_enabled())
			$links[] = new ContentSharingActionsMenuLink('sms', LangLoader::get_message('common.share.sms', 'common-lang'), new Url('sms:?body=' . (rawurlencode(HOST . REWRITED_SCRIPT))), (new FileTemplate('framework/content/share/share_sms_image_render.tpl'))->render(), null, '', true);
		else if (!AppContext::get_request()->is_mobile_device() && $config->is_content_sharing_print_enabled())
			$links[] = new ContentSharingActionsMenuLink('print', LangLoader::get_message('common.printable', 'common-lang'), new Url('#'), (new FileTemplate('framework/content/share/share_print_image_render.tpl'))->render(), null, 'javascript:window.print()', true);

		return $links;
	}
}
?>
