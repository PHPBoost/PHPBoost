# IF C_DISPLAY_ARTICLE #		
	<script type="text/javascript">
	<!--
		function Confirm_del_article() {
		return confirm("{L_ALERT_DELETE_ARTICLE}");
		}
		function display_mail()
		{
			if( document.getElementById('mail').style.display== "block"  )
			{
					hide_div("mail");
			}
				else
			{
					show_div("mail");
					new Effect.ScrollTo('mail',{duration:1.2});
			}

		}
		//Vérifie une adresse email
		function check_mail_validity(mail)
		{
			regex = new RegExp("^[a-z0-9._!#$%&\'*+/=?^|~-]+@([a-z0-9._-]{2,}\.)+[a-z]{2,4}$", "i");
			return regex.test(trim(mail));
		}
		function check_mail(value,id) 
		{
			if (!check_mail_validity(value))
			{	
				if(id == "mail_recipient")
				{
					document.getElementById('msg_mail_recipient').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_div_mail_recipient').innerHTML = "{L_MAIL_INVALID}";
				}
				else
				{
					document.getElementById('msg_user_mail').innerHTML = '<img src="../templates/{THEME}/images/forbidden_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_div_user_mail').innerHTML = "{L_MAIL_INVALID}";
				
				}
			}
			else
			{
				if(id == "mail_recipient")
				{
					document.getElementById('msg_mail_recipient').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_div_mail_recipient').innerHTML = '';
				}
				else
				{
					document.getElementById('msg_user_mail').innerHTML = '<img src="../templates/{THEME}/images/processed_mini.png" alt="" class="valign_middle" />';
					document.getElementById('msg_div_user_mail').innerHTML = '';
				
				}
			}
		}
		function check_form()
		{								
			if (document.getElementById("subject").value == "")
			{
				alert("{L_REQUIRE_SUBJECT}");
				return false;
			}
			else if (document.getElementById("exp").value == "")
			{
				alert("{L_REQUIRE_SENDER}");
				return false;
			}
			else if (!check_mail_validity(document.getElementById("user_mail").value))
			{
				alert("{L_EMAIL_ERROR}");
				return false;
			}
			else if (!check_mail_validity(document.getElementById("mail_recipient").value))
			{
				alert("{L_EMAIL_ERROR}");
				return false;
			}
			else
				return true;
		}
	-->
	</script>	
	# IF C_TAB #
	# IF C_CAROUSEL #
	<script type="text/javascript" src="carousel.js"></script>
	<script type="text/javascript">
		<!--
			window.onload = function() {
			new CarouselJs('tab_c', { speed: 50, pauseInterval: 0.1  ,startsegment: Math.floor(parseInt(getParam('p'))/7.1 ) });
			}
			
	# ELSE #
		<script type="text/javascript">
		<!--
	# ENDIF #
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
		function toggleTab(num,numelems,opennum,animate) 
		{
			if ($('tabContent'+num).style.display == 'none')
			{
				for (var i=1;i<=numelems;i++)
				{
					if ((opennum == null) || (opennum != i))
					{
						var temph = 'tabHeader'+i;
						var h = $(temph);
						if (!h)
						{
							var h = $('tabHeaderActive');
							h.id = temph;
						}
						var tempc = 'tabContent'+i;
						var c = $(tempc);
						if(c.style.display != 'none')
						{
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
				if (animate || typeof animate == 'undefined')
				{
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
			border-top: 1px solid #5D7C94;
		}
	</style>
	# ENDIF #
	
	# INCLUDE message_helper #
	
	<div class="module_position">					
		<div class="module_top_l"></div>		
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				<a href="{PATH_TO_ROOT}/syndication.php?m=articles&amp;cat={IDCAT}" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="../templates/{THEME}/images/rss.png" alt="Rss" title="Rss" /></a>  <strong>&nbsp;{NAME}</strong>	
			</div>
			<div style="float:right">
				# IF C_IS_ADMIN #
				&nbsp;&nbsp;<a href="{U_ARTICLES_EDIT}" title="{L_EDIT}"><img src="../templates/{THEME}/images/{LANG}/edit.png" class="valign_middle" alt="{L_EDIT}" /></a>
				&nbsp;&nbsp;<a href="{U_ARTICLES_DEL}" title="{L_DELETE}" onclick="javascript:return Confirm_del_article();"><img src="../templates/{THEME}/images/{LANG}/delete.png" class="valign_middle" alt="{L_DELETE}" /></a>
				# ENDIF #
			</div>
		</div>
		# IF C_TAB #
		<div id="tabs">
			<ul>
				 <div style="margin-left:auto;width:800px;overflow: hidden; height: 24px; position: relative;" class="carouseljs">
				# IF C_CAROUSEL #
					<a style="position: absolute; z-index: 2; left: 0px;margin-left:0px;" class="cjs-left cjs-disabled" title="Double-click to skip to beginning.">previous</a>
				# ENDIF #	
					<ol style="width: 750px; position: absolute; left: 0px;" id="tab_c">
						# START tab #
							<li {tab.STYLE} id="tabHeader{tab.ID_TAB_ACT}" style="display:{tab.DISPLAY_TAB}"><a href="javascript:void(0)" class="tab_a"onClick="toggleTab({tab.ID_TAB},{tab.TOTAL_TAB},0,false)"><span>{tab.PAGE_NAME}</span></a></li>
						# END tab #
					</ol>
				# IF C_CAROUSEL #
					<a style="position: absolute; z-index: 2; right: 0px;" class="cjs-right cjs-enabled" title="Double-click to skip to end.">next</a>
				# ENDIF #
				</div>
			</ul>
		</div>	
		<div class="module_contents">
			<div id="tabcontent">
				# START tab #
					<div id="tabContent{tab.ID_TAB}" class="tabContent" style="display:{tab.DISPLAY};">
					<div style="float:right;top:0px;">
							<table class="tab_extend_field">
								<tr>
									<th style="text-align:center;" colspan="2" >
										{L_INFO}
									</th>
								</tr>
								# IF C_AUTHOR #		
								<tr>
									<td class="row2 info_row">				
										<span>{L_WRITTEN}  </span>
									</td>
									<td class="row2" >	
										<span><a class="small_link" href="../member/member{U_USER_ID}">{PSEUDO}</a></span>
									</td>
								</tr>
								# ENDIF #					
								# IF C_DATE #			
								<tr>
									<td class="row2 info_row">				
										<span>{L_DATE} </span>
									</td>
									<td class="row2">	
										<span> {DATE}</span>
									</td>
								</tr>
								# ENDIF #
								# IF C_SOURCES #
								<tr>
									<td class="row2 info_row">					
										<span> {L_SOURCE} </span>
									</td>
									<td class="row2">	
										<span># START sources # <a href="{sources.URL}"> {sources.SOURCE}</a><br /> # END sources #</span>
									</td>
								</tr>
								# ENDIF #
								# IF C_COM #						
								<tr>
									<td class="row2 info_row">				
										<span>{L_COM} </span>
									</td>
									<td class="row2" >	
										<span>{COM}</span>
									</td>
								</tr>
								# ENDIF #
								# IF C_PRINT #
								<tr>
									<td class="row2 info_row">				
										<span>{L_PRINTABLE_VERSION} </span>
									</td>
									<td class="row2" >	
										<span><a href="{U_PRINT_ARTICLE}" title="{L_PRINTABLE_VERSION}"><img src="../templates/{THEME}/images/print_mini.png" alt="{L_PRINTABLE_VERSION}" class="valign_middle" /></a></span>
									</td>
								</tr>
								# ENDIF #				
								# IF C_MAIL #
								<tr>
									<td class="row2 info_row">				
										<span>{L_LINK_MAIL} </span>
									</td>
									<td class="row2">	
										<span><a href="javascript:display_mail()"><img src="../templates/{THEME}/images/pm_mini.png" class="valign_middle" alt="{L_LINK_MAIL}" /></a></span>
									</td>
								</tr>
								# ENDIF #
								# IF C_NOTE #
								<tr>
									<td class="row2" colspan="2" style="text-align:center;">	
										<span>{KERNEL_NOTATION}</span>
									</td>
								</tr>
								# ENDIF #
							</table>				
						</div>
						{tab.CONTENTS_TAB}		
						{TEST}		
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
				<div>
					<div style="float:left;width:65%">
						{CONTENTS}	
					</div>
					<div style="float:right;">
						<table class="tab_extend_field">
							<tr>
								<th style="text-align:center;" colspan="2" >
									{L_INFO}
								</th>
							</tr>
							# IF C_AUTHOR #		
							<tr>
								<td class="row2 info_row">				
									<span>{L_WRITTEN}  </span>
								</td>
								<td class="row2" >	
									<span><a class="small_link" href="../member/member{U_USER_ID}">{PSEUDO}</a></span>
								</td>
							</tr>
							# ENDIF #					
							# IF C_DATE #			
							<tr>
								<td class="row2 info_row">				
									<span>{L_DATE} </span>
								</td>
								<td class="row2">	
									<span> {DATE}</span>
								</td>
							</tr>
							# ENDIF #
							# IF C_SOURCES #
							<tr>
								<td class="row2 info_row">					
									<span> {L_SOURCE} </span>
								</td>
								<td class="row2">	
									<span># START sources # <a href="{sources.URL}"> {sources.SOURCE}</a><br /> # END sources #</span>
								</td>
							</tr>
							# ENDIF #
							# IF C_COM #						
							<tr>
								<td class="row2 info_row">				
									<span>{L_COM} </span>
								</td>
								<td class="row2" >	
									<span>{COM}</span>
								</td>
							</tr>
							# ENDIF #
							# IF C_PRINT #
							<tr>
								<td class="row2 info_row">				
									<span>{L_PRINTABLE_VERSION} </span>
								</td>
								<td class="row2" >	
									<span><a href="{U_PRINT_ARTICLE}" title="{L_PRINTABLE_VERSION}"><img src="../templates/{THEME}/images/print_mini.png" alt="{L_PRINTABLE_VERSION}" class="valign_middle" /></a></span>
								</td>
							</tr>
							# ENDIF #				
							# IF C_MAIL #
							<tr>
								<td class="row2 info_row">				
									<span>{L_LINK_MAIL} </span>
								</td>
								<td class="row2">	
									<span><a href="javascript:display_mail()"><img src="../templates/{THEME}/images/pm_mini.png" class="valign_middle" alt="{L_LINK_MAIL}" /></a></span>
								</td>
							</tr>
							# ENDIF #
							# IF C_NOTE #
							<tr>
								<td class="row2" colspan="2" style="text-align:center;">	
									<span>{KERNEL_NOTATION}</span>
								</td>
							</tr>
							# ENDIF #
						</table>
					</div>
				</div>
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
			<div class="spacer"></div>
		</div>
	</div>
	<br /><br />
	# IF C_COM #
	{COMMENTS}
	# ENDIF #
	# IF C_EXTEND_FIELD #
		<table class="tab_extend_field">
			<tr>
				<th>
					&nbsp;&nbsp;
				</th>
				<th>
					&nbsp;&nbsp;
				</th>
			</tr>
		# START extend_field #
			<tr>
				<td class="row2 extend_field_left">				
					<span>{extend_field.NAME} : </span>
				</td>
				<td class="row2 extend_field_right">	
					<span>{extend_field.CONTENTS}</span>
				</td>
			</tr>
		# END extend_field #
		</table>
	# ENDIF #
	# IF C_MAIL #
	<div id ="mail" style="display:none;">
		<form action="articles.php?cat={IDCAT}&amp;id={IDART}&amp;token={TOKEN}" name="form" method="post" onsubmit="return check_form();" class="fieldset_content" id="form">
			<fieldset>
				<legend>{L_MAIL_ARTICLES}</legend>
				<dl>
					<dt>
						<label for="recipient">{L_MAIL_RECIPIENT} :  </label>	
					</dt>
					<dd>
						<label><input type="text" size="50" id="mail_recipient" name="mail_recipient" value="" onblur="check_mail(this.value,this.id);" /></label> &nbsp;<span id="msg_mail_recipient"></span><div id="msg_div_mail_recipient"></div>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="exp">{L_SENDER} :  </label>	
					</dt>
					<dd>
						<label><input type="text" size="50" id="exp" name="exp" value="{SENDER}" /></label>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="user_mail">{L_USER_MAIL} :  </label>	
					</dt>
					<dd>
						<label><input type="text" size="50" id="user_mail" name="user_mail" value="{USER_MAIL}"  onblur="check_mail(this.value,this.id);" /></label> &nbsp;<span id="msg_user_mail"></span><div id="msg_div_user_mail"></div>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="subject"> {L_SUBJECT} :  </label>	
					</dt>
					<dd>
						<label><input type="text" size="50" id="subject" name="subject" value="" /></label>
					</dd>
				</dl>				
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend>{L_SUBMIT}</legend>
				<input type="hidden" id="link" name="link" value="{PATH_TO_ROOT}/articles/{U_ARTICLES_LINK}" />
				<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
				<input type="reset" value="{L_ERASE}" class="reset" />				
			</fieldset>	
		</form>
	</div>
	# ENDIF #
# ENDIF #
	