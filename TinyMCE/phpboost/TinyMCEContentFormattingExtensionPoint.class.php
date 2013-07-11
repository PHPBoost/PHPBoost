<?php
/*##################################################
 *                           TinyMCEContentFormattingExtensionPoint.class.php
 *                            -------------------
 *   begin                : October 11, 2011
 *   copyright            : (C) 2011 K�vin MASSY
 *   email                : kevin.massy@phpboost.com
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

class TinyMCEContentFormattingExtensionPoint extends AbstractContentFormattingExtensionPoint
{
	public function get_name()
	{
		return 'TinyMCE';
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function get_parser()
	{
		$parser = new TinyMCEParser();
		$parser->set_forbidden_tags($this->get_forbidden_tags());
		$parser->set_html_auth($this->get_html_auth());
		return $parser;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_unparser()
	{
		return new TinyMCEUnparser();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_second_parser()
	{
		return new ContentSecondParser();
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_editor()
	{
		$editor = new TinyMCEEditor();
		$editor->set_forbidden_tags($this->get_forbidden_tags());
		return $editor;
	}
}
?>