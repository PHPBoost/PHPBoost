<menu class="dynamic-menu group center">
	<ul>
		# IF C_FIX_BUG #
			<li><a href="{U_FIX}" title="{@bugs.actions.fix}"><i class="fa fa-wrench"></i> {@bugs.actions.fix}</a></li>
		# ENDIF #
		# IF C_PENDING_BUG #
			<li><a href="{U_PENDING}" title="{@bugs.actions.pending}"><i class="fa fa-clock-o"></i> {@bugs.actions.pending}</a></li>
		# ENDIF #
		# IF C_ASSIGN_BUG #
			<li><a href="{U_ASSIGN}" title="{@bugs.actions.assign}"><i class="fa fa-user"></i> {@bugs.actions.assign}</a></li>
		# ENDIF #
		# IF C_REOPEN_BUG #
			<li><a href="{U_REOPEN}" title="{@bugs.actions.reopen}"><i class="fa fa-eye"></i> {@bugs.actions.reopen}</a></li>
		# ENDIF #
		# IF C_REJECT_BUG #
			<li><a href="{U_REJECT}" title="{@bugs.actions.reject}"><i class="fa fa-eye-slash"></i> {@bugs.actions.reject}</a></li>
		# ENDIF #
			<li><a href="{U_HISTORY}" title="{@bugs.actions.history}"><i class="fa fa-info"></i> {@bugs.actions.history}</a></li>
		# IF C_EDIT_BUG #
			<li><a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'main')}"><i class="fa fa-edit"></i></a></li>
		# ENDIF #
		# IF C_DELETE_BUG #
			<li><a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'main')}"><i class="fa fa-delete"></i></a></li>
		# ENDIF #
	</ul>
</menu>

<fieldset>
	<legend>
		{@bugs.titles.bugs_treatment_state}
	</legend>
	# IF C_PROGRESS #
	<div class="form-element">
		<label>{@bugs.labels.fields.progress}</label>
		<div class="form-field" style="width: 25%;">
			{PROGRESS}%
			<div class="progressbar-container">
				<div class="progressbar" style="width:{PROGRESS}%"></div>
			</div>
			</div>
		</div>
	# ENDIF #
	<div class="form-element">
		<label>{@bugs.labels.fields.status}</label>
		<div class="form-field">{STATUS}</div>
	</div>
	<div class="form-element">
		<label>{@bugs.labels.fields.assigned_to_id}</label>
		<div class="form-field"># IF USER_ASSIGNED #<a href="{LINK_USER_ASSIGNED_PROFILE}" class="small_link {USER_ASSIGNED_LEVEL_CLASS}" # IF C_USER_ASSIGNED_GROUP_COLOR # style="color:{USER_ASSIGNED_GROUP_COLOR}" # ENDIF #>{USER_ASSIGNED}</a># ELSE #{@bugs.notice.no_one}# ENDIF #</div>
	</div>
	# IF C_FIXED_IN #
	<div class="form-element">
		<label>{@bugs.labels.fields.fixed_in}</label>
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
		<label>{@bugs.labels.fields.type}</label>
		<div class="form-field">{TYPE}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_CATEGORIES #
	<div class="form-element">
		<label>{@bugs.labels.fields.category}</label>
		<div class="form-field">{CATEGORY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_SEVERITIES #
	<div class="form-element">
		<label>{@bugs.labels.fields.severity}</label>
		<div class="form-field">{SEVERITY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_PRIORITIES #
	<div class="form-element">
		<label>{@bugs.labels.fields.priority}</label>
		<div class="form-field">{PRIORITY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_VERSIONS #
	<div class="form-element">
		<label>{@bugs.labels.fields.detected_in}</label>
		<div class="form-field">{DETECTED_IN}</div>
	</div>
	# ENDIF #
	<div class="form-element">
		<label>{@bugs.labels.fields.reproductible}</label>
		<div class="form-field"># IF C_REPRODUCTIBLE #${LangLoader::get_message('yes', 'main')}# ELSE #${LangLoader::get_message('no', 'main')}# ENDIF #</div>
	</div>
</fieldset>

# IF C_REPRODUCTION_METHOD #
<fieldset>
	<legend>{@bugs.labels.fields.reproduction_method}</legend>
	<div class="content">{REPRODUCTION_METHOD}</div>
</fieldset>
# ENDIF #

<div class="text_small float-right">
	{@bugs.labels.fields.author_id} # IF AUTHOR #<a href="{U_AUTHOR_PROFILE}" class="small_link {AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #${LangLoader::get_message('guest', 'main')}# ENDIF #, ${LangLoader::get_message('on', 'main')} # IF C_IS_DATE_FORM_SHORT #{SUBMIT_DATE_SHORT}# ELSE #{SUBMIT_DATE}# ENDIF #
</div>

<div class="spacer">&nbsp;</div>

# INCLUDE COMMENTS #
