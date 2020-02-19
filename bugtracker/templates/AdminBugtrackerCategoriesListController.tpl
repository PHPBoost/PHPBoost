<script>
<!--
var BugtrackerFormFieldCategories = function(){
	this.integer = {NEXT_ID};
	this.max_input = {MAX_INPUT};
};

BugtrackerFormFieldCategories.prototype = {
	add_category : function () {
		if (this.integer <= this.max_input) {
			var id = this.integer;

			jQuery('<tr/>', {id : 'tr_' + id}).appendTo('#categories_list');

			jQuery('<td/>', {id : 'td1_' + id, class : 'custom-radio', 'data-th' : ${escapejs(@labels.default)}}).appendTo('#tr_' + id);

			jQuery('<div/>', {id : 'categories_radio_' + id, class: 'form-field-radio'}).appendTo('#td1_' + id);
			jQuery('<label/> ', {class : 'radio', for : 'default_category' + id}).appendTo('#categories_radio_' + id);
			jQuery('<input/> ', {type : 'radio', id : 'default_category' + id, name : 'default_category', value : id}).appendTo('#categories_radio_' + id + ' label');
			jQuery('<span/>', {class : 'custom-radio'}).appendTo('#categories_radio_' + id + ' label');

			jQuery('<td/>', {id : 'td2_' + id, 'data-th' : ${escapejs(LangLoader::get_message('form.name', 'common'))}}).appendTo('#tr_' + id);

			jQuery('<span/>', {id : 'td2_' + id + '_bt', class : 'bt-content'}).appendTo('#td2_' + id);

			jQuery('<input/> ', {type : 'text', id : 'category_' + id, name : 'category_' + id, placeholder : ${escapejs(LangLoader::get_message('form.name', 'common'))}}).appendTo('#td2_' + id + '_bt');

			jQuery('<td/>', {id : 'td3_' + id, 'data-th' : ${escapejs(LangLoader::get_message('delete', 'common'))}}).appendTo('#tr_' + id);

			jQuery('<a/> ', {id : 'delete_' + id, onclick : 'BugtrackerFormFieldCategories.delete_category(' + id + ');return false;', 'aria-label' : ${escapejs(@titles.del_category)}}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#td3_' + id);

			this.integer++;
		}
		if (this.integer == this.max_input) {
			jQuery('#add-category').hide();
		}
		if (this.integer) {
			jQuery('#no-category').hide();
		}
	},
	delete_category : function (id) {
		jQuery('#tr_' + id).remove();
		this.integer--;
		if (this.integer >= 1)
			jQuery('#no-category').hide();
		else
			jQuery('#no-category').show();

		jQuery('#add-category').show();
	},
};

var BugtrackerFormFieldCategories = new BugtrackerFormFieldCategories();
-->
</script>

<table class="table categories-list">
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
	<tbody id="categories_list">
		<tr id="no-category"# IF C_CATEGORIES # style="display: none;"# ENDIF #>
			<td colspan="3">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# START categories #
		<tr>
			<td class="custom-radio">
				<div id="categories_radio_{categories.ID}" class="form-field-radio">
					<label class="radio" for="default_category{categories.ID}">
						<input aria-label="{categories.NAME}" id="default_category{categories.ID}" type="radio" name="default_category" value="{categories.ID}"# IF categories.C_IS_DEFAULT # checked="checked"# ENDIF # />
						<span></span>
					</label>
				</div>
			</td>
			<td>
				<input type="text" name="category{categories.ID}" value="{categories.NAME}" />
			</td>
			<td>
				<a href="{categories.LINK_DELETE}" aria-label="${@titles.del_category}" data-confirmation="delete-element"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
			</td>
		</tr>
		# END categories #
	</tbody>
	<tfoot>
		<tr>
			<td>
				<a href="#" onclick="BugtrackerFormFieldCategories.add_category();return false;" aria-label="{@titles.add_category}" id="add-category"><i class="fa fa-plus" aria-hidden="true"></i></a>
			</td>
			<td colspan="2" class="align-right">
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #<a href="{LINK_DELETE_DEFAULT}" data-confirmation="{@actions.confirm.del_default_value}"><i class="far fa-trash-alt" aria-hidden="true"></i> {@labels.del_default_value}</a># ENDIF #
			</td>
		</tr>
	</tfoot>
</table>
