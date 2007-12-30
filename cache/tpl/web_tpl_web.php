		<?php echo isset($this->_var['JAVA']) ? $this->_var['JAVA'] : ''; ?> 

		<?php if( !isset($this->_block['cat']) || !is_array($this->_block['cat']) ) $this->_block['cat'] = array();
foreach($this->_block['cat'] as $cat_key => $cat_value) {
$_tmpb_cat = &$this->_block['cat'][$cat_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<?php echo isset($_tmpb_cat['L_CATEGORIES']) ? $_tmpb_cat['L_CATEGORIES'] : ''; echo ' '; echo isset($_tmpb_cat['EDIT']) ? $_tmpb_cat['EDIT'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($_tmpb_cat['PAGINATION']) ? $_tmpb_cat['PAGINATION'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<?php if( !isset($_tmpb_cat['web']) || !is_array($_tmpb_cat['web']) ) $_tmpb_cat['web'] = array();
foreach($_tmpb_cat['web'] as $web_key => $web_value) {
$_tmpb_web = &$_tmpb_cat['web'][$web_key]; ?>
				<div style="float:left;text-align:center;width:<?php echo isset($_tmpb_web['WIDTH']) ? $_tmpb_web['WIDTH'] : ''; ?>%;height:80px;">
					<?php echo isset($_tmpb_web['U_IMG_CAT']) ? $_tmpb_web['U_IMG_CAT'] : ''; ?>
					<a href="../web/web<?php echo isset($_tmpb_web['U_WEB_CAT']) ? $_tmpb_web['U_WEB_CAT'] : ''; ?>"><?php echo isset($_tmpb_web['CAT']) ? $_tmpb_web['CAT'] : ''; ?></a> (<?php echo isset($_tmpb_web['TOTAL']) ? $_tmpb_web['TOTAL'] : ''; ?>)<br />
					<span class="text_small"><?php echo isset($_tmpb_web['CONTENTS']) ? $_tmpb_web['CONTENTS'] : ''; ?></span>
					<br /><br /><br />
				</div>	
				<?php } ?>
				
				<div class="text_small" style="text-align:center;clear:both">
					<?php echo isset($_tmpb_cat['TOTAL_FILE']) ? $_tmpb_cat['TOTAL_FILE'] : ''; echo ' '; echo isset($_tmpb_cat['L_HOW_LINK']) ? $_tmpb_cat['L_HOW_LINK'] : ''; ?>
				</div>
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:right">
					<?php echo isset($_tmpb_cat['PAGINATION']) ? $_tmpb_cat['PAGINATION'] : ''; ?>
				</div>
			</div>
		</div>
		<?php } ?>

		
		
		<?php if( !isset($this->_block['link']) || !is_array($this->_block['link']) ) $this->_block['link'] = array();
foreach($this->_block['link'] as $link_key => $link_value) {
$_tmpb_link = &$this->_block['link'][$link_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<?php echo isset($_tmpb_link['CAT_NAME']) ? $_tmpb_link['CAT_NAME'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<table class="module_table">
					<tr>
						<th style="text-align:center;">
							<a href="web<?php echo isset($this->_var['U_WEB_ALPHA_TOP']) ? $this->_var['U_WEB_ALPHA_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_LINK']) ? $this->_var['L_LINK'] : ''; ?>
							<a href="web<?php echo isset($this->_var['U_WEB_ALPHA_BOTTOM']) ? $this->_var['U_WEB_ALPHA_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web<?php echo isset($this->_var['U_WEB_DATE_TOP']) ? $this->_var['U_WEB_DATE_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>					
							<a href="web<?php echo isset($this->_var['U_WEB_DATE_BOTTOM']) ? $this->_var['U_WEB_DATE_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web<?php echo isset($this->_var['U_WEB_VIEW_TOP']) ? $this->_var['U_WEB_VIEW_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_VIEW']) ? $this->_var['L_VIEW'] : ''; ?>					
							<a href="web<?php echo isset($this->_var['U_WEB_VIEW_BOTTOM']) ? $this->_var['U_WEB_VIEW_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web<?php echo isset($this->_var['U_WEB_NOTE_TOP']) ? $this->_var['U_WEB_NOTE_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_NOTE']) ? $this->_var['L_NOTE'] : ''; ?>					
							<a href="web<?php echo isset($this->_var['U_WEB_NOTE_BOTTOM']) ? $this->_var['U_WEB_NOTE_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
						<th style="text-align:center;">
							<a href="web<?php echo isset($this->_var['U_WEB_COM_TOP']) ? $this->_var['U_WEB_COM_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_COM']) ? $this->_var['L_COM'] : ''; ?>
							<a href="web<?php echo isset($this->_var['U_WEB_COM_BOTTOM']) ? $this->_var['U_WEB_COM_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</th>
					</tr>
					<?php if( !isset($_tmpb_link['web']) || !is_array($_tmpb_link['web']) ) $_tmpb_link['web'] = array();
foreach($_tmpb_link['web'] as $web_key => $web_value) {
$_tmpb_web = &$_tmpb_link['web'][$web_key]; ?>
					<tr>	
						<td class="row2">
							&raquo; <a href="web<?php echo isset($_tmpb_web['U_WEB_LINK']) ? $_tmpb_web['U_WEB_LINK'] : ''; ?>"><?php echo isset($_tmpb_web['NAME']) ? $_tmpb_web['NAME'] : ''; ?></a>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_web['DATE']) ? $_tmpb_web['DATE'] : ''; ?>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_web['COMPT']) ? $_tmpb_web['COMPT'] : ''; ?> 
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_web['NOTE']) ? $_tmpb_web['NOTE'] : ''; ?>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_web['COM']) ? $_tmpb_web['COM'] : ''; ?> 
						</td>
					</tr>
					<?php } ?>
				</table>
				<p style="text-align:center;padding:6px;"><?php echo isset($_tmpb_link['NO_CAT']) ? $_tmpb_link['NO_CAT'] : ''; ?></p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left">
					<strong><?php echo isset($_tmpb_link['CAT_NAME']) ? $_tmpb_link['CAT_NAME'] : ''; ?></strong>
				</div>
				<div style="float:right">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
				</div>
			</div>
		</div>		
		<?php } ?>

		

		<?php if( !isset($this->_block['web']) || !is_array($this->_block['web']) ) $this->_block['web'] = array();
foreach($this->_block['web'] as $web_key => $web_value) {
$_tmpb_web = &$this->_block['web'][$web_key]; ?>
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left">
					<?php echo isset($_tmpb_web['NAME']) ? $_tmpb_web['NAME'] : ''; echo ' '; echo isset($this->_var['EDIT']) ? $this->_var['EDIT'] : '';  echo isset($this->_var['DEL']) ? $this->_var['DEL'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($_tmpb_web['COM']) ? $_tmpb_web['COM'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<p>					
					<strong><?php echo isset($_tmpb_web['L_DESC']) ? $_tmpb_web['L_DESC'] : ''; ?>:</strong> <?php echo isset($_tmpb_web['CONTENTS']) ? $_tmpb_web['CONTENTS'] : ''; ?>
					<br /><br />
					<strong><?php echo isset($_tmpb_web['L_CAT']) ? $_tmpb_web['L_CAT'] : ''; ?>:</strong> 
					<a href="../web/web<?php echo isset($_tmpb_web['U_WEB_CAT']) ? $_tmpb_web['U_WEB_CAT'] : ''; ?>" title="<?php echo isset($_tmpb_web['CAT']) ? $_tmpb_web['CAT'] : ''; ?>"><?php echo isset($_tmpb_web['CAT']) ? $_tmpb_web['CAT'] : ''; ?></a><br />
					
					<strong><?php echo isset($_tmpb_web['L_DATE']) ? $_tmpb_web['L_DATE'] : ''; ?>:</strong> <?php echo isset($_tmpb_web['DATE']) ? $_tmpb_web['DATE'] : ''; ?><br />						
					<strong><?php echo isset($_tmpb_web['L_VIEWS']) ? $_tmpb_web['L_VIEWS'] : ''; ?>:</strong> <?php echo isset($_tmpb_web['COMPT']) ? $_tmpb_web['COMPT'] : ''; echo ' '; echo isset($_tmpb_web['L_TIMES']) ? $_tmpb_web['L_TIMES'] : ''; ?>
					<div class="spacer">&nbsp;</div>
				</p>
				<p style="text-align: center;">					
					<a href="<?php echo isset($_tmpb_web['URL']) ? $_tmpb_web['URL'] : ''; ?>" title="<?php echo isset($_tmpb_web['NAME']) ? $_tmpb_web['NAME'] : ''; ?>" onclick="document.location = 'count.php?id=<?php echo isset($_tmpb_web['IDWEB']) ? $_tmpb_web['IDWEB'] : ''; ?>';"><img src="<?php echo isset($_tmpb_web['MODULE_DATA_PATH']) ? $_tmpb_web['MODULE_DATA_PATH'] : ''; ?>/images/<?php echo isset($_tmpb_web['LANG']) ? $_tmpb_web['LANG'] : ''; ?>/bouton_url.gif" alt="" /></a>
					<br />
					<?php echo isset($_tmpb_web['URL']) ? $_tmpb_web['URL'] : ''; ?>
				</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<strong><?php echo isset($_tmpb_web['L_NOTE']) ? $_tmpb_web['L_NOTE'] : ''; ?>:</strong> <?php echo isset($_tmpb_web['NOTE']) ? $_tmpb_web['NOTE'] : ''; ?>&nbsp;
			</div>
		</div>
			
		<br /><br />
		<?php $this->tpl_include('handle_com'); ?>
		
		<?php } ?>


		<?php if( !isset($this->_block['note']) || !is_array($this->_block['note']) ) $this->_block['note'] = array();
foreach($this->_block['note'] as $note_key => $note_value) {
$_tmpb_note = &$this->_block['note'][$note_key]; ?>
		<form action="../web/web<?php echo isset($_tmpb_note['U_WEB_ACTION_NOTE']) ? $_tmpb_note['U_WEB_ACTION_NOTE'] : ''; ?>" method="post" class="fieldset_content">
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
		