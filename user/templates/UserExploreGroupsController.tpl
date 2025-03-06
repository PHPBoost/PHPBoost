# IF C_ONE_GROUP #
	<section id="module-user-group-list">
		<header class="section-header">
			<div class="controls align-right">
				{@user.members.list} {@user.group.of.group}
				# IF C_ADMIN #
					<a class="offload" href="{U_ADMIN_GROUPS}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
				# ENDIF #
			</div>
			<h1>
				{GROUP_NAME} ({NUMBER_MEMBERS})
			</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="cell-flex cell-tile cell-columns-2 user-card">
					# IF C_NO_MEMBERS #
						<span class="text-strong">{@user.no.member}</span>
					# ELSE #
						# START members_list #
							<article class="user-groups-item several-item cell">
								<header class="cell-header">
									<h5 class="cell-name"><a href="{members_list.U_PROFILE}" class="{members_list.LEVEL_CLASS} offload" # IF members_list.C_GROUP_COLOR # style="color:{members_list.GROUP_COLOR}" # ENDIF #>{members_list.PSEUDO}</a></h5>
									# IF members_list.C_AVATAR #<img class="user-card-avatar" src="{members_list.U_AVATAR}" alt="{members_list.PSEUDO}" /># ENDIF #
								</header>
								<div class="cell-list">
									<ul>
										<li>{members_list.LEVEL}</li>
										# IF C_EXTENDED_FIELDS #
											# START members_list.extended_fields #
												<li><span class="text-strong">{members_list.extended_fields.NAME}</span> : {members_list.extended_fields.VALUE}</li>
											# END members_list.extended_fields #
										# ENDIF #
									</ul>
								</div>
							</article>
						# END members_list #
					# ENDIF #
				</div>
			</div>
		</div>
		<footer>
			<div class="sub-section">
				<div class="content-container">
					<div class="content align-center">
						<a href="{U_GROUP_LIST}" class="button offload">{@user.groups.list}</a>
					</div>
				</div>
			</div>
		</footer>
	</section>
