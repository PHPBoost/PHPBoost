		# INCLUDE forum_top #

		<article itemscope="itemscope" itemtype="http://schema.org/Creativework" id="article-forum-stats">
			<header>
				<h2>{L_STATS}</h2>
			</header>
			<div class="content">
				<table class="table">
					<thead>
						<tr>
							<th>
								{L_STATS}
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<p class="forum-stats">
									<span>{L_NBR_TOPICS}: <strong>{NBR_TOPICS}</strong></span>
									<span>{L_NBR_MSG}: <strong>{NBR_MSG}</strong></span>
								</p>
								<p class="forum-stats">
									<span>{L_NBR_TOPICS_DAY}: <strong>{NBR_TOPICS_DAY}</strong></span>
									<span>{L_NBR_MSG_DAY}: <strong>{NBR_MSG_DAY}</strong></span>
									<span>{L_NBR_TOPICS_TODAY}: <strong>{NBR_TOPICS_TODAY}</strong></span>
									<span>{L_NBR_MSG_TODAY}: <strong>{NBR_MSG_TODAY}</strong></span>
								</p>
							</td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<thead>
						<tr>
							<th>
								{L_LAST_MSG}
							</th>
						</tr>
					</thead>
					<tbody>
						# START last_msg #
						<tr>
							<td>
								<a href="{PATH_TO_ROOT}/forum/topic{last_msg.U_TOPIC_ID}">{last_msg.TITLE}</a>
							</td>
						</tr>
						# END last_msg #
					</tbody>
				</table>

				<table class="table">
					<thead>
						<tr>
							<th>
								{L_POPULAR}
							</th>
						</tr>
					</thead>
					<tbody>
						# START popular #
						<tr>
							<td>
								<a href="{PATH_TO_ROOT}/forum/topic{popular.U_TOPIC_ID}">{popular.TITLE}</a>
							</td>
						</tr>
						# END popular #
					</tbody>
				</table>

				<table class="table">
					<thead>
						<tr>
							<th>
								{L_ANSWERS}
							</th>
						</tr>
					</thead>
					<tbody>
						# START answers #
						<tr>
							<td>
								<a href="{PATH_TO_ROOT}/forum/topic{answers.U_TOPIC_ID}">{answers.TITLE}</a>
							</td>
						</tr>
						# END answers #
					</tbody>
				</table>
			</div>
			<footer>{L_STATS}</footer>
		</article>

		# INCLUDE forum_bottom #
