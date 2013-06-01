<script type="text/javascript">
	<!--
	function Confirm() {
		return confirm("{@bugs.actions.confirm.del_bug}");
	}
	-->
</script>

<fieldset>
	<legend>
		{@bugs.titles.bugs_treatment_state}
		# IF C_REOPEN_BUG #
			&nbsp;&nbsp;
			<a href="{LINK_BUG_REOPEN}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/visible.png" alt="{@bugs.actions.reopen}" title="{@bugs.actions.reopen}" />
			</a>
		# ENDIF #
		# IF C_REJECT_BUG #
			<a href="{LINK_BUG_REJECT}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/unvisible.png" alt="{@bugs.actions.reject}" title="{@bugs.actions.reject}" />
			</a>
		# ENDIF #
		# IF C_EDIT_BUG #
			<a href="{LINK_BUG_EDIT}">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_UPDATE}" title="{L_UPDATE}" />
			</a>
		# ENDIF #
		# IF C_HISTORY_BUG #
			<a href="{LINK_BUG_HISTORY}">
				<img src="{PATH_TO_ROOT}/bugtracker/templates/images/history.png" alt="{@bugs.actions.history}" title="{@bugs.actions.history}" />
			</a>
		# ENDIF #
		# IF C_DELETE_BUG #
			<a href="{LINK_BUG_DELETE}" onclick="javascript:return Confirm();">
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" title="{L_DELETE}" />
			</a>
		# ENDIF #
	</legend>
	<dl>
		<dt>{@bugs.labels.fields.status}</dt>
		<dd>{STATUS}</dd>
	</dl>
	<dl>
		<dt>{@bugs.labels.fields.assigned_to_id}</dt>
		<dd># IF USER_ASSIGNED #<a href="{LINK_USER_ASSIGNED_PROFILE}" class="small_link {USER_ASSIGNED_LEVEL_CLASS}" # IF C_USER_ASSIGNED_GROUP_COLOR # style="color:{USER_ASSIGNED_GROUP_COLOR}" # ENDIF #>{USER_ASSIGNED}</a># ELSE #{@bugs.notice.no_one}# ENDIF #</dd>
	</dl>
	# IF C_FIXED_IN #
	<dl>
		<dt>{@bugs.labels.fields.fixed_in}</dt>
		<dd>{FIXED_IN}</dd>
	</dl>
	# ENDIF #
</fieldset>
<br />
<fieldset>
	<legend>{TITLE}</legend>
	{CONTENTS}
	<br /><br />
</fieldset>
<br />
<fieldset>
	<legend>{@bugs.titles.bugs_infos}</legend>
	# IF NOT C_EMPTY_TYPES #
	<dl>
		<dt>{@bugs.labels.fields.type}</dt>
		<dd>{TYPE}</dd>
	</dl>
	# ENDIF #
	# IF NOT C_EMPTY_CATEGORIES #
	<dl>
		<dt>{@bugs.labels.fields.category}</dt>
		<dd>{CATEGORY}</dd>
	</dl>
	# ENDIF #
	# IF NOT C_EMPTY_SEVERITIES #
	<dl>
		<dt>{@bugs.labels.fields.severity}</dt>
		<dd>{SEVERITY}</dd>
	</dl>
	# ENDIF #
	# IF NOT C_EMPTY_PRIORITIES #
	<dl>
		<dt>{@bugs.labels.fields.priority}</dt>
		<dd>{PRIORITY}</dd>
	</dl>
	# ENDIF #
	# IF NOT C_EMPTY_VERSIONS #
	<dl>
		<dt>{@bugs.labels.fields.detected_in}</dt>
		<dd>{DETECTED_IN}</dd>
	</dl>
	# ENDIF #
	<dl>
		<dt>{@bugs.labels.fields.reproductible}</dt>
		<dd>{REPRODUCTIBLE}</dd>
	</dl>
</fieldset>
<br />
# IF C_REPRODUCTIBLE #
<fieldset>
	<legend>{@bugs.labels.fields.reproduction_method}</legend>
	{REPRODUCTION_METHOD}
	<br /><br />
</fieldset>
# ENDIF #

<div class="text_small float_right">
	{@bugs.labels.fields.author_id}: # IF AUTHOR #<a href="{LINK_AUTHOR_PROFILE}" class="small_link {AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #{L_GUEST}# ENDIF #, {L_ON}: {SUBMIT_DATE}
</div>

<div class="spacer">&nbsp;</div>

<div class="text_center">
	<strong><a href="{LINK_RETURN}" title="${escape(RETURN_NAME)}">${escape(RETURN_NAME)}</a></strong>
</div>

<br /><br />
{COMMENTS}