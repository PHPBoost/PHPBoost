		<div class="news_container">
			<div class="news_top_l"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<h3 class="title valign_middle"><?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?> :: <?php echo isset($this->_var['CAT_NAME']) ? $this->_var['CAT_NAME'] : ''; echo ' '; echo isset($this->_var['EDIT']) ? $this->_var['EDIT'] : ''; ?></h3>
			</div>	
			<div class="news_content" style="border-bottom:none;">
				<ul style="margin:20px;">
				<?php if( !isset($this->_block['list']) || !is_array($this->_block['list']) ) $this->_block['list'] = array();
foreach($this->_block['list'] as $list_key => $list_value) {
$_tmpb_list = &$this->_block['list'][$list_key]; ?>						
					<li>
						<?php echo isset($_tmpb_list['ICON']) ? $_tmpb_list['ICON'] : ''; ?> <a href="<?php echo isset($_tmpb_list['U_NEWS']) ? $_tmpb_list['U_NEWS'] : ''; ?>"><?php echo isset($_tmpb_list['TITLE']) ? $_tmpb_list['TITLE'] : ''; ?></a>	(<?php echo isset($_tmpb_list['COM']) ? $_tmpb_list['COM'] : ''; ?>)
					</li>		
				<?php } ?>
				</ul>	
			</div>
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		