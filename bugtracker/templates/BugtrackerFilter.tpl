<script>
<!--
function delete_filter(id) {
	if (confirm(${escapejs(@actions.confirm.del_filter)})) {
		new Ajax.Request('${relative_url(BugtrackerUrlBuilder::delete_filter())}', {
			method:'post',
			asynchronous: false,
			parameters: {'id' : id, 'token' : '{TOKEN}'},
			onSuccess: function(transport) {
				if (transport.responseText > 0) {
					var elementToDelete = $('filter' + id);
					elementToDelete.parentNode.removeChild(elementToDelete);
				}
			}
		});
	}
}
-->
</script>
<menu class="dynamic-menu">
	<ul>
		<li>
			<a href="" onclick="Effect.toggle('table_filters', 'appear'); return false;"><i class="fa fa-filter"></i> {L_FILTERS}</a> 
		</li>
	</ul>
</menu>
<table id="table_filters"# IF NOT C_HAS_SELECTED_FILTERS # style="display:none;"# ENDIF #>
	<thead>
		<tr>
			# IF C_DISPLAY_TYPES #
			<th>
				{@labels.fields.type}
			</th>
			# ENDIF #
			# IF C_DISPLAY_CATEGORIES #
			<th>
				{@labels.fields.category}
			</th>
			# ENDIF #
			# IF C_DISPLAY_SEVERITIES #
			<th>
				{@labels.fields.severity}
			</th>
			# ENDIF #
			<th>
				{@labels.fields.status}
			</th>
			# IF C_DISPLAY_VERSIONS #
			<th>
				{@labels.fields.version}
			</th>
			# ENDIF #
			# IF C_DISPLAY_SAVE_BUTTON #
			<th>
			</th>
			# ENDIF #
		</tr>
	</thead>
	# IF BUGS_NUMBER #
	<tfoot>
		<tr>
			<th colspan="{FILTERS_NUMBER}">{@labels.number} # IF C_FILTER #{@labels.matching_selected_filter} # ELSE ## IF C_FILTERS #{@labels.matching_selected_filters} # ENDIF ## ENDIF #: {BUGS_NUMBER}</th>
		</tr>
	</tfoot>
	# ENDIF #
	<tbody>
		<tr>
			# IF C_DISPLAY_TYPES #
			<td class="no-separator">
				# INCLUDE SELECT_TYPE #
			</td>
			# ENDIF #
			# IF C_DISPLAY_CATEGORIES #
			<td class="no-separator">
				# INCLUDE SELECT_CATEGORY #
			</td>
			# ENDIF #
			# IF C_DISPLAY_SEVERITIES #
			<td class="no-separator">
				# INCLUDE SELECT_SEVERITY #
			</td>
			# ENDIF #
			<td class="no-separator">
				# INCLUDE SELECT_STATUS #
			</td>
			# IF C_DISPLAY_VERSIONS #
			<td class="no-separator">
				# INCLUDE SELECT_VERSION #
			</td>
			# ENDIF #
			# IF C_DISPLAY_SAVE_BUTTON #
			<td class="no-separator">
				<a href="{LINK_FILTER_SAVE}" title="{@labels.save_filters}" class="fa fa-save"></a>
			</td>
			# ENDIF #
		</tr>
		# IF C_SAVED_FILTERS #
		# START filters #
		<tr id="filter{filters.ID}">
			<td colspan="{FILTERS_NUMBER}">
				<a href="" title="${LangLoader::get_message('delete', 'main')}" id="delete_{filters.ID}" onclick="return false;" class="fa fa-delete"></a> <a href="{filters.LINK_FILTER}">{filters.FILTER}</a>
			</td>
		</tr>
		<script>
		<!--
		Event.observe(window, 'load', function() {
			$('delete_{filters.ID}').observe('click',function(){
				delete_filter('{filters.ID}');
			});
		});
		-->
		</script>
		# END filters #
		# ENDIF #
	</tbody>
</table>

<div class="spacer">&nbsp;</div>