<script>
	function insert_date(field, input_field, date, calendar_number)
	{
		if (jQuery('\#' + input_field)) {
			if (date) {
				jQuery('\#' + input_field).val(date);

				var array = date.split('-');
				year = array[0];
				month = array[1];
				day = array[2];
				jQuery('\#' + input_field + '_link').attr('onclick', "xmlhttprequest_calendar('" + field + "', '" + input_field + "', '" + year + "', '" + month + "', '" + day + "', '" + calendar_number + "', 1);return false;");
				jQuery('\#' + input_field).attr('onclick', "xmlhttprequest_calendar('" + field + "', '" + input_field + "', '" + year + "', '" + month + "', '" + day + "', '" + calendar_number + "');return false;");

				jQuery('\#' + input_field).focus();
				// jQuery('\#calendar' + calendar_number).hide();
			}
		}
	}

	function xmlhttprequest_calendar(field, input_field, year, month, day, calendar_number, toggle)
	{
		jQuery.ajax({
			url: '{PATH_TO_ROOT}/kernel/framework/ajax/mini_calendar_xmlhttprequest.php?input_field=' + input_field + '&field=' + field + '&calendar_number=' + calendar_number + '&d=' + day + '&m=' + month + '&y=' + year,
			type: "get",
			data: { token: '{TOKEN}' },
			success: function(returnData){
				jQuery('\#' + field).html(returnData);

				// if (toggle)
				// 	jQuery('\#calendar' + calendar_number).toggle();
				// else
				// 	jQuery('\#calendar' + calendar_number).show();
			}
		});
	}

	var BugtrackerFormFieldVersions = function(){
		this.integer = {NEXT_ID};
		this.max_input = {MAX_INPUT};
	};

	BugtrackerFormFieldVersions.prototype = {
		add_version : function () {
			if (this.integer <= this.max_input) {
				var id = this.integer;

				jQuery('<tr/>', {id : 'tr_' + id}).appendTo('#versions_list');

				jQuery('<td/>', {id : 'td1_' + id, class : 'custom-radio', 'data-th' : ${escapejs(@form.is.default)}}).appendTo('#tr_' + id);

				jQuery('<div/>', {id : 'versions_radio_' + id, class: 'form-field-radio', style : 'display:none;'}).appendTo('#td1_' + id);
				jQuery('<label/> ', {class : 'radio',for : 'default_version' + id}).appendTo('#versions_radio_' + id);
				jQuery('<input/> ', {type : 'radio', id : 'default_version' + id, name : 'default_version', value : id}).appendTo('#versions_radio_' + id + ' label');
				jQuery('<span/>').appendTo('#versions_radio_' + id + ' label');

				jQuery('<td/>', {id : 'td2_' + id, 'data-th' : ${escapejs(@labels.version_name)}}).appendTo('#tr_' + id);

				jQuery('<div/>', {id : 'td2_' + id + '_bt', class : 'bt-content'}).appendTo('#td2_' + id);

				jQuery('<input/> ', {type : 'text', id : 'version_' + id, name : 'version_' + id, placeholder : ${escapejs(@form.name)}}).appendTo('#td2_' + id + '_bt');

				jQuery('<td/>', {id : 'td3_' + id, 'data-th' : ${escapejs(@labels.fields.version_release_date)}}).appendTo('#tr_' + id);

				jQuery('<div/>', {id : 'td3_' + id + '_bt', class : 'bt-content'}).appendTo('#td3_' + id);

				jQuery('<div/>', {class : 'grouped-inputs'}).appendTo('#td3_' + id + '_bt');

				jQuery('<input/> ', {type : 'text', maxlength : 10, id : 'release_date' + id, class : 'grouped-element input-date', name : 'release_date' + id, onclick : "xmlhttprequest_calendar('release_date" + id + "_date', 'release_date" + id + "', '', '', '', '" + id + "');", placeholder : ${escapejs(@date.format)}}).appendTo('#td3_' + id + '_bt .grouped-inputs').attr('size', '11');
				jQuery('#release_date' + id).after(' ');

				jQuery('<div/> ', {id : 'calendar' + id + '_container', class : 'calendar-container modal-container cell-modal cell-tile grouped-element'}).appendTo('#td3_' + id + '_bt .grouped-inputs');

				jQuery('<a/> ', {id : 'release_date' + id + '_link', class : 'bgc-full link-color', 'data-modal' : '', 'data-target': 'calendar' + id, onclick : "xmlhttprequest_calendar('release_date" + id + "_date', 'release_date" + id + "', '', '', '', '" + id + "', 1);return false;", style : 'cursor:pointer;', 'aria-label' : ${escapejs(@titles.calendar)}}).html('<i class="fa fa-calendar-alt" aria-hidden="true"></i>').appendTo('#calendar' + id + '_container');

				jQuery('<div/> ', {id : 'calendar' + id, class : 'modal modal-animation'}).appendTo('#calendar' + id + '_container');

				jQuery('<div/> ', {text : ${escapejs(@common.close)}, class : 'close-modal'}).appendTo('#calendar' + id);

				jQuery('<div/> ', {id : 'release_date' + id + '_date', class : 'content-panel cell calendar-block'}).appendTo('#calendar' + id);

				jQuery('<td/>', {id : 'td4_' + id, class : 'mini-checkbox', 'data-th' : ${escapejs(@labels.fields.version_detected)}}).appendTo('#tr_' + id);

				jQuery('<div/>', {id : 'td4_' + id + '_bt', class : 'bt-content'}).appendTo('#td4_' + id);

				jQuery('<div/>', {id : 'versions_checkbox_' + id, class: 'form-field-checkbox'}).appendTo('#td4_' + id + '_bt');
				jQuery('<label/> ', {class : 'checkbox', for : 'detected_in' + id}).appendTo('#versions_checkbox_' + id);
				jQuery('<input/> ', {type : 'checkbox', id : 'detected_in' + id, name : 'detected_in' + id, onclick : 'display_default_version_radio(' + id + ');'}).appendTo('#versions_checkbox_' + id + ' label');
				jQuery('<span/>').html('&nbsp;').appendTo('#versions_checkbox_' + id + ' label');

				jQuery('<td/>', {id : 'td5_' + id, 'data-th' : ${escapejs(@common.delete)}}).appendTo('#tr_' + id);

				jQuery('<a/> ', {id : 'delete_' + id, onclick : 'BugtrackerFormFieldVersions.delete_version(' + id + ');return false;', 'aria-label' : ${escapejs(@titles.del_version)}}).html('<i class="far fa-trash-alt" aria-hidden="true"></i>').appendTo('#td5_' + id);

				jQuery('<script/>').html('jQuery("#release_date' + id + '_link").multiTabs({ pluginType: "modal" });').appendTo('#calendar' + id + '_container');

				this.integer++;
			}
			if (this.integer == this.max_input) {
				jQuery('#add-version').hide();
			}
			if (this.integer) {
				jQuery('#no-version').hide();
			}
		},
		delete_version : function (id) {
			jQuery('#tr_' + id).remove();
			this.integer--;
			if (this.integer >= 1)
				jQuery('#no-version').show();
			else
				jQuery('#no-version').show();

			jQuery('#add-version').show();
		}
	};

	var BugtrackerFormFieldVersions = new BugtrackerFormFieldVersions();

	function display_default_version_radio(version_id)
	{
		if (jQuery('#detected_in' + version_id).prop('checked'))
			jQuery('#versions_radio_' + version_id).show();
		else
		{
			jQuery('#default_version' + version_id).prop('checked', false);
			jQuery('#versions_radio_' + version_id).hide();
		}
	}
