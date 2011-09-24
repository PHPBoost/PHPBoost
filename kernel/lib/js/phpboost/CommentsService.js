/*##################################################
 *                              CommentsService.js
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
 
var CommentsService = Class.create({
	refresh_comments_list : function (module_id, id_in_module) {
		new Ajax.Updater('comments_list', PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/comments/display/', {
			parameters: {module_id: module_id, id_in_module: id_in_module},
			insertion: Insertion.Bottom
		})
		
		$('refresh_comments').remove();
	},
	positive_vote : function(module_id, id_in_module, comment_id) {
		new Ajax.Request(PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/comments/notation/', {
			  method: 'post',
			  parameters: {module_id: module_id, id_in_module: id_in_module, note_type: 'plus', comment_id: comment_id},
			  onComplete: function(response) {
				  alert(response.responseJSON.message);
			  }
		});
	},
	negative_vote : function (module_id, id_in_module, comment_id) {
		new Ajax.Request(PATH_TO_ROOT + '/kernel/framework/ajax/dispatcher.php?url=/comments/notation/', {
			  method: 'post',
			  parameters: {module_id: module_id, id_in_module: id_in_module, note_type: 'less', comment_id: comment_id},
			  onComplete: function(response) {
				  alert(response.responseJSON.message);
			  }
		});
	},
});