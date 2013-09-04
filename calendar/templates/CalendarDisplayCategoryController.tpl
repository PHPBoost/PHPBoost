		# INCLUDE MSG #
		
		<script type="text/javascript">
		<!--
		function Confirm() {
			return confirm("{@calendar.actions.confirm.del_event}");
		}
		-->
		</script>
		
		<div class="module_position">
			<div class="module_top_l"></div>
			<div class="module_top_r"></div>
			<div class="module_top">
				<div class="module_top_title">
					<strong>{@module_title}</strong>
				</div>
				<div class="module_actions">
				# IF C_ADD #
				<a href="{U_ADD}" title="{@calendar.titles.add_event}" class="img_link">
					<img src="{PATH_TO_ROOT}/templates/{THEME}/images/{LANG}/add.png" class="valign_middle" alt="{@calendar.titles.add_event}" />
				</a>
				# ENDIF #
				</div>
			</div>
			<div class="module_contents">
				<div id="calendar">
					# INCLUDE CALENDAR #
				</div>
			</div>
			
			<div class="module_bottom_l"></div>
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
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
				<div class="module_position" itemscope="itemscope" itemtype="http://schema.org/Event">
					<div class="module_top_l"></div>
					<div class="module_top_r"></div>
					<div class="module_top">
						<div class="module_top_title">
							<a href="{event.U_SYNDICATION}" title="{@syndication}" class="img_link">
								<img class="valign_middle" src="{PATH_TO_ROOT}/templates/{THEME}/images/rss.png" alt="{@syndication}"/>
							</a>
							<a href="{event.U_LINK}"><span id="name" itemprop="name">{event.TITLE}</span></a>
						</div>
						
						<meta itemprop="url" content="{event.U_LINK}">
						<div itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							<meta itemprop="about" content="{event.CATEGORY_NAME}">
							<meta itemprop="discussionUrl" content="{event.U_COMMENTS}">
							<meta itemprop="interactionCount" content="{event.NUMBER_COMMENTS} UserComments">
						</div>
						
						<div class="module_top_com">
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
						</div>
						<div class="spacer"></div>
					</div>
					<div class="module_contents">
						<span itemprop="text">{event.CONTENTS}</span>
						<div class="spacer">&nbsp;</div>
						# IF event.C_LOCATION #
						<div itemprop="location" itemscope itemtype="http://schema.org/Place">
							<span class="text_strong">{@calendar.labels.location}</span> :
							<span itemprop="name">{event.LOCATION}</span>
						</div>
						# ENDIF #
					</div>
					<div class="module_bottom_l"></div>
					<div class="module_bottom_r"></div>
					<div class="module_bottom">
						<div class="event_display_author" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
							{@calendar.labels.created_by} : # IF event.AUTHOR #<a itemprop="author" href="{event.U_AUTHOR_PROFILE}" class="small_link {event.AUTHOR_LEVEL_CLASS}" # IF event.C_AUTHOR_GROUP_COLOR # style="color:{event.AUTHOR_GROUP_COLOR}" # ENDIF #>{event.AUTHOR}</a># ELSE #{L_GUEST}# ENDIF #
						</div>
						<div class="event_display_dates">
							{@calendar.labels.start_date} : <span class="float_right"><time datetime="{event.START_DATE_ISO8601}" itemprop="startDate">{event.START_DATE}</time></span>
							<div class="spacer"></div>
							{@calendar.labels.end_date} : <span class="float_right"><time datetime="{event.END_DATE_ISO8601}" itemprop="endDate">{event.END_DATE}</time></span>
						</div>
					</div>
					<div class="spacer">&nbsp;</div>
				</div>
				# END event #
			# ELSE #
				<p class="text_center">{@calendar.notice.no_current_action}</p>
			# ENDIF #
		</div>