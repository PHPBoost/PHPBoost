<nav id="cssmenu-bugtrackeractions" class="cssmenu cssmenu-group">
	<ul>
		# IF C_CHANGE_STATUS #
			<li><a href="{U_CHANGE_STATUS}" class="cssmenu-title"><i class="fas fa-cogs" aria-hidden="true"></i> {@actions.change_status}</a></li>
		# ENDIF #
		<li><a href="{U_HISTORY}" class="cssmenu-title"><i class="fas fa-history" aria-hidden="true"></i> {@actions.history}</a></li>
		# IF C_EDIT_BUG #
			<li><a href="{U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}" class="cssmenu-title"><i class="far fa-edit" aria-hidden="true"></i></a></li>
		# ENDIF #
		# IF C_DELETE_BUG #
			<li><a href="{U_DELETE}" aria-label="${LangLoader::get_message('delete', 'common')}" class="cssmenu-title"><i class="fa fa-trash-alt" aria-hidden="true"></i></a></li>
		# ENDIF #
	</ul>
</nav>
<script>
	jQuery("#cssmenu-bugtrackeractions").menumaker({
		title: "${LangLoader::get_message('form.options', 'common')}",
		format: "multitoggle",
		breakpoint: 768
	});
</script>
<fieldset>
	<legend>
		{@titles.bugs_treatment_state} :
	</legend>
	# IF C_PROGRESS #
	<div class="form-element">
		<label>{@labels.fields.progress}</label>
		<div class="form-field form-field-progressbar">
			<div class="progressbar-container">
				<div class="progressbar-infos">{PROGRESS}%</div>
				<div class="progressbar" style="width:{PROGRESS}%"></div>
			</div>
			</div>
		</div>
	# ENDIF #
	<div class="form-element">
		<label>{@labels.fields.status}</label>
		<div class="form-field">{STATUS}</div>
	</div>
	<div class="form-element">
		<label>{@labels.fields.assigned_to_id}</label>
		<div class="form-field"># IF C_USER_ASSIGNED #<a href="{LINK_USER_ASSIGNED_PROFILE}" class="{USER_ASSIGNED_LEVEL_CLASS}" # IF C_USER_ASSIGNED_GROUP_COLOR # style="color:{USER_ASSIGNED_GROUP_COLOR}" # ENDIF #>{USER_ASSIGNED}</a># ELSE #{@notice.no_one}# ENDIF #</div>
	</div>
	# IF C_FIXED_IN #
	<div class="form-element">
		<label>{@labels.fields.fixed_in}</label>
		<div class="form-field">{FIXED_IN}</div>
	</div>
	# ENDIF #
</fieldset>

<fieldset>
	<legend><h1>{@labels.fields.title} : {TITLE}</h1></legend>
	<div class="content">{CONTENTS}</div>
</fieldset>

<fieldset>
	<legend>{@titles.bugs_infos} :</legend>
	# IF NOT C_EMPTY_TYPES #
	<div class="form-element">
		<label>{@labels.fields.type}</label>
		<div class="form-field">{TYPE}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_CATEGORIES #
	<div class="form-element">
		<label>{@labels.fields.category}</label>
		<div class="form-field">{CATEGORY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_SEVERITIES #
	<div class="form-element">
		<label>{@labels.fields.severity}</label>
		<div class="form-field">{SEVERITY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_PRIORITIES #
	<div class="form-element">
		<label>{@labels.fields.priority}</label>
		<div class="form-field">{PRIORITY}</div>
	</div>
	# ENDIF #
	# IF NOT C_EMPTY_VERSIONS #
	<div class="form-element">
		<label>{@labels.fields.version}</label>
		<div class="form-field">{DETECTED_IN}</div>
	</div>
	# ENDIF #
	<div class="form-element">
		<label>{@labels.fields.reproductible}</label>
		<div class="form-field"># IF C_REPRODUCTIBLE #${LangLoader::get_message('yes', 'common')}# ELSE #${LangLoader::get_message('no', 'common')}# ENDIF #</div>
	</div>
</fieldset>

# IF C_REPRODUCTION_METHOD #
<fieldset>
	<legend>{@labels.fields.reproduction_method}</legend>
	<div class="content">{REPRODUCTION_METHOD}</div>
</fieldset>
# ENDIF #

<div class="float-right">
	${LangLoader::get_message('by', 'common')} # IF C_AUTHOR_EXIST #<a href="{U_AUTHOR_PROFILE}" class="{AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #{AUTHOR}# ENDIF #, ${TextHelper::lcfirst(LangLoader::get_message('the', 'common'))} {SUBMIT_DATE_FULL}
</div>

<div class="spacer"></div>

# INCLUDE COMMENTS #
