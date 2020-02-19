# IF C_ONE_GROUP #
<section id="module-user-group-list">
	<header>
		<h1>
			{@members_list} {@group.of_group} {GROUP_NAME} ({NUMBER_MEMBERS})
			# IF C_ADMIN #
				<a href="{U_ADMIN_GROUPS}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a>
			# ENDIF #
			</h1>
	</header>
	<div class="content elements-container">
		# IF C_NO_MEMBERS #
		<span class="text-strong">{@no_member}</span>
		# ELSE #
			# START members_list #
			<article class="block user-card">
				<header></header>
				<div class="content">
					<div class="avatar-container">
					# IF members_list.C_AVATAR #<img class="valign-middle" src="{members_list.U_AVATAR}" alt="{members_list.PSEUDO}" /># ENDIF #
					</div>
					<div class="infos-container">
						<div class="user-level">{members_list.LEVEL}</div>
						<div class="user-pseudo">
							<a href="{members_list.U_PROFILE}" class="{members_list.LEVEL_CLASS}" # IF members_list.C_GROUP_COLOR # style="color:{members_list.GROUP_COLOR}" # ENDIF #>{members_list.PSEUDO}</a>
						</div>

					# IF C_EXTENDED_FIELDS #
					# START members_list.extended_fields #
						<div class="user-extended-field">{members_list.extended_fields.NAME} : {members_list.extended_fields.VALUE}</div>

					# END members_list.extended_fields #
					# ENDIF #
					</div>
				</div>
				<footer></footer>
			</article>
			# END members_list #
		# ENDIF #
	</div>
	<footer>
		<div class="align-center">
			<a href="{U_GROUP_LIST}" class="button">{@groups.list}</a>
		</div>
	</footer>
</section>

# ELSE #

<section id="module-user-groups-list" class="groups-list-container">
	<header>
		# IF C_HAS_GROUP #
		<span class="groups-list-title">{@groups.select}</span>
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
	<div class="content group-container">
		# IF C_HAS_ADMINS #
		<section id="list-members-container-admin" class="list-admins-container list-members-container selected">
			<header>
				<h2>
					<span class="list-members-container-action">
						<a href="#" onclick="open_group('admin', 0);return false;" aria-label="{@group.hide_list_members}" class="action-less"><i class="fa fa-minus" aria-hidden="true"></i></a>
						<a href="#" onclick="open_group('admin', 1);return false;" aria-label="{@group.view_list_members}" class="action-more"><i class="fa fa-plus" aria-hidden="true"></i></a>
					</span>
					{@admins.list} <span class="small">({NUMBER_ADMINS})</span>
				</h2>
			</header>
			<div class="content elements-container">
				# START admins_list #
				<article class="block user-card">
					<header></header>
					<div class="content">
						<div class="avatar-container">
							# IF admins_list.C_AVATAR #
							<img class="valign-middle" src="{admins_list.U_AVATAR}" alt="{admins_list.PSEUDO}" />
							# ENDIF #
						</div>
						<div class="infos-container">
							<div class="user-level">{admins_list.LEVEL}</div>
							<div class="user-pseudo">
								<a href="{admins_list.U_PROFILE}" class="{admins_list.LEVEL_CLASS}" # IF admins_list.C_GROUP_COLOR # style="color:{admins_list.GROUP_COLOR}" # ENDIF #>{admins_list.PSEUDO}</a>
							</div>

							# IF C_EXTENDED_FIELDS #
							# START admins_list.extended_fields #
							<div class="user-extended-field">{admins_list.extended_fields.NAME} : {admins_list.extended_fields.VALUE}</div>
							# END admins_list.extended_fields #
							# ENDIF #
						</div>
					</div>
					<footer></footer>
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
						<a href="#" onclick="open_group('modo', 0);return false;" aria-label="{@group.hide_list_members}" class="action-less"><i class="fa fa-minus" aria-hidden="true"></i></a>
						<a href="#" onclick="open_group('modo', 1);return false;" aria-label="{@group.view_list_members}" class="action-more"><i class="fa fa-plus" aria-hidden="true"></i></a>
					</span>
					{@modos.list} <span class="small">({NUMBER_MODOS})</span>
				</h2>
			</header>
			<div class="content elements-container">
				# START modos_list #
				<article class="block user-card">
					<header></header>
					<div class="content">
						<div class="avatar-container">
							# IF modos_list.C_AVATAR #
							<img class="valign-middle" src="{modos_list.U_AVATAR}" alt="{modos_list.PSEUDO}" />
							# ENDIF #
						</div>
						<div class="infos-container">
							<div class="user-level">{modos_list.LEVEL}</div>
							<div class="user-pseudo">
								<a href="{modos_list.U_PROFILE}" class="{modos_list.LEVEL_CLASS}" # IF modos_list.C_GROUP_COLOR # style="color:{modos_list.GROUP_COLOR}" # ENDIF #>{modos_list.PSEUDO}</a>
							</div>

							# IF C_EXTENDED_FIELDS #
							# START modos_list.extended_fields #
							<div class="user-extended-field">{modos_list.extended_fields.NAME} : {modos_list.extended_fields.VALUE}</div>
							# END modos_list.extended_fields #
							# ENDIF #
						</div>
					</div>
					<footer></footer>
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
						<a href="#" onclick="open_group({group.GROUP_ID}, 0);return false;" aria-label="{@group.hide_list_members}" class="action-less"><i class="fa fa-minus" aria-hidden="true"></i></a>
						<a href="#" onclick="open_group({group.GROUP_ID}, 1);return false;" aria-label="{@group.view_list_members}" class="action-more"><i class="fa fa-plus" aria-hidden="true"></i></a>
					</span>
					<a href="{group.U_GROUP}" class="group-name">{group.GROUP_NAME} <span class="small">({group.NUMBER_MEMBERS})</span></a>
					# IF C_ADMIN #
						<a href="{group.U_ADMIN_GROUPS}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="far fa-edit" aria-hidden="true"></i></a>
					# ENDIF #
				</h2>
			</header>
			<div class="content elements-container">
				# IF group.C_HAS_MEMBERS #
					# START group.group_members_list #
						<article class="block user-card">
							<header></header>
							<div class="content">
								<div class="avatar-container">
									# IF group.group_members_list.C_AVATAR #
									<img class="valign-middle" src="{group.group_members_list.U_AVATAR}" alt="{group.group_members_list.PSEUDO}" />
									# ENDIF #
								</div>
								<div class="infos-container">
									<div class="user-level">{group.group_members_list.LEVEL}</div>
									<div class="user-pseudo">
										<a href="{group.group_members_list.U_PROFILE}" class="{group.group_members_list.LEVEL_CLASS}" # IF group.group_members_list.C_GROUP_COLOR # style="color:{group.group_members_list.GROUP_COLOR}" # ENDIF #>{group.group_members_list.PSEUDO}</a>
									</div>

									# IF C_EXTENDED_FIELDS #
									# START group.group_members_list.extended_fields #
									<div class="user-extended-field">{group.group_members_list.extended_fields.NAME} : {group.group_members_list.extended_fields.VALUE}</div>
									# END group.group_members_list.extended_fields #
									# ENDIF #
								</div>
							</div>
							<footer></footer>
						</article>
					# END group.group_members_list #
				# ELSE #
					{@no_member}
				# ENDIF #
			</div>
			<footer></footer>
		</section>
		# END group #
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
