	<?php if( !isset($this->_block['main']) || !is_array($this->_block['main']) ) $this->_block['main'] = array();
foreach($this->_block['main'] as $main_key => $main_value) {
$_tmpb_main = &$this->_block['main'][$main_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><strong><?php echo isset($this->_var['L_POLL']) ? $this->_var['L_POLL'] : ''; echo ' '; echo isset($this->_var['EDIT']) ? $this->_var['EDIT'] : ''; ?></strong></div>
			<div class="module_contents" style="text-align:center;">
				<?php echo isset($this->_var['L_POLL_MAIN']) ? $this->_var['L_POLL_MAIN'] : ''; ?>
				<br /><br />		
				<?php if( !isset($_tmpb_main['poll']) || !is_array($_tmpb_main['poll']) ) $_tmpb_main['poll'] = array();
foreach($_tmpb_main['poll'] as $poll_key => $poll_value) {
$_tmpb_poll = &$_tmpb_main['poll'][$poll_key]; ?>					
				<a href="poll<?php echo isset($_tmpb_poll['U_POLL_ID']) ? $_tmpb_poll['U_POLL_ID'] : ''; ?>"><?php echo isset($_tmpb_poll['QUESTION']) ? $_tmpb_poll['QUESTION'] : ''; ?>
				<br />  
				<a href="poll<?php echo isset($_tmpb_poll['U_POLL_ID']) ? $_tmpb_poll['U_POLL_ID'] : ''; ?>"><img src="poll.png" alt="" /></a> 
				<br /><br />
				<?php } ?>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"><?php echo isset($this->_var['U_ARCHIVE']) ? $this->_var['U_ARCHIVE'] : ''; ?></div>
		</div>
	<?php } ?>
		
		
		
	<?php if( !isset($this->_block['poll']) || !is_array($this->_block['poll']) ) $this->_block['poll'] = array();
foreach($this->_block['poll'] as $poll_key => $poll_value) {
$_tmpb_poll = &$this->_block['poll'][$poll_key]; ?>
		<?php echo isset($this->_var['JAVA']) ? $this->_var['JAVA'] : ''; ?>
		
		<form method="post" action="poll<?php echo isset($this->_var['U_POLL_ACTION']) ? $this->_var['U_POLL_ACTION'] : ''; ?>">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top"><?php echo isset($_tmpb_poll['QUESTION']) ? $_tmpb_poll['QUESTION'] : ''; echo ' '; echo isset($this->_var['EDIT']) ? $this->_var['EDIT'] : '';  echo isset($this->_var['DEL']) ? $this->_var['DEL'] : ''; ?></div>
				<div class="module_contents">
					<?php if( !isset($_tmpb_poll['error_handler']) || !is_array($_tmpb_poll['error_handler']) ) $_tmpb_poll['error_handler'] = array();
foreach($_tmpb_poll['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_poll['error_handler'][$error_handler_key]; ?>
					<br />
					<span id="errorh"></span>
					<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
						<br />	
					</div>
					<?php } ?>
					
					<table class="module_table">
						<?php if( !isset($_tmpb_poll['question']) || !is_array($_tmpb_poll['question']) ) $_tmpb_poll['question'] = array();
foreach($_tmpb_poll['question'] as $question_key => $question_value) {
$_tmpb_question = &$_tmpb_poll['question'][$question_key]; ?>
						<?php if( !isset($_tmpb_question['radio']) || !is_array($_tmpb_question['radio']) ) $_tmpb_question['radio'] = array();
foreach($_tmpb_question['radio'] as $radio_key => $radio_value) {
$_tmpb_radio = &$_tmpb_question['radio'][$radio_key]; ?>
						<tr>
							<td class="row2" style="font-size:10px;">
								<label><input type="<?php echo isset($_tmpb_radio['TYPE']) ? $_tmpb_radio['TYPE'] : ''; ?>" name="radio" value="<?php echo isset($_tmpb_radio['NAME']) ? $_tmpb_radio['NAME'] : ''; ?>" /> <?php echo isset($_tmpb_radio['ANSWERS']) ? $_tmpb_radio['ANSWERS'] : ''; ?></label>
							</td>
						</tr>
						<?php } ?>
					
						<?php if( !isset($_tmpb_question['checkbox']) || !is_array($_tmpb_question['checkbox']) ) $_tmpb_question['checkbox'] = array();
foreach($_tmpb_question['checkbox'] as $checkbox_key => $checkbox_value) {
$_tmpb_checkbox = &$_tmpb_question['checkbox'][$checkbox_key]; ?>
						<tr>
							<td class="row2">
								<label><input type="<?php echo isset($_tmpb_checkbox['TYPE']) ? $_tmpb_checkbox['TYPE'] : ''; ?>" name="<?php echo isset($_tmpb_checkbox['NAME']) ? $_tmpb_checkbox['NAME'] : ''; ?>" value="<?php echo isset($_tmpb_checkbox['NAME']) ? $_tmpb_checkbox['NAME'] : ''; ?>" /> <?php echo isset($_tmpb_checkbox['ANSWERS']) ? $_tmpb_checkbox['ANSWERS'] : ''; ?></label>
							</td>
						</tr>
						<?php } ?>
						
						<tr>	
							<td class="row1" style="text-align:center;">	
								<input class="submit" name="valid_poll" type="submit" value="<?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>" /><br />
								<a class="small_link" href="../poll/poll<?php echo isset($this->_var['U_POLL_RESULT']) ? $this->_var['U_POLL_RESULT'] : ''; ?>"><?php echo isset($this->_var['L_RESULT']) ? $this->_var['L_RESULT'] : ''; ?></a>
							</td>
						</tr>
						<?php } ?>
						
						
						
						<?php if( !isset($_tmpb_poll['result']) || !is_array($_tmpb_poll['result']) ) $_tmpb_poll['result'] = array();
foreach($_tmpb_poll['result'] as $result_key => $result_value) {
$_tmpb_result = &$_tmpb_poll['result'][$result_key]; ?>
						<tr>
							<td class="row2" style="font-size:10px;">
								<?php echo isset($_tmpb_result['ANSWERS']) ? $_tmpb_result['ANSWERS'] : ''; ?>
								<table width="95%">
									<tr>
										<td>
											<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll_left.png" height="10px" width="" alt="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll.png" height="10px" width="<?php echo isset($_tmpb_result['WIDTH']) ? $_tmpb_result['WIDTH'] : ''; ?>" alt="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll_right.png" height="10px" width="" alt="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" /> <?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>% [<?php echo isset($_tmpb_result['NBRVOTE']) ? $_tmpb_result['NBRVOTE'] : ''; echo ' '; echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>]
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<?php } ?>	
						
						<tr>
							<td class="row3">
								<span class="text_small" style="float:left;"><?php echo isset($_tmpb_poll['VOTES']) ? $_tmpb_poll['VOTES'] : ''; echo ' '; echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?></span>
								<span class="text_small" style="float:right;"><?php echo isset($this->_var['L_ON']) ? $this->_var['L_ON'] : ''; ?>:&nbsp;&nbsp;<?php echo isset($_tmpb_poll['DATE']) ? $_tmpb_poll['DATE'] : ''; ?>&nbsp;&nbsp;</span>
							</td>
						</tr>
					</table>				
				</div>
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"><a href="poll.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_BACK_POLL']) ? $this->_var['L_BACK_POLL'] : ''; ?></a></div>
			</div>
		</form>
	<?php } ?>
	
	
	<?php if( !isset($this->_block['archives']) || !is_array($this->_block['archives']) ) $this->_block['archives'] = array();
foreach($this->_block['archives'] as $archives_key => $archives_value) {
$_tmpb_archives = &$this->_block['archives'][$archives_key]; ?>
		<script type='text/javascript'>
		<!--
		function Confirm() {
		return confirm('<?php echo isset($this->_var['L_ALERT_DELETE_POLL']) ? $this->_var['L_ALERT_DELETE_POLL'] : ''; ?>');
		}
		-->
		</script>
			
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span style="float:left;"><?php echo isset($this->_var['L_ARCHIVE']) ? $this->_var['L_ARCHIVE'] : ''; ?></span>
				&nbsp;<span style="float:right;"><?php echo isset($_tmpb_archives['PAGINATION']) ? $_tmpb_archives['PAGINATION'] : ''; ?></span>
			</div>
			<div class="module_contents">
				<?php if( !isset($_tmpb_archives['main']) || !is_array($_tmpb_archives['main']) ) $_tmpb_archives['main'] = array();
foreach($_tmpb_archives['main'] as $main_key => $main_value) {
$_tmpb_main = &$_tmpb_archives['main'][$main_key]; ?>
				<table class="module_table">
					<tr>
						<th><?php echo isset($_tmpb_main['QUESTION']) ? $_tmpb_main['QUESTION'] : ''; echo ' '; echo isset($_tmpb_main['EDIT']) ? $_tmpb_main['EDIT'] : '';  echo isset($_tmpb_main['DEL']) ? $_tmpb_main['DEL'] : ''; ?></th>
					</tr>	
					<tr>	
						<td class="row2">
							<table class="module_table">								
								<?php if( !isset($_tmpb_main['result']) || !is_array($_tmpb_main['result']) ) $_tmpb_main['result'] = array();
foreach($_tmpb_main['result'] as $result_key => $result_value) {
$_tmpb_result = &$_tmpb_main['result'][$result_key]; ?>
								<tr>
									<td class="row3" style="font-size:10px;">
										<?php echo isset($_tmpb_result['ANSWERS']) ? $_tmpb_result['ANSWERS'] : ''; ?>
										<table width="95%">
											<tr>
												<td>
													<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll_left.png" height="10px" width="" alt="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll.png" height="10px" width="<?php echo isset($_tmpb_result['WIDTH']) ? $_tmpb_result['WIDTH'] : ''; ?>" alt="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll_right.png" height="10px" width="" alt="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" title="<?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>%" /> <?php echo isset($_tmpb_result['PERCENT']) ? $_tmpb_result['PERCENT'] : ''; ?>% [<?php echo isset($_tmpb_result['NBRVOTE']) ? $_tmpb_result['NBRVOTE'] : ''; echo ' '; echo isset($_tmpb_result['L_VOTE']) ? $_tmpb_result['L_VOTE'] : ''; ?>]
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<?php } ?>									
							</table>
						</td>
					</tr>
					<tr>
						<td class="row2">
							<span class="text_small" style="float:left;"><?php echo isset($_tmpb_main['VOTE']) ? $_tmpb_main['VOTE'] : ''; echo ' '; echo isset($_tmpb_main['L_VOTE']) ? $_tmpb_main['L_VOTE'] : ''; ?></span>
							<span class="text_small" style="float: right;"><?php echo isset($this->_var['L_ON']) ? $this->_var['L_ON'] : ''; ?>:&nbsp;&nbsp;<?php echo isset($_tmpb_main['DATE']) ? $_tmpb_main['DATE'] : ''; ?>&nbsp;&nbsp;</span>
						</td>
					</tr>
				</table>						
				<br /><br />
					
				<?php } ?>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;"><a href="poll.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_BACK_POLL']) ? $this->_var['L_BACK_POLL'] : ''; ?></a></span>
				&nbsp;<span style="float:right;"><?php echo isset($_tmpb_archives['PAGINATION']) ? $_tmpb_archives['PAGINATION'] : ''; ?></span>
			</div>
		</div>
	<?php } ?>			
	