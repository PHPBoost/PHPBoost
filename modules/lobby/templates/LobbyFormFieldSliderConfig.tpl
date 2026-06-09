<script>
	var LobbyFormFieldSliderConfig = function(){
		this.integer = {NBR_FIELDS};
		this.id_input = ${escapejs(ID)};
		this.max_input = {MAX_INPUT};
	};

	LobbyFormFieldSliderConfig.prototype = {
		add_field : function () {
			if (this.integer <= this.max_input) {
				var id = this.id_input + '_' + this.integer;

				jQuery('<div/>', {'id' : id}).appendTo('#input_fields_' + this.id_input);

				jQuery('<textarea/> ', {id : 'field_description_' + id, name : 'field_description_' + id, class : 'grouped-area', placeholder : ${escapejs(@lobby.carousel.description)}}).appendTo('#' + id);
				jQuery('#' + id).append(' ');

				jQuery('<div/> ', {class : 'grouped-inputs'}).appendTo('#' + id);

				jQuery('<input/> ', {type : 'text', id : 'field_link_' + id, name : 'field_link_' + id, class : 'grouped-element', placeholder : ${escapejs(@lobby.carousel.link.url)}}).appendTo('#' + id + ' .grouped-inputs');
				jQuery('#' + id).append(' ');

				jQuery('<input/> ', {type : 'text', id : 'field_picture_url_' + id, name : 'field_picture_url_' + id, class : 'grouped-element', placeholder : ${escapejs(@lobby.carousel.picture.url)}}).appendTo('#' + id + ' .grouped-inputs');
				jQuery('#' + id).append(' ');

				jQuery('<a/> ', {class : 'grouped-element bgc-full link-color', href : '', 'aria-label' : ${escapejs(@lobby.carousel.upload)}, onclick : "window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&fd=field_picture_url_" + id + "&parse=true&no_path=true', '', 'height=500,width=769,resizable=yes,scrollbars=yes');return false;"}).html('<i class="fa fa-cloud-upload-alt fa-fw" aria-hidden="true"></i>').appendTo('#' + id + ' .grouped-inputs');
				jQuery('#' + id).append(' ');

				jQuery('<a/> ', {class : 'grouped-element bgc-full error', href : 'javascript:LobbyFormFieldSliderConfig.delete_field('+ this.integer +');', 'aria-label' : ${escapejs(@lobby.carousel.del)}}).html('<i class="fa fa-trash-alt fa-fw" aria-hidden="true"></i>').appendTo('#' + id + ' .grouped-inputs');

				this.integer++;
			}
			if (this.integer == this.max_input) {
				jQuery('#add-' + this.id_input).hide();
			}
		},
		delete_field : function (id) {
			var id = this.id_input + '_' + id;
			jQuery('#' + id).remove();
			this.integer--;
			jQuery('#add-' + this.id_input).show();
		}
	};

	var LobbyFormFieldSliderConfig = new LobbyFormFieldSliderConfig();
</script>

<div id="input_fields_${escape(ID)}">
# START fieldelements #
	<div id="${escape(ID)}_{fieldelements.ID}">
		<textarea class="grouped-area" name="field_description_${escape(ID)}_{fieldelements.ID}" id="field_description_${escape(ID)}_{fieldelements.ID}" placeholder="{@lobby.carousel.description}">{fieldelements.DESCRIPTION}</textarea>
		<div class="grouped-inputs">
			<input class="grouped-element" type="text" name="field_link_${escape(ID)}_{fieldelements.ID}" id="field_link_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.LINK}" placeholder="{@lobby.carousel.link.url}"/>
			<input class="grouped-element" type="text" name="field_picture_url_${escape(ID)}_{fieldelements.ID}" id="field_picture_url_${escape(ID)}_{fieldelements.ID}" value="{fieldelements.U_PICTURE}" placeholder="{@lobby.carousel.picture.url}"/>
			<a class="grouped-element bgc-full link-color" href="#" onclick="window.open('{PATH_TO_ROOT}/user/upload.php?popup=1&fd=field_picture_url_${escape(ID)}_{fieldelements.ID}&parse=true&no_path=true', '', 'height=500,width=769,resizable=yes,scrollbars=yes');return false;" aria-label="{@lobby.carousel.upload}"><i class="fa fa-cloud-upload-alt fa-fw" aria-hidden="true"></i></a>
			<a class="grouped-element bgc-full error" href="javascript:LobbyFormFieldSliderConfig.delete_field({fieldelements.ID});" data-confirmation="delete-element" aria-label="{@lobby.carousel.del}"><i class="fa fa-trash-alt fa-fw" aria-hidden="true"></i></a>
		</div>
	</div>
# END fieldelements #
</div>
<a href="javascript:LobbyFormFieldSliderConfig.add_field();" id="add-${escape(ID)}" class="add-more-values" aria-label="{@lobby.carousel.add}"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>
