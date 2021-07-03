# INCLUDE FORUM_TOP #

<article itemscope="itemscope" itemtype="https://schema.org/Creativework" id="article-forum-stats">
	<header>
		<h2>{@forum.statistics}</h2>
	</header>
	<div class="content">
		<div class="cell-flex cell-tile cell-columns-2">
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">{@forum.stats}</h6>
				</div>
				<div class="cell-list">
					<ul>
						<li class="li-stretch">
							<span>{@forum.topics.number}</span><span>{TOPICS_NUMBERS}</span>
						</li>
						<li class="li-stretch">
							<span>{@forum.messages.number}</span><span>{MESSAGES_NUMBER}</span>
						</li>
						<li class="li-stretch">
							<span>{@forum.topics.number.per.day}</span><span>{TOPICS_NUMBERS_DAY}</span>
						</li>
						<li class="li-stretch">
							<span>{@forum.messages.number.per.day}</span><span>{MESSAGES_NUMBER_DAY}</span>
						</li>
						<li class="li-stretch">
							<span>{@forum.topics.number.today}</span><span>{TOPICS_NUMBERS_TODAY}</span>
						</li>
						<li class="li-stretch">
							<span>{@forum.messages.number.today}</span><span>{MESSAGES_NUMBER_TODAY}</span>
						</li>
					</ul>
				</div>
			</div>
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">{@forum.last.10.active.topics}</h6>
				</div>
				<div class="cell-list">
					<ul>
						# START last_msg #
							<li><a class="offload" href="{PATH_TO_ROOT}/forum/topic{last_msg.U_TOPIC_ID}">{last_msg.TITLE}</a></li>
						# END last_msg #
					</ul>
				</div>
			</div>
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">{@forum.10.most.popular.topics}</h6>
				</div>
				<div class="cell-list">
					<ul>
						# START popular #
							<li><a class="offload" href="{PATH_TO_ROOT}/forum/topic{popular.U_TOPIC_ID}">{popular.TITLE}</a></li>
						# END popular #
					</ul>
				</div>
			</div>
			<div class="cell">
				<div class="cell-header">
					<h6 class="cell-name">{@forum.10.most.active.topics}</h6>
				</div>
				<div class="cell-list">
					<ul>
						# START answers #
							<li><a class="offload" href="{PATH_TO_ROOT}/forum/topic{answers.U_TOPIC_ID}">{answers.TITLE}</a></li>
						# END answers #
					</ul>
				</div>
			</div>
		</div>
	</div>
</article>

# INCLUDE FORUM_BOTTOM #
