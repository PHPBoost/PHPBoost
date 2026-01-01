<script>
	function img_change(id, url, img)
	{
		if (document.getElementById(id))
			document.getElementById(id).src = url + img;
	}
</script>

<nav id="admin-quick-menu">
	<a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
		<i class="fa fa-bars" aria-hidden="true"></i> {@forum.ranks.management}
	</a>
	<ul>
		<li>
			<a href="{PATH_TO_ROOT}/forum" class="quick-link">{@common.home}</a>
		</li>
		<li>
			<a href="admin_ranks.php" class="quick-link">{@forum.ranks.management}</a>
		</li>
		<li>
			<a href="admin_ranks_add.php" class="quick-link">{@forum.rank.add}</a>
		</li>
		<li>
			<a href="${relative_url(ForumUrlBuilder::configuration())}" class="quick-link">{@form.configuration}</a>
		</li>
	</ul>
</nav>

<div id="admin-contents">

	# INCLUDE MESSAGE_HELPER #

	<form action="admin_ranks.php" method="post">
		<table class="table">
			<caption>{@forum.ranks.management}</caption>
			<thead>
				<tr>
					<th>
						{@forum.rank.name}
					</th>
					<th>
						{@forum.rank.messages.number}
					</th>
					<th>
						{@forum.rank.thumbnail}
					</th>
					<th>
						{@common.delete}
					</th>
				</tr>
			</thead>
			<tbody>
				# START rank #
					<tr>
						<td>
							<input type="text" maxlength="30" name="{rank.ID}name" value="{rank.RANK}">
						</td>
						<td>
							# IF rank.C_CUSTOM_RANK #<input type="number" min="0" name="{rank.ID}msg" value="{rank.MESSAGE}"># ELSE #{@forum.special.rank}# ENDIF #
						</td>
						<td>
							<select name="{rank.ID}icon" onchange="img_change('icon{rank.ID}', '{rank.JS_PATH_RANKS}', this.options[selectedIndex].value)">
								{rank.RANK_OPTIONS}
							</select>
							# IF rank.RANK_THUMBNAIL #
								<span class="field-description">
									<img src="{rank.U_RANK_THUMBNAIL}" id="icon{rank.ID}" alt="{rank.RANK_THUMBNAIL}" />
								</span>
							# ENDIF #
						</td>
						<td>
							# IF rank.C_CUSTOM_RANK #<a href="{rank.U_DELETE}" class="far fa-trash-alt" data-confirmation="delete-element"></a># ELSE #{@forum.special.rank}# ENDIF #
						</td>
					</tr>
				# END rank #
			</tbody>
		</table>
		<fieldset class="fieldset-submit">
			<legend>{@form.submit}</legend>
			<div class="fieldset-inset">
				<button type="submit" name="valid" value="true" class="button submit">{@form.submit}</button>
				<button type="reset" class="button reset-button" value="true">{@form.reset}</button>
				<input type="hidden" name="token" value="{TOKEN}">
			</div>
		</fieldset>
	</form>
</div>
