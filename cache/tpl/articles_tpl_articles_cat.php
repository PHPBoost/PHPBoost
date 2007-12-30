		<?php echo isset($this->_var['JAVA']) ? $this->_var['JAVA'] : ''; ?> 

		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				<div style="float:left" class="text_strong">
					<a href="articles.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_ARTICLES_INDEX']) ? $this->_var['L_ARTICLES_INDEX'] : ''; ?></a> &raquo; <?php echo isset($this->_var['U_ARTICLES_CAT_LINKS']) ? $this->_var['U_ARTICLES_CAT_LINKS'] : ''; echo ' '; echo isset($this->_var['ADD_ARTICLES']) ? $this->_var['ADD_ARTICLES'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
				</div>
			</div>
			<div class="module_contents">
				<?php if( !isset($this->_block['cat']) || !is_array($this->_block['cat']) ) $this->_block['cat'] = array();
foreach($this->_block['cat'] as $cat_key => $cat_value) {
$_tmpb_cat = &$this->_block['cat'][$cat_key]; ?>
				<table class="module_table">
					<tr>
						<th colspan="<?php echo isset($this->_var['COLSPAN']) ? $this->_var['COLSPAN'] : ''; ?>">
							<?php echo isset($this->_var['L_CATEGORIES']) ? $this->_var['L_CATEGORIES'] : ''; echo ' '; echo isset($_tmpb_cat['EDIT']) ? $_tmpb_cat['EDIT'] : ''; ?>
						</th>
					</tr>
					
					<?php if( !isset($_tmpb_cat['list']) || !is_array($_tmpb_cat['list']) ) $_tmpb_cat['list'] = array();
foreach($_tmpb_cat['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_cat['list'][$list_key]; ?>
					<?php echo isset($_tmpb_list['TR_START']) ? $_tmpb_list['TR_START'] : ''; ?>								
						<td class="row2" style="vertical-align:bottom;text-align:center;width:<?php echo isset($this->_var['COLUMN_WIDTH_CATS']) ? $this->_var['COLUMN_WIDTH_CATS'] : ''; ?>%">
							<?php echo isset($_tmpb_list['ICON_CAT']) ? $_tmpb_list['ICON_CAT'] : ''; ?>
							<a href="articles<?php echo isset($_tmpb_list['U_CAT']) ? $_tmpb_list['U_CAT'] : ''; ?>"><?php echo isset($_tmpb_list['CAT']) ? $_tmpb_list['CAT'] : ''; ?></a> <?php echo isset($_tmpb_list['EDIT']) ? $_tmpb_list['EDIT'] : ''; ?>
							<br />
							<span class="text_small"><?php echo isset($_tmpb_list['DESC']) ? $_tmpb_list['DESC'] : ''; ?></span> 
							<br />
							<span class="text_small"><?php echo isset($_tmpb_list['L_NBR_ARTICLES']) ? $_tmpb_list['L_NBR_ARTICLES'] : ''; ?></span> 
						</td>	
					<?php echo isset($_tmpb_list['TR_END']) ? $_tmpb_list['TR_END'] : ''; ?>
					<?php } ?>						
				
					<?php if( !isset($_tmpb_cat['end_td']) || !is_array($_tmpb_cat['end_td']) ) $_tmpb_cat['end_td'] = array();
foreach($_tmpb_cat['end_td'] as $end_td_key => $end_td_value) {
$_tmpb_end_td = &$_tmpb_cat['end_td'][$end_td_key]; ?>
						<?php echo isset($_tmpb_end_td['TD_END']) ? $_tmpb_end_td['TD_END'] : ''; ?>
					<?php echo isset($_tmpb_end_td['TR_END']) ? $_tmpb_end_td['TR_END'] : ''; ?>
					<?php } ?>
					
				</table>	
				<?php } ?>
				
				<?php if( !isset($this->_block['link']) || !is_array($this->_block['link']) ) $this->_block['link'] = array();
foreach($this->_block['link'] as $link_key => $link_value) {
$_tmpb_link = &$this->_block['link'][$link_key]; ?>
				<br />
				<table class="module_table">
					<tr>
						<th colspan="6">
							<?php echo isset($_tmpb_link['CAT']) ? $_tmpb_link['CAT'] : ''; ?> &nbsp;<?php echo isset($_tmpb_link['EDIT']) ? $_tmpb_link['EDIT'] : ''; ?>
						</th>	
					</tr>
					<tr>
						<td colspan="6" class="row3">
							<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
						</td>	
					</tr>
					<tr style="font-weight:bold;text-align: center;">
						<td class="row2">
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_ALPHA_TOP']) ? $this->_var['U_ARTICLES_ALPHA_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_ARTICLES']) ? $this->_var['L_ARTICLES'] : ''; ?>
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_ALPHA_BOTTOM']) ? $this->_var['U_ARTICLES_ALPHA_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</td>
						<td class="row2">
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_DATE_TOP']) ? $this->_var['U_ARTICLES_DATE_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>					
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_DATE_BOTTOM']) ? $this->_var['U_ARTICLES_DATE_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</td>
						<td class="row2">
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_VIEW_TOP']) ? $this->_var['U_ARTICLES_VIEW_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_VIEW']) ? $this->_var['L_VIEW'] : ''; ?>					
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_VIEW_BOTTOM']) ? $this->_var['U_ARTICLES_VIEW_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</td>
						<td class="row2">
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_NOTE_TOP']) ? $this->_var['U_ARTICLES_NOTE_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_NOTE']) ? $this->_var['L_NOTE'] : ''; ?>					
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_NOTE_BOTTOM']) ? $this->_var['U_ARTICLES_NOTE_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</td>
						<td class="row2">
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_COM_TOP']) ? $this->_var['U_ARTICLES_COM_TOP'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/top.png" alt="" /></a>
							<?php echo isset($this->_var['L_COM']) ? $this->_var['L_COM'] : ''; ?>
							<a href="articles<?php echo isset($this->_var['U_ARTICLES_COM_BOTTOM']) ? $this->_var['U_ARTICLES_COM_BOTTOM'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/bottom.png" alt="" /></a>
						</td>
					</tr>
					<?php if( !isset($_tmpb_link['articles']) || !is_array($_tmpb_link['articles']) ) $_tmpb_link['articles'] = array();
foreach($_tmpb_link['articles'] as $articles_key => $articles_value) {
$_tmpb_articles = &$_tmpb_link['articles'][$articles_key]; ?>
					<tr>	
						<td class="row2" style="padding-left:25px">
							<?php echo isset($_tmpb_articles['ICON']) ? $_tmpb_articles['ICON'] : ''; ?> &nbsp;&nbsp;<a href="articles<?php echo isset($_tmpb_articles['U_ARTICLES_LINK']) ? $_tmpb_articles['U_ARTICLES_LINK'] : ''; ?>"><?php echo isset($_tmpb_articles['NAME']) ? $_tmpb_articles['NAME'] : ''; ?></a>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_articles['DATE']) ? $_tmpb_articles['DATE'] : ''; ?>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_articles['COMPT']) ? $_tmpb_articles['COMPT'] : ''; ?> 
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_articles['NOTE']) ? $_tmpb_articles['NOTE'] : ''; ?>
						</td>
						<td class="row2" style="text-align: center;">
							<?php echo isset($_tmpb_articles['COM']) ? $_tmpb_articles['COM'] : ''; ?> 
						</td>
					</tr>
					<?php } ?>
					<tr>
						<td colspan="6" class="row3">
							<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
						</td>	
					</tr>
				</table>
				<br />
				<?php } ?>
				
				<p style="text-align:center" class="text_small">
					<?php echo isset($this->_var['L_NO_ARTICLES']) ? $this->_var['L_NO_ARTICLES'] : ''; echo ' '; echo isset($this->_var['L_TOTAL_ARTICLE']) ? $this->_var['L_TOTAL_ARTICLE'] : ''; ?>
				</p>
			</div>
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<div style="float:left" class="text_strong">
					<a href="articles.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_ARTICLES_INDEX']) ? $this->_var['L_ARTICLES_INDEX'] : ''; ?></a> &raquo; <?php echo isset($this->_var['U_ARTICLES_CAT_LINKS']) ? $this->_var['U_ARTICLES_CAT_LINKS'] : ''; echo ' '; echo isset($this->_var['ADD_ARTICLES']) ? $this->_var['ADD_ARTICLES'] : ''; ?>
				</div>
				<div style="float:right">
					<?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?>
				</div>
			</div>
		</div>
		