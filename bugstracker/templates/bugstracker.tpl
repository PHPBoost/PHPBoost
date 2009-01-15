<?php
/**
 *  bugstracker.tpl
 * 
 * @package     Bugstracker
 * @author         alain91
 * @copyright   (c) 2008-2009 Alain Gandon
 * @license        GPL
 *   
 */
?>
# START preview #
	<table class="module_table">
		<tr>
			<th colspan="2">
				{L_PREVIEW}
			</th>
		</tr>
		<tr>
			<td>
				<br />
				<div class="module_position">
					<div class="module_top_l"></div>
					<div class="module_top_r"></div>
					<div class="module_top">
						{preview.TITLE}
					</div>
					<div class="module_contents">
						<dl>
							<dt>{L_COMPONENT}</dt>
							<dd>{preview.COMPONENT}&nbsp;</dd>
						</dl>						
						<dl>
							<dt>{L_CONTENTS}</dt>
							<dd>{preview.CONTENTS}&nbsp;</dd>
						</dl>
					</div>
					<div class="module_bottom_l"></div>
					<div class="module_bottom_r"></div>
					<div class="module_bottom">
						<div style="float:left" class="text_small">
							&nbsp;
						</div>
						<div style="float:right" class="text_small">
							{L_WRITTEN_BY}: {preview.PSEUDO}, {L_ON}: {preview.DATE}
						</div>
					</div>
				</div>
				<br />
			</td>
		</tr>
	</table>
	<br /><br />
# END preview #

# START error_handler #
	<div class="error_handler_position">
		<span id="errorh"></span>
		<div class="{error_handler.CLASS}" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/{THEME}/images/{error_handler.IMG}.png" alt="" style="float:left;padding-right:6px;" /> {error_handler.L_ERROR}
			<br />
		</div>
	</div>
# END error_handler #

