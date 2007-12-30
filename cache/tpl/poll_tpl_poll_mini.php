		<?php if( !isset($this->_block['question']) || !is_array($this->_block['question']) ) $this->_block['question'] = array();
foreach($this->_block['question'] as $question_key => $question_value) {
$_tmpb_question = &$this->_block['question'][$question_key]; ?>		
		<form method="post" action="../poll/poll<?php echo isset($_tmpb_question['ID']) ? $_tmpb_question['ID'] : ''; ?>" class="normal_form">
			<div class="module_mini_container">
				<div class="module_mini_top">
					<h5 class="sub_title"><?php echo isset($this->_var['L_MINI_POLL']) ? $this->_var['L_MINI_POLL'] : ''; ?></h5>
				</div>
				<div class="module_mini_table" style="text-align:center">
					<span style="font-size:10px;"><?php echo isset($_tmpb_question['QUESTION']) ? $_tmpb_question['QUESTION'] : ''; ?></span>

					<hr style="width:90%;" />
					<p style="padding-left: 6px;text-align: left;">		
						<?php if( !isset($_tmpb_question['radio']) || !is_array($_tmpb_question['radio']) ) $_tmpb_question['radio'] = array();
foreach($_tmpb_question['radio'] as $radio_key => $radio_value) {
$_tmpb_radio = &$_tmpb_question['radio'][$radio_key]; ?>
						<label><input type="<?php echo isset($_tmpb_radio['TYPE']) ? $_tmpb_radio['TYPE'] : ''; ?>" name="radio" value="<?php echo isset($_tmpb_radio['NAME']) ? $_tmpb_radio['NAME'] : ''; ?>" /> <span class="text_small"><?php echo isset($_tmpb_radio['ANSWERS']) ? $_tmpb_radio['ANSWERS'] : ''; ?></span></label>
						<br /><br />	
						<?php } ?>
					
						<?php if( !isset($_tmpb_question['checkbox']) || !is_array($_tmpb_question['checkbox']) ) $_tmpb_question['checkbox'] = array();
foreach($_tmpb_question['checkbox'] as $checkbox_key => $checkbox_value) {
$_tmpb_checkbox = &$_tmpb_question['checkbox'][$checkbox_key]; ?>
						<label><input type="<?php echo isset($_tmpb_checkbox['TYPE']) ? $_tmpb_checkbox['TYPE'] : ''; ?>" name="<?php echo isset($_tmpb_checkbox['NAME']) ? $_tmpb_checkbox['NAME'] : ''; ?>" value="<?php echo isset($_tmpb_checkbox['NAME']) ? $_tmpb_checkbox['NAME'] : ''; ?>" /> <span class="text_small"><?php echo isset($_tmpb_checkbox['ANSWERS']) ? $_tmpb_checkbox['ANSWERS'] : ''; ?></span></label>
						<br /><br />	
						<?php } ?>
					</p>
					<p>
						<input class="submit" name="valid_poll" type="submit" value="<?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>" /><br />
						<a class="small_link" href="../poll/poll<?php echo isset($this->_var['U_POLL_RESULT']) ? $this->_var['U_POLL_RESULT'] : ''; ?>"><?php echo isset($this->_var['L_POLL_RESULT']) ? $this->_var['L_POLL_RESULT'] : ''; ?></a>
					</p>
				</div>	
				<div class="module_mini_bottom">
				</div>
			</div>		
		</form>	
		<?php } ?>	

		<?php if( !isset($this->_block['result']) || !is_array($this->_block['result']) ) $this->_block['result'] = array();
foreach($this->_block['result'] as $result_key => $result_value) {
$_tmpb_result = &$this->_block['result'][$result_key]; ?>
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title"><?php echo isset($this->_var['L_MINI_POLL']) ? $this->_var['L_MINI_POLL'] : ''; ?></h5>
			</div>
			<div class="module_mini_table" style="text-align:center">			
				<span style="font-size:10px;"><?php echo isset($_tmpb_result['QUESTION']) ? $_tmpb_result['QUESTION'] : ''; ?></span>
				
				<hr style="width:90%;" />
				<?php if( !isset($_tmpb_result['answers']) || !is_array($_tmpb_result['answers']) ) $_tmpb_result['answers'] = array();
foreach($_tmpb_result['answers'] as $answers_key => $answers_value) {
$_tmpb_answers = &$_tmpb_result['answers'][$answers_key]; ?>					
				<p style="padding-left: 6px;text-align: left;">
					<span class="text_small"><?php echo isset($_tmpb_answers['ANSWERS']) ? $_tmpb_answers['ANSWERS'] : ''; ?></span>
					<br />
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll_left.png" height="8px" width="" alt="<?php echo isset($_tmpb_answers['WIDTH']) ? $_tmpb_answers['WIDTH'] : ''; ?>%" title="<?php echo isset($_tmpb_answers['WIDTH']) ? $_tmpb_answers['WIDTH'] : ''; ?>%" /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll.png" height="8px" width="<?php echo isset($_tmpb_answers['WIDTH']) ? $_tmpb_answers['WIDTH'] : ''; ?>" alt="<?php echo isset($_tmpb_answers['WIDTH']) ? $_tmpb_answers['WIDTH'] : ''; ?>%" title="<?php echo isset($_tmpb_answers['WIDTH']) ? $_tmpb_answers['WIDTH'] : ''; ?>%" /><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/poll_right.png" height="8px" width="" alt="<?php echo isset($_tmpb_answers['WIDTH']) ? $_tmpb_answers['WIDTH'] : ''; ?>%" title="<?php echo isset($_tmpb_answers['WIDTH']) ? $_tmpb_answers['WIDTH'] : ''; ?>%" />
					<span class="text_small">
						<?php echo isset($_tmpb_answers['PERCENT']) ? $_tmpb_answers['PERCENT'] : ''; ?>%
					</span>
				</p>			
				<?php } ?>
				
				<div class="text_small" style="padding-left: 8px;padding-top: 10px;">
					<?php echo isset($_tmpb_result['VOTES']) ? $_tmpb_result['VOTES'] : ''; echo ' '; echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>
				</div>
			</div>		
			<div class="module_mini_bottom">
			</div>
		</div>
		<?php } ?>
			