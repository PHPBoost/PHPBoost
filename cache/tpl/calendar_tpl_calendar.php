		<?php echo isset($this->_var['JAVA']) ? $this->_var['JAVA'] : ''; ?>


		<?php if( !isset($this->_block['show']) || !is_array($this->_block['show']) ) $this->_block['show'] = array();
foreach($this->_block['show'] as $show_key => $show_value) {
$_tmpb_show = &$this->_block['show'][$show_key]; ?>

		<form action="calendar.php" method="get">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					<strong><?php echo isset($this->_var['L_CALENDAR']) ? $this->_var['L_CALENDAR'] : ''; echo ' '; echo isset($this->_var['ADMIN_CALENDAR']) ? $this->_var['ADMIN_CALENDAR'] : ''; ?></strong>
				</div>
				<div class="module_contents" style="text-align:center;">
					<?php echo isset($this->_var['ADD']) ? $this->_var['ADD'] : ''; ?>	
					<select name="m">
						<?php if( !isset($_tmpb_show['month']) || !is_array($_tmpb_show['month']) ) $_tmpb_show['month'] = array();
foreach($_tmpb_show['month'] as $month_key => $month_value) {
$_tmpb_month = &$_tmpb_show['month'][$month_key]; ?>
						<?php echo isset($_tmpb_month['MONTH']) ? $_tmpb_month['MONTH'] : ''; ?>
						<?php } ?>	
					</select>
					&nbsp;
					<select class="nav" name="y">
					<?php if( !isset($_tmpb_show['year']) || !is_array($_tmpb_show['year']) ) $_tmpb_show['year'] = array();
foreach($_tmpb_show['year'] as $year_key => $year_value) {
$_tmpb_year = &$_tmpb_show['year'][$year_key]; ?>
						<?php echo isset($_tmpb_year['YEAR']) ? $_tmpb_year['YEAR'] : ''; ?>
					<?php } ?>
					</select>	
					<input type="hidden" name="d" value="1" />
					&nbsp;
					<input type="submit" name="date" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />	
					
					<br /><br />
					<span id="act"></span>
					<table class="module_table" style="width: auto;padding:5px;"> 
						<tr>
							<td class="row3">
								<a href="calendar<?php echo isset($this->_var['U_PREVIOUS']) ? $this->_var['U_PREVIOUS'] : ''; ?>" title="">«</a>
							</td> 
							<td colspan="5" class="row3">
								<?php echo isset($this->_var['DATE']) ? $this->_var['DATE'] : ''; ?> 
							</td> 
							<td class="row3">
								<a href="calendar<?php echo isset($this->_var['U_NEXT']) ? $this->_var['U_NEXT'] : ''; ?>" title="">»</a>
							</td> 
						</tr>
						<tr style="text-align:center;">
							<?php if( !isset($_tmpb_show['day']) || !is_array($_tmpb_show['day']) ) $_tmpb_show['day'] = array();
foreach($_tmpb_show['day'] as $day_key => $day_value) {
$_tmpb_day = &$_tmpb_show['day'][$day_key]; ?>
							<?php echo isset($_tmpb_day['L_DAY']) ? $_tmpb_day['L_DAY'] : ''; ?>
							<?php } ?>
						</tr>
						<tr style="text-align:center;">			
							<?php if( !isset($_tmpb_show['calendar']) || !is_array($_tmpb_show['calendar']) ) $_tmpb_show['calendar'] = array();
foreach($_tmpb_show['calendar'] as $calendar_key => $calendar_value) {
$_tmpb_calendar = &$_tmpb_show['calendar'][$calendar_key]; ?>					
							<?php echo isset($_tmpb_calendar['DAY']) ? $_tmpb_calendar['DAY'] : ''; ?>
							<?php echo isset($_tmpb_calendar['TR']) ? $_tmpb_calendar['TR'] : ''; ?>
							<?php } ?>
						</tr>
						<tr>
							<td style="width:16px;" class="row3">
								<?php echo isset($this->_var['U_PREVIOUS_EVENT']) ? $this->_var['U_PREVIOUS_EVENT'] : ''; ?>
							</td>
							<td  colspan="5" class="row3">
								<?php echo isset($this->_var['L_EVENTS']) ? $this->_var['L_EVENTS'] : ''; ?> 
							</td>
							<td style="width:16px;" class="row3">
								<?php echo isset($this->_var['U_NEXT_EVENT']) ? $this->_var['U_NEXT_EVENT'] : ''; ?>
							</td> 
						</tr>
					</table>
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</form>
		
		<?php if( !isset($_tmpb_show['error_handler']) || !is_array($_tmpb_show['error_handler']) ) $_tmpb_show['error_handler'] = array();
foreach($_tmpb_show['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_show['error_handler'][$error_handler_key]; ?>
		<br />
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<br />		
		<?php } ?>
		<br /><br />
		
		<?php if( !isset($_tmpb_show['action']) || !is_array($_tmpb_show['action']) ) $_tmpb_show['action'] = array();
foreach($_tmpb_show['action'] as $action_key => $action_value) {
$_tmpb_action = &$_tmpb_show['action'][$action_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span class="text_strong" style="float:left;"><?php echo isset($_tmpb_action['TITLE']) ? $_tmpb_action['TITLE'] : ''; ?></span>
				<span style="float:right;"><?php echo isset($_tmpb_action['COM']) ? $_tmpb_action['COM'] : '';  echo isset($_tmpb_action['EDIT']) ? $_tmpb_action['EDIT'] : '';  echo isset($_tmpb_action['DEL']) ? $_tmpb_action['DEL'] : ''; ?></span>
			</div>
			<div class="module_contents">
				<?php echo isset($_tmpb_action['CONTENTS']) ? $_tmpb_action['CONTENTS'] : ''; ?>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left;padding-top:4px;padding-bottom:4px;">
					<?php echo isset($_tmpb_action['LOGIN']) ? $_tmpb_action['LOGIN'] : ''; ?>
				</div>				
				<div class="text_small" style="padding:4px;text-align: right;">
					<?php echo isset($this->_var['L_ON']) ? $this->_var['L_ON'] : ''; ?>:&nbsp;&nbsp;<?php echo isset($_tmpb_action['DATE']) ? $_tmpb_action['DATE'] : ''; ?>
				</div>
			</div>
		</div>
		<br /><br />

		<?php $this->tpl_include('handle_com'); ?>
		
		<?php } ?>

		<?php } ?>


		<?php if( !isset($this->_block['form']) || !is_array($this->_block['form']) ) $this->_block['form'] = array();
foreach($this->_block['form'] as $form_key => $form_value) {
$_tmpb_form = &$this->_block['form'][$form_key]; ?>

		<script type="text/javascript">
		<!--
		function check_form_cl(){
			if(document.getElementById('title').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TITLE']) ? $this->_var['L_REQUIRE_TITLE'] : ''; ?>");
				return false;
			}
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
			}
			return true;
		}

		-->
		</script>

		<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.js"></script>
		<?php if( !isset($_tmpb_form['error_handler']) || !is_array($_tmpb_form['error_handler']) ) $_tmpb_form['error_handler'] = array();
foreach($_tmpb_form['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_form['error_handler'][$error_handler_key]; ?>
		<span id="errorh"></span>
		<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
			<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
			<br />	
		</div>
		<?php } ?>
		
		<form action="calendar.php<?php echo isset($_tmpb_form['UPDATE']) ? $_tmpb_form['UPDATE'] : ''; ?>" method="post" onsubmit="return check_form_cl();" class="fieldset_content" style="width:70%">
			<fieldset>
				<legend><?php echo isset($this->_var['L_EDIT_EVENT']) ? $this->_var['L_EDIT_EVENT'] : ''; ?></legend>
				<dl class="overflow_visible">
					<dt><label for="date">* <?php echo isset($this->_var['L_DATE_CALENDAR']) ? $this->_var['L_DATE_CALENDAR'] : ''; ?></label></dt>
					<dd><label>
						<?php echo isset($this->_var['L_ON']) ? $this->_var['L_ON'] : ''; ?>&nbsp;
						<label><input type="text" size="8" maxlength="8" id="date" name="date" value="<?php echo isset($_tmpb_form['DATE']) ? $_tmpb_form['DATE'] : ''; ?>" class="text" /></label> 
						
						<div style="position:relative;z-index:100;top:220px;left:90px;float:left;display:none;" id="calendar1">
							<div id="cl_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
							</div>
						</div>
						<a onClick="xmlhttprequest_calendar('cl_date', '?input_field=date&amp;field=cl_date&amp;d=<?php echo isset($_tmpb_form['DAY_DATE']) ? $_tmpb_form['DAY_DATE'] : ''; ?>&amp;m=<?php echo isset($_tmpb_form['MONTH_DATE']) ? $_tmpb_form['MONTH_DATE'] : ''; ?>&amp;y=<?php echo isset($_tmpb_form['YEAR_DATE']) ? $_tmpb_form['YEAR_DATE'] : ''; ?>');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
					
						<?php echo isset($this->_var['L_AT']) ? $this->_var['L_AT'] : ''; ?>
						<label><input type="text" size="2" maxlength="2" name="hour" value="<?php echo isset($_tmpb_form['HOUR']) ? $_tmpb_form['HOUR'] : ''; ?>" class="text" /></label> H <label><input type="text" size="2" maxlength="2" name="min" value="<?php echo isset($_tmpb_form['MIN']) ? $_tmpb_form['MIN'] : ''; ?>" class="text" /></label>
					</dd>
				</dl>
				<dl>
					<dt><label for="title">* <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label></dt>
					<dd><label><input type="text" maxlength="50" size="50" maxlength="150" id="title" name="title" value="<?php echo isset($_tmpb_form['TITLE']) ? $_tmpb_form['TITLE'] : ''; ?>" class="text" /></label></dd>
				</dl>
				<br />
				<label for="contents">* <?php echo isset($this->_var['L_ACTION']) ? $this->_var['L_ACTION'] : ''; ?></label>
				<?php $this->tpl_include('handle_bbcode'); ?>
				<label><textarea type="text" rows="10" cols="60" id="contents" name="contents"><?php echo isset($_tmpb_form['CONTENTS']) ? $_tmpb_form['CONTENTS'] : ''; ?></textarea> </label>
			</fieldset>	
			
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?></legend>
				<input type="submit" name="valid" value="<?php echo isset($this->_var['L_SUBMIT']) ? $this->_var['L_SUBMIT'] : ''; ?>" class="submit" />
				&nbsp;&nbsp; 
				<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />
			</fieldset>
		</form>

		<?php } ?>
