<script type="text/javascript">
	<!--
	# IF C_REOPEN_BUG #
	function Confirm_reopen() {
		return confirm("{@bugs.actions.confirm.reopen_bug}");
	}
	# ENDIF #
	
	# IF C_REJECT_BUG #
	function Confirm_reject() {
		return confirm("{@bugs.actions.confirm.reject_bug}");
	}
	# ENDIF #
	-->
</script>

<fieldset>
	<legend>
		{@bugs.titles.bugs_treatment_state}
		&nbsp;
		# IF C_REOPEN_BUG #
			<a href="{U_REOPEN}" onclick="javascript:return Confirm_reopen();" class="pbt-icon-bugtracker-rejected" title="{@bugs.actions.reopen}"></a>
		# ENDIF #
		# IF C_REJECT_BUG #
			<a href="{U_REJECT}" onclick="javascript:return Confirm_reject();" class="pbt-icon-bugtracker-opened" title="{@bugs.actions.reject}"></a>
		# ENDIF #
		# IF C_EDIT_BUG #
			<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="pbt-icon-edit"></a>
		# ENDIF #
		# IF C_HISTORY_BUG #
			<a href="{U_HISTORY}" title="{@bugs.actions.history}" class="pbt-icon-bugtracker-history"></a>
		# ENDIF #
		# IF C_DELETE_BUG #
			<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="pbt-icon-delete" data-confirmation="delete-element"></a>
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

<fieldset>
	<legend>{TITLE}</legend>
	<div class="content">{CONTENTS}</div>
</fieldset>

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
		<dd># IF C_REPRODUCTIBLE #${LangLoader::get_message('yes', 'main')}# ELSE #${LangLoader::get_message('no', 'main')}# ENDIF #</dd>
	</dl>
</fieldset>

# IF C_REPRODUCTION_METHOD #
<fieldset>
	<legend>{@bugs.labels.fields.reproduction_method}</legend>
	<div class="content">{REPRODUCTION_METHOD}</div>
</fieldset>
# ENDIF #

<div class="text_small float_right">
	{@bugs.labels.fields.author_id} # IF AUTHOR #<a href="{U_AUTHOR_PROFILE}" class="small_link {AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #, ${LangLoader::get_message('on', 'main')} # IF C_IS_DATE_FORM_SHORT #{SUBMIT_DATE_SHORT}# ELSE #{SUBMIT_DATE}# ENDIF #
</div>

<div class="spacer">&nbsp;</div>

<div class="center">
	<strong><a href="javascript:history.back(1);">${LangLoader::get_message('back', 'main')}</a></strong>
</div>

# INCLUDE COMMENTS #
