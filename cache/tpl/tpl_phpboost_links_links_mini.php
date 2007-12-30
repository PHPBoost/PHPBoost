		<div class="module_mini_container">
			<ul style="margin:0px;padding:0px;margin-left:-1px;list-style-type:none;">
			<?php if( !isset($this->_block['links']) || !is_array($this->_block['links']) ) $this->_block['links'] = array();
foreach($this->_block['links'] as $links_key => $links_value) {
$_tmpb_links = &$this->_block['links'][$links_key]; ?>		
				<?php if( !isset($_tmpb_links['title']) || !is_array($_tmpb_links['title']) ) $_tmpb_links['title'] = array();
foreach($_tmpb_links['title'] as $title_key => $title_value) {
$_tmpb_title = &$_tmpb_links['title'][$title_key]; ?>			
				<li class="module_mini_top">
					<h5 class="sub_title"><?php echo isset($_tmpb_title['NAME']) ? $_tmpb_title['NAME'] : ''; ?></h5>
				</li>
				<?php } ?>
				<?php if( !isset($_tmpb_links['subtitle']) || !is_array($_tmpb_links['subtitle']) ) $_tmpb_links['subtitle'] = array();
foreach($_tmpb_links['subtitle'] as $subtitle_key => $subtitle_value) {
$_tmpb_subtitle = &$_tmpb_links['subtitle'][$subtitle_key]; ?>
				<li class="link_subtitle">
					<h6 class="sub_title2"><a href="<?php echo isset($_tmpb_subtitle['URL']) ? $_tmpb_subtitle['URL'] : ''; ?>" title="<?php echo isset($_tmpb_subtitle['NAME']) ? $_tmpb_subtitle['NAME'] : ''; ?>"><?php echo isset($_tmpb_subtitle['NAME']) ? $_tmpb_subtitle['NAME'] : ''; ?></a></h6>				
				</li>
				<?php } ?>		
			<?php } ?>
			</ul>
			<div class="module_mini_bottom_left">&nbsp;
			</div>
		</div>