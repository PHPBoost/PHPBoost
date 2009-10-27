<link href="{PICTURES_DATA_PATH}/articles.css" rel="stylesheet" type="text/css" media="screen, handheld">
{ADMIN_MENU}
<div id="admin_contents">
	# IF C_ERROR_HANDLER #
			<span id="errorh"></span>
			<div id="error_msg">
				<div class="{ERRORH_CLASS}" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/{THEME}/images/{ERRORH_IMG}.png" alt="" style="float:left;padding-right:6px;" /> {L_ERRORH}
				</div>
			<br />
			</div>
			<script type="text/javascript">
			<!--
				//Javascript timeout to hide this message
				setTimeout('Effect.Fade("error_msg");', 4000);
			-->
			</script>
	# ENDIF #
	# START removing_interface #
	<form action="admin_articles_cat.php?token={TOKEN}" method="post" class="fieldset_content">
				<fieldset>
					<legend>{L_REMOVING_CATEGORY}</legend>
					<p>{L_EXPLAIN_REMOVING}</p>

					<label>
						<input type="radio" name="action" value="delete"# IF EMPTY_CATS # checked="checked"# ENDIF # /> {L_DELETE_CATEGORY_AND_CONTENT}
					</label>
					<br /> <br />
					<label>
						<input type="radio" name="action" value="move"# IF EMPTY_CATS # disabled="disabled"# ELSE # checked="checked"# ENDIF # /> {L_MOVE_CONTENT}
					</label>
					&nbsp;
					<select id="{FORM_ID}" name="{FORM_NAME}">
						<option value="0" disabled="disabled">{L_ROOT}</option>
					# START options #
						<option value="{options.ID}" {options.SELECTED_OPTION}>{options.PREFIX} {options.NAME}</option>
					# END options #
					</select>
				</fieldset>

				<fieldset class="fieldset_submit">
					<legend>{L_SUBMIT}</legend>
					<input type="hidden" name="cat_to_del" value="{removing_interface.IDCAT}" />
					<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
				</fieldset>
			</form>
	# END removing_interface #
	# START models_management #
		
		<table class="module_table" style="width:99%;">
			<tr>			
				<th colspan="3">
					{models_management.L_MODELS_MANAGEMENT}
				</th>
			</tr>							
			<tr>
				<td style="padding-left:20px;" class="row2">
					# START models #
						<div class="block_container" style="margin-bottom:20px;">
						<div class="block_contents">
								<p>
									<a href="../articles/admin_articles_models{models.U_MODEL_LINK}" class="big_link">{models.NAME}</a>
									<a href="{models.U_ADMIN_EDIT_MODEL}">
										<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/edit.png" alt="" />
									</a>
									<a href="{models.U_ADMIN_DELETE_MODEL}" onclick="return Confirm_del_model();">
										<img class="valign_middle" src="../templates/{THEME}/images/{LANG}/delete.png" alt="" />
									</a>
								</p>
								<div>
									<div style="float:left;width:28%;text-align:justify">
										<b>{models_management.L_DESCRIPTION} : </b>{models.DESC}
									</div>
									<div style="float:right;width:70%;">
										<table class="module_table" style="margin-top:0px;">
											<tr>
											 <th colspan="2">
												{models_management.L_MODEL_INO}
											 </th>
											</tr>
											<tr style="text-align:left;">		
												<td class="row2 text_small" style="width:30%;">
													<b>{models_management.L_CAT_TPL} :</b>
												</td>
												<td class="row2 text_small" >
													 {models.TPL_CATS}
												</td>
											</tr>	
											<tr style="text-align:left;">		
												<td class="row2 text_small">
													<b>{models_management.L_ARTICLES_TPL} :</b>
												</td>
												<td class="row2 text_small">
													{models.TPL_ARTICLES}
												</td>
											</tr>	
											<tr style="text-align:left;">		
												<td class="row2 text_small">
													<b>{models_management.L_EXTEND_FIELD} :</b>
												</td>
												<td class="row2 text_small">
													{models.EXTEND_FIELD}
												</td>
											</tr>	
											<tr style="text-align:left;">	
												<td class="row2 text_small">
													<b>{models_management.L_SPECIAL_OPTION} :</b>
												</td>
												<td class="row2 text_small">
													<b>{models_management.L_AUTHOR} : </b>{models.AUTHOR}<br />
													<b>{models_management.L_COM} : </b>{models.COM}<br />
													<b>{models_management.L_NOTE} : </b>{models.NOTE}<br />
													<b>{models_management.L_PRINTABLE} : </b>{models.IMPR}<br />
													<b>{models_management.L_DATE} : </b>{models.DATE} <br />
													<b>{models_management.L_LINK_MAIL} : </b>{models.MAIL}<br />
												</td>
											</tr>	
											<tr style="text-align:left;">		
												<td class="row2 text_small">
													<b>{models_management.L_USE_TAB} :</b>
												</td>
												<td class="row2 text_small">
													{models.USE_TAB}
												</td>
											</tr>									
										</table>
									</div>
								</div>
							<div class="spacer"></div>		
						</div>
					</div>
					# END models #
					<br />
				</td>
			</tr>
		</table>	
	# END models_management #
	# START edition_interface #
	<script type="text/javascript">
	<!--
	function check_form()
	{
		if (document.getElementById('name').value == "")
		{
			alert("{L_REQUIRE_TITLE}");
			return false;
		}

		return true;
	}

	var articles_tpl = {edition_interface.JS_SPECIAL_ARTICLES_TPL};
	function change_articles_tpl()
	{
		if( articles_tpl )
			hide_div("hide_articles_tpl");
		else
			show_div("hide_articles_tpl");
		articles_tpl = !articles_tpl;
	}
	
	var extend_field = {edition_interface.JS_EXTEND_FIELD};
	function change_extend_field()
	{
		if( extend_field )
			hide_div("hide_extend_field");
		else
			show_div("hide_extend_field");
		extend_field = !extend_field;
	}
	
	var global_option = {edition_interface.JS_SPECIAL_OPTION};
	function change_status_option()
	{
	
		if( global_option )
			hide_div("hide_special_option");
		else
			show_div("hide_special_option");
		global_option = !global_option;
	}
	
	function add_field(i, i_max) 
	{
		var i2 = i + 1;

		if( document.getElementById('a'+i) )
			document.getElementById('a'+i).innerHTML = '<label><input type="text" size="40" name="a'+i+'" value="" class="text" /></label><br /><span id="a'+i2+'"></span>';	
		if( document.getElementById('v'+i) )
			document.getElementById('v'+i).innerHTML = '<label><input type="text" size="40" name="v'+i+'" value="" class="text" /></label><br /><span id="v'+i2+'"></span>';	
		if( document.getElementById('s'+i) )
			document.getElementById('s'+i).innerHTML = (i < i_max) ? '<div style="height:22px;text-align:center;line-height:22px;" id="s'+i2+'"><a href="javascript:add_field('+i2+', '+i_max+')"><img style="vertical-align:bottom;" src="../templates/{THEME}/images/form/plus.png" alt="+" />&nbsp;&nbsp;{L_ADD_SOURCE}</a></span>' : '';					
	}
	-->
	</script>
	<form action="admin_articles_models.php?token={TOKEN}" method="post" onsubmit="return check_form();" class="fieldset_content">
		<fieldset>
			<legend>{L_MODEL}</legend>
			<p>{L_REQUIRE}</p>
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
			<label for="description">
				{L_DESCRIPTION}
			</label>
			{KERNEL_EDITOR}
			<textarea id="contents" rows="15" cols="40" name="description">{edition_interface.DESCRIPTION}</textarea>
			<br />
			<dl>
				<dt><label for="tab">* {L_USE_TAB}</label></dt>
				<dd><label><input type="radio" {edition_interface.TAB} name="tab" value="1" /> {L_ENABLE}</label>
							&nbsp;&nbsp; 
					<label><input type="radio" {edition_interface.NO_TAB} name="tab" value="0" />{L_DESABLE}</label></dd>	
			</dl>
		</fieldset>
		<fieldset>
			<legend>
				{L_TPL}
			</legend>
			<dl>
				<dt><label for="special_auth">{L_TPL}</label>
				<br />
				<span class="text_small">{L_ARTICLES_TPL_EXPLAIN}</span></dt>
				<dd>
					<input type="checkbox" name="articles_tpl_checkbox" id="articles_tpl_checkbox" onclick="javascript: change_articles_tpl();" {edition_interface.ARTICLES_TPL_CHECKED} />
				</dd>					
			</dl>
			<div id="hide_articles_tpl" style="display:{edition_interface.DISPLAY_ARTICLES_TPL};">
				<dl>
					<dt>
						<label for="name">
							* {L_ARTICLES_TPL}
						</label>
					</dt>
					<dd>
						<select name="tpl_articles">
							{edition_interface.TPL_ARTICLES_LIST}
						</select>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="name">
							* {L_CAT_TPL}
						</label>
					</dt>
					<dd>
						<select name="tpl_cat">
							{edition_interface.TPL_CAT_LIST}
						</select>
					</dd>
				</dl>
			</div>
		</fieldset>
		<fieldset>
			<legend>
				{L_SPECIAL_OPTION}
			</legend>
			<dl>
				<dt><label for="special_auth">{L_SPECIAL_OPTION}</label>
				<br />
				<span class="text_small">{L_SPECIAL_OPTION_MODEL_EXPLAIN}</span></dt>
				<dd>
					<input type="checkbox" name="special_option" id="special_option" onclick="javascript: change_status_option();" {edition_interface.OPTION_CHECKED} />
				</dd>					
			</dl>
			<div id="hide_special_option" style="display:{edition_interface.DISPLAY_SPECIAL_OPTION};">
				<dl>
					<dt>
						<label for="option_notation">{L_NOTE} : </label>
					</dt>
					<dd>
						<label>
							<select id="note" name="note">
								<option value="0" {edition_interface.SELECTED_NOTATION_HIDE}>{L_HIDE}</option>
								<option value="1" {edition_interface.SELECTED_NOTATION_DISPLAY}>{L_DISPLAY}</option>
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="option_com">{L_COM} : </label>
					</dt>
					<dd>
						<label>
							<select id="com" name="com">
								<option value="0" {edition_interface.SELECTED_COM_HIDE}>{L_HIDE}</option>
								<option value="1" {edition_interface.SELECTED_COM_DISPLAY}>{L_DISPLAY}</option>
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="option_impr">{L_PRINTABLE} : </label>
					</dt>
					<dd>
						<label>
							<select id="impr" name="impr">
								<option value="0" {edition_interface.SELECTED_IMPR_HIDE}>{L_DESABLE}</option>
								<option value="1" {edition_interface.SELECTED_IMPR_DISPLAY}>{L_ENABLE}</option>
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="option_date">{L_DATE} : </label>
					</dt>
					<dd>
						<label>
							<select id="date" name="date">
								<option value="0" {edition_interface.SELECTED_DATE_HIDE}>{L_HIDE}</option>
								<option value="1" {edition_interface.SELECTED_DATE_DISPLAY} >{L_DISPLAY}</option>
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="option_author">{L_AUTHOR} : </label>
					</dt>
					<dd>
						<label>
							<select id="author" name="author">
								<option value="0" {edition_interface.SELECTED_AUTHOR_HIDE}>{L_HIDE}</option>
								<option value="1" {edition_interface.SELECTED_AUTHOR_DISPLAY}>{L_DISPLAY}</option>
							</select>
						</label>
					</dd>
				</dl>
				<dl>
					<dt>
						<label for="option_mail">{L_LINK_MAIL} : </label>
					</dt>
					<dd>
						<label>
							<select id="mail" name="mail">
								<option value="0" {edition_interface.SELECTED_MAIL_HIDE}>{L_HIDE}</option>
								<option value="1" {edition_interface.SELECTED_MAIL_DISPLAY}>{L_DISPLAY}</option>
							</select>
						</label>
					</dd>
				</dl>
			</div>
		</fieldset>
		<fieldset>
			<legend>
				{L_EXTEND_FIELD}
			</legend>
			<dl>
				<dt><label for="extend_field">{L_EXTEND_FIELD}</label>
				<br />
				<span class="text_small">{L_EXTEND_FIELD_EXPLAIN}</span></dt>
				<dd>
					<input type="checkbox" name="extend_field_checkbox" id="extend_field_checkbox" onclick="javascript: change_extend_field();" {edition_interface.EXTEND_FIELD_CHECKED} />
				</dd>					
			</dl>
			<div id="hide_extend_field" style="display:{edition_interface.DISPLAY_EXTEND_FIELD};">
				<table style="margin:auto;text-align:center;border:none;border-spacing:0;">
					<tr>
						<th style="text-align:center;">
							{L_FIELD_NAME}
						</th>
						<th style="text-align:center;">
							{L_FIELD_TYPE}
						</th>
					</tr>
					<tr>
						<td class="row2" style="text-align:center;">	
							# START field #
							<label><input type="text" size="40" name="a{field.I}" id="a{field.I}" value="{field.NAME}" class="text" /></label><br />
							# END field #
							<span id="a{edition_interface.NB_FIELD}"></span>
						</td>
						<td class="row2" style="text-align:center;">	
							# START field #					
							<label><input type="text" size="40" name="v{field.I}" id="v{field.I}" value="{field.TYPE}" class="text" /> </label><br />
							# END field #
							<span id="v{edition_interface.NB_FIELD}"></span>
						</td>
					</tr>
					<tr>
						<td style="text-align:center;margin-top:5px;" colspan="2">
							<div id="s{edition_interface.NB_FIELD}" style="height:22px;text-align:center;line-height:22px;"><a href="javascript:add_field({edition_interface.NB_FIELD}, 100)"><img style="vertical-align:bottom;" src="../templates/{THEME}/images/form/plus.png" alt="+" />&nbsp;&nbsp;{L_ADD_FIELD}</a></div>								
						</td>
					</tr>
				</table>
			</div>
		</fieldset>
		<fieldset class="fieldset_submit">
			<legend>{L_SUBMIT}</legend>
			<input type="hidden" name="id_model" value="{edition_interface.ID_MODEL}" />
			<input type="submit" name="submit" value="{L_SUBMIT}" class="submit" />
			&nbsp;&nbsp;
			<input type="button" name="preview" value="{L_PREVIEW}" onclick="XMLHttpRequest_preview();" class="submit" />
			&nbsp;&nbsp;
			<input type="reset" value="{L_RESET}" class="reset" />		
		</fieldset>
	</form>
	# END edition_interface #
	
</div>
