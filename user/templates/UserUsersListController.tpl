<section id="module-user-users-list">
	<header class="section-header">
		<h1>{@user.users}</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				<form id="UserUsersListController" class="fieldset-content">
					<fieldset id="UserUsersListController_search_member">
						<legend>{@user.search.member}</legend>
						<div class="fieldset-inset">
							<div id="UserUsersListController_member_field" class="form-element">
								<label for="UserUsersListController_member">
									{@user.display.name}
								</label>
								<div id="onblurContainerResponseUserUsersListController_member" class="form-field form-field-text picture-status-constraint">
									<input data-listorder-control="textbox-filter" data-group="users-items" data-path=".lo-name" size="30" maxlength="255" type="text" value="">
								</div>
							</div>
						</div>
					</fieldset>
				</form>
				<div class="cell-flex cell-tile cell-columns-2">
					<div class="listorder-type-filter cell">
						<div class="cell-body">
							<div class="cell-content">
								<span>{@user.groups.select} :</span>
								<div class="type-filter-radio">
									<div class="selected-label">
										<span>{@user.groups.all}</span> <i class="fa fa-fw fa-caret-down" aria-hidden="true"></i>
									</div>
									<div class="label-list dropdown-container">
										<label class="listorder-label" for="default-radio">
											<input
												id="default-radio"
												type="radio"
												data-listorder-control="radio-buttons-path-filter"
												data-path="default"
												data-group="users-items"
												name="groups-filter"
												checked /> {@user.groups.all}
										</label>
										<label class="listorder-label" for="is-administrator">
											<input
												id="is-administrator"
												type="radio"
												data-listorder-control="radio-buttons-path-filter"
												data-path=".is-administrator"
												data-group="users-items"
												name="groups-filter"
												value="{@user.administrators}"/>{@user.administrators}
										</label>
										<label class="listorder-label" for="is-moderator">
											<input
												id="is-moderator"
												type="radio"
												data-listorder-control="radio-buttons-path-filter"
												data-path=".is-moderator"
												data-group="users-items"
												name="groups-filter"
												value="{@user.moderators}"/>{@user.moderators}
										</label>
										# START groups #
											<label class="listorder-label" for="{groups.GROUP_NAME_FILTER}">
												<input
													id="{groups.GROUP_NAME_FILTER}"
													type="radio"
													data-listorder-control="radio-buttons-path-filter"
													data-path=".{groups.GROUP_NAME_FILTER}"
													data-group="users-items"
													name="groups-filter"
													value="{groups.GROUP_NAME}"/>{groups.GROUP_NAME}
											</label>
										# END groups #
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- sort dropdown -->
					<div class="sort-list cell">
						<div class="cell-body">
							<div class="cell-content">
								<span>{@common.sort.by} :</span>
								<div
									data-listorder-control="dropdown-sort"
									class="listorder-drop-down"
									data-group="users-items"
									data-name="sorttitle">
									<div data-type="panel" class="listorder-dd-panel"></div>
									<ul data-type="content" class="dropdown-container">
										<li> {@user.display.name}
											<em class="sort-type bgc-full link-color" data-path=".lo-name" data-order="asc" data-type="text" data-selected="true"><span class="sr-only">{@user.display.name} &#8593;</span> <i class="fa fa-sort-alpha-up-alt"></i></em>
											<em class="sort-type bgc-full logo-color" data-path=".lo-name" data-order="desc" data-type="text"><span class="sr-only">{@user.display.name} &#8595;</span> <i class="fa fa-sort-alpha-down-alt"></i></em>
										</li>
										<li> {@user.registration.date}
											<em class="sort-type bgc-full link-color" data-path=".lo-registration-date" data-order="asc" data-type="number"><span class="sr-only">{@user.registration.date} &#8593;</span> <i class="fa fa-sort-numeric-up-alt"></i></em>
											<em class="sort-type bgc-full logo-color" data-path=".lo-registration-date" data-order="desc" data-type="number"><span class="sr-only">{@user.registration.date} &#8595;</span> <i class="fa fa-sort-numeric-down-alt"></i></em>
										</li>
										<li> {@user.last.connection}
											<em class="sort-type bgc-full link-color" data-path=".lo-last-connection" data-order="asc" data-type="number"><span class="sr-only">{@user.last.connection} &#8593;</span> <i class="fa fa-sort-numeric-up-alt"></i></em>
											<em class="sort-type bgc-full logo-color" data-path=".lo-last-connection" data-order="desc" data-type="number"><span class="sr-only">{@user.last.connection} &#8595;</span> <i class="fa fa-sort-numeric-down-alt"></i></em>
										</li>
										<li> {@user.publications}
											<em class="sort-type bgc-full link-color" data-path=".lo-publications-number" data-order="asc" data-type="number"><span class="sr-only">{@user.publications} &#8593;</span> <i class="fa fa-sort-numeric-up-alt"></i></em>
											<em class="sort-type bgc-full logo-color" data-path=".lo-publications-number" data-order="desc" data-type="number"><span class="sr-only">{@user.publications} &#8595;</span> <i class="fa fa-sort-numeric-down-alt"></i></em>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>

				# IF C_TABLE_VIEW #
					<div class="spacer"></div>
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th>{@user.display.name}</th>
									<th>{@user.registration.date}</th>
									<th>{@user.last.connection}</th>
									<th>{@user.publications}</th>
									<th>{@user.contact}</th>
									<th>{@user.groups}</th>
									# IF IS_ADMIN #<th></th># ENDIF #
								</tr>
							</thead>
							<tbody data-listorder-group="users-items">
								# START users #
									<tr data-listorder-item>
										<td class="lo-name">
											<a href="{users.U_PROFILE}"# IF users.C_IS_GROUP # style="color: {users.GROUP_COLOR};"# ELSE # class="{users.LEVEL_COLOR} offload"# ENDIF #>{users.DISPLAYED_NAME}</a>
										</td>
										<td>{users.REGISTRATION_DATE}<span class="lo-registration-date hidden">{users.REGISTRATION_DATE_TIMESTAMP}</span></td>
										<td>{users.LAST_CONNECTION}<span class="lo-last-connection hidden">{users.LAST_CONNECTION_TIMESTAMP}</span></td>
										<td class="lo-publications-number" aria-label="# START users.modules # {users.modules.MODULE_NAME}: {users.modules.MODULE_PUBLICATIONS_NUMBER}<br /># END users.modules #">
											<a class="offload" href="{users.U_PUBLICATIONS}" aria-label="{@user.view.publications}">{users.PUBLICATIONS_NUMBER}</a>
										</td>
										<td>
											<a href="{users.U_MP}" class="pinned bgc-full notice offload" aria-label="{@user.private.message}"}><i class="fa fa-fw fa-people-arrows"></i></a>
											# IF users.C_ENABLED_EMAIL #
												<span><a href="mailto:{users.U_EMAIL}" class="pinned bgc-full member" aria-label="{@user.email}"><i class="iboost fa fa-iboost-email"></i></a></span>
											# ENDIF #
											# IF users.C_HAS_WEBSITE #
												<a href="{users.U_WEBSITE}" class="pinned bgc-full link-color offload" aria-label="{@form.website}"><i class="fa fa-globe"></i></a>
											# ENDIF #
										</td>
										<td>
											# IF users.C_CONTROLS #<span class="pinned small is-{users.LEVEL_COLOR}">{users.RANK_LEVEL}</span># ENDIF #
											# START users.groups #
												<span class="pinned small {users.groups.GROUP_NAME_FILTER}" data-color-surround="{users.groups.GROUP_COLOR}">{users.groups.GROUP_NAME}</span><br />
											# END users.groups #
										</td>
										# IF IS_ADMIN #
											<td>
												<a class="offload" href="{users.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit"></i></a>
												# IF users.C_DELETE #<a href="{users.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt"></i></a># ENDIF #
											</td>
										# ENDIF #
									</tr>
								# END users #
								<tr class="no-result hidden">
									<td colspan="# IF IS_ADMIN #7# ELSE #6# ENDIF #"><div class="message-helper bgc notice">{@common.no.item.now}</div></td>
								</tr>
							</tbody>
						</table>
					</div>
				# ELSE #
					<div class="cell-flex cell-tile cell-columns-2 user-card " data-listorder-group="users-items">
						# START users #
							<article data-listorder-item class="cell">
								<header class="cell-header">
									<h5 class="cell-name">
										<a href="{users.U_PROFILE}"# IF users.C_IS_GROUP # style="color: {users.GROUP_COLOR};"# ELSE # class="{users.LEVEL_COLOR} is-{users.LEVEL_COLOR} lo-name offload"# ENDIF #>{users.DISPLAYED_NAME}</a>
										# IF users.C_CONTROLS #<span class="description-field smaller">{users.RANK_LEVEL}</span># ENDIF #
									</h5>
									# IF C_ENABLED_AVATAR #<img class="user-card-avatar" src="{users.U_AVATAR}" alt="{users.DISPLAYED_NAME}"># ENDIF #
								</header>
								<div class="cell-list">
									<ul>
										<li class="li-stretch">
											<span class="small">{@user.registration.date}<span class="lo-registration-date hidden">{users.REGISTRATION_DATE_TIMESTAMP}</span></span>
											<span>{users.REGISTRATION_DATE}</span>
										</li>
										<li class="li-stretch">
											<span class="small">{@user.last.connection}<span class="lo-last-connection hidden">{users.LAST_CONNECTION_TIMESTAMP}</span></span>
											<span>{users.LAST_CONNECTION}</span>
										</li>
										<li class="li-stretch lo-publications-number" aria-label="# START users.modules # {users.modules.MODULE_NAME}: {users.modules.MODULE_PUBLICATIONS_NUMBER}<br /># END users.modules #">
											<span class="small">{@user.publications}</span>
											<a class="offload" href="{users.U_PUBLICATIONS}" aria-label="{@user.view.publications}">{users.PUBLICATIONS_NUMBER}</a>
										</li>
										<li class="li-stretch">
											<span class="small">{@user.contact}</span>
											<span>
												<a href="{users.U_MP}" class="pinned bgc-full notice offload" aria-label="{@user.private.message}"}><i class="fa fa-fw fa-people-arrows"></i></a>
												# IF users.C_ENABLED_EMAIL #
													<span><a href="mailto:{users.U_EMAIL}" class="pinned bgc-full member" aria-label="{@user.email}"><i class="iboost fa fa-iboost-email"></i></a></span>
												# ENDIF #
												# IF users.C_HAS_WEBSITE #
													<a href="{users.U_WEBSITE}" class="pinned bgc-full link-color offload" aria-label="{@form.website}"><i class="fa fa-globe"></i></a>
												# ENDIF #
											</span>
										</li>
										# IF users.C_HAS_GROUP #
											<li class="li-stretch">
												<span class="small">{@user.groups}</span>
												<span>
													# START users.groups #<span class="pinned small {users.groups.GROUP_NAME_FILTER}" data-color-surround="{users.groups.GROUP_COLOR}">{users.groups.GROUP_NAME}</span><br /># END users.groups #
												</span>
											</li>
										# ENDIF #
										# IF IS_ADMIN #
											<li class="li-stretch controls">
												<a class="offload" href="{users.U_EDIT}" aria-label="{@common.edit}"><i class="far fa-edit"></i></a>
												# IF users.C_DELETE #<a href="{users.U_DELETE}" aria-label="{@common.delete}" data-confirmation="delete-element"><i class="far fa-trash-alt"></i></a># ENDIF #
											</li>
										# ENDIF #
									</ul>
								</div>
							</article>
						# END users #
					</div>
					<div class="no-result hidden message-helper bgc notice"> {@common.no.item.now} </div>
				# ENDIF #
			</div>
		</div>
	</div>

	# IF C_PAGINATION #
		<div class="sub-section items-pagination">
			<div class="content-container">
				<nav
						class="pagination listorder-pagination"
						data-listorder-control="pagination"
						data-group="users-items"
						data-items-per-page="{ITEMS_PER_PAGE}"
						data-current-page="0"
						data-name="pagination1"
						data-name="paging">
					<p data-type="info" class="align-center">
						{@common.listorder.item.start} - {@common.listorder.item.end} / {@common.listorder.items.number} ${TextHelper::lcfirst(@user.users)}
					</p>
					<ul>
						<li class="pagination-item" data-type="first" aria-label="{@common.pagination.first}"><a href="#"><i class="fa fa-chevron-circle-left" aria-hidden="true"></i></a> </li>
						<li class="pagination-item" data-type="prev" aria-label="{@common.pagination.previous}"><a href="#"><i class="fa fa-chevron-left" aria-hidden="true"></i></a> </li>

						<ul class="listorder-holder" data-type="pages">
							<li class="pagination-item" data-type="page"><a href="#">{@common.listorder.page.number}</a></li>
						</ul>

						<li class="pagination-item" data-type="next" aria-label="{@common.pagination.next}"><a href="#"><i class="fa fa-chevron-right" aria-hidden="true"></i></a> </li>
						<li class="pagination-item" data-type="last" aria-label="{@common.pagination.last}"><a href="#"><i class="fa fa-chevron-circle-right" aria-hidden="true"></i></a> </li>
					</ul>
				</nav>
				<div class="align-center">
					<select data-type="items-per-page">
						<option value="{ITEMS_PER_PAGE}"> {ITEMS_PER_PAGE} {@common.pagination.per}</option>
						<option value="50"> 50 {@common.pagination.per}</option>
						<option value="100"> 100 {@common.pagination.per}</option>
						<option value="0"> {@user.members.all} </option>
					</select>
				</div>
			</div>
		</div>
	# ENDIF #
	<footer></footer>
</section>

<script>
	jQuery('document').ready(function(){
		// listorder
		listorder.init();

		jQuery('input[type=radio][name=groups-filter]').change(function(){
			var itemsNumber = jQuery('[data-listorder-item]').length,
				maxItems = {ITEMS_PER_PAGE};
			if (itemsNumber < 1) jQuery('.no-result').show();
			else jQuery('.no-result').hide();
			if (itemsNumber < maxItems) jQuery('.items-pagination').hide();
			else jQuery('.items-pagination').show();
		});

		// Filters
			// toggle sub-menu on click (close on click outside)
		jQuery('.selected-label').on('click', function(e){
			jQuery('.label-list').toggleClass('reveal-list');
			e.stopPropagation();
		});
		jQuery(document).click(function(e) {
			if (jQuery(e.target).is('.selected-label') === false) {
				jQuery('.label-list').removeClass('reveal-list');
			}
		});
			// send label text of selected input to title on click
		jQuery('.label-list input').on('click', function(e) {
			var radioText = e.currentTarget.nextSibling.data;
			jQuery('.selected-label span').html(radioText);
		});
	});
</script>