# START update #
	<script type="text/javascript">
	<!--
	function check_form(f){
		/*
		if(f.status.value == "") {
			alert("{L_REQUIRE_STATUS}");
			f.status.focus();
			return false;
		}
		*/
		return true;
	}
	-->
	</script>

	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{L_BUG_TITLE}
			</div>
			<div style="float:right">
				{L_COM}
				# IF update.C_ADMIN #
				&nbsp;&nbsp;<a href="admin_bugstracker.php">Admin</a>
				# ENDIF #
			</div>
		</div>
		<div class="module_contents">

		<form action="bugstracker.php" method="post" style="margin:auto;" onsubmit="return check_form(this);" class="fieldset_content">
			<input type="hidden" name="bug_id" value="{U_BUG_ID}" />

			<fieldset>
				<legend>{L_BUG_DECLARE}</legend>
				<dl>
					<dt>{L_TITLE}</dt>
					<dd>{update.TITLE}</dd>
				</dl>
				<dl>
					<dt>{L_AUTHOR}</dt>
					<dd>{update.AUTHOR}</dd>
				</dl>
				<dl>
					<dt>{L_CONTENTS}</dt>
					<dd>{update.CONTENTS}</dd>
				</dl>
				<dl>
					<dt>{L_COMPONENT}</dt>
					<dd>{update.COMPONENT}</dd>
				</dl>
			</fieldset>

			<fieldset>
				<legend>{L_BUG_PROCESS}</legend>
				<p>{L_REQUIRE}</p>
				<dl>
					<dt><label for="severity">{L_SEVERITY}</label></dt>
					<dd>
						<select name="severity" id="severity">
						# START update.select_severity #
							<option value="{update.select_severity.VALUE}" {update.select_severity.SELECT}>{update.select_severity.TEXT}</option>
						# END update.select_severity #
						</select>
					</dd>
				</dl>
				<dl>
					<dt><label for="component">{L_COMPONENT}</label></dt>
					<dd>
						<select name="component" id="component">
						# START update.select_component #
							<option value="{update.select_component.VALUE}" {update.select_component.SELECT}>{update.select_component.TEXT}</option>
						# END update.select_component #
						</select>		
					</dd>
				</dl>
				<dl>
					<dt><label for="assigned_to">{L_ASSIGNED_TO}</label></dt>
					<dd>
						<select name="assigned_to_id" id="assigned_to_id">
						# START update.select_assigned_to #
							<option value="{update.select_assigned_to.VALUE}" {update.select_assigned_to.SELECT}>{update.select_assigned_to.TEXT}</option>
						# END update.select_assigned_to #
						</select>
					</dd>
				</dl>
				<dl>
					<dt><label for="status">{L_STATUS}</label></dt>
					<dd>
						<select name="status" id="status">
						# START update.select_status #
							<option value="{update.select_status.VALUE}" {update.select_status.SELECT}>{update.select_status.TEXT}</option>
						# END update.select_status #
						</select>
					</dd>
				</dl>
				<dl>
					<dt><label for="target">{L_TARGET}</label></dt>
					<dd>
						<select name="target" id="target">
						# START update.select_target #
							<option value="{update.select_target.VALUE}" {update.select_target.SELECT}>{update.select_target.TEXT}</option>
						# END update.select_target #
						</select>
					</dd>
				</dl>
				<dl>
					<dt><label for="fixed_in">{L_FIXED_IN}</label></dt>
					<dd>
						<select name="fixed_in" id="fixed_in">
						# START update.select_fixed_in #
							<option value="{update.select_fixed_in.VALUE}" {update.select_fixed_in.SELECT}>{update.select_fixed_in.TEXT}</option>
						# END update.select_fixed_in #
						</select>
					</dd>
				</dl>
				<dl>
					<dt>{L_UPDATED_DATE}</dt>
					<dd>{update.UPDATED_DATE}</dd>
				</dl>
				<dl>
					<dt>{L_UPDATED_BY}</dt>
					<dd>{update.UPDATED_BY}</dd>
				</dl>
			</fieldset>

			<fieldset class="fieldset_submit">
				<legend>{L_UPDATE}</legend>
				<input type="submit" name="valid_edit" value="{L_SUBMIT}" class="submit" />
				&nbsp;&nbsp;
				<input type="reset" value="{L_RESET}" class="reset" />
			</fieldset>
		</form>

		<fieldset class="fieldset_content">
		<legend>{L_BUG_HISTORY}</legend>
		<table class="module_table">
			<tr>
				<td colspan="8" class="row1">
					{PAGINATION}&nbsp;
				</td>
			</tr>
			<tr style="font-weight:bold;text-align: center;">
				<td class="row3">
					{L_H_ID}
				</td>
				<td class="row3">
					{L_UPDATED_DATE}
				</td>
				<td class="row3">
					{L_UPDATED_BY}
				</td>
				<td class="row3">
					{L_UPDATED_FIELD}
				</td>
				<td class="row3">
					{L_MODIFICATION}
				</td>
			</tr>

			# START update.history #
			<tr>
				<td class="row2" style="text-align:center;padding:4px 0px;">
					{update.history.ID}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;">
					{update.history.UPDATED_DATE}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;">
					{update.history.UPDATED_BY}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;">
					{update.history.UPDATED_FIELD}
				</td>
				<td class="row2" style="text-align:center;padding:4px 0px;">
					{update.history.MODIFICATION}
				</td>
			</tr>
			# END update.history #
		</table>
		</fieldset>
	</div>
	<div class="module_bottom_l"></div>
	<div class="module_bottom_r"></div>
	<div class="module_bottom">
		<div style="float:left" class="text_small"></div>
		<div style="float:right" class="text_small"></div>
	</div>
		
	<br /><br />
	{COMMENTS}
	
	</div>
# END update #

