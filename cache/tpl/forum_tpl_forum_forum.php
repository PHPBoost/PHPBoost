		<?php $this->tpl_include('forum_top'); ?>
		
		<?php if( !isset($this->_block['error_auth_write']) || !is_array($this->_block['error_auth_write']) ) $this->_block['error_auth_write'] = array();
foreach($this->_block['error_auth_write'] as $error_auth_write_key => $error_auth_write_value) {
$_tmpb_error_auth_write = &$this->_block['error_auth_write'][$error_auth_write_key]; ?>
		<div class="forum_text_column" style="width:350px;margin:auto;height:auto;padding:2px;margin-bottom:20px;">
			<?php echo isset($_tmpb_error_auth_write['L_ERROR_AUTH_WRITE']) ? $_tmpb_error_auth_write['L_ERROR_AUTH_WRITE'] : ''; ?>			
		</div>
		<?php } ?>
		
		<?php if( !isset($this->_block['cat']) || !is_array($this->_block['cat']) ) $this->_block['cat'] = array();
foreach($this->_block['cat'] as $cat_key => $cat_value) {
$_tmpb_cat = &$this->_block['cat'][$cat_key]; ?>		
		<div style="margin-top:20px;margin-bottom:20px;">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					<a href="rss.php" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a> 
					&nbsp;&nbsp;<strong><?php echo isset($_tmpb_cat['L_NAME']) ? $_tmpb_cat['L_NAME'] : ''; ?></strong>
				</div>
				<div class="module_contents forum_contents">
					<table class="module_table" style="width:100%">
						<tr>			
							<td class="forum_text_column" style="min-width:175px;"><?php echo isset($this->_var['L_FORUM']) ? $this->_var['L_FORUM'] : ''; ?></td>
							<td class="forum_text_column" style="width:60px;"><?php echo isset($this->_var['L_TOPIC']) ? $this->_var['L_TOPIC'] : ''; ?></td>
							<td class="forum_text_column" style="width:60px;"><?php echo isset($this->_var['L_MESSAGE']) ? $this->_var['L_MESSAGE'] : ''; ?></td>
							<td class="forum_text_column" style="width:150px;"><?php echo isset($this->_var['L_LAST_MESSAGE']) ? $this->_var['L_LAST_MESSAGE'] : ''; ?></td>
						</tr>
					</table>
				</div>
			</div>				
		<?php } ?>
		
		
		<?php if( !isset($this->_block['s_cats']) || !is_array($this->_block['s_cats']) ) $this->_block['s_cats'] = array();
foreach($this->_block['s_cats'] as $s_cats_key => $s_cats_value) {
$_tmpb_s_cats = &$this->_block['s_cats'][$s_cats_key]; ?>			
			<div class="module_position">
				<div class="module_contents forum_contents">
					<table class="module_table" style="width:100%">
						<tr>
							<td class="forum_sous_cat" style="width:25px;text-align:center;">
								<?php echo isset($_tmpb_s_cats['ANNOUNCE']) ? $_tmpb_s_cats['ANNOUNCE'] : ''; ?>
							</td>
							<td class="forum_sous_cat" style="min-width:150px;">
								<a href="forum<?php echo isset($_tmpb_s_cats['U_FORUM_VARS']) ? $_tmpb_s_cats['U_FORUM_VARS'] : ''; ?>"><?php echo isset($_tmpb_s_cats['NAME']) ? $_tmpb_s_cats['NAME'] : ''; ?></a>
								<br />
								<span class="text_small"><?php echo isset($_tmpb_s_cats['DESC']) ? $_tmpb_s_cats['DESC'] : ''; ?></span>
								<span class="text_small"><?php echo isset($_tmpb_s_cats['SUBFORUMS']) ? $_tmpb_s_cats['SUBFORUMS'] : ''; ?></span>
							</td>
							<td class="forum_sous_cat_compteur">
								<?php echo isset($_tmpb_s_cats['NBR_TOPIC']) ? $_tmpb_s_cats['NBR_TOPIC'] : ''; ?>
							</td>
							<td class="forum_sous_cat_compteur">
								<?php echo isset($_tmpb_s_cats['NBR_MSG']) ? $_tmpb_s_cats['NBR_MSG'] : ''; ?>
							</td>
							<td class="forum_sous_cat_last">
								<?php echo isset($_tmpb_s_cats['U_LAST_TOPIC']) ? $_tmpb_s_cats['U_LAST_TOPIC'] : ''; ?>
							</td>
						</tr>	
					</table>		
				</div>
			</div>
		<?php } ?>
		
		<?php if( !isset($this->_block['end_cat']) || !is_array($this->_block['end_cat']) ) $this->_block['end_cat'] = array();
