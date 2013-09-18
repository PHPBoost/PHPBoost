# INCLUDE MSG #

<script type="text/javascript">
<!--
function Confirm() {
	return confirm("{@calendar.actions.confirm.del_event}");
}
-->
</script>

<section>
	<header>
		<h1>
			<a href="${relative_url(SyndicationUrlBuilder::rss('calendar'))}" class="syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
			{@module_title}
		</h1>
		<div class="module_actions">
		# IF C_ADD #
		<a href="{U_ADD}" title="{@calendar.titles.add_event}" class="img_link">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{@calendar.titles.add_event}" />
		</a>
		# ENDIF #
		</div>
	</header>
		
	<div class="content">
		<div id="calendar">
			# INCLUDE CALENDAR #
		</div>
	</div>
	
	<div class="spacer">&nbsp;</div>
	
	<div id="events" class="event_container">
		<div class="module_actions">
		# IF C_ADD #
		<a href="{U_ADD}" title="{@calendar.titles.add_event}" class="img_link">
			<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{@calendar.titles.add_event}" />
		</a>
		# ENDIF #
		</div>
		<div class="event_top_title">
			<strong>{@calendar.titles.events}</strong>
		</div>
		<div class="event_date">{DATE}</div>
		
		# IF C_EVENT #
			# START event #
			<article itemscope="itemscope" itemtype="http://schema.org/Event">
				<header>
					<h1>
						<a href="{event.U_SYNDICATION}" class="syndication" title="${LangLoader::get_message('syndication', 'main')}"></a>
						<a href="{event.U_LINK}"><span id="name" itemprop="name">{event.TITLE}</span></a>
						
						<span class="tools">
							# IF C_COMMENTS_ENABLED #<a href="{event.U_COMMENTS}"><img src="{PATH_TO_ROOT}/templates/{THEME}/images/com_mini.png" alt="" class="valign_middle" /> {event.L_COMMENTS}</a># ENDIF #
							# IF event.C_EDIT #
							<a href="{event.U_EDIT}" title="{L_EDIT}" class="img_link">
								<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/edit.png" alt="{L_EDIT}" />
							</a>
							# ENDIF #
							# IF event.C_DELETE #
							<a href="{event.U_DELETE}" title="{L_DELETE}"# IF NOT event.C_BELONGS_TO_A_SERIE # onclick="javascript:return Confirm();"# ENDIF #>
								<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/delete.png" alt="{L_DELETE}" />
							</a>
							# ENDIF #
						</span>
					</h1>
					
					<meta itemprop="url" content="{event.U_LINK}">
					<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						<meta itemprop="about" content="{event.CATEGORY_NAME}">
						<meta itemprop="discussionUrl" content="{event.U_COMMENTS}">
						<meta itemprop="interactionCount" content="{event.NUMBER_COMMENTS} UserComments">
					</div>
				</header>
				<div class="content">
					<span itemprop="text">{event.CONTENTS}</span>
					<div class="spacer">&nbsp;</div>
					# IF event.C_LOCATION #
					<div itemprop="location" itemscope itemtype="http://schema.org/Place">
						<span class="text_strong">{@calendar.labels.location}</span> :
						<span itemprop="name">{event.LOCATION}</span>
					</div>
					# ENDIF #
					
					<div class="event_display_author" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
						{@calendar.labels.created_by} : # IF event.AUTHOR #<a itemprop="author" href="{event.U_AUTHOR_PROFILE}" class="small_link {event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}" # ENDIF #>{event.AUTHOR}</a># ELSE #{L_GUEST}# ENDIF #
					</div>
					<div class="event_display_dates">
						{@calendar.labels.start_date} : <span class="float_right"><time datetime="{event.START_DATE_ISO8601}" itemprop="startDate">{event.START_DATE}</time></span>
						<div class="spacer"></div>
						{@calendar.labels.end_date} : <span class="float_right"><time datetime="{event.END_DATE_ISO8601}" itemprop="endDate">{event.END_DATE}</time></span>
					</div>
				</div>
				<footer></footer>
			</article>
			# END event #
		# ELSE #
			<p class="center">{@calendar.notice.no_current_action}</p>
		# ENDIF #
	</div>
	<footer></footer>
</section>