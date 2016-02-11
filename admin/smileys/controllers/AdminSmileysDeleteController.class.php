<?php
/*##################################################
 *                              AdminSmileysDeleteController.class.php
 *                            -------------------
 *   begin                : May 22, 2015
 *   copyright            : (C) 2015 Julien BRISWALTER
 *   email                : j1.seth@phpboost.com
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

class AdminSmileysDeleteController extends AdminController
{
	public function execute(HTTPRequestCustom $request)
	{
		$id = $request->get_getint('id', 0);
		
		PersistenceContext::get_querier()->delete(DB_TABLE_SMILEYS, 'WHERE idsmiley = :id', array('id' => $id));
		
		###### Régénération du cache des smileys #######
		SmileysCache::invalidate();
		
		AppContext::get_response()->redirect(AdminSmileysUrlBuilder::management());
	}
}
?>
