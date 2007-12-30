		<?php $this->tpl_include('forum_top'); ?>

			
	<?php if( !isset($this->_block['all']) || !is_array($this->_block['all']) ) $this->_block['all'] = array();
foreach($this->_block['all'] as $all_key => $all_value) {
$_tmpb_all = &$this->_block['all'][$all_key]; ?>				
			<?php if( !isset($_tmpb_all['cats']) || !is_array($_tmpb_all['cats']) ) $_tmpb_all['cats'] = array();
foreach($_tmpb_all['cats'] as $cats_key => $cats_value) {
$_tmpb_cats = &$_tmpb_all['cats'][$cats_key]; ?>			
		<div style="margin-top:20px;">
			<div class="module_position">					
				<div class="module_top_l"></div>		
				<div class="module_top_r"></div>
				<div class="module_top">
					<a href="rss.php" title="Rss"><img style="vertical-align:middle;margin-top:-2px;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a> 
					&nbsp;&nbsp;<a href="<?php echo isset($_tmpb_cats['U_FORUM_VARS']) ? $_tmpb_cats['U_FORUM_VARS'] : ''; ?>" class="forum_link_cat"><?php echo isset($_tmpb_cats['NAME']) ? $_tmpb_cats['NAME'] : ''; ?></a>
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
			
			<?php if( !isset($_tmpb_all['s_cats']) || !is_array($_tmpb_all['s_cats']) ) $_tmpb_all['s_cats'] = array();
foreach($_tmpb_all['s_cats'] as $s_cats_key => $s_cats_value) {
$_tmpb_s_cats = &$_tmpb_all['s_cats'][$s_cats_key]; ?>		
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
			
			<?php if( !isset($_tmpb_all['end_s_cats']) || !is_array($_tmpb_all['end_s_cats']) ) $_tmpb_all['end_s_cats'] = array();
foreach($_tmpb_all['end_s_cats'] as $end_s_cats_key => $end_s_cats_value) {
$_tmpb_end_s_cats = &$_tmpb_all['end_s_cats'][$end_s_cats_key]; ?>
			<div class="module_position">
				<div class="module_bottom_l"></div>		
				<div class="module_bottom_r"></div>
				<div class="module_bottom"></div>
			</div>
		</div>	
			<?php } ?>
		
	<?php } ?>

		
		<?php $this->tpl_include('forum_bottom'); ?>

		