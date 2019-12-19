/**
 * @copyright   &copy; 2005-2020 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Kevin MASSY <reidlos@phpboost.com>
 * @version     PHPBoost 5.3 - last update: 2018 12 27
 * @since       PHPBoost 3.0 - 2010 02 15
 * @contributor Julien BRISWALTER <j1.seth@phpboost.com>
 * @contributor Arnaud GENET <elenwii@phpboost.com>
*/

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

	jQuery('#notation-'+object.id+' .stars .star').on('click', function(){
		var star_nbr = this.id.replace(/star-([0-9]+)-([0-9]+)/g, "$2");
		object.send_request(star_nbr);
	});

	jQuery('#notation-'+object.id+' .stars .star').mouseover(function(){
		var star_nbr = this.id.replace(/star-([0-9]+)-([0-9]+)/g, "$2");
		object.change_picture_status(star_nbr);
	});
}

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
		var star_width;
		var decimal;
		for(var i = 1; i <= this.notation_scale; i++)
		{
			var id_star = 'star-' + this.id + '-' + i;

			decimal = 1 - (i - note); /* */
			if(decimal >= 1) {
				star_class = 'far star fa-star';
				star_width = 'star-width star-width-100';
			}
			else if(decimal >= 0.75 && decimal < 1) {
				star_class = 'far star fa-star';
				star_width = 'star-width star-width-75';
			}
			else if(decimal >= 0.50 && decimal < 0.75) {
				star_class = 'far star fa-star';
				star_width = 'star-width star-width-50';
			}
			else if(decimal >= 0.05 && decimal < 0.50) {
				star_class = 'far star fa-star';
				star_width = 'star-width star-width-25';
			}
			else {
				star_class = 'far star fa-star';
				star_width = 'star-width star-width-0';
			}

			if(jQuery('#' + id_star)) {
				jQuery('#' + id_star)[0].className = star_class;
				jQuery('#' + id_star).children('.star-width')[0].className = star_width;
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
