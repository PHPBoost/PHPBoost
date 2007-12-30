		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_CONFIRM_ERASE_POOL']) ? $this->_var['L_CONFIRM_ERASE_POOL'] : ''; ?>");
		}
		-->
		</script>
		
		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_POLL_MANAGEMENT']) ? $this->_var['L_POLL_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_poll.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll.php" class="quick_link"><?php echo isset($this->_var['L_POLL_MANAGEMENT']) ? $this->_var['L_POLL_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_poll_add.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_add.php" class="quick_link"><?php echo isset($this->_var['L_POLL_ADD']) ? $this->_var['L_POLL_ADD'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_poll_config.php"><img src="poll.png" alt="" /></a>
					<br />
					<a href="admin_poll_config.php" class="quick_link"><?php echo isset($this->_var['L_POLL_CONFIG']) ? $this->_var['L_POLL_CONFIG'] : ''; ?></a>
				</li>
			</ul>
		</div> 
		
		<div id="admin_contents">
			
			
			<table class="module_table">
				<tr style="text-align:center;">
					<th>
						<?php echo isset($this->_var['L_POLLS']) ? $this->_var['L_POLLS'] : ''; ?>
					</th>
					<th>
						<?php echo isset($this->_var['L_QUESTION']) ? $this->_var['L_QUESTION'] : ''; ?>
					</th>
					<th>
						<?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?>
					</th>
					<th>
						<?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>
					</th>
					<th>
						<?php echo isset($this->_var['L_ARCHIVED']) ? $this->_var['L_ARCHIVED'] : ''; ?>
					</th>
					<th>
						<?php echo isset($this->_var['L_APROB']) ? $this->_var['L_APROB'] : ''; ?>
					</th>
					<th>
						<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>
					</th>
					<th>
						<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>
					</th>
				</tr>
				
				<?php if( !isset($this->_block['questions']) || !is_array($this->_block['questions']) ) $this->_block['questions'] = array();
foreach($this->_block['questions'] as $questions_key => $questions_value) {
$_tmpb_questions = &$this->_block['questions'][$questions_key]; ?>
				<tr style="text-align:center;"> 
					<td class="row2">
						<a href="../poll/poll.php?id=<?php echo isset($_tmpb_questions['IDPOLL']) ? $_tmpb_questions['IDPOLL'] : ''; ?>"><?php echo isset($this->_var['L_SHOW']) ? $this->_var['L_SHOW'] : ''; ?></a>
					</td>
					<td class="row2"> 
						<?php echo isset($_tmpb_questions['QUESTIONS']) ? $_tmpb_questions['QUESTIONS'] : ''; ?>
					</td>			
					<td class="row2"> 
						<?php echo isset($_tmpb_questions['PSEUDO']) ? $_tmpb_questions['PSEUDO'] : ''; ?>
					</td>
					<td class="row2">
						<?php echo isset($_tmpb_questions['DATE']) ? $_tmpb_questions['DATE'] : ''; ?>
					</td>
					<td class="row2">
						<?php echo isset($_tmpb_questions['ARCHIVES']) ? $_tmpb_questions['ARCHIVES'] : ''; ?>
					</td>	
					<td class="row2">
						<?php echo isset($_tmpb_questions['APROBATION']) ? $_tmpb_questions['APROBATION'] : ''; ?> 
						<br />
						<span class="text_small"><?php echo isset($_tmpb_questions['VISIBLE']) ? $_tmpb_questions['VISIBLE'] : ''; ?></span>
					</td>
					<td class="row2"> 
						<a href="admin_poll.php?id=<?php echo isset($_tmpb_questions['IDPOLL']) ? $_tmpb_questions['IDPOLL'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" title="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" /></a>
					</td>
					<td class="row2">
						<a href="admin_poll.php?delete=true&amp;id=<?php echo isset($_tmpb_questions['IDPOLL']) ? $_tmpb_questions['IDPOLL'] : ''; ?>" onClick="javascript:return Confirm();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" /></a>
					</td>
				</tr>
				<?php } ?>

			</table>

			<br /><br />
			<p style="text-align: center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></p>
		</div>
