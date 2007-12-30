		<script type="text/javascript">
		<!--
		var sum_height = <?php echo isset($this->_var['SUM_HEIGHT']) ? $this->_var['SUM_HEIGHT'] : ''; ?>;
		var sum_width = <?php echo isset($this->_var['SUM_WIDTH']) ? $this->_var['SUM_WIDTH'] : ''; ?>;
		var hidden_height = <?php echo isset($this->_var['HIDDEN_HEIGHT']) ? $this->_var['HIDDEN_HEIGHT'] : ''; ?>;		
		var hidden_width = <?php echo isset($this->_var['HIDDEN_WIDTH']) ? $this->_var['HIDDEN_WIDTH'] : ''; ?>;		
		var scroll_speed = <?php echo isset($this->_var['SCROLL_SPEED']) ? $this->_var['SCROLL_SPEED'] : ''; ?>;
		var scroll_mode = '<?php echo isset($this->_var['SCROLL_MODE']) ? $this->_var['SCROLL_MODE'] : ''; ?>';
		<?php echo isset($this->_var['ARRAY_PICS']) ? $this->_var['ARRAY_PICS'] : ''; ?>
		-->
		</script>
		
		<div class="module_mini_container">
			<div class="module_mini_top">
				<h5 class="sub_title"><?php echo isset($this->_var['L_RANDOM_PICS']) ? $this->_var['L_RANDOM_PICS'] : ''; ?></h5>
			</div>
			<div class="module_mini_table">
				<div style="position:relative;width:<?php echo isset($this->_var['WIDTH_DIV']) ? $this->_var['WIDTH_DIV'] : ''; ?>px;height:<?php echo isset($this->_var['HEIGHT_DIV']) ? $this->_var['HEIGHT_DIV'] : ''; ?>px;overflow:hidden;margin:auto;margin-left:-2px;">	
					<div id="thumb_mini" style="left:0px;top:0px;position:relative;margin-top:5px;" onmouseover="temporize_scroll()" onmouseout="temporize_scroll();">
						<?php if( isset($this->_var['C_VERTICAL_SCROLL']) && $this->_var['C_VERTICAL_SCROLL'] ) { ?>	
							<?php if( !isset($this->_block['pics_mini']) || !is_array($this->_block['pics_mini']) ) $this->_block['pics_mini'] = array();
foreach($this->_block['pics_mini'] as $pics_mini_key => $pics_mini_value) {
$_tmpb_pics_mini = &$this->_block['pics_mini'][$pics_mini_key]; ?>
						<a href="<?php echo isset($_tmpb_pics_mini['U_PICS']) ? $_tmpb_pics_mini['U_PICS'] : ''; ?>#pics_max"><img src="<?php echo isset($_tmpb_pics_mini['PICS']) ? $_tmpb_pics_mini['PICS'] : ''; ?>" alt="<?php echo isset($_tmpb_pics_mini['NAME']) ? $_tmpb_pics_mini['NAME'] : ''; ?>" width="<?php echo isset($_tmpb_pics_mini['WIDTH']) ? $_tmpb_pics_mini['WIDTH'] : ''; ?>" height="<?php echo isset($_tmpb_pics_mini['HEIGHT']) ? $_tmpb_pics_mini['HEIGHT'] : ''; ?>" /></a>
							<?php } ?>
						<?php } ?>
						
						<?php if( isset($this->_var['C_HORIZONTAL_SCROLL']) && $this->_var['C_HORIZONTAL_SCROLL'] ) { ?>
						<table>
							<tr>
								<?php if( !isset($this->_block['pics_mini']) || !is_array($this->_block['pics_mini']) ) $this->_block['pics_mini'] = array();
foreach($this->_block['pics_mini'] as $pics_mini_key => $pics_mini_value) {
$_tmpb_pics_mini = &$this->_block['pics_mini'][$pics_mini_key]; ?>
								<td style="padding:4px;"><a href="<?php echo isset($_tmpb_pics_mini['U_PICS']) ? $_tmpb_pics_mini['U_PICS'] : ''; ?>#pics_max"><img src="<?php echo isset($_tmpb_pics_mini['PICS']) ? $_tmpb_pics_mini['PICS'] : ''; ?>" alt="<?php echo isset($_tmpb_pics_mini['NAME']) ? $_tmpb_pics_mini['NAME'] : ''; ?>" width="<?php echo isset($_tmpb_pics_mini['WIDTH']) ? $_tmpb_pics_mini['WIDTH'] : ''; ?>" height="<?php echo isset($_tmpb_pics_mini['HEIGHT']) ? $_tmpb_pics_mini['HEIGHT'] : ''; ?>" /></a></td>
								<?php } ?>
							</tr>
						</table>
						<?php } ?>
					</div>
					<?php echo isset($this->_var['L_NO_RANDOM_PICS']) ? $this->_var['L_NO_RANDOM_PICS'] : ''; ?>
				</div>
				<div>
					<a class="small_link" href="../gallery/gallery.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_GALLERY']) ? $this->_var['L_GALLERY'] : ''; ?></a>
				</div>
			</div>
			<div class="module_mini_bottom">
			</div>
		</div>
		<script type="text/javascript" src="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/images/js/scroll.js"></script>
		