		<script>
		<!--
		function Confirm_read_topics() {
			return confirm("{L_CONFIRM_READ_TOPICS}");
		}
		
		//Rafraissiement des topics non lus.
		function XMLHttpRequest_unread_topics(divID)
		{
			if( document.getElementById('refresh_unread' + divID) )
				document.getElementById('refresh_unread' + divID).className = 'fa fa-spinner fa-spin';
				
			var xhr_object = xmlhttprequest_init('{PATH_TO_ROOT}/forum/xmlhttprequest.php?token={TOKEN}&refresh_unread=1');
			xhr_object.onreadystatechange = function() 
			{
				if( xhr_object.readyState == 4 && xhr_object.status == 200 && xhr_object.responseText != '' )
				{
					if( document.getElementById('refresh_unread' + divID) )
						document.getElementById('refresh_unread' + divID).className = 'fa fa-refresh';
					
					var array_unread_topics = new Array('', '');
					eval(xhr_object.responseText);
					
					if( array_unread_topics[0] > 0 )
						forum_display_block('forum_unread' + divID);
						
					document.getElementById('nbr_unread_topics').innerHTML = array_unread_topics[1];
					document.getElementById('nbr_unread_topics2').innerHTML = array_unread_topics[1];
					document.getElementById('forum_blockforum_unread').innerHTML = array_unread_topics[2];
					document.getElementById('forum_blockforum_unread2').innerHTML = array_unread_topics[2];
				}
				else if( xhr_object.readyState == 4 && xhr_object.responseText == '' )
				{
					alert("{L_AUTH_ERROR}");
					if( document.getElementById('refresh_unread' + divID) )
						document.getElementById('refresh_unread' + divID).className = 'fa fa-refresh';
				}
			}
			xmlhttprequest_sender(xhr_object, null);
		}
		
		var delay_forum = 1000;
		var timeout_forum;
		var displayed_forum = false;
		var previous_forumblock;

		//Affiche le bloc.
		function forum_display_block(divID)
		{
			if( timeout_forum )
				clearTimeout(timeout_forum);
			
			var block = document.getElementById('forum_block' + divID);
			if( block.style.display == 'none' )
			{
				if( document.getElementById(previous_forumblock) )
					document.getElementById(previous_forumblock).style.display = 'none';
				block.style.display = 'block';
				displayed_forum = true;
				previous_forumblock = 'forum_block' + divID;
			}
			else
			{
				block.style.display = 'none';
				displayed_forum = false;
			}
		}

		//Cache le bloc.
		function forum_hide_block(forumid, stop)
		{
			if( stop && timeout_forum )
				clearTimeout(timeout_forum);
			else if( displayed_forum )
			{
				clearTimeout(timeout_forum);
				timeout_forum = setTimeout('forum_display_block(\'' + forumid + '\')', delay_forum);
			}
		}
		-->
		</script>
<section id="module-forum">
	<header id="forum-top">
		
		<h1>{FORUM_NAME}</h1>
		
		<div class="forum-links">
			<nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="cssmenu cssmenu-group float-left forum-index">
				<ul>
					<li>
						<span class="cssmenu-title">
							<i class="fa fa-globe"></i> <a class="small" href="index.php" title="{L_FORUM_INDEX}">{L_FORUM_INDEX}</a>
						</span>
					</li>
				</ul>
			</nav>
		# IF C_USER_CONNECTED #
			<nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="cssmenu cssmenu-group float-right" id="cssmenu-forum-top-link">
				<ul>
					<li>
						<span class="cssmenu-title">
							<i class="fa fa-msg-track"></i> {U_TOPIC_TRACK}
						</span>
						
					</li>
					<li>
						<span class="cssmenu-title">
							<i class="fa fa-lastview"></i> {U_LAST_MSG_READ}
						</span>
					</li>
					<li>
						<span class="cssmenu-title">
							<i class="fa fa-notread"></i> <span id="nbr_unread_topics">{U_MSG_NOT_READ}</span>
							<div class="forum-refresh">
								<div id="forum_blockforum_unread" style="display:none;"></div>
							</div>
							<a href="" onclick="XMLHttpRequest_unread_topics('');return false;" onmouseover="forum_hide_block('forum_unread', 1);" onmouseout="forum_hide_block('forum_unread', 0);"><i class="fa fa-refresh" id="refresh_unread"></i></a>
						</span>
						
					</li>
					<li>
						<span class="cssmenu-title">
							<i class="fa fa-eraser"></i> {U_MSG_SET_VIEW}
						</span>
					</li>
			# IF C_FORUM_CONNEXION #
					<li>
						<span class="cssmenu-title">
							<i class="fa fa-sign-out"></i> <a title="{L_DISCONNECT}" class="small" href="${relative_url(UserUrlBuilder::disconnect())}">{L_DISCONNECT}</a>
						</span>
					</li>
			# ENDIF #
				</ul>
			</nav>
		# ELSE #
			# IF C_FORUM_CONNEXION #
			<nav itemscope itemtype="http://schema.org/SiteNavigationElement" class="cssmenu cssmenu-group float-right" id="cssmenu-forum-top-link">
				<ul>
					<li>
						<span class="cssmenu-title">
							<i class="fa fa-sign-in"></i> <a title="{L_CONNECT}" class="small" href="${relative_url(UserUrlBuilder::connect())}">{L_CONNECT}</a>
						</span>
					</li>
					<li>
						<span class="cssmenu-title">
							<i class="fa fa-ticket"></i> <a title="{L_REGISTER}" class="small" href="${relative_url(UserUrlBuilder::registration())}">{L_REGISTER}</a>
						</span>
					</li>
				</ul>
			</nav>
			# ENDIF #
		# ENDIF #
			
			<div class="spacer"></div>
		</div>
		<script>
			<!--
			jQuery("#cssmenu-forum-top-link").menumaker({ title: " ${LangLoader::get_message('forum.links', 'common', 'forum')} ", format: "multitoggle", breakpoint: 768, menu_static: false });
			-->
		</script>
	</header>
