<div class="wiki-tools-container">
	<nav id="cssmenu-wikitools" class="cssmenu cssmenu-right cssmenu-actionslinks cssmenu-tools">
		<ul class="level-0 hidden">
			# IF C_ACTIVE_COMMENTS #
				<li>
					<a href="{U_COMMENTS}" class="cssmenu-title offload">
						<i class="fa fa-fw fa-comments" aria-hidden="true"></i>
						<span>{@common.comments} ({COMMENTS_NUMBER})</span>
					</a>
				</li>
			# ENDIF #
			<li>
				<a href="{U_HISTORY}" class="cssmenu-title offload">
					<i class="fa fa-fw fa-reply" aria-hidden="true"></i>
					<span>{@wiki.history}</span>
				</a>
			</li>
			# IF C_INDEX_PAGE #
				# IF IS_ADMIN #
					<li>
						<a href="{U_EDIT_INDEX}" class="cssmenu-title offload">
							<i class="far fa-fw fa-edit" aria-hidden="true"></i>
							<span>{@wiki.update.index}</span>
						</a>
					</li>
				# ENDIF #
				<li>
					<a href="{U_RANDOM}" class="cssmenu-title offload">
						<i class="fa fa-fw fa-random" aria-hidden="true"></i>
						<span>{@wiki.random.page}</span>
					</a>
				</li>
			# ELSE #
				# IF C_EDIT #
					<li>
						<a href="{U_EDIT}" class="cssmenu-title offload">
							<i class="far fa-fw fa-edit" aria-hidden="true"></i>
							<span>{@common.modify}</span>
						</a>
					</li>
				# ENDIF #
				# IF C_DELETE #
					<li>
						<a href="{U_DELETE}" data-confirmation="delete-element" class="cssmenu-title">
							<i class="far fa-fw fa-trash-alt" aria-hidden="true"></i>
							<span>{@common.delete}</span>
						</a>
					</li>
				# ENDIF #
				# IF C_RENAME #
					<li>
						<a href="{U_RENAME}" class="cssmenu-title offload">
							<i class="fa fa-fw fa-magic" aria-hidden="true"></i>
							<span>{@common.rename}</span>
						</a>
					</li>
				# ENDIF #
				# IF C_REDIRECT #
					<li>
						<a href="{U_REDIRECT}" class="cssmenu-title offload">
							<i class="fa fa-fw fa-fast-forward" aria-hidden="true"></i>
							<span>{@wiki.redirections}</span>
						</a>
					</li>
				# ENDIF #
				# IF C_MOVE #
					<li>
						<a href="{U_MOVE}" class="cssmenu-title offload">
							<i class="fa fa-fw fa-share" aria-hidden="true"></i>
							<span>{@common.move}</span>
						</a>
					</li>
				# ENDIF #
				# IF C_STATUS #
					<li>
						<a href="{U_STATUS}" class="cssmenu-title offload">
							<i class="fa fa-fw fa-tasks" aria-hidden="true"></i>
							<span>{@wiki.define.status}</span>
						</a>
					</li>
				# ENDIF #
				# IF C_RESTRICTION #
					<li>
						<a href="{U_RESTRICTION}" class="cssmenu-title offload">
							<i class="fa fa-fw fa-lock" aria-hidden="true"></i>
							<span>{@wiki.restriction.level}</span>
						</a>
					</li>
				# ENDIF #
				# IF IS_USER_CONNECTED #
					<li>
						<a href="{U_TRACK}" class="cssmenu-title offload">
							<i class="fa fa-fw fa-heart" aria-hidden="true"></i>
							<span>{L_TRACK}</span>
						</a>
					</li>
				# ENDIF #
				<li>
					<a href="{U_PRINT}" class="cssmenu-title offload">
						<i class="fa fa-fw fa-print" aria-hidden="true"></i>
						<span>{@common.print}</span>
					</a>
				</li>
			# ENDIF #
		</ul>
	</nav>
	<script>
		jQuery("#cssmenu-wikitools").menumaker({
			title: "{@wiki.tools}",
			format: "multitoggle",
			breakpoint: 768
		});
		jQuery(document).ready(function() {
			jQuery("#cssmenu-wikitools ul").removeClass('hidden');
		});
	</script>
</div>
<div class="spacer"></div>
