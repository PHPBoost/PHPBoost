<?php
/*##################################################
 *                       CachedStringTemplate.class.php
 *                            -------------------
 *   begin                : February 6, 2010
 *   copyright            : (C) 2010 Benoit Sautel
 *   email                : ben.popeye@phpboost.com
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

/**
 * @package io
 * @subpackage template
 * @author Benoit Sautel <ben.popeye@phpboost.com>
 * @desc This class enables you to handle a template string.
 */
class CachedStringTemplate extends AbstractTemplate
{
	public function __construct($content, $auto_load_vars = self::AUTO_LOAD_FREQUENT_VARS)
	{
		parent::__construct(new CachedStringTemplateLoader($content), new DefaultTemplateRenderer(), new DefaultTemplateData(), $auto_load_vars);
	}
}
?>