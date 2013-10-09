# IF C_PARTICIPATE #
<script type="text/javascript">
<!--
function change_registration() {
	var action = '';
	
	if (document.getElementById("subscription_type").value == 0)
		action = 'subscribe';
	else
		action = 'unsubscribe';
		
	new Ajax.Request('${relative_url(CalendarUrlBuilder::ajax_change_participation())}', {
		method:'post',
		asynchronous: false,
		parameters: {'event_id' : {ID}, 'user_id' : {USER_ID}, 'token' : '{TOKEN}', 'action': action},
		onSuccess: function(transport) {
			if (transport.responseText == 1)
			{
				$('subscription').value = "{@calendar.labels.unsuscribe}";
				$('subscription_type').value = 1;
			}
			else if (transport.responseText == 0)
			{
				$('subscription').value = "{@calendar.labels.suscribe}";
				$('subscription_type').value = 0;
			}
		}
	});
}
-->
</script>
# ENDIF #

<article itemscope="itemscope" itemtype="http://schema.org/Event">
	<header>
		<h1>
			<a href="{U_SYNDICATION}" title="${LangLoader::get_message('syndication', 'main')}" class="syndication"></a>
			<span id="name" itemprop="name">{TITLE}</span>
			<span class="tools">
				# IF C_EDIT #
					<a href="{U_EDIT}" title="${LangLoader::get_message('edit', 'main')}" class="edit"></a>
				# ENDIF #
				# IF C_DELETE #
					<a href="{U_DELETE}" title="${LangLoader::get_message('delete', 'main')}" class="delete"></a>
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
		<div class="spacer">&nbsp;</div>
		# IF C_LOCATION #
		<div itemprop="location" itemscope itemtype="http://schema.org/Place">
			<span class="text_strong">{@calendar.labels.location}</span> :
			<span itemprop="name">{LOCATION}</span>
		</div>
		# ENDIF #
		# IF C_PARTICIPANTS #
		<div class="spacer">&nbsp;</div>
		<div>
			<span class="text_strong">{@calendar.labels.participants}</span> :
			<span>{PARTICIPANTS}</span>
		</div>
		# ENDIF #
		
		<div class="spacer">&nbsp;</div>
		<div class="event_display_author" itemscope="itemscope" itemtype="http://schema.org/CreativeWork">
			{@calendar.labels.created_by} : # IF AUTHOR #<a itemprop="author" href="{U_AUTHOR_PROFILE}" class="small_link {AUTHOR_LEVEL_CLASS}" # IF C_AUTHOR_GROUP_COLOR # style="color:{AUTHOR_GROUP_COLOR}" # ENDIF #>{AUTHOR}</a># ELSE #{L_GUEST}# ENDIF #
		</div>
		# IF C_PARTICIPATE #<a style="cursor:pointer;padding-left:25px;" onclick="change_registration()" id="subscription"># IF IS_PARTICIPANT #{@calendar.labels.unsuscribe}# ELSE #{@calendar.labels.suscribe}# ENDIF #</a><span id="subscription_type" style="display:none;"># IF IS_PARTICIPANT #1# ELSE #0# ENDIF #</span># ENDIF #
		<div class="event_display_dates">
			{@calendar.labels.start_date} : <span class="float_right"><time datetime="{START_DATE_ISO8601}" itemprop="startDate">{START_DATE}</time></span>
			<div class="spacer"></div>
			{@calendar.labels.end_date} : <span class="float_right"><time datetime="{END_DATE_ISO8601}" itemprop="endDate">{END_DATE}</time></span>
		</div>
		
		<div class="spacer">&nbsp;</div>
		<hr style="width:70%;margin:0px auto 40px auto;">
		
		# IF C_COMMENTS_ENABLED #
			# INCLUDE COMMENTS #
		# ENDIF #
	</div>
	<footer></footer>
</article>