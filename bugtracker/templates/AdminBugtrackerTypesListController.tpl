<script>
<!--
var BugtrackerFormFieldTypes = function(){
	this.integer = {NEXT_ID};
	this.max_input = {MAX_INPUT};
};

BugtrackerFormFieldTypes.prototype = {
	add_type : function () {
		if (this.integer <= this.max_input) {
			var id = this.integer;

			jQuery('<tr/>', {id : 'tr_' + id}).appendTo('#types_list');

			jQuery('<td/>', {id : 'td1_' + id, class : 'custom-radio', 'data-th' : ${escapejs(@labels.default)}}).appendTo('#tr_' + id);

			jQuery('<div/>', {id : 'types_radio_' + id, class: 'form-field-radio'}).appendTo('#td1_' + id);
			jQuery('<label/> ', {class : 'radio',for : 'default_type' + id}).appendTo('#types_radio_' + id);
			jQuery('<input/> ', {type : 'radio', id : 'default_type' + id, name : 'default_type', value : id}).appendTo('#types_radio_' + id + ' label');
			jQuery('<span/>', {class : 'custom-radio'}).appendTo('#types_radio_' + id + ' label');

			jQuery('<td/>', {id : 'td2_' + id, 'data-th' : ${escapejs(LangLoader::get_message('form.name', 'common'))}}).appendTo('#tr_' + id);

			jQuery('<span/>', {id : 'td2_' + id + '_bt', class : 'bt-content'}).appendTo('#td2_' + id);

			jQuery('<input/> ', {type : 'text', id : 'type_' + id, name : 'type_' + id, placeholder : ${escapejs(LangLoader::get_message('form.name', 'common'))}}).appendTo('#td2_' + id + '_bt');

			jQuery('<td/>', {id : 'td3_' + id, 'data-th' : ${escapejs(LangLoader::get_message('delete', 'common'))}}).appendTo('#tr_' + id);

			jQuery('<a/> ', {id : 'delete_' + id, onclick : 'BugtrackerFormFieldTypes.delete_type(' + id + ');return false;', 'aria-label' : ${escapejs(@titles.del_type)}}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#td3_' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-type').hide();
		}
		if (this.integer) {
			jQuery('#no-type').hide();
		}
	},
	delete_type : function (id) {
		jQuery('#tr_' + id).remove();
		this.integer--;
		if (this.integer >= 1)
			jQuery('#no-type').hide();
		else
			jQuery('#no-type').show();

		jQuery('#add-type').show();
	},
};

var BugtrackerFormFieldTypes = new BugtrackerFormFieldTypes();
-->
</script>

<table class="table type-list">
	<thead>
		<tr>
			<th>
				{@labels.default}
			</th>
			<th>
				${LangLoader::get_message('form.name', 'common')}
			</th>
			<th class="small-column">
				${LangLoader::get_message('delete', 'common')}
			</th>
		</tr>
	</thead>
	<tbody id="types_list">
		<tr id="no-type"# IF C_TYPES # style="display: none;"# ENDIF #>
			<td colspan="3">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# START types #
		<tr>
			<td class="custom-radio">
				<div id="types_radio_{types.ID}" class="form-field-radio">
					<label class="radio" for="default_type{types.ID}">
						<input aria-label="{types.NAME}" id="default_type{types.ID}" type="radio" name="default_type" value="{types.ID}"# IF types.C_IS_DEFAULT # checked="checked"# ENDIF # />
						<span></span>
					</label>
				</div>
			</td>
			<td>
				<input type="text" name="type{types.ID}" value="{types.NAME}" />
			</td>
			<td>
				<a href="{types.LINK_DELETE}" aria-label="${@titles.del_type}" data-confirmation="delete-element"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
			</td>
		</tr>
		# END types #
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3">
				<div class="cw25 float-left">
					<a href="#" onclick="BugtrackerFormFieldTypes.add_type();return false;" aria-label="{@titles.add_type}" id="add-type"><i class="fa fa-plus" aria-hidden="true"></i></a>
				</div>
				<div class="float-right">
					# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #<a href="{LINK_DELETE_DEFAULT}" data-confirmation="{@actions.confirm.del_default_value}"><i class="far fa-trash-alt" aria-hidden="true"></i> {@labels.del_default_value}</a># ENDIF #
				</div>
			</td>
		</tr>
	</tfoot>
</table>
