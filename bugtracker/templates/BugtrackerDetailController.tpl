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
	# IF C_PROGRESS #
	<div class="form-element">
		{@bugs.labels.fields.progress}
		<div class="form-field">
			<h6>{PROGRESS}%</h6> 
			<div class="progressbar-container" style="width:35%">
				<div class="progressbar" style="width:{PROGRESS}%"></div>
			</div>
			</div>
		</div>
	# ENDIF #
	<div class="form-element">
		{@bugs.labels.fields.status}
		<div class="form-field">{STATUS}</div>
	</div>
	<div class="form-element">
		{@bugs.labels.fields.assigned_to_id}
		<div class="form-field"># IF USER_ASSIGNED #<a href="{LINK_USER_ASSIGNED_PROFILE}" class="small_link {USER_ASSIGNED_LEVEL_CLASS}" # IF C_USER_ASSIGNED_GROUP_COLOR # style="color:{USER_ASSIGNED_GROUP_COLOR}" # ENDIF #>{USER_ASSIGNED}</a># ELSE #{@bugs.notice.no_one}# ENDIF #</div>
	</div>
	# IF C_FIXED_IN #
	<div class="form-element">
		{@bugs.labels.fields.fixed_in}
		<div class="form-field">{FIXED_IN}</div>
	</div>
	# ENDIF #
</fieldset>

<fieldset>
	<legend>{TITLE}</legend>
	<div class="content">{CONTENTS}</div>
</fieldset>

<fieldset>
	<legend>{@bugs.titles.bugs_infos}</legend>
	# IF NOT C_EMPTY_TYPES #
	<div class="form-element">
		{@bugs.labels.fields.type}
		<div class="form-field">{TYPE}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_CATEGORIES #
	<div class="form-element">
		{@bugs.labels.fields.category}
		<div class="form-field">{CATEGORY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_SEVERITIES #
	<div class="form-element">
		{@bugs.labels.fields.severity}
		<div class="form-field">{SEVERITY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_PRIORITIES #
	<div class="form-element">
		{@bugs.labels.fields.priority}
		<div class="form-field">{PRIORITY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_VERSIONS #
	<div class="form-element">
		{@bugs.labels.fields.detected_in}
		<div class="form-field">{DETECTED_IN}</div>
	</div>
	# ENDIF #
	<div class="form-element">
		{@bugs.labels.fields.reproductible}
		<div class="form-field"># IF C_REPRODUCTIBLE #${LangLoader::get_message('yes', 'main')}# ELSE #${LangLoader::get_message('no', 'main')}# ENDIF #</div>
	</div>
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

# INCLUDE COMMENTS #
