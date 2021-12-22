# IF C_DISPLAY #
	<section id="module-media">
		<header class="section-header">
			<h1>{@user.moderation.panel}</h1>
		</header>
		<div class="sub-section">
			<div class="content-container">
				<form action="moderation_media.php" method="post" class="fieldset-content media-filter">
					<fieldset>
						<legend class="sr-only">{@media.filter}</legend>
						<div id="form-filter" class="align-center fieldset-inset">
							{@media.display.files}&nbsp;
							<select name="state" id="state" class="nav" onchange="change_order()">
								<option value="all"{SELECTED_ALL}>{@media.all.files}</option>
								<option value="visible"{SELECTED_VISIBLE}>{@media.visible}</option>
								<option value="invisible"{SELECTED_INVISIBLE}>{@media.invisible}</option>
								<option value="disapproved"{SELECTED_DISAPPROVED}>{@media.disapproved}</option>
							</select>
							<label for="show_sub_cats" class="checkbox">
								<span>{@media.include.sub.categories}</span>
								<input type="checkbox" id="show_sub_cats" name="sub_cats" value="1"{SUB_CATS}>
							</label>
						</div>
					</fieldset>
					<fieldset class="fieldset-submit">
						<legend>{@form.submit}</legend>
						<div class="fieldset-inset">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="filter" value="true" class="button submit">{@form.submit}</button>
							<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
						</div>
					</fieldset>
				</form>

				<script>
					function check_all (type)
					{
						var item = new Array({JS_ARRAY});

						if (type == "delete")
						{
							if (confirm ('{@media.confirm.delete.all.files}'))
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
										{@common.name}
									</th>
									<th>
										{@common.category}
									</th>
									<th onclick="check_all('visible');" onmouseover="pointer('visible');" id="visible">
										{@common.status.visible}
									</th>
									<th onclick="check_all('invisible');" onmouseover="pointer('invisible');" id="invisible">
										{@common.status.invisible}
									</th>
									<th>
										{@common.status.unapproved}
									</th>
									<th onclick="check_all('delete');" onmouseover="pointer('delete');" id="delete">
										{@common.delete}
									</th>
									<th>{@common.edit}</th>
								</tr>
							</thead>
							<tbody>
								# IF C_NO_ITEM #
									<tr>
										<td colspan="6"><div class="message-helper notice">{@common.no.item.now}</div></td>
									</tr>
								# ELSE #
									# START items #
										<tr>
											<td class="{items.COLOR}">
												<a class="offload" href="{items.U_ITEM}">{items.TITLE}</a>
											</td>
											<td class="{items.COLOR}">
												<a class="offload" href="{items.U_CATEGORY}">{items.CATEGORY_NAME}</a>
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
												<a class="offload" href="{items.U_EDIT}"><i class="fa fa-edit" aria-hidden="true"></i><span class="sr-only">{@common.edit}</span></a>
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
													{@media.disapproved.description}
												</span>
												<span class="pinned small" data-color-surround="rgba(var(--warning-rgb-t), 0.4)">
													{@media.invisible.description}
												</span>
												<span class="pinned small" data-color-surround="rgba(var(--success-rgb-t), 0.4)">
													{@media.visible.description}
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
						<legend>{@form.submit}</legend>
						<div class="fieldset-inset">
							<input type="hidden" name="token" value="{TOKEN}">
							<button type="submit" name="submit" value="true" class="button submit">{@form.submit}</button>
							<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</section>
# ENDIF #
