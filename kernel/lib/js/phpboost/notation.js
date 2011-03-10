/*##################################################
 *                              notation.js
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
 
 var Note = Class.create({
	id : 0,
	timeout : null,
	notation_scale : 0,
	default_note : 0,
	already_post : 1,
	user_connected : 0,
	current_url : '',
	lang : new Array(),
	initialize : function(id, notation_scale) {
		this.id = id;
		this.notation_scale = notation_scale;
	},
	get_id : function() {
		return this.id;
	},
	set_default_note : function(note_bdd) {
		this.default_note = note_bdd;
	},
	get_default_note : function() {
		return this.default_note;
	},
	set_already_post : function(already_post) {
		this.already_post = already_post;
	},
	set_user_connected : function(user_connected) {
		this.user_connected = user_connected;
	},
	set_current_url : function(current_url) {
		this.current_url = current_url;
	},
	get_current_url : function() {
		return this.current_url;
	},
	add_lang : function(name, value) {
		this.lang[name] = value;
	},
	get_lang : function(name) {
		return this.lang[name];
	},
	send_request : function(note) {
		var id = Note.get_id();
		var user_connected = this.user_connected;
		var already_post = this.already_post;
		var auth_error = this.get_lang('auth_error');
		var already_vote = this.get_lang('already_vote');
		
		$('noteloading' + id).update('<img src="' + PATH_TO_ROOT + '/templates/' + THEME + '/images/loading_mini.gif" alt="" class="valign_middle" />');

		new Ajax.Request(
		this.get_current_url(),
		{
			method: 'post',
			parameters: {'note': note, 'valid_note': true},
			onSuccess: function() {
				$('noteloading' + id).update('');
				if (user_connected == 0) {
					alert(auth_error);
				}
				else if(already_post == 1) {
					alert(already_vote);
				}
				else {
					Note.set_default_note(note);
					Note.set_already_post(1);
					Note.change_picture_status(note);
				}
			}
		});
	},
	over_event : function () {
		clearTimeout(this.timeout);
		this.timeout = null;
	},
	out_event : function () {
		if(this.timeout == null)
			this.timeout = setTimeout('Note.change_picture_status(' + this.get_default_note() + ');', '50');
	},
	change_picture_status : function (note) {
		var picture_star;
		var decimal;
		for(var i = 1; i <= this.notation_scale; i++)
		{
			var name_picture_id = 'n' + this.id + '_stars' + i;
			picture_star = 'stars.png';
			if(note < i)
			{			
				decimal = i - note;
				if(decimal >= 1)
					picture_star = 'stars0.png';
				else if(decimal >= 0.75)
					picture_star = 'stars1.png';
				else if(decimal >= 0.50)
					picture_star = 'stars2.png';
				else
					picture_star = 'stars3.png';
			}
			
			if($(name_picture_id)) {
				$(name_picture_id).src = PATH_TO_ROOT + '/templates/' + THEME + '/images/' + picture_star;
			}
		}
	}
});