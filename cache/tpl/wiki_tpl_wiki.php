		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top"><?php echo isset($this->_var['TITLE']) ? $this->_var['TITLE'] : ''; ?></div>
			<div class="module_contents">
				<?php $this->tpl_include('wiki_tools'); ?>
				
				<?php if( !isset($this->_block['warning']) || !is_array($this->_block['warning']) ) $this->_block['warning'] = array();
foreach($this->_block['warning'] as $warning_key => $warning_value) {
$_tmpb_warning = &$this->_block['warning'][$warning_key]; ?>
					<br /><br />
					<div class="row3"><?php echo isset($_tmpb_warning['UPDATED_ARTICLE']) ? $_tmpb_warning['UPDATED_ARTICLE'] : ''; ?></div>
					<br />
				<?php } ?>
						
				<?php if( !isset($this->_block['redirect']) || !is_array($this->_block['redirect']) ) $this->_block['redirect'] = array();
foreach($this->_block['redirect'] as $redirect_key => $redirect_value) {
$_tmpb_redirect = &$this->_block['redirect'][$redirect_key]; ?>
					<div class="row2" style="width:30%;">
					<?php echo isset($_tmpb_redirect['REDIRECTED']) ? $_tmpb_redirect['REDIRECTED'] : ''; ?>
						<?php if( !isset($_tmpb_redirect['remove_redirection']) || !is_array($_tmpb_redirect['remove_redirection']) ) $_tmpb_redirect['remove_redirection'] = array();
foreach($_tmpb_redirect['remove_redirection'] as $remove_redirection_key => $remove_redirection_value) {
$_tmpb_remove_redirection = &$_tmpb_redirect['remove_redirection'][$remove_redirection_key]; ?>
							<a href="<?php echo isset($_tmpb_remove_redirection['U_REMOVE_REDIRECTION']) ? $_tmpb_remove_redirection['U_REMOVE_REDIRECTION'] : ''; ?>" title="<?php echo isset($_tmpb_remove_redirection['L_REMOVE_REDIRECTION']) ? $_tmpb_remove_redirection['L_REMOVE_REDIRECTION'] : ''; ?>" onclick="javascript: return confirm('<?php echo isset($_tmpb_remove_redirection['L_ALERT_REMOVE_REDIRECTION']) ? $_tmpb_remove_redirection['L_ALERT_REMOVE_REDIRECTION'] : ''; ?>');"><img src="<?php echo isset($this->_var['WIKI_PATH']) ? $this->_var['WIKI_PATH'] : ''; ?>/images/delete.png" alt="<?php echo isset($_tmpb_remove_redirection['L_REMOVE_REDIRECTION']) ? $_tmpb_remove_redirection['L_REMOVE_REDIRECTION'] : ''; ?>" style="vertical-align:middle;" /></a>
						<?php } ?>
					</div>
					<br />
				<?php } ?>
				
				<?php if( !isset($this->_block['status']) || !is_array($this->_block['status']) ) $this->_block['status'] = array();
foreach($this->_block['status'] as $status_key => $status_value) {
$_tmpb_status = &$this->_block['status'][$status_key]; ?>
					<br /><br />
					<div class="row3"><?php echo isset($_tmpb_status['ARTICLE_STATUS']) ? $_tmpb_status['ARTICLE_STATUS'] : ''; ?></div>
					<br />
				<?php } ?>
				
				<?php if( !isset($this->_block['message']) || !is_array($this->_block['message']) ) $this->_block['message'] = array();
foreach($this->_block['message'] as $message_key => $message_value) {
$_tmpb_message = &$this->_block['message'][$message_key]; ?>
					<?php echo isset($_tmpb_message['ARTICLE_DOES_NOT_EXIST']) ? $_tmpb_message['ARTICLE_DOES_NOT_EXIST'] : ''; ?>
				<?php } ?>
				
				<?php if( !isset($this->_block['menu']) || !is_array($this->_block['menu']) ) $this->_block['menu'] = array();
