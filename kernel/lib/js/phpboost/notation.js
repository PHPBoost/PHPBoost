/*##################################################
 *                              notation.js
 *                            -------------------
 *   begin                : February 15, 2010
 *   copyright            : (C) 2010 Kevin MASSY
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
 
 var Note = Class.create({
	id : 0,
	timeout : null,
	notation_scale : 0,
	default_note : 0,
	already_post : 1,
	user_connected : 0,
	current_url : '',
	lang : new Array(),
	initialize : function(id, notation_scale, default_note) {
		this.id = id;
		this.notation_scale = notation_scale;
		this.default_note = default_note;
		
		object = this;
		
		Event.observe(window, 'load', function() {
			$$('#notation-'+object.id+' .stars').invoke('observe', 'mouseover', function(event) {
				clearTimeout(object.timeout);
				object.timeout = null;
			});

			$$('#notation-'+object.id+' .stars').invoke('observe', 'mouseout', function(event) {
				if(object.timeout == null) {
					object.timeout = window.setTimeout(function() {
						object.change_picture_status(object.default_note); 
					}, 50);
				}
			});
			
			$$('#notation-'+object.id+' .star').invoke('observe', 'click', function(event) {
				var id_element = event.element().id;
				var star_nbr = id_element.replace(/star-([0-9]+)-([0-9]+)/g, "$2");
				object.send_request(star_nbr);
			});
	
			$$('#notation-'+object.id+' .star').invoke('observe', 'mouseover', function(event) {
				var id_element = event.element().id;
				var star_nbr = id_element.replace(/star-([0-9]+)-([0-9]+)/g, "$2");
				object.change_picture_status(star_nbr);
			});
		});
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
	add_lang : function(name, value) {
		this.lang[name] = value;
	},
	send_request : function(note) {
		var id = this.id;
		var user_connected = this.user_connected;
		var already_post = this.already_post;
		var auth_error = this.lang['auth_error'];
		var already_vote = this.lang['already_vote'];

		$$('#notation-' + id + ' .stars').invoke('insert', {after: '<i id="loading-'+ id +'" class="icon-spinner icon-spin"></i>'});

		object = this;
		
		new Ajax.Request(
		this.current_url,
		{
			method: 'post',
			parameters: {'note': note, 'id': id, 'token' : TOKEN},
			onSuccess: function() {
				$('loading-' + id).remove();
				if (user_connected == 0) {
					alert(auth_error);
				}
				else if(already_post == 1) {
					alert(already_vote);
				}
				else {
					object.default_note = note;
					object.already_post = 1;
					object.change_picture_status(note);
					object.change_nbr_note();
				}
			}
		});
		
	},
	change_picture_status : function (note) {
		var star_class;
		var decimal;
		for(var i = 1; i <= this.notation_scale; i++)
		{
			var id_star = 'star-' + this.id + '-' + i;
			
			decimal = i - note;
			if(decimal >= 1)
				star_class = 'star star-hover icon-star-o';
			else if(decimal <= 0.50 && decimal > 0)
				star_class = 'star star-hover icon-star-half-o';
			else if(note >= i)
				star_class = 'star star-hover icon-star';
			
			if($(id_star)) {
				$(id_star).className = star_class;
			}
		}
	},
	change_nbr_note : function () {
		var number_notes_el = $$('#notation-' + this.id + ' span.number-notes').first();
		var number_notes = parseInt(number_notes_el.innerHTML) + 1;
		number_notes_el.update(number_notes);
		$$('#notation-' + this.id + ' .notes span:not(.number-notes)').invoke('update', (number_notes > 1 ? this.lang['notes'] : this.lang['note']));
	}
});