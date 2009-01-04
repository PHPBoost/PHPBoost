<div style="text-align:center;"><ul id="countrytabs" class="shadetabs">
<li><a href="#" rel="#default" class="selected">Tab 1</a></li>
<li><a href="../cache/rss_forum.html" rel="countrycontainer">Tab 2</a></li>
<li><a href="external3.htm" rel="countrycontainer">Tab 3</a></li>
<li><a href="external4.htm" rel="#iframe">Tab 4</a></li>
<li><a href="http://www.dynamicdrive.com">Dynamic Drive</a></li>
</ul>
</div>

<div id="countrydivcontainer" style="border:1px solid gray; width:450px; margin-bottom: 1em; padding: 10px">
<p>This is some default tab content, embedded directly inside this space and not via Ajax. It can be shown when no tabs are automatically selected, or associated with a certain tab, in this case, the first tab.</p>
</div>
<script type="text/javascript">

var countries=new ddajaxtabs("countrytabs", "countrydivcontainer")
countries.setpersist(true)
countries.setselectedClassTarget("link") //"link" or "linkparent"
countries.init()

</script>


		# INCLUDE forum_top #
			
	# START forums_list #				
			# START forums_list.cats #		
		<div style="margin-top:20px;">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					<span style="float:left">
						<a href="syndication.php?idcat={forums_list.cats.IDCAT}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>
						&nbsp;&nbsp;<a href="{forums_list.cats.U_FORUM_VARS}" class="forum_link_cat">{forums_list.cats.NAME}</a>
					</span>
					<span style="float:right">
						<a href="unread.php?cat={forums_list.cats.IDCAT}" title="{L_DISPLAY_UNREAD_MSG}"><img src="{MODULE_DATA_PATH}/images/new_mini.png" alt="" /></a>
					</span>
				</div>
				<div class="module_contents forum_contents">
					<table class="module_table" style="width:100%">
						<tr>			
							<td class="forum_text_column" style="min-width:175px;">{L_FORUM}</td>
							<td class="forum_text_column" style="width:60px;">{L_TOPIC}</td>
							<td class="forum_text_column" style="width:60px;">{L_MESSAGE}</td>
							<td class="forum_text_column" style="width:150px;">{L_LAST_MESSAGE}</td>
						</tr>
					</table>
				</div>
			</div>		
			# END forums_list.cats #
			# START forums_list.subcats #		
			<div class="module_position">
				<div class="module_contents forum_contents">
					<table class="module_table" style="width:100%">
						<tr>
							<td class="forum_sous_cat" style="width:25px;text-align:center;">
								{forums_list.subcats.ANNOUNCE}
							</td>
							<td class="forum_sous_cat" style="min-width:150px;">
								<a href="forum{forums_list.subcats.U_FORUM_VARS}">{forums_list.subcats.NAME}</a>
								<br />
								<span class="text_small">{forums_list.subcats.DESC}</span>
								<span class="text_small">{forums_list.subcats.SUBFORUMS}</span>
							</td>
							<td class="forum_sous_cat_compteur">
								{forums_list.subcats.NBR_TOPIC}
							</td>
							<td class="forum_sous_cat_compteur">
								{forums_list.subcats.NBR_MSG}
							</td>
							<td class="forum_sous_cat_last">
								{forums_list.subcats.U_LAST_TOPIC}
							</td>
						</tr>	
					</table>		
				</div>
			</div>
			# END forums_list.subcats #				
			# START forums_list.endcats #
			<div class="module_position">
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</div>	
			# END forums_list.endcats #		
	# END forums_list #
		
		# INCLUDE forum_bottom #
		