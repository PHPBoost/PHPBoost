# INCLUDE FORUM_TOP #

<script>
	function check_convers(status, id)
	{
		for(i = 0; i < {TOPICS_NUMBERS}; i++)
			document.getElementById(id + i).checked = status;
	}
</script>

<form action="track{U_TRACK_ACTION}" method="post">
	<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-track">
		<header>
			<h2>
				<a class="offload" href="{U_CATEGORY}">{CATEGORY_NAME}</a>
				# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span># ENDIF #
			</h2>
		</header>
		<div class="content">
			<div class="text_small">{@H|forum.track.clue}</div>

			<table class="table module-table forum-table">
				<thead>
					<tr class="forum-text-column">
						<th class="forum-announce-topic" aria-label="{@forum.topic.status}">
							<i class="far fa-eye" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topic.status}</span>
						</th>
						<th class="forum-fixed-topic" aria-label="{@forum.topic.options}">
							<i class="fa fa-check success" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topic.options}</span>
						</th>
						<th class="forum-topic" aria-label="{@forum.topics}">
							<i class="far fa-file hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.topics}</span>
						</th>
						<th class="forum-author" aria-label="{@common.author}">
							<i class="far fa-user hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@common.author}</span>
						</th>
						<th class="forum-message-nb" aria-label="{@forum.messages.number}">
							<i class="far fa-comments hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.messages.number}</span>
						</th>
						<th class="forum-view" aria-label="{@forum.views.number}">
							<i class="far fa-eye hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.views.number}</span>
						</th>
						<th class="forum-pm" aria-label="{@forum.track.topic.pm}">
							<label for="" class="checkbox">
								<input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'p');">
								<span><i class="fa fa-people-arrows hidden-small-screens" aria-hidden="true"></i></span>
							</label>
							<span class="hidden-large-screens">{@forum.track.topic.pm}</span>
						</th>
						<th class="forum-mail" aria-label="{@forum.track.topic.email}">
							<label for="" class="checkbox">
								<input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'm');">
								<span><i class="fa iboost fa-iboost-email hidden-small-screens" aria-hidden="true"></i></span>
							</label>
							<span class="hidden-large-screens">{@forum.track.topic.email}</span>
						</th>
						<th class="forum-delete" aria-label="{@forum.untrack.topic}">
							<label for="" class="checkbox">
								<input type="checkbox" class="valign-middle" onclick="check_convers(this.checked, 'd');">
								<span><i class="far fa-trash-alt hidden-small-screens" aria-hidden="true"></i></span>
							</label>
							<span class="hidden-large-screens">{@forum.untrack.topic}</span>
						</th>
						<th class="forum-last-topic" aria-label="{@forum.last.messages}">
							<i class="fa fa-clock hidden-small-screens" aria-hidden="true"></i><span class="hidden-large-screens">{@forum.last.messages}</span>
						</th>
					</tr>
				</thead>
				<tbody>
					# IF C_NO_TRACKED_TOPICS #
						<tr>
							<td colspan="10">
								<div class="message-helper bgc notice">{@common.no.item.now}</div>
							</td>
						</tr>
					# ENDIF #

					# START topics #
						<tr class="category-{topics.CATEGORY_ID}">
							<td class="forum-announce-topic">
								# IF NOT topics.C_HOT_TOPIC #
									<i class="fa {topics.TOPIC_ICON}"></i>
								# ELSE #
									<i class="fa # IF topics.C_BLINK #blink # ENDIF #{topics.TOPIC_ICON}-hot"></i>
								# ENDIF #
							</td>
							<td class="forum-fixed-topic">
								# IF topics.C_DISPLAY_ISSUE_STATUS #<i class="fa fa-check success"></i># ENDIF # # IF C_POLL #<i class="fa fa-tasks"></i># ENDIF #
							</td>
							<td class="forum-topic">
								<a class="offload" href="{topics.U_ANCHOR}" aria-label="{topics.TITLE}"><i class="fa fa-hand-point-right" aria-hidden="true"></i></a> <strong>{topics.TYPE}</strong> <a class="offload" href="topic{topics.U_TOPIC}">{topics.L_ISSUE_STATUS_MESSAGE} {topics.TITLE}</a>
								<span class="smaller">{topics.DESCRIPTION}</span>
							</td>
							<td class="forum-author">
								# IF topics.C_AUTHOR #
									<a itemprop="author" href="{topics.U_AUTHOR_PROFILE}" class="small {topics.AUTHOR_LEVEL} offload"# IF topics.C_GROUP_COLOR # style="color:{topics.GROUP_COLOR}"# ENDIF #>{topics.AUTHOR}</a>
								# ELSE #
									<span class="small">{@user.guest}</span>
								# ENDIF #
							</td>
							<td class="forum-message-nb">
								{topics.MESSAGES_NUMBER}
							</td>
							<td class="forum-view">
								{topics.VIEWS_NUMBER}
							</td>
							<td class="forum-pm">
								<input type="checkbox" id="p{topics.INCR}" name="p{topics.ID}"# IF topics.C_TRACK_PM_SELECTED # checked="checked"# ENDIF #>
							</td>
							<td class="forum-mail">
								<input type="checkbox" id="m{topics.INCR}" name="m{topics.ID}"# IF topics.C_TRACK_MAIL_SELECTED # checked="checked"# ENDIF #>
							</td>
							<td class="forum-delete">
								<input type="checkbox" id="d{topics.INCR}" name="d{topics.ID}">
							</td>
							<td class="forum-last-topic">
								<span class="d-block">
									<a class="offload" href={topics.U_LAST_MESSAGE} aria-label="{topics.TITLE}<br /> {topics.LAST_MESSAGE_DATE_FULL}"> {topics.LAST_MESSAGE_DATE_FULL}</a>
								</span>
								<span class="d-block">
									<i class="fa fa-user fa-fw"></i>
									# IF topics.C_LAST_MESSAGE_GUEST #
										<a href="{topics.U_LAST_USER_PROFILE}" class="small {topics.LAST_USER_LEVEL} offload"# IF topics.C_LAST_USER_GROUP_COLOR # style="color:{topics.LAST_USER_GROUP_COLOR}"# ENDIF #>{topics.LAST_USER_LOGIN}</a>
									# ELSE #
										<span class="small">{@user.guest}</span>
									# ENDIF #
								</span>
							</td>
						</tr>
					# END topics #
				</tbody>
			</table>
			<div class="align-center">
				<input type="hidden" name="token" value="{TOKEN}">
				<button type="submit" class="button submit" name="valid" value="true">{@form.submit}</button>
			</div>
		</div>
		<footer>
			# IF C_PAGINATION #<span class="float-right"># INCLUDE PAGINATION #</span><div class="spacer"></div># ENDIF #
		</footer>
	</article>
</form>

# INCLUDE FORUM_BOTTOM #
