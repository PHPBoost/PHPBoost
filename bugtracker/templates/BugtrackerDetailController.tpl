<div class="more">
	<span class="pinned item-author">{@common.by} # IF C_AUTHOR_EXISTS #<a href="{U_AUTHOR_PROFILE}" class="{AUTHOR_LEVEL_CLASS} offload" itemprop="author" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #{AUTHOR}# ENDIF #</span>
	<span class="pinned item-creation-date">{@common.on.date} {SUBMIT_DATE_FULL}</span>
</div>
<nav id="cssmenu-bugtrackeractions" class="cssmenu cssmenu-group">
	<ul>
		# IF C_CHANGE_STATUS #
			<li><a href="{U_CHANGE_STATUS}" class="cssmenu-title offload"><i class="fa fa-fw fa-cogs" aria-hidden="true"></i><span>{@bugtracker.change.status}</span></a></li>
		# ENDIF #
		<li><a href="{U_HISTORY}" class="cssmenu-title offload"><i class="fa fa-fw fa-history" aria-hidden="true"></i> <span>{@bugtracker.history}</span></a></li>
		# IF C_EDIT_BUG #
			<li><a href="{U_EDIT}" aria-label="{@common.edit}" class="cssmenu-title cssmenu-title-icon offload"><i class="far fa-fw fa-edit" aria-hidden="true"></i></a></li>
		# ENDIF #
		# IF C_DELETE_BUG #
			<li><a href="{U_DELETE}" aria-label="{@common.delete}" class="cssmenu-title cssmenu-title-icon offload"><i class="far fa-fw fa-trash-alt" aria-hidden="true"></i></a></li>
		# ENDIF #
	</ul>
</nav>
<script>
	jQuery("#cssmenu-bugtrackeractions").menumaker({
		title: ${escapejs(@common.options)},
		format: "multitoggle",
		breakpoint: 768
	});
</script>

<div class="cell-flex cell-columns-2 cell-tile">
	<div class="cell">
		<div class="cell-header">
			<h6 class="cell-name">{@bugtracker.processing.status}</h6>
		</div>
		<div class="cell-list">
			<ul>
				# IF C_PROGRESS #
					<li class="li-stretch">
						<span>{@bugtracker.progress}</span>
						<div class="cell-progressbar">
							<div class="progressbar-container" role="progressbar" aria-valuenow="{PROGRESS}" aria-valuemin="0" aria-valuemax="100">
								<div class="progressbar-infos">{PROGRESS}%</div>
								<div class="progressbar" style="width:{PROGRESS}%"></div>
							</div>
						</div>
					</li>
				# ENDIF #
				<li class="li-stretch">
					<span>{@common.status}</span>
					<span>{STATUS}</span>
				</li>
				<li class="li-stretch">
					<span>{@bugtracker.assigned}</span>
					<span># IF C_USER_ASSIGNED #<a href="{LINK_USER_ASSIGNED_PROFILE}" class="{USER_ASSIGNED_LEVEL_CLASS} offload" # IF C_USER_ASSIGNED_GROUP_COLOR # style="color:{USER_ASSIGNED_GROUP_COLOR}" # ENDIF #>{USER_ASSIGNED}</a># ELSE #{@common.nobody}# ENDIF #</span>

				</li>
				# IF C_FIXED_IN #
					<li class="li-stretch">
						<span>{@bugtracker.solved.in}</span>
						<span>{FIXED_IN}</span>
					</li>
				# ENDIF #
			</ul>
		</div>
	</div>
	<div class="cell">
		<div class="cell-header">
			<h6 class="cell-name">{@bugtracker.infos}</h6>
		</div>
		<div class="cell-list">
			<ul>
				# IF C_TYPES #
					<li class="li-stretch">
						<span>{@common.type}</span>
						<span>{TYPE}</span>
					</li>
				# ENDIF #
				# IF C_CATEGORIES #
					<li class="li-stretch">
						<span>{@common.category}</span>
						<span>{CATEGORY}</span>
					</li>
				# ENDIF #
				# IF C_SEVERITIES #
					<li class="li-stretch">
						<span>{@bugtracker.severity}</span>
						<span>{SEVERITY}</span>
					</li>
				# ENDIF #
				# IF C_PRIORITIES #
					<li class="li-stretch">
						<span>{@bugtracker.priority}</span>
						<span>{PRIORITY}</span>
					</li>
				# ENDIF #
				# IF C_VERSIONS #
					<li class="li-stretch">
						<span>{@common.version}</span>
						<span>{DETECTED_IN}</span>
					</li>
				# ENDIF #
				<li class="li-stretch">
					<span>{@bugtracker.reproducible}</span>
					<span># IF C_REPRODUCTIBLE #{@common.yes}# ELSE #{@common.no}# ENDIF #</span>
				</li>
			</ul>
		</div>
	</div>
</div>

<article>
	<header>
		<h2><span class="small">{@common.title} :</span> {TITLE}</h2>
	</header>
	{CONTENT}
</article>

# IF C_REPRODUCTION_METHOD #
	<article>
		<header>
			<h2>{@bugtracker.reproduction.method}</h2>
		</header>
		{REPRODUCTION_METHOD}
	</article>
# ENDIF #

# IF C_ENABLED_COMMENTS #
	<aside>
		# INCLUDE COMMENTS #
	</aside>
# ENDIF #
