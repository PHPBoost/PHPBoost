		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('search').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
		    }
			return true;
		}
		-->
		</script>

		<?php $this->tpl_include('forum_top'); ?>
		
		<div class="module_position">					
			<div class="module_top_l"></div>		
			<div class="module_top_r"></div>
			<div class="module_top">
				&bull; <a href="index.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FORUM_INDEX']) ? $this->_var['L_FORUM_INDEX'] : ''; ?></a> &raquo; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; ?>
			</div>
			<div class="module_contents">
				<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
				<span id="errorh"></span>
				<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>">
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
				</div>		
				<?php } ?>
					
				<form action="search.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>#errorh" method="post" onsubmit="return check_form();" class="fieldset_content">
					<fieldset>
						<legend><?php echo isset($this->_var['L_SEARCH_FORUM']) ? $this->_var['L_SEARCH_FORUM'] : ''; ?></legend>
						<dl>
							<dt><label for="search"><?php echo isset($this->_var['L_KEYWORDS']) ? $this->_var['L_KEYWORDS'] : ''; ?></label></dt>
							<dd><label><input type="text" size="35" id="search" name="search" value="<?php echo isset($this->_var['SEARCH']) ? $this->_var['SEARCH'] : ''; ?>"  class="text" /></label></dd>
						</dl>
						<dl>
							<dt><label for="time"><?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?></label></dt>
							<dd><label>	
								<select id="time" name="time">
									<option value="30000" selected="selected"><?php echo isset($this->_var['L_ALL']) ? $this->_var['L_ALL'] : ''; ?></option>
									<option value="1">1 <?php echo isset($this->_var['L_DAY']) ? $this->_var['L_DAY'] : ''; ?></option>
									<option value="7">7 <?php echo isset($this->_var['L_DAYS']) ? $this->_var['L_DAYS'] : ''; ?></option>
									<option value="15">15 <?php echo isset($this->_var['L_DAYS']) ? $this->_var['L_DAYS'] : ''; ?></option>
									<option value="30">1 <?php echo isset($this->_var['L_MONTH']) ? $this->_var['L_MONTH'] : ''; ?></option>
									<option value="180">6 <?php echo isset($this->_var['L_MONTH']) ? $this->_var['L_MONTH'] : ''; ?></option>
									<option value="360">1 <?php echo isset($this->_var['L_YEAR']) ? $this->_var['L_YEAR'] : ''; ?></option>
								</select>
							</label></dd>
						</dl>
						<dl>
							<dt><label for="idcat"><?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?></label></dt>
							<dd><label>
								<select name="idcat" id="idcat">
									<?php if( !isset($this->_block['cat']) || !is_array($this->_block['cat']) ) $this->_block['cat'] = array();
foreach($this->_block['cat'] as $cat_key => $cat_value) {
$_tmpb_cat = &$this->_block['cat'][$cat_key]; ?>
										<?php echo isset($_tmpb_cat['CAT']) ? $_tmpb_cat['CAT'] : ''; ?>
									<?php } ?>
								</select>
							</label></dd>
						</dl>
						<dl>
							<dt><label for="where"><?php echo isset($this->_var['L_OPTIONS']) ? $this->_var['L_OPTIONS'] : ''; ?></label></dt>
							<dd>
								<label><input type="radio" name="where" id="where" value="contents"  checked="checked" /> <?php echo isset($this->_var['L_CONTENTS']) ? $this->_var['L_CONTENTS'] : ''; ?></label>
								<br />
								<label><input type="radio" name="where" value="title" /> <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label>
							</dd>
						</dl>
					</fieldset>			
					<fieldset class="fieldset_submit">
						<legend><?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?></legend>
						<input type="submit" name="valid_search" value="<?php echo isset($this->_var['L_SEARCH']) ? $this->_var['L_SEARCH'] : ''; ?>" class="submit" />			
					</fieldset>
					
					<?php if( !isset($this->_block['list']) || !is_array($this->_block['list']) ) $this->_block['list'] = array();
foreach($this->_block['list'] as $list_key => $list_value) {
$_tmpb_list = &$this->_block['list'][$list_key]; ?>
					<div class="module_position" style="width:100%;">					
						<div class="msg_top_l"></div>		
						<div class="msg_top_r"></div>
						<div class="msg_top"></div>
						<div class="msg_container">
							<div class="msg_top_row">
								<div class="msg_pseudo_mbr">
									<?php echo isset($_tmpb_list['USER_ONLINE']) ? $_tmpb_list['USER_ONLINE'] : ''; echo ' '; echo isset($_tmpb_list['USER_PSEUDO']) ? $_tmpb_list['USER_PSEUDO'] : ''; ?>
								</div>
								<span class="text_strong" style="float:left;">&nbsp;&nbsp;<?php echo isset($this->_var['L_TOPIC']) ? $this->_var['L_TOPIC'] : ''; ?>: <?php echo isset($_tmpb_list['U_TITLE']) ? $_tmpb_list['U_TITLE'] : ''; ?></span>
								<span class="text_small" style="float: right;"><?php echo isset($this->_var['L_ON']) ? $this->_var['L_ON'] : ''; ?>: <?php echo isset($_tmpb_list['DATE']) ? $_tmpb_list['DATE'] : ''; ?></span>&nbsp;
							</div>
							<div class="msg_contents_container">
								<div class="msg_info_mbr">
								</div>
								<div class="msg_contents">
									<div class="msg_contents_overflow">
										<?php echo isset($_tmpb_list['CONTENTS']) ? $_tmpb_list['CONTENTS'] : ''; ?>
									</div>									
								</div>
							</div>
						</div>	
						<div class="msg_bottom_l"></div>		
						<div class="msg_bottom_r"></div>
						<div class="msg_bottom"><span class="text_small"><?php echo isset($this->_var['L_RELEVANCE']) ? $this->_var['L_RELEVANCE'] : ''; ?>: <?php echo isset($_tmpb_list['RELEVANCE']) ? $_tmpb_list['RELEVANCE'] : ''; ?>%</span></div>
					</div>
					<br />
					<?php } ?>	
				</form>			
			</div>	
			<div class="module_bottom_l"></div>		
			<div class="module_bottom_r"></div>
			<div class="module_bottom">
				<span style="float:left;">
					&bull; <a href="index.php<?php echo isset($this->_var['SID']) ? $this->_var['SID'] : ''; ?>"><?php echo isset($this->_var['L_FORUM_INDEX']) ? $this->_var['L_FORUM_INDEX'] : ''; ?></a> &raquo; <?php echo isset($this->_var['U_FORUM_CAT']) ? $this->_var['U_FORUM_CAT'] : ''; ?>
				</span>
				<span style="float:right;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></span>&nbsp;
			</div>
		</div>
		
		<?php $this->tpl_include('forum_bottom'); ?>
		