foreach($this->_block['end_cat'] as $end_cat_key => $end_cat_value) {
$_tmpb_end_cat = &$this->_block['end_cat'][$end_cat_key]; ?>
			<div class="module_position">
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>		
		</div>	
		<?php } ?>
				
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<span style="float:left;">
					<a href="rss.php?cat=<?php echo isset($this->_var['IDCAT']) ? $this->_var['IDCAT'] : ''; ?>" title="Rss"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a> &bull; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; echo ' '; echo isset($this->_var['U_POST_NEW_SUBJECT']) ? $this->_var['U_POST_NEW_SUBJECT'] : ''; ?>
				</span>
				<span style="float:right;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></span>&nbsp;
			</div>
			<div class="module_contents forum_contents">
				<table class="module_table" style="width:100%">
					<tr>			
						<td class="forum_text_column" style="min-width:175px;"><?php echo isset($this->_var['L_TOPIC']) ? $this->_var['L_TOPIC'] : ''; ?></td>
						<td class="forum_text_column" style="width:100px;"><?php echo isset($this->_var['L_AUTHOR']) ? $this->_var['L_AUTHOR'] : ''; ?></td>
						<td class="forum_text_column" style="width:60px;"><?php echo isset($this->_var['L_ANSWERS']) ? $this->_var['L_ANSWERS'] : ''; ?></td>
						<td class="forum_text_column" style="width:60px;"><?php echo isset($this->_var['L_VIEW']) ? $this->_var['L_VIEW'] : ''; ?></td>
						<td class="forum_text_column" style="width:150px;"><?php echo isset($this->_var['L_LAST_MESSAGE']) ? $this->_var['L_LAST_MESSAGE'] : ''; ?></td>
					</tr>
				</table>
			</div>			
		</div>	
		<div class="module_position">
			<div class="module_contents forum_contents">
				<table class="module_table" style="width:100%">
					<?php if( !isset($this->_block['msg_read']) || !is_array($this->_block['msg_read']) ) $this->_block['msg_read'] = array();
foreach($this->_block['msg_read'] as $msg_read_key => $msg_read_value) {
$_tmpb_msg_read = &$this->_block['msg_read'][$msg_read_key]; ?>
					<tr>
						<td class="forum_sous_cat" style="text-align:center;">
							0 <?php echo isset($_tmpb_msg_read['L_MSG_NOT_READ']) ? $_tmpb_msg_read['L_MSG_NOT_READ'] : ''; ?>
						</td>
					</tr>	
					<?php } ?>

					<?php if( !isset($this->_block['topics']) || !is_array($this->_block['topics']) ) $this->_block['topics'] = array();