# START list #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{L_PROFIL}
			</div>
			<div style="float:right">
				# IF list.C_ADD #
				<a href="bugstracker.php?add=1">Ajouter</a>
				# ENDIF #
				# IF list.C_ADMIN #
				&nbsp;&nbsp;<a href="admin_bugstracker.php">Admin</a>
				# ENDIF #
			</div>
		</div>
		<div class="module_contents">
	
	<table class="module_table" style="width: 98%;">
		<tr>
			<td colspan="8" class="row1">
				{PAGINATION}&nbsp;
			</td>
		</tr>
		<tr style="font-weight:bold;text-align: center;">
			<td class="row3" style="font-size:70%">
				<a href="{U_ID}">{L_ID}</a>
			</td>
			<td class="row3">
				<a href="{U_TITLE}">{L_TITLE}</a>
			</td>
			<td class="row3">
				<a href="{U_SEVERITY}">{L_SEVERITY}</a>
			</td>
			<td class="row3">
				<a href="{U_SUBMITTED_DATE}">{L_SUBMITTED_DATE}</a>
			</td>
			<td class="row3">
				<a href="{U_UPDATED_DATE}">{L_UPDATED_DATE}</a>
			</td>
			<td class="row3">
				<a href="{U_ASSIGNED_TO}">{L_ASSIGNED_TO}</a>
			</td>
			<td class="row3">
				<a href="{U_STATUS}">{L_STATUS}</a>
			</td>
		</tr>

		# START list.bug #
		<tr>
			<td class="row2" style="text-align:center;padding:4px 0px;">
				<a href="{list.bug.U_ACTION}">{list.bug.ID}</a>
			</td>
			<td class="row2" style="text-align:center;padding:4px 0px;">
				<a href="{list.bug.U_ACTION}">{list.bug.TITLE}</a>
			</td>
			<td class="row2" style="text-align:center;padding:4px 0px;">
				{list.bug.SEVERITY}
			</td>
			<td class="row2" style="text-align:center;padding:4px 0px;">
				{list.bug.SUBMITTED_DATE}
			</td>
			<td class="row2" style="text-align:center;padding:4px 0px;">
				{list.bug.UPDATED_DATE}
			</td>
			<td class="row2" style="text-align:center;padding:4px 0px;">
				{list.bug.ASSIGNED_TO}
			</td>
			<td class="row2" style="text-align:center;padding:4px 0px;">
				{list.bug.STATUS}
			</td>
		</tr>
		# END list.bug #
		
	</table>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
	</div>
# END list #

# START add #
	<script type="text/javascript">
	<!--
	function check_form(f){
	if(f.title.value == "") {
		alert("{L_ERROR_TITLE}");
		return false;
	}
	if(f.contents.value == "") {
		alert("{L_ERROR_TEXT}");
		return false;
	}
	return true;
	}
	-->
	</script>

	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{L_BUG_ADD}
			</div>
			<div style="float:right">
			</div>
		</div>
		<div class="module_contents">

	<form action="bugstracker.php" method="post" style="margin:auto;" onsubmit="return check_form(this);" class="fieldset_content">
		<input type="hidden" name="unique" value="{V_UNIQUE}" />
		<fieldset>
			<p>{L_REQUIRE}</p>
			<dl>
				<dt><label for="title">{L_TITLE}&nbsp;(*)</label></dt>
				<dd><input type="text" maxlength="50" size="30" id="title" name="title" value="{add.TITLE}" class="text" /></dd>
			</dl>
			<dl>
				<dt><label for="component_orig">{L_COMPONENT_ORIG}</label></dt>
				<dd>
					<select name="component_orig" id="component_orig">
					# START add.select_component_orig #
						<option value="{add.select_component_orig.VALUE}" {add.select_component_orig.SELECT}>{add.select_component_orig.TEXT}</option>
					# END add.select_component_orig #
					</select>
				</dd>
			</dl>
			<label for="contents">{L_CONTENTS}&nbsp;(*)</label>
			{BBCODE}
			<textarea type="text" rows="30" cols="75" id="contents" name="contents">{add.CONTENTS}</textarea>
			<br />
		</fieldset>

		<fieldset class="fieldset_submit">
			<legend>{L_UPDATE}</legend>
			<input type="submit" name="valid_add" value="{L_SUBMIT}" class="submit" />
			&nbsp;&nbsp;
			<input type="submit" name="previs_add" value="{L_PREVIEW}" class="submit" />
			&nbsp;&nbsp;
			<input type="reset" value="{L_RESET}" class="reset" />
		</fieldset>
	</form>
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
	</div>
