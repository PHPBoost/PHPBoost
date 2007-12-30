		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><?php echo isset($this->_var['TITLE']) ? $this->_var['TITLE'] : ''; ?></div>
			<div class="module_contents">
				<br />		
				<?php $this->tpl_include('wiki_tools'); ?>
				<br /><br />
				<?php echo isset($this->_var['INDEX_TEXT']) ? $this->_var['INDEX_TEXT'] : ''; ?>
				<br /><br /><br />
				<div style="text-align:center;" class="row3">
					<a href="<?php echo isset($this->_var['U_EXPLORER']) ? $this->_var['U_EXPLORER'] : ''; ?>"><img src="<?php echo isset($this->_var['WIKI_PATH']) ? $this->_var['WIKI_PATH'] : ''; ?>/images/explorer.png" alt="<?php echo isset($this->_var['L_EXPLORER']) ? $this->_var['L_EXPLORER'] : ''; ?>" class="valign_middle" /></a> &nbsp; <a href="<?php echo isset($this->_var['U_EXPLORER']) ? $this->_var['U_EXPLORER'] : ''; ?>"><?php echo isset($this->_var['L_EXPLORER']) ? $this->_var['L_EXPLORER'] : ''; ?></a>
				</div>
				<br />
				<?php if( !isset($this->_block['cat_list']) || !is_array($this->_block['cat_list']) ) $this->_block['cat_list'] = array();
foreach($this->_block['cat_list'] as $cat_list_key => $cat_list_value) {
$_tmpb_cat_list = &$this->_block['cat_list'][$cat_list_key]; ?>
					<hr /><br />
					<strong><em><?php echo isset($_tmpb_cat_list['L_CATS']) ? $_tmpb_cat_list['L_CATS'] : ''; ?></em></strong>
					<br /><br />
					<?php if( !isset($_tmpb_cat_list['list']) || !is_array($_tmpb_cat_list['list']) ) $_tmpb_cat_list['list'] = array();
foreach($_tmpb_cat_list['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_cat_list['list'][$list_key]; ?>
						<img src="<?php echo isset($this->_var['WIKI_PATH']) ? $this->_var['WIKI_PATH'] : ''; ?>/images/cat.png" class="valign_middle" alt="" />&nbsp;<a href="<?php echo isset($_tmpb_list['U_CAT']) ? $_tmpb_list['U_CAT'] : ''; ?>"><?php echo isset($_tmpb_list['CAT']) ? $_tmpb_list['CAT'] : ''; ?></a><br />
					<?php } ?>
					<?php echo isset($this->_var['L_NO_CAT']) ? $this->_var['L_NO_CAT'] : ''; ?>
				<?php } ?>
				
				<?php if( !isset($this->_block['last_articles']) || !is_array($this->_block['last_articles']) ) $this->_block['last_articles'] = array();
foreach($this->_block['last_articles'] as $last_articles_key => $last_articles_value) {
$_tmpb_last_articles = &$this->_block['last_articles'][$last_articles_key]; ?>				
				<hr /><br />
				<table class="module_table">
					<tr>
						<th colspan="2">
							<strong><em><?php echo isset($_tmpb_last_articles['L_ARTICLES']) ? $_tmpb_last_articles['L_ARTICLES'] : ''; ?></em></strong> <?php echo isset($_tmpb_last_articles['RSS']) ? $_tmpb_last_articles['RSS'] : ''; ?>
						</th>
					</tr>
					<tr>
						<?php if( !isset($_tmpb_last_articles['list']) || !is_array($_tmpb_last_articles['list']) ) $_tmpb_last_articles['list'] = array();
foreach($_tmpb_last_articles['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_last_articles['list'][$list_key]; ?>
						<?php echo isset($_tmpb_list['TR']) ? $_tmpb_list['TR'] : ''; ?>
							<td class="row2" style="width:50%">
								<img src="<?php echo isset($this->_var['WIKI_PATH']) ? $this->_var['WIKI_PATH'] : ''; ?>/images/article.png" class="valign_middle" alt="" />&nbsp;<a href="<?php echo isset($_tmpb_list['U_ARTICLE']) ? $_tmpb_list['U_ARTICLE'] : ''; ?>"><?php echo isset($_tmpb_list['ARTICLE']) ? $_tmpb_list['ARTICLE'] : ''; ?></a>
							</td>
						<?php } ?>
						<?php echo isset($this->_var['L_NO_ARTICLE']) ? $this->_var['L_NO_ARTICLE'] : ''; ?>
					</tr>
				</table>
				<?php } ?>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom"></div>
		</div>
		