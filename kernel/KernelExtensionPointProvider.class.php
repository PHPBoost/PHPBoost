<?php
/*##################################################
 *                          KernelExtensionPointProvider.class.php
 *                            -------------------
 *   begin                : February 06, 2010
 *   copyright            : (C) 2010 Loic Rouchon
 *   email                : loic.rouchon@phpboost.com
 *
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
}
?>