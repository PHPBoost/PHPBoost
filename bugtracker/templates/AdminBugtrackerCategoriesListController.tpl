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
			
			jQuery('<td/>', {id : 'td1_' + id, 'data-th' : ${escapejs(@labels.default)}}).appendTo('#tr_' + id);
			
			jQuery('<div/>', {id : 'categories_radio_' + id, class: 'form-field-radio'}).appendTo('#td1_' + id);
			jQuery('<input/> ', {type : 'radio', id : 'default_category' + id, name : 'default_category', value : id}).appendTo('#categories_radio_' + id);
			jQuery('<label/> ', {for : 'default_category' + id}).appendTo('#categories_radio_' + id);
			
			jQuery('<td/>', {id : 'td2_' + id, 'data-th' : ${escapejs(LangLoader::get_message('form.name', 'common'))}}).appendTo('#tr_' + id);
			
			jQuery('<span/>', {id : 'td2_' + id + '_bt', class : 'bt-content'}).appendTo('#td2_' + id);
			
			jQuery('<input/> ', {type : 'text', id : 'category_' + id, name : 'category_' + id, placeholder : ${escapejs(LangLoader::get_message('form.name', 'common'))}}).appendTo('#td2_' + id + '_bt');
			
			jQuery('<td/>', {id : 'td3_' + id, 'data-th' : ${escapejs(LangLoader::get_message('delete', 'common'))}}).appendTo('#tr_' + id);
			
			jQuery('<a/> ', {id : 'delete_' + id, onclick : 'BugtrackerFormFieldCategories.delete_category(' + id + ');return false;', title : ${escapejs(LangLoader::get_message('delete', 'common'))}}).html('<i class="fa fa-delete"></i>').appendTo('#td3_' + id);
			
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

<table id="table2" class="categories-list">
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
	<tfoot>
		<tr>
			<th>
				<a href="" onclick="BugtrackerFormFieldCategories.add_category();return false;" title="{@titles.add_category}" id="add-category"><i class="fa fa-plus"></i></a>
			</th>
			<th colspan="2" class="right">
				# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #<a href="{LINK_DELETE_DEFAULT}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="{@actions.confirm.del_default_value}"><i class="fa fa-delete"></i> {@labels.del_default_value}</a># ENDIF #
			</th>
		</tr>
	</tfoot>
	<tbody id="categories_list">
		<tr id="no-category"# IF C_CATEGORIES # style="display:none;"# ENDIF #>
			<td colspan="3">
				${LangLoader::get_message('no_item_now', 'common')}
			</td>
		</tr>
		# START categories #
		<tr>
			<td>
				<div id="categories_radio_{categories.ID}" class="form-field-radio">
					<input id="default_category" type="radio" name="default_category" value="{categories.ID}"# IF categories.C_IS_DEFAULT # checked="checked"# ENDIF # />
					<label for="default_category{categories.ID}"></label>
				</div>
			</td>
			<td>
				<input type="text" name="category{categories.ID}" value="{categories.NAME}" />
			</td>
			<td>
				<a href="{categories.LINK_DELETE}" title="${LangLoader::get_message('delete', 'common')}" data-confirmation="delete-element"><i class="fa fa-delete"></i></a>
			</td>
		</tr>
		# END categories #
	</tbody>
</table>
