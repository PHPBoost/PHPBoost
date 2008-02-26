		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu">{L_FAQ_MANAGEMENT}</li>
				<li>
					<a href="admin_faq.php"><img src="faq.png" alt="{L_CONFIG_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq.php" class="quick_link">{L_CONFIG_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_faq_cats.php"><img src="faq.png" alt="{L_CATS_MANAGEMENT}" /></a>
					<br />
					<a href="admin_faq_cats.php" class="quick_link">{L_CATS_MANAGEMENT}</a>
				</li>
				<li>
					<a href="admin_faq_cats.php?new=1"><img src="faq.png" alt="{L_ADD_CAT}" /></a>
					<br />
					<a href="admin_faq_cats.php?new=1" class="quick_link">{L_ADD_CAT}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
		
			# IF C_ERROR_HANDLER #
				<span id="errorh"></span>
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				</div>		
			# ENDIF #
		
			# START categories_management #
			<table class="module_table" style="width:99%;">
				<tr>			
					<th colspan="3">
						{L_CATS_MANAGEMENT}
					</th>
				</tr>							
				<tr>
					<td style="padding-left:20px;" class="row2">
						<br />
						{categories_management.CATEGORIES}
						<br />
					</td>
				</tr>
			</table>
			
			# END categories_management #
			
			# START removing_interface #

			# END removing_interface #

			# START edition_interface #
			
			<script type="text/javascript">
			<!--
			function check_form(){
				if(document.getElementById('title').value == "")
				{
					alert("{L_REQUIRE_TITLE}");
					return false;
			    }

				return true;
			}
			-->
			</script>
			<form action="admin_faq_cats.php" method="post" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend>{L_CATEGORY}</legend>
					<p>{L_REQUIRED_FIELDS}</p>
					<dl>
						<dt>
							<label for="name">
								* {L_NAME}
							</label>
						</dt>
						<dd>
							<input type="text" size="65" maxlength="100" id="name" name="name" value="{edition_interface.NAME}" class="text" />
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="id_parent">
								* {L_LOCATION}
							</label>
						</dt>
						<dd>
							{edition_interface.CATEGORIES_TREE}
						</dd>
					</dl>
					<dl>
						<dt>
							<label for="image">
								{L_IMAGE}
							</label>
						</dt>
						<dd>
							<input type="text" size="65" maxlength="100" id="image" name="image" value="{edition_interface.IMAGE}" class="text" />
						</dd>
					</dl>
					<label for="description">
						{L_DESCRIPTION}
					</label>
					# INCLUDE handle_bbcode #
					<textarea id="contents" rows="15" cols="40" name="description">{edition_interface.DESCRIPTION}</textarea>
				</fieldset>
				
				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="idcat" value="{edition_interface.IDCAT}" />
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
					&nbsp;&nbsp;
					<input type="button" name="preview" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview(this.form);" class="submit" />
					&nbsp;&nbsp;
					<input type="reset" value="{L_RESET}" class="reset" />				
				</fieldset>
			</form>
			# END edition_interface #
			
		</div>
		