# ELSE #
	<section id="module-user-groups-list" class="groups-list-container">
		<header class="section-header">
			# IF C_HAS_GROUP #
				<span class="groups-list-title">{@user.groups.select}</span>
				<div class="groups-list-select">
					# START group #
						# IF group.C_GROUP_HAS_IMG #
							<a href="#" id="group-button-{group.GROUP_ID}" class="group-button group-has-img" onclick="open_group({group.GROUP_ID});return false;"><img alt="{group.GROUP_NAME}" src="{group.U_GROUP_IMG}" /></a>
						# ELSE #
							<a href="#" id="group-button-{group.GROUP_ID}" class="button group-button group-without-img" onclick="open_group({group.GROUP_ID});return false;">{group.GROUP_NAME}</a>
						# ENDIF #
					# END group #
				</div>
			# ENDIF #
		</header>
		<div class="sub-section">
			<div class="content-container">
				<div class="group-container">
					# IF C_HAS_ADMINS #
						<section id="list-members-container-admin" class="list-admins-container list-members-container selected">
							<header>
								<h2>
									<span class="list-members-container-action">
										<a href="#" onclick="open_group('admin', 0);return false;" aria-label="{@user.group.hide.list.members}" class="action-less"><i class="fa fa-minus" aria-hidden="true"></i></a>
										<a href="#" onclick="open_group('admin', 1);return false;" aria-label="{@user.group.view.list.members}" class="action-more"><i class="fa fa-plus" aria-hidden="true"></i></a>
									</span>
									{@user.admins.list} <span class="small">({NUMBER_ADMINS})</span>
								</h2>
							</header>
							<div class="cell-flex cell-tile cell-columns-2 user-card">
								# START admins_list #
									<article class="user-groups-item several-items cell">
										<header class="cell-header">
											<h5 class="cell-name"><a href="{admins_list.U_PROFILE}" class="{admins_list.LEVEL_CLASS} offload" # IF admins_list.C_GROUP_COLOR # style="color:{admins_list.GROUP_COLOR}" # ENDIF #>{admins_list.PSEUDO}</a></h5>
											# IF admins_list.C_AVATAR #<img class="user-card-avatar" src="{admins_list.U_AVATAR}" alt="{admins_list.PSEUDO}" /># ENDIF #
										</header>
										<div class="cell-list">
											<ul>
												<li>{admins_list.LEVEL}</li>
												# IF C_EXTENDED_FIELDS #
													# START admins_list.extended_fields #
														<li><span class="text-strong">{admins_list.extended_fields.NAME}</span> : {admins_list.extended_fields.VALUE}</li>
													# END admins_list.extended_fields #
												# ENDIF #
											</ul>
										</div>
									</article>
								# END admins_list #
							</div>
							<footer></footer>
						</section>
					# ENDIF #
					# IF C_HAS_MODOS #
						<section id="list-members-container-modo" class="list-modos-container list-members-container selected">
							<header>
								<h2>
									<span class="list-members-container-action">
										<a href="#" onclick="open_group('modo', 0);return false;" aria-label="{@user.group.hide.list.members}" class="action-less"><i class="fa fa-minus" aria-hidden="true"></i></a>
										<a href="#" onclick="open_group('modo', 1);return false;" aria-label="{@user.group.view.list.members}" class="action-more"><i class="fa fa-plus" aria-hidden="true"></i></a>
									</span>
									{@user.modos.list} <span class="small">({NUMBER_MODOS})</span>
								</h2>
							</header>
							<div class="cell-flex cell-tile cell-columns-2 user-card">
								# START modos_list #
									<article class="user-groups-item several-items cell">
										<header class="cell-header">
											<h5 class="cell-name"><a href="{modos_list.U_PROFILE}" class="{modos_list.LEVEL_CLASS} offload" # IF modos_list.C_GROUP_COLOR # style="color:{modos_list.GROUP_COLOR}" # ENDIF #>{modos_list.PSEUDO}</a></h5>
											# IF modos_list.C_AVATAR #<img class="user-card-avatar" src="{modos_list.U_AVATAR}" alt="{modos_list.PSEUDO}" /># ENDIF #
										</header>
										<div class="cell-list">
											<ul>
												<li>{modos_list.LEVEL}</li>
												# IF C_EXTENDED_FIELDS #
													# START modos_list.extended_fields #
														<li><span class="text-strong">{modos_list.extended_fields.NAME}</span> : {modos_list.extended_fields.VALUE}</li>
													# END modos_list.extended_fields #
												# ENDIF #
											</ul>
										</div>
									</article>
								# END modos_list #
							</div>
							<footer></footer>
						</section>
					# ENDIF #

					# START group #
						<section id="list-members-container-{group.GROUP_ID}" class="list-group-container list-members-container">
							<header>
								<h2>
									<span class="list-members-container-action">
										<a href="#" onclick="open_group({group.GROUP_ID}, 0);return false;" aria-label="{@user.group.hide.list.members}" class="action-less"><i class="fa fa-minus" aria-hidden="true"></i></a>
										<a href="#" onclick="open_group({group.GROUP_ID}, 1);return false;" aria-label="{@user.group.view.list.members}" class="action-more"><i class="fa fa-plus" aria-hidden="true"></i></a>
									</span>
									<a href="{group.U_GROUP}" class="group-name">{group.GROUP_NAME} <span class="small">({group.NUMBER_MEMBERS})</span></a>
									# IF C_ADMIN #
										<a class="offload" href="{group.U_ADMIN_GROUPS}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a>
									# ENDIF #
								</h2>
							</header>
							<div class="cell-flex cell-tile cell-columns-2 user-card">
								# IF group.C_HAS_MEMBERS #
									# START group.group_members_list #
										<article class="user-groups-item several-items cell">
											<header class="cell-header">
												<h5 class="cell-name"><a href="{group.group_members_list.U_PROFILE}" class="{group.group_members_list.LEVEL_CLASS} offload" # IF group.group_members_list.C_GROUP_COLOR # style="color:{group.group_members_list.GROUP_COLOR}" # ENDIF #>{group.group_members_list.PSEUDO}</a></h5>
												# IF group.group_members_list.C_AVATAR #<img class="user-card-avatar" src="{group.group_members_list.U_AVATAR}" alt="{group.group_members_list.PSEUDO}" /># ENDIF #
											</header>
											<div class="cell-list">
												<ul>
													<li>{group.group_members_list.LEVEL}</li>
													# IF C_EXTENDED_FIELDS #
														# START group.group_members_list.extended_fields #
															<li><span class="text-strong">{group.group_members_list.extended_fields.NAME}</span> : {group.group_members_list.extended_fields.VALUE}</li>
														# END group.group_members_list.extended_fields #
													# ENDIF #
												</ul>
											</div>
										</article>
									# END group.group_members_list #
								# ELSE #
									{@user.no.member}
								# ENDIF #
							</div>
							<footer></footer>
						</section>
					# END group #
				</div>
			</div>
		</div>
		<footer></footer>
	</section>

	<script>
		function open_group(myid, mytype)
		{
			var myclass = 'selected';
			var mytype = (typeof mytype !== 'undefined') ? mytype : 2;

			if ((jQuery('#list-members-container-' + myid).hasClass(myclass) && mytype == 2 ) || mytype == 0)
			{
				if (typeof myid == 'number')
					jQuery('#group-button-' + myid).removeClass(myclass);
				jQuery('#list-members-container-' + myid).removeClass('reorder-top');
				jQuery('#list-members-container-' + myid).removeClass(myclass);

			}
			else {
				if (typeof myid == 'number')
					jQuery('#group-button-' + myid).addClass(myclass);
				if (mytype == 2)
					jQuery('#list-members-container-' + myid).addClass('reorder-top');
				jQuery('#list-members-container-' + myid).addClass(myclass);
			}
		}
	</script>
# ENDIF #
