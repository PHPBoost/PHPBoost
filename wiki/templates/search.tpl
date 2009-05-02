		<form action="{TARGET}" method="get">
			<table class="module_table">
				<tr>
					<th>
						{L_SEARCH}
					</th>
				</tr>
				# IF C_ERROR_HANDLER #
				<tr>
					<td colspan="3">
						<span id="errorh"></span>
						<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;margin-top:15px;margin-bottom:15px;">
							<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						</div>
					</td>
				</tr>
				# ENDIF #
				<tr>
					<td class="row2" style="text-align:center;">
						{L_KEY_WORDS} &nbsp;
						<input type="text" name="search" class="text" value="{KEY_WORDS}" maxlength="255" size="40" />
					</td>
				</tr>
				<tr>
					<td class="row2" style="text-align:center;">
						<label>
							<input name="where" value="title" type="radio" {SELECTED_TITLE} />
							{L_TITLE}
						</label>
						<label>
							<input name="where" value="contents" type="radio" {SELECTED_CONTENTS} />
							{L_CONTENTS}
						</label>
						<br /><br />
						<input type="submit" class="submit" value="{L_SEARCH}" />
					</td>
				</tr>
			</table>
		</form>

		<br /><br />

		# START search_result #
			<table class="module_table">
				<tr>
					<th colspan="2">
						{L_SEARCH_RESULT}
					</th>
				</tr>
				<tr>
					<td class="row3" style="text-align:center;">
						{ARTICLE_TITLE}
					</td>
					<td class="row3" style="text-align:center;">
						{RELEVANCE}
					</td>
				</tr>
				# START search_result.item #
				<tr>
					<td class="row2">
						<a href="{search_result.item.U_TITLE}">{search_result.item.TITLE}</a>
					</td>
					<td class="row2" style="text-align:center;">
						{search_result.item.RELEVANCE}
					</td>
				</tr>
				# END search_result.item #
				# IF C_ERROR_HANDLER #
				<tr>
					<td colspan="2" class="row2">
						<span id="errorh"></span>
						<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
							<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
						</div>
					</td>
				</tr>
				# ENDIF #
				<tr>
					<td class="row2" colspan="2" style="text-align:center;">
						{search_result.PAGES}
					</td>
				</tr>
			</table>
		# END search_result #
		