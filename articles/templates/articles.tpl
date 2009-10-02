	# IF C_DISPLAY_ARTICLE #
			
		<script type="text/javascript">
		<!--
			function Confirm_del_article() {
			return confirm("{L_ALERT_DELETE_ARTICLE}");
			}
		-->
		</script>	
		# IF C_TAB #


<script type="text/javascript" src="carousel.js"></script>
			<script type="text/javascript">
			<!--
					window.onload = function() {
				new CarouselJs('tab_c', { speed: 50, pauseInterval: 0.1  });
				}
				function getParam(name)
				{
					var str_location = String(location);
					if(str_location.search('/articles-') != -1)
					{
						url=String(location).substr(str_location.search('/articles-') + 10);
						
						tab=url.split('+');
						tab1=tab[0].split('-');
						if(tab1.length > 2 )
						{
							return tab1[2];
						}
						else
						{
							return false;
						}	
					}
					else
					{
						var start=location.search.indexOf("?"+name+"=" );
						if (start<0) start=location.search.indexOf("&"+name+"=" );
						if (start<0) return '';
						start += name.length+2;
						var end=location.search.indexOf("&",start)-1;
						if (end<0) end=location.search.length;
						var result='';
						for(var i=start;i<=end;i++) 
						{
							var c=location.search.charAt(i);
							result=result+(c=='+'?' ':c);
						}
						if(result == "")
						{
							return false;
						}
						else
						{
							return unescape(result);
						}
					}
					
					
				}
				function start_tab(param)
				{
					if(param != false)
					{
						toggleTab(param,{TOTAL_TAB},0,false);
					}
				}
					
								
							/*-----------------------------------------------------------
						Toggles element's display value
						Input: any number of element id's
						Output: none 
						---------------------------------------------------------*/
					function toggleDisp() {
						for (var i=0;i<arguments.length;i++){
							var d = $(arguments[i]);
							if (d.style.display == 'none')
								d.style.display = 'block';
							else
								d.style.display = 'none';
						}
					}
					/*-----------------------------------------------------------
						Toggles tabs - Closes any open tabs, and then opens current tab
						Input:     1.The number of the current tab
										2.The number of tabs
										3.(optional)The number of the tab to leave open
										4.(optional)Pass in true or false whether or not to animate the open/close of the tabs
						Output: none 
						---------------------------------------------------------*/
					function toggleTab(num,numelems,opennum,animate) {
						if ($('tabContent'+num).style.display == 'none'){
							for (var i=1;i<=numelems;i++){
								if ((opennum == null) || (opennum != i)){
									var temph = 'tabHeader'+i;
									var h = $(temph);
									if (!h){
										var h = $('tabHeaderActive');
										h.id = temph;
									}
									var tempc = 'tabContent'+i;
									var c = $(tempc);
									if(c.style.display != 'none'){
										if (animate || typeof animate == 'undefined')
											Effect.toggle(tempc,'blind',{duration:0.5, queue:{scope:'menus', limit: 3}});
										else
											toggleDisp(tempc);
									}
								}
							}
							var h = $('tabHeader'+num);
							if (h)
								h.id = 'tabHeaderActive';
							h.blur();
							var c = $('tabContent'+num);
							c.style.marginTop = '2px';
							if (animate || typeof animate == 'undefined'){
								Effect.toggle('tabContent'+num,'blind',{duration:0.5, queue:{scope:'menus', position:'end', limit: 3}});
							}else{
								toggleDisp('tabContent'+num);
							}
						}
					}
					
					-->
			</script>
		
			<style>
				.module_contents
				{
					border-left:1px #5D7C94 solid;
					border-right:1px #5D7C94 solid;
					border-bottom:1px #5D7C94 solid;
				}
			</style>
		# ENDIF #
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<a href="{PATH_TO_ROOT}/syndication.php?m=articles&amp;cat={IDCAT}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>  <strong>&nbsp;{NAME}</strong>	
				</div>
				<div style="float:right">
					{COM}
					# IF C_IS_ADMIN #
					&nbsp;&nbsp;<a href="../articles/management.php?edit={IDART}" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="{L_EDIT}" /></a>
					&nbsp;&nbsp;<a href="../articles/management.php?del=1&amp;id={IDART}&amp;token={TOKEN}" title="{L_DELETE}" onclick="javascript:return Confirm_del_article();"><img src="../templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="{L_DELETE}" /></a>
					# ENDIF #
					# IF C_PRINT #
					&nbsp;&nbsp;<a href="{U_PRINT_ARTICLE}" title="{L_PRINTABLE_VERSION}"><img src="../templates/{THEME}/images/print_mini.png" alt="{L_PRINTABLE_VERSION}" class="valign_middle" /></a>
					# ENDIF #
				</div>
			</div>
			# IF C_TAB #
			<div id="tabs">
				<ul>
					 <div style="overflow: hidden; height: 24px; width: 900px; position: relative;" class="carouseljs">
						<a style="position: absolute; z-index: 2; left: 0px;margin-left:0px;" class="cjs-left cjs-disabled" title="Double-click to skip to beginning.">previous</a>
						<ol style="width: 750px; position: absolute; left: 0px;" id="tab_c">
							# START tab #
								<li {tab.STYLE} id="tabHeader{tab.ID_TAB_ACT}" style="display:{tab.DISPLAY_TAB}"><a href="javascript:void(0)" class="tab_a"onClick="toggleTab({tab.ID_TAB},{tab.TOTAL_TAB},0,false)"><span>{tab.PAGE_NAME}</span></a></li>
							# END tab #
						</ol>
						<a style="position: absolute; z-index: 2; right: 0px;" class="cjs-right cjs-enabled" title="Double-click to skip to end.">next</a>
					</div>
				</ul>
			</div>	
			<div class="module_contents">				
				<div id="tabscontent">
					# START tab #
						<div id="tabContent{tab.ID_TAB}" class="tabContent" style="display:{tab.DISPLAY};">
							<br /><div>{tab.CONTENTS_TAB}</div>
						</div>
					# END tab #
				</div>					
				<script type="text/javascript">
					<!--
					start_tab(getParam('p'));
					-->
				</script>
			# ENDIF #
			# IF NOT C_TAB #
			<div class="module_contents">
					# IF PAGINATION_ARTICLES #
					<div style="float:right;margin-right:35px;width:250px;">
						<form action="" method="post">
							<p class="row2 text_strong" style="padding:2px;text-indent:4px;">{L_SUMMARY}:</p>
							<p class="row1" style="padding:2px;padding-bottom:15px">
								<select name="page_list" style="display:block;width:100%;margin:auto;font-size:12px;" onchange="document.location = {U_ONCHANGE_ARTICLE}">
									{PAGES_LIST}
								</select>
								<input type="submit" name="valid" id="articles_page_list" value="{L_SUBMIT}" class="submit" />
								<script type="text/javascript">
								<!--				
								document.getElementById('articles_page_list').style.display = 'none';
								-->
								</script>
							</p>
						</form>
					</div>
					<div class="spacer">&nbsp;</div>
					# ENDIF #
					
					# IF PAGE_NAME #
					<h2 class="title" style="text-indent:35px;">{PAGE_NAME}</h2>
					# ENDIF #
					
					{CONTENTS}
					s
					<div class="spacer" style="margin-top:35px;">&nbsp;</div>
					# IF PAGINATION_ARTICLES #
					<div style="float:left;width:33%;text-align:right">&nbsp;{PAGE_PREVIOUS_ARTICLES}</div>
					<div style="float:left;width:33%" class="text_center">{PAGINATION_ARTICLES}</div>
					<div style="float:left;width:33%;">{PAGE_NEXT_ARTICLES}&nbsp;</div>
					# ENDIF #		
			# ENDIF #
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left" class="text_small">
					{KERNEL_NOTATION}
				</div>
				<div style="float:right" class="text_small">
					{L_WRITTEN}: <a class="small_link" href="../member/member{U_USER_ID}">{PSEUDO}</a>, {L_ON}: {DATE}
				</div>
				<div class="spacer"></div>
			</div>
		</div>
		<br /><br />
		{COMMENTS}
	# ENDIF #
		