</script>

<table class="table version-list">
	<thead>
		<tr>
			<th class="col-medium">
				{@form.is.default}
			</th>
			<th>
				{@labels.version_name}
			</th>
			<th class="col-larger">
				{@labels.fields.version_release_date}
			</th>
			<th class="col-large">
				{@labels.fields.version_detected}
			</th>
			<th class="col-small">
				{@common.delete}
			</th>
		</tr>
	</thead>
	<tbody id="versions_list">
		<tr id="no-version"# IF C_VERSIONS # style="display: none;"# ENDIF #>
			<td colspan="5">
				{@common.no.item.now}
			</td>
		</tr>
		# START versions #
			<tr>
				<td class="custom-radio">
					<div id="versions_radio_{versions.ID}" class="form-field-radio"# IF NOT versions.C_DETECTED_IN # style="display: none;"# ENDIF #>
						<label class="radio" for="default_version{versions.ID}">
							<input aria-label="{versions.NAME}" type="radio" id="default_version{versions.ID}" name="default_version" value="{versions.ID}"# IF versions.C_IS_DEFAULT # checked="checked"# ENDIF #>
							<span></span>
						</label>
					</div>
				</td>
				<td>
					<input type="text" name="version{versions.ID}" value="{versions.NAME}" />
				</td>
				<td>
					<div class="grouped-inputs">
						<input type="text" size="11" maxlength="10" id="release_date{versions.ID}" class="grouped-element input-date" name="release_date{versions.ID}" value="{versions.RELEASE_DATE}" onclick="xmlhttprequest_calendar('release_date{versions.ID}_date', 'release_date{versions.ID}', ${escapejs(versions.YEAR)}, ${escapejs(versions.MONTH)}, ${escapejs(versions.DAY)}, ${escapejs(versions.ID)});return false;" placeholder="{@date.format}">
						<div class="calendar-container modal-container cell-modal cell-tile grouped-element">
							<a class="bgc-full link-color" data-modal="" data-target="calendar{versions.ID}" id="release_date{versions.ID}_link" href="#" onclick="xmlhttprequest_calendar('release_date{versions.ID}_date', 'release_date{versions.ID}', ${escapejs(versions.YEAR)}, ${escapejs(versions.MONTH)}, ${escapejs(versions.DAY)}, ${escapejs(versions.ID)}, 1);return false;" aria-label="{@titles.calendar}"><i class="fa fa-calendar-alt" aria-hidden="true"></i></a>
							<div id="calendar{versions.ID}" class="modal modal-animation">
								<div class="close-modal"></div>
								<div id="release_date{versions.ID}_date" class="content-panel cell calendar-block"></div>
							</div>
						</div>

					</div>
				</td>
				<td class="mini-checkbox">
					<div id="versions_checkbox_{versions.ID}" class="form-field-checkbox">
						<label class="checkbox" for="detected_in{versions.ID}">
							<input type="checkbox" id="detected_in{versions.ID}" name="detected_in{versions.ID}" onclick="display_default_version_radio('{versions.ID}');"# IF versions.C_DETECTED_IN # checked="checked"# ENDIF # />
							<span>&nbsp;</span>
						</label>
					</div>
				</td>
				<td>
					<a href="{versions.LINK_DELETE}" aria-label="{@titles.del_version}" data-confirmation="delete-element"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
				</td>
			</tr>
		# END versions #
	</tbody>
	<tfoot>
		<tr>
			# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #
				<td>
					<a href="{LINK_DELETE_DEFAULT}" data-confirmation="{@actions.confirm.del_default_value}" aria-label="{@labels.del_default_value}"><i class="far fa-lg fa-dot-circle error" aria-hidden="true"></i></a>
				</td>
			# ENDIF #
			<td colspan="# IF C_DISPLAY_DEFAULT_DELETE_BUTTON #4# ELSE #5# ENDIF #">
				<a href="#" onclick="BugtrackerFormFieldVersions.add_version();return false;" aria-label="{@titles.add_version}" id="add-version"><i class="far fa-lg fa-plus-square" aria-hidden="true"></i></a>
			</td>
		</tr>
	</tfoot>
</table>
