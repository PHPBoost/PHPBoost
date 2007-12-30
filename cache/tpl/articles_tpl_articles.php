		<?php if( !isset($this->_block['article']) || !is_array($this->_block['article']) ) $this->_block['article'] = array();
foreach($this->_block['article'] as $article_key => $article_value) {
$_tmpb_article = &$this->_block['article'][$article_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<?php echo isset($_tmpb_article['NAME']) ? $_tmpb_article['NAME'] : ''; ?> 
				</div>
				<div style="float:right">
					<?php echo isset($_tmpb_article['COM']) ? $_tmpb_article['COM'] : ''; echo ' '; echo isset($this->_var['EDIT']) ? $this->_var['EDIT'] : ''; echo ' '; echo isset($this->_var['DEL']) ? $this->_var['DEL'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<?php if( isset($_tmpb_article['PAGINATION_ARTICLES']) && $_tmpb_article['PAGINATION_ARTICLES'] ) { ?>
					<p style="text-align:center"><?php echo isset($_tmpb_article['PAGINATION_ARTICLES']) ? $_tmpb_article['PAGINATION_ARTICLES'] : ''; ?></p>
				<?php } ?>
				
				<?php echo isset($_tmpb_article['CONTENTS']) ? $_tmpb_article['CONTENTS'] : ''; ?>
				
				<?php if( isset($_tmpb_article['PAGINATION_ARTICLES']) && $_tmpb_article['PAGINATION_ARTICLES'] ) { ?>
					<p style="text-align:center"><?php echo isset($_tmpb_article['PAGINATION_ARTICLES']) ? $_tmpb_article['PAGINATION_ARTICLES'] : ''; ?></p>
				<?php } ?>
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left" class="text_small">
					<?php echo isset($_tmpb_article['L_NOTE']) ? $_tmpb_article['L_NOTE'] : ''; ?>: <?php echo isset($_tmpb_article['NOTE']) ? $_tmpb_article['NOTE'] : ''; ?>
				</div>
				<div style="float:right" class="text_small">
					<?php echo isset($_tmpb_article['L_WRITTEN']) ? $_tmpb_article['L_WRITTEN'] : ''; ?>: <a href="../member/member<?php echo isset($_tmpb_article['U_MEMBER_ID']) ? $_tmpb_article['U_MEMBER_ID'] : ''; ?>"><?php echo isset($_tmpb_article['PSEUDO']) ? $_tmpb_article['PSEUDO'] : ''; ?></a>, <?php echo isset($_tmpb_article['L_ON']) ? $_tmpb_article['L_ON'] : ''; ?>: <?php echo isset($_tmpb_article['DATE']) ? $_tmpb_article['DATE'] : ''; ?>
				</div>
			</div>
		</div>
		
		<br /><br />
		<?php $this->tpl_include('handle_com'); ?>
		
		<?php } ?>


		<?php if( !isset($this->_block['note']) || !is_array($this->_block['note']) ) $this->_block['note'] = array();
foreach($this->_block['note'] as $note_key => $note_value) {
$_tmpb_note = &$this->_block['note'][$note_key]; ?>
		<form action="../articles/articles<?php echo isset($_tmpb_note['U_ARTICLE_ACTION_NOTE']) ? $_tmpb_note['U_ARTICLE_ACTION_NOTE'] : ''; ?>" method="post" class="fieldset_content">
			<span id="note"></span>
			<fieldset>
				<legend><?php echo isset($this->_var['L_NOTE']) ? $this->_var['L_NOTE'] : ''; ?></legend>
				<dl>
					<dt><label for="note_select"><?php echo isset($this->_var['L_NOTE']) ? $this->_var['L_NOTE'] : ''; ?></label></dt>
					<dd>
						<span class="text_small"><?php echo isset($this->_var['L_ACTUAL_NOTE']) ? $this->_var['L_ACTUAL_NOTE'] : ''; ?>: <?php echo isset($_tmpb_note['NOTE']) ? $_tmpb_note['NOTE'] : ''; ?></span>	
						<label>
							<select id="note_select" name="note">
								<?php echo isset($_tmpb_note['SELECT']) ? $_tmpb_note['SELECT'] : ''; ?>
							</select>
						</label>
					</dd>					
				</dl>
			</fieldset>
			<fieldset class="fieldset_submit">
				<legend><?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?></legend>
				<input type="submit" name="valid_note" value="<?php echo isset($this->_var['L_VOTE']) ? $this->_var['L_VOTE'] : ''; ?>" class="submit" />
			</fieldset>
		</form>
		<?php } ?>
		