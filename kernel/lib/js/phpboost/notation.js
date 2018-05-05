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
 
 function Note(id, notation_scale, default_note, already_post){
	this.id = id;
	this.timeout = null;
	this.notation_scale = notation_scale;
	this.default_note = default_note;
	this.already_post = already_post;

	var object = this;
		
	jQuery('#notation-'+object.id+' .stars').mouseover(function(){
		clearTimeout(object.timeout);
		object.timeout = null;
	});

	jQuery('#notation-'+object.id+' .stars').mouseout(function(){
		if(object.timeout == null) {
			object.timeout = window.setTimeout(function() {
				object.change_picture_status(object.default_note); 
			}, 50);
		}
	});

	jQuery('#notation-'+object.id+' .stars .star').click(function(){
		var star_nbr = this.id.replace(/star-([0-9]+)-([0-9]+)/g, "$2");
		object.send_request(star_nbr);
	});

	jQuery('#notation-'+object.id+' .stars .star').mouseover(function(){
		var star_nbr = this.id.replace(/star-([0-9]+)-([0-9]+)/g, "$2");
		object.change_picture_status(star_nbr);
	});
};

Note.prototype = {
	send_request: function (note) {
		var id = this.id;
		var object = this;

		if (NOTATION_USER_CONNECTED == 0) {
			alert(NOTATION_LANG_AUTH);
		} 
		else {
			jQuery('#notation-' + id + ' .stars').after('<i id="loading-'+ id +'" class="fa fa-spinner fa-spin"></i>');
			
			jQuery.ajax({
				url: '',
				type: "post",
				data: {'note': note, 'id': id, 'token' : TOKEN},
				success: function(){
					jQuery('#loading-' + id).remove();
					if(object.already_post == 1) {
						alert(NOTATION_LANG_ALREADY_VOTE);
					}
					else {
						object.default_note = note;
						object.already_post = 1;
						object.change_picture_status(note);
						object.change_nbr_note();
					}
				}
			});
		}
	},
	change_picture_status: function (note) {
		var star_class;
		var decimal;
		for(var i = 1; i <= this.notation_scale; i++)
		{
			var id_star = 'star-' + this.id + '-' + i;
			
			decimal = i - note;
			if(decimal >= 1)
				star_class = 'fa star star-hover fa-star-o';
			else if(decimal <= 0.50 && decimal > 0)
				star_class = 'fa star star-hover fa-star-half-o';
			else if(note >= i)
				star_class = 'fa star star-hover fa-star';

			if(jQuery('#' + id_star)) {
				jQuery('#' + id_star)[0].className = star_class;
			}
		}
	},
	change_nbr_note: function () {
		var number_notes_el = jQuery('#notation-' + this.id + ' span.number-notes').first();
		var number_notes = parseInt(number_notes_el.text()) + 1;
		number_notes_el.text(number_notes);
		jQuery('#notation-' + this.id + ' .notes span:not(.number-notes)').text((number_notes > 1 ? NOTATION_LANG_NOTES : NOTATION_LANG_NOTE));
	}
};