# END add #

# START view #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				BUG #{view.ID}
			</div>
			<div style="float:right">
				{L_COM} {L_EDIT}
			</div>
		</div>
		<div class="module_contents">
				<fieldset>
					<legend>{L_BUG_DECLARE}</legend>
					<dl>
						<dt>{L_TITLE}</dt>
						<dd>{view.TITLE}</dd>
					</dl>
					<dl>
						<dt>{L_AUTHOR}</dt>
						<dd>{view.AUTHOR}</dd>
					</dl>
					<dl>
						<dt>{L_CONTENTS}</dt>
						<dd>{view.CONTENTS}</dd>
					</dl>
					<dl>
						<dt>{L_COMPONENT}</dt>
						<dd>{view.COMPONENT_ORIG}</dd>
					</dl>
				</fieldset>

				<fieldset>
					<legend>{L_BUG_PROCESS}</legend>
					<dl>
						<dt>{L_COMPONENT}</dt>
						<dd>{view.COMPONENT}</dd>
					</dl>
					<dl>
						<dt>{L_SEVERITY}</dt>
						<dd>{view.SEVERITY}</dd>
					</dl>
					<dl>
						<dt>{L_STATUS}</dt>
						<dd>{view.STATUS}</dd>
					</dl>
					<dl>
						<dt>{L_TARGET}</dt>
						<dd>{view.TARGET}</dd>
					</dl>
					<dl>
						<dt>{L_FIXED_IN}</dt>
						<dd>{view.FIXED_IN}</dd>
					</dl>
					<dl>
						<dt>{L_ASSIGNED_TO}</dt>
						<dd>{view.ASSIGNED_TO}</dd>
					</dl>
				</fieldset>

		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small">{L_UPDATED_BY}&nbsp;: {view.UPDATED_BY}, {L_UPDATED_DATE}&nbsp;: {view.UPDATED_DATE}</div>
		</div>
	</div>

	<br /><br />
	{COMMENTS}

# END view #

# START summary #
	<div class="module_position">
		<div class="module_top_l"></div>
		<div class="module_top_r"></div>
		<div class="module_top">
			<div style="float:left">
				{L_PROFIL}
			</div>
			<div style="float:right">
				# START summary.m_add #
				<a href="bugstracker.php?add=1">Ajouter</a>
				# END summary.m_add #
				# START summary.m_admin #
				&nbsp;&nbsp;<a href="admin_bugstracker_config.php">Admin</a>
				# END summary.m_admin #
			</div>
		</div>
		<div class="module_contents">

	# START summary.component #
	<fieldset>
	<legend><a href="bugstracker.php?list=1&amp;id={summary.component.ID}">{L_COMPONENT} : {summary.component.LABEL}</a></legend>
		<table width="100%">
		# START summary.component.status #
		<tr>
		<td width="66%">{L_STATUS} : {summary.component.status.LABEL}</td>
		<td width="34%">{summary.component.status.VALUE}</td>
		</tr>
		# END summary.component.status #
		</table>
	</fieldset>
	# END summary.component #
	
		</div>
		<div class="module_bottom_l"></div>
		<div class="module_bottom_r"></div>
		<div class="module_bottom">
			<div style="float:left" class="text_small"></div>
			<div style="float:right" class="text_small"></div>
		</div>
	</div>
# END summary #