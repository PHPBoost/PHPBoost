<article itemscope="itemscope" itemtype="http://schema.org/Event">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'main')}" class="icon-syndication"></a>
			<span id="name" itemprop="name">{TITLE}</span>
			<span class="actions">
				# IF C_EDIT #
					<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="icon-edit"></a>
				# ENDIF #
				# IF C_DELETE #
					<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="icon-delete"# IF NOT C_BELONGS_TO_A_SERIE # data-confirmation="delete-element"# ENDIF #></a>
				# ENDIF #
			</span>
		</h1>
		
		<meta itemprop="url" content="{U_LINK}">
		<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			<meta itemprop="about" content="{CATEGORY_NAME}">
			<meta itemprop="discussionUrl" content="{U_COMMENTS}">
			<meta itemprop="interactionCount" content="{NUMBER_COMMENTS} UserComments">
		</div>
	</header>
	<div class="content">
		<span itemprop="text">{CONTENTS}</span>
		# IF C_LOCATION #
		<div class="spacer">&nbsp;</div>
		<div itemprop="location" itemscope itemtype="http://schema.org/Place">
			<span class="text-strong">{@calendar.labels.location}</span> :
			<span itemprop="name">{LOCATION}</span>
		</div>
		# ENDIF #
		# IF C_PARTICIPATION_ENABLED #
		<div class="spacer">&nbsp;</div>
		# IF C_DISPLAY_PARTICIPANTS #
		<div>
			<span class="text-strong">{@calendar.labels.participants}</span> :
			<span>
				# IF C_PARTICIPANTS #
					# START participant #
						<a href="{participant.U_PROFILE}" class="small_link {participant.LEVEL_CLASS}" # IF participant.C_GROUP_COLOR # style="color:{participant.GROUP_COLOR}" # ENDIF #>{participant.LOGIN}</a>
					# END participant #
				# ELSE #
					{@calendar.labels.no_one}
				# ENDIF #
			</span>
		</div>
		# ENDIF #
		# IF C_PARTICIPATE ## IF C_IS_PARTICIPANT #<a href="{U_UNSUSCRIBE}" class="basic-button">{@calendar.labels.unsuscribe}</a># ELSE #<a href="{U_SUSCRIBE}" class="basic-button">{@calendar.labels.suscribe}</a># ENDIF ## ENDIF #
		# ENDIF #
		
		<div class="spacer">&nbsp;</div>
		<div class="event_display_author" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			{@calendar.labels.created_by} : # IF AUTHOR #<a itemprop="author" href="{U_AUTHOR_PROFILE}" class="small_link {AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #{L_GUEST}# ENDIF #
		</div>
		<div class="event_display_dates">
			{@calendar.labels.start_date} : <span class="float-right"><time datetime="{START_DATE_ISO8601}" itemprop="startDate">{START_DATE}</time></span>
			<div class="spacer"></div>
			{@calendar.labels.end_date} : <span class="float-right"><time datetime="{END_DATE_ISO8601}" itemprop="endDate">{END_DATE}</time></span>
		</div>
		
		<div class="spacer">&nbsp;</div>
		<hr style="width:70%;margin:0px auto 40px auto;">
		
		# INCLUDE COMMENTS #
	</div>
	<footer></footer>
</article>