foreach($this->_block['topics'] as $topics_key => $topics_value) {
$_tmpb_topics = &$this->_block['topics'][$topics_key]; ?>		
					<tr>
						<td class="forum_sous_cat" style="width:25px;text-align:center;">
							<?php if( isset($this->_var['C_MASS_MODO_CHECK']) && $this->_var['C_MASS_MODO_CHECK'] ) { ?> <input type="checkbox" name="ck<?php echo isset($_tmpb_topics['ID']) ? $_tmpb_topics['ID'] : ''; ?>" /> <?php } ?>
						</td>
						<td class="forum_sous_cat" style="width:25px;text-align:center;">
							<img src="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/images/<?php echo isset($_tmpb_topics['ANNOUNCE']) ? $_tmpb_topics['ANNOUNCE'] : ''; ?>.gif" alt="" />
						</td>
						<td class="forum_sous_cat" style="width:35px;text-align:center;">
							<?php echo isset($_tmpb_topics['DISPLAY_MSG']) ? $_tmpb_topics['DISPLAY_MSG'] : ''; echo ' '; echo isset($_tmpb_topics['TRACK']) ? $_tmpb_topics['TRACK'] : ''; echo ' '; echo isset($_tmpb_topics['POLL']) ? $_tmpb_topics['POLL'] : ''; ?>
						</td>
						<td class="forum_sous_cat" style="min-width:115px;">
							<?php echo isset($_tmpb_topics['ANCRE']) ? $_tmpb_topics['ANCRE'] : ''; ?> <strong><?php echo isset($_tmpb_topics['TYPE']) ? $_tmpb_topics['TYPE'] : ''; ?></strong> <a href="topic<?php echo isset($_tmpb_topics['U_TOPIC_VARS']) ? $_tmpb_topics['U_TOPIC_VARS'] : ''; ?>"><?php echo isset($_tmpb_topics['L_DISPLAY_MSG']) ? $_tmpb_topics['L_DISPLAY_MSG'] : ''; echo ' '; echo isset($_tmpb_topics['TITLE']) ? $_tmpb_topics['TITLE'] : ''; ?></a>
							<br />
							<span class="text_small"><?php echo isset($_tmpb_topics['DESC']) ? $_tmpb_topics['DESC'] : ''; ?></span> &nbsp;<span class="pagin_forum"><?php echo isset($_tmpb_topics['PAGINATION_TOPICS']) ? $_tmpb_topics['PAGINATION_TOPICS'] : ''; ?></span>
						</td>
						<td class="forum_sous_cat_compteur" style="width:100px;">
							<?php echo isset($_tmpb_topics['AUTHOR']) ? $_tmpb_topics['AUTHOR'] : ''; ?>
						</td>
						<td class="forum_sous_cat_compteur">
							<?php echo isset($_tmpb_topics['MSG']) ? $_tmpb_topics['MSG'] : ''; ?>
						</td>
						<td class="forum_sous_cat_compteur">
							<?php echo isset($_tmpb_topics['VUS']) ? $_tmpb_topics['VUS'] : ''; ?>
						</td>
						<td class="forum_sous_cat_last">
							<?php echo isset($_tmpb_topics['U_LAST_MSG']) ? $_tmpb_topics['U_LAST_MSG'] : ''; ?>
						</td>
					</tr>	
					<?php } ?>
					
					<?php if( !isset($this->_block['no_topics']) || !is_array($this->_block['no_topics']) ) $this->_block['no_topics'] = array();
foreach($this->_block['no_topics'] as $no_topics_key => $no_topics_value) {
$_tmpb_no_topics = &$this->_block['no_topics'][$no_topics_key]; ?>
					<tr>
						<td class="forum_sous_cat" style="text-align:center;">
							<?php echo isset($_tmpb_no_topics['L_NO_TOPICS']) ? $_tmpb_no_topics['L_NO_TOPICS'] : ''; ?>
						</td>
					</tr>
					<?php } ?>
				</table>		
			</div>
		</div>
				
		<div class="module_position">
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;" class="text_strong">
					<a href="rss.php?cat=<?php echo isset($this->_var['IDCAT']) ? $this->_var['IDCAT'] : ''; ?>" title="Rss"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a> &bull; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; echo ' '; echo isset($this->_var['U_POST_NEW_SUBJECT']) ? $this->_var['U_POST_NEW_SUBJECT'] : ''; ?>
				</span>
				<span style="float:right;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></span>&nbsp;
			</div>
		</div>
		
		<?php $this->tpl_include('forum_bottom'); ?>
		