/*##################################################
 *                              Comment.js
 *                            -------------------
 *   begin                : February 15, 2010
 *   copyright            : (C) 2010 Kévin MASSY
 *   email                : soldier.weasel@gmail.com
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
 
var Comment = Class.create({
	already_post : 0,
	id_comment = 0,
	is_member : true,
	lang_already_posted : 'Already posted',
	set_id_comment : function (id_comment) {
		this.id_comment = id_comment;
	},
	set_already_post : function (already_post) {
		this.already_post = already_post;
	},
	set_lang_already_posted : function (lang_already_posted) {
		this.lang_already_posted = lang_already_posted;
	},
	set_is_member : function (is_member) {
		this.is_member = is_member;
	},
	positively_vote : function() {
		this.verificate_authorizations();
		
		//Add AJAX request
		
		this.already_post = 1;
	},
	negative_vote : function () {
		this.verificate_authorizations();
		
		//Add AJAX request
		
		this.already_post = 1;
	},
	verificate_authorizations : function() {
		if (this.already_post !== 0 )
		{
			alert(this.lang_already_posted);
		}
	}
});