foreach($this->_block['menu'] as $menu_key => $menu_value) {
$_tmpb_menu = &$this->_block['menu'][$menu_key]; ?>
					<div class="row3" style="width:60%">
						<div style="text-align:center;"><strong><?php echo isset($this->_var['L_TABLE_OF_CONTENTS']) ? $this->_var['L_TABLE_OF_CONTENTS'] : ''; ?></strong></div>
						<?php echo isset($_tmpb_menu['MENU']) ? $_tmpb_menu['MENU'] : ''; ?>
					</div>
				<?php } ?>
				<br /><br /><br />
				<?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?>
				<br /><br />
				<?php if( !isset($this->_block['cat']) || !is_array($this->_block['cat']) ) $this->_block['cat'] = array();
foreach($this->_block['cat'] as $cat_key => $cat_value) {
$_tmpb_cat = &$this->_block['cat'][$cat_key]; ?>
					<hr />
					<br />
					<strong><?php echo isset($this->_var['L_SUB_CATS']) ? $this->_var['L_SUB_CATS'] : ''; ?></strong>
					<br /><br />
					<?php if( !isset($_tmpb_cat['list_cats']) || !is_array($_tmpb_cat['list_cats']) ) $_tmpb_cat['list_cats'] = array();
foreach($_tmpb_cat['list_cats'] as $list_cats_key => $list_cats_value) {
$_tmpb_list_cats = &$_tmpb_cat['list_cats'][$list_cats_key]; ?>
						<img src="<?php echo isset($this->_var['WIKI_PATH']) ? $this->_var['WIKI_PATH'] : ''; ?>/images/cat.png"  style="vertical-align:middle;" alt="" />&nbsp;<a href="<?php echo isset($_tmpb_list_cats['U_CAT']) ? $_tmpb_list_cats['U_CAT'] : ''; ?>"><?php echo isset($_tmpb_list_cats['NAME']) ? $_tmpb_list_cats['NAME'] : ''; ?></a><br />
					<?php } ?>
					
					<?php if( !isset($_tmpb_cat['no_sub_cat']) || !is_array($_tmpb_cat['no_sub_cat']) ) $_tmpb_cat['no_sub_cat'] = array();
foreach($_tmpb_cat['no_sub_cat'] as $no_sub_cat_key => $no_sub_cat_value) {
$_tmpb_no_sub_cat = &$_tmpb_cat['no_sub_cat'][$no_sub_cat_key]; ?>
					<?php echo isset($_tmpb_no_sub_cat['NO_SUB_CAT']) ? $_tmpb_no_sub_cat['NO_SUB_CAT'] : ''; ?><br />
					<?php } ?>
					
					<br />
					<strong><?php echo isset($this->_var['L_SUB_ARTICLES']) ? $this->_var['L_SUB_ARTICLES'] : ''; ?></strong> &nbsp; <?php echo isset($_tmpb_cat['RSS']) ? $_tmpb_cat['RSS'] : ''; ?>
					<br /><br />
					<?php if( !isset($_tmpb_cat['list_art']) || !is_array($_tmpb_cat['list_art']) ) $_tmpb_cat['list_art'] = array();
foreach($_tmpb_cat['list_art'] as $list_art_key => $list_art_value) {
$_tmpb_list_art = &$_tmpb_cat['list_art'][$list_art_key]; ?>
						<img src="<?php echo isset($this->_var['WIKI_PATH']) ? $this->_var['WIKI_PATH'] : ''; ?>/images/article.png"  style="vertical-align:middle;" alt="" />&nbsp;<a href="<?php echo isset($_tmpb_list_art['U_ARTICLE']) ? $_tmpb_list_art['U_ARTICLE'] : ''; ?>"><?php echo isset($_tmpb_list_art['TITLE']) ? $_tmpb_list_art['TITLE'] : ''; ?></a><br />
					<?php } ?>
					
					<?php if( !isset($_tmpb_cat['no_sub_article']) || !is_array($_tmpb_cat['no_sub_article']) ) $_tmpb_cat['no_sub_article'] = array();
foreach($_tmpb_cat['no_sub_article'] as $no_sub_article_key => $no_sub_article_value) {
$_tmpb_no_sub_article = &$_tmpb_cat['no_sub_article'][$no_sub_article_key]; ?>
					<?php echo isset($_tmpb_no_sub_article['NO_SUB_ARTICLE']) ? $_tmpb_no_sub_article['NO_SUB_ARTICLE'] : ''; ?>
					<?php } ?>
					
				<?php } ?>
				<div class="spacer">&nbsp;</div>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom" style="text-align:center;"><?php echo isset($this->_var['HITS']) ? $this->_var['HITS'] : ''; ?></div>
		</div>
		