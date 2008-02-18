<?php
/*##################################################
 *                             faq_cats.class.php
 *                            -------------------
 *   begin                : February 17, 2008
 *   copyright          : (C) 2008 Benoît Sautel
 *   email                : ben.popeye@phpboost.com
 *
 *   
###################################################
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 * 
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
###################################################*/

include_once('../includes/cats_management.class.php');

class FaqCats extends CategoriesManagement
{
	## Public methods ##
	
	//Constructor
	function FaqCats()
	{
		parent::CategoriesManagement('faq_cats', 'xmlhttprequest_faq.php');
	}
	
	//Method which removes all subcategories and their content
	function Delete_category_recursively($id)
	{
		global $FAQ_CATS;
		
		foreach( $FAQ_CATS as $id_cat => $properties )
		{
			if( $properties['id_parent'] == $id )
				$this->Delete_category_recursively($id_cat);
		}
		
	}
	
	//Method which deletes a category and move its content in another category
	function Delete_category_and_move_content($id_category, $new_id_cat_content)
	{
		global $FAQ_CATS, $Sql;
		
		if( !array_key_exists($id_category, $FAQ_CATS) )
		{
			parent::add_error(NEW_PARENT_CATEGORY_DOES_NOT_EXIST);
			return false;
		}
		
		foreach( $FAQ_CATS as $id_cat => $properties )
		{
			if( $properties['id_parent'] == $id_category )
				parent::Move_category_into_another_category($id_category, $new_id_cat_content);			
		}
		$Sql->Query_inject("UPDATE ".PREFIX."faq SET idcat = '" . $new_id_cat_content . "' WHERE idcat = '" . $id_category . "'", __LINE__, __FILE__);
		return true;
	}
	
	## Private methods ##
	
	//method which deletes a category and its content (not recursive)
	function delete_category_with_content($id)
	{
		global $Sql;
		//If the category is successfully deleted
		if( parent::Delete_category($id) )
		{
			//We remove its whole content
			$Sql->Query_inject("DELETE FROM ".PREFIX."faq WHERE idcat = '" . $id . "'", __LINE__, __FILE__);
			return true;
		}
		else
			return false;
	}
}

?>
