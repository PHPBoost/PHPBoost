<?php
/*##################################################
 *   				FaqUrlBuilder.class.php
 *  				-----------------------
 *   begin                : August 13, 2011
 *   copyright            : (C) 2011 Alain091
 *   email                : alain091@gmail.com
 *
 *
 *###################################################
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
 *###################################################
 */

class FaqUrlBuilder
{
	public static function get_title($id_faq)
	{
		global $FAQ_CATS,$FAQ_LANG;
		
		if ($id_faq > 0)
			$title = array_key_exists($id_faq, $FAQ_CATS) ? $FAQ_CATS[$id_faq]['name'] : $FAQ_LANG['faq'];
		else
			$title = FaqConfig::load()->get_faq_name();
		return $title;
	}

	public static function get_link_cat($id_faq, $name)
	{
		return url(
			'faq.php?id=' . $id_faq,
			'faq-' . $id_faq . '+' . Url::encode_rewrite($name) . '.php');
	}

	public static function get_link_question($id_faq,$id,$name='')
	{
		if (empty($name))
			$name = self::get_title($id_faq);
		return url(
			'faq.php?id=' . $id_faq . '&amp;question=' . $id,
			'faq-' . $id_faq . '+' . Url::encode_rewrite($name) . '.php?question=' . $id . '#q' . $id);
	}
}
?>
