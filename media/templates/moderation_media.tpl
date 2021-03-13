# IF C_DISPLAY #
	<section id="module-media">
		<header class="section-header">
			<h1>{L_MODO_PANEL}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<form action="moderation_media.php" method="post" class="fieldset-content media-filter">
					<fieldset>
						<legend class="sr-only">{L_FILTER}</legend>
						<div id="form-filter" class="align-center fieldset-inset">
							{L_DISPLAY_FILE}&nbsp;
							<select name="state" id="state" class="nav" onchange="change_order()">
								<option value="all"{SELECTED_ALL}>{L_ALL}</option>
								<option value="visible"{SELECTED_VISIBLE}>{L_FVISIBLE}</option>
								<option value="invisible"{SELECTED_INVISIBLE}>{L_INVISIBLE}</option>
								<option value="disapproved"{SELECTED_DISAPPROVED}>{L_FDISAPPROVED}</option>
							</select>
							<label for="show_sub_cats" class="checkbox">
								<span>{L_INCLUDE_SUB_CATS}</span>
								<input type="checkbox" id="show_sub_cats" name="sub_cats" value="1"{SUB_CATS}>
							</label>
						</div>
					</fieldset>
					<fieldset class="fieldset-submit">
						<legend>${LangLoader::get_message('submit', 'main')}</legend>
						<div class="fieldset-inset">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="filter" value="true" class="button submit">${LangLoader::get_message('submit', 'main')}</button>
							<button type="reset" class="button reset-button" value="true">${LangLoader::get_message('reset', 'main')}</button>
						</div>
					</fieldset>
				</form>

				<script>
					function check_all (type)
					{
						var item = new Array({JS_ARRAY});

						if (type == "delete")
						{
							if (confirm ('{L_CONFIRM_DELETE_ALL}'))
							{
								for (var i=0; i < item.length; i++)
									document.getElementById(type + item[i]).checked = 'checked';
							}
						}
						else
						{
							for (var i=0; i < item.length; i++)
								document.getElementById(type + item[i]).checked = 'checked';
						}
					}

					function pointer (id)
					{
						document.getElementById(id).style.cursor = 'pointer';
					}
				</script>
				<form action="moderation_media.php" method="post" class="fieldset-content">
					<div class="responsive-table">
						<table class="table">
							<thead>
								<tr>
									<th>
										${LangLoader::get_message('name', 'main')}
									</th>
									<th>
										${LangLoader::get_message('category', 'categories-common')}
									</th>
									<th onclick="check_all('visible');" onmouseover="pointer('visible');" id="visible">
										{@visible}
									</th>
									<th onclick="check_all('invisible');" onmouseover="pointer('invisible');" id="invisible">
										{@invisible}
									</th>
									<th>
										{@disapproved}
									</th>
									<th onclick="check_all('delete');" onmouseover="pointer('delete');" id="delete">
										${LangLoader::get_message('delete', 'common')}
									</th>
									<th>${LangLoader::get_message('edit', 'common')}</th>
								</tr>
							</thead>
							<tbody>
								# IF C_NO_MODERATION #
									<tr>
										<td colspan="6">{L_NO_MODERATION}</td>
									</tr>
								# ELSE #
									# START items #
										<tr>
											<td class="{items.COLOR}">
												<a href="{items.U_ITEM}">{items.TITLE}</a>
											</td>
											<td class="{items.COLOR}">
												<a href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
											</td>
											<td class="{items.COLOR}">
												<input type="radio" id="visible{items.ID}" name="action[{items.ID}]" value="visible"{items.VISIBLE}>
											</td>
											<td class="{items.COLOR}">
												<input type="radio" id="invisible{items.ID}" name="action[{items.ID}]" value="invisible"{items.INVISIBLE}>
											</td>
											<td class="{items.COLOR}">
												<input type="radio" name="action[{items.ID}]" value="disapproved"{items.DISAPPROVED} # IF NOT items.DISAPPROVED #disabled="disabled" # ENDIF #/>
											</td>
											<td class="{items.COLOR}">
												<input type="radio" id="delete{items.ID}" name="action[{items.ID}]" value="delete" data-confirmation="delete-element">
											</td>
											<td class="{items.COLOR}">
												<a href="{items.U_EDIT}" aria-label="${LangLoader::get_message('edit', 'common')}"><i class="fa fa-edit" aria-hidden="true"></i></a>
											</td>
										</tr>
									# END items #
								# ENDIF #
							</tbody>
							# IF C_PAGINATION #
								<tfoot>
									<tr>
										<td colspan="7">
											<div class="align-center">
												<span class="pinned small" data-color-surround="rgba(var(--error-rgb-t), 0.4)">
													{L_FILE_DISAPPROVED}
												</span>
												<span class="pinned small" data-color-surround="rgba(var(--warning-rgb-t), 0.4)">
													{L_FILE_INVISIBLE}
												</span>
												<span class="pinned small" data-color-surround="rgba(var(--success-rgb-t), 0.4)">
													{L_FILE_VISIBLE}
												</span>
											</div>
											# INCLUDE PAGINATION #
										</td>
									</tr>
								</tfoot>
							# ENDIF #
						</table>
					</div>
					<fieldset class="fieldset-submit">
						<legend>{L_SUBMIT}</legend>
						<input type="hidden" name="token" value="{TOKEN}">
						<button type="submit" name="submit" value="true" class="button submit">{L_SUBMIT}</button>
						<button type="reset" class="button reset-button" value="true">{L_RESET}</button>
					</fieldset>
				</form>

			</div>
		</div>
	</section>
# ENDIF #
