# INCLUDE MESSAGE_HELPER #

<section id="module-calendar" class="several-items">
	<header class="section-header">
		<div class="controls align-right">
			<a class="offload" href="${relative_url(SyndicationUrlBuilder::rss('calendar'))}" aria-label="{@common.syndication}"><i class="fa fa-rss warning" aria-hidden="true"></i></a>
			# IF NOT C_ROOT_CATEGORY #{@calendar.module.title}# ENDIF #
			# IF C_CATEGORY ## IF IS_ADMIN #<a class="offload" href="{U_EDIT_CATEGORY}" aria-label="{@common.edit}"><i class="far fa-edit" aria-hidden="true"></i></a># ENDIF ## ENDIF #
		</div>
		<h1>
			# IF C_PENDING_ITEMS #
				{@calendar.pending.items}
			# ELSE #
				# IF C_MEMBER_ITEMS #
					# IF C_MY_ITEMS #{@calendar.my.items}# ELSE #{@calendar.member.items} {MEMBER_NAME}# ENDIF #
				# ELSE #
					# IF C_ROOT_CATEGORY #{@calendar.module.title}# ELSE #{CATEGORY_NAME}# ENDIF #
				# ENDIF #
			# ENDIF #
		</h1>
	</header>
	<div class="sub-section">
		<div class="content-container">
			<div class="content">
				# IF NOT C_PENDING_ITEMS #
					# IF NOT C_MEMBER_ITEMS #
						<div id="calendar">
							# INCLUDE CALENDAR #
						</div>
					# ENDIF #
				# ENDIF #
			</div>
		</div>
	</div>
	<div class="sub-section">
		<div class="content-container">
			<div id="events">
				# INCLUDE EVENTS #
			</div>
		</div>
	</div>
	<footer># IF C_PAGINATION # <div class="sub-section"><div class="content-container"># INCLUDE PAGINATION #</div></div># ENDIF #</footer>
</section>
