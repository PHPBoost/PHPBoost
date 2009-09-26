		# IF C_DISPLAY_ARTICLE #
			
		<script type="text/javascript">
		<!--
		function Confirm_del_article() {
		return confirm("{L_ALERT_DELETE_ARTICLE}");
		}
		-->
		</script>		
		<style>
		#tabs{
        margin-left: 4px;
        padding: 0;
        background: transparent;
        voice-family: "\"}\"";
        voice-family: inherit;
        padding-left: 5px;
    }
    #tabs ul{
        font: bold 11px Arial, Verdana, sans-serif;
        margin:0;
        padding:0;
        list-style:none;

    }
    #tabs li{
        display:inline;
        margin:0 2px 0 0;
        padding:0;
        text-transform:uppercase;
				
    }
    #tabs a{
        float:left;
		background:#FFFFFF url(../templates/base/theme/images/contentbg.png) repeat-x;
		min-width:100px;
		 border:1px #cccccc solid;
		-moz-border-radius-topleft:4px;
		-khtml-border-radius-topleft:5px;
		-webkit-border-top-left-radius:5px;
	
		-moz-border-radius-topright:4px;
		-khtml-border-radius-topright:5px;
		-webkit-border-top-right-radius:5px;
		border-radius:5px;
        margin:0 2px 0 0;
        padding:0 0 1px 3px;
        text-decoration:none;
    }
    #tabs a span{
        float:left;
        display:block;
        background: transparent url(images/tabs_right.gif) no-repeat right top;
        padding:4px 9px 2px 6px;
    }
    #tabs a span{float:none;}
    #tabs a:hover{background-color: #cccccc;color: white;}
    #tabs a:hover span{margin-left:-4px;padding-bottom:-2px;background-color: #cccccc;}
	
    #tabHeaderActive span, #tabHeaderActive a { 
		background-color: #cccccc; 
		color:#000;
	
	}
    .tabContent {
        clear:both;
        border:2px solid #42577B;
        padding-top:2px;
        background-color:#FFF;
		padding-left:5px;
    }
		</style>
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
			<div class="module_contents">
				# IF NOT C_TAB #
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
				
				# IF C_TAB #	
					<script type="text/javascript">
							<!--
							
							
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

				<div id="tabs">
					<ul>

						# START tab #
						 <li {tab.STYLE} id="tabHeader{tab.ID_TAB1}"><a href="javascript:void(0)" onClick="toggleTab({tab.ID_TAB},{tab.TOTAL_TAB},0,false)"><span>{tab.PAGE_NAME}</span></a></li>

						# END tab #
					</ul>
				</div>
					<div id="tabscontent">
						# START tab #
							<div id="tabContent{tab.ID_TAB}" class="tabContent" style="display:{tab.DISPLAY};">
								<br /><div>{tab.CONTENTS_TAB}</div>
							</div>
						# END tab #
					</div>
			
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
		