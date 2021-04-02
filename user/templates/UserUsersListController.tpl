<section id="module-user-users-list">
	<header class="section-header">
		<h1>{@users}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# INCLUDE FORM #

				# IF C_ARE_GROUPS #
					# INCLUDE SELECT_GROUP #
				# ENDIF #
				<div class="spacer"></div>
				# IF C_TABLE_VIEW #
					# INCLUDE TABLE #
				# ELSE #
						{USERS_NUMBER} users
					<div class="cell-flex cell-tile cell-columns-2 user-card">
						# START users #
							<article class="cell">
								<header class="cell-header">
									<h5 class="cell-name">
										<a href="{users.U_PROFILE}"# IF users.C_IS_GROUP # style="color: {users.GROUP_COLOR};"# ELSE # class="{users.LEVEL_COLOR}"# ENDIF #>{users.DISPLAYED_NAME}</a>
										# IF users.C_CONTROLS #<span class="description-field smaller">{users.RANK_LEVEL}</span># ENDIF #
									</h5>
									# IF C_ENABLED_AVATAR #<img class="user-card-avatar" src="{users.U_AVATAR}" alt="{users.DISPLAYED_NAME}"># ENDIF #
								</header>
								<div class="cell-list">
									<ul>
										<li class="li-stretch">
											<span class="small">{@registration_date}</span>
											<span>{users.REGISTRATION_DATE}</span>
										</li>
										<li class="li-stretch">
											<span class="small">{@last_connection}</span>
											<span>{users.LAST_CONNECTION}</span>
										</li>
										<li class="li-stretch" aria-label="# START users.modules # {users.modules.MODULE_NAME}: {users.modules.MODULE_CONTRIBUTIONS_NUMBER}<br /># END users.modules #">
											<span class="small">{@publications.number}</span>
											<span>{users.CONTRIBUTIONS_NUMBER}</span>
										</li>
										<li class="li-stretch">
											<span class="small">${LangLoader::get_message('contact', 'main')}</span>
											<span>
												<a href="{users.U_MP}" class="pinned bgc-full notice" aria-label="{@private_message}"}><i class="fa fa-fw fa-people-arrows"></i></a>
												# IF users.C_ENABLED_EMAIL #
													<span><a href="mailto:{users.U_EMAIL}" class="pinned bgc-full member" aria-label="{@email}"><i class="iboost fa fa-iboost-email"></i></a></span>
												# ENDIF #
												# IF users.C_HAS_WEBSITE #
													<a href="{users.U_WEBSITE}" class="pinned bgc-full link-color" aria-label="${LangLoader::get_message('regex.website', 'admin-user-common')}"><i class="fa fa-globe"></i></a>
												# ENDIF #
											</span>
										</li>
										# IF users.C_HAS_GROUP #
											<li class="li-stretch">
												<span class="small">${LangLoader::get_message('groups', 'main')}</span>
												<span>
													# START users.groups #<span class="pinned small" data-color-surround="{users.groups.GROUP_COLOR}">{users.groups.GROUP_NAME}</span><br /># END users.groups #
												</span>
											</li>
										# ENDIF #
									</ul>
								</div>
							</article>
						# END users #
					</div>
				# ENDIF #
			</div>
		</div>
	</div>
	<footer></footer>
</section>
