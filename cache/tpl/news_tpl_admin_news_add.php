		<link href="<?php echo isset($this->_var['MODULE_DATA_PATH']) ? $this->_var['MODULE_DATA_PATH'] : ''; ?>/news.css" rel="stylesheet" type="text/css" media="screen, handheld">

		<script type="text/javascript" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.js"></script>
		<script type="text/javascript">
		<!--
		function check_form(){
			if(document.getElementById('title').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TITLE']) ? $this->_var['L_REQUIRE_TITLE'] : ''; ?>");
				return false;
		    }
			if(document.getElementById('contents').value == "") {
				alert("<?php echo isset($this->_var['L_REQUIRE_TEXT']) ? $this->_var['L_REQUIRE_TEXT'] : ''; ?>");
				return false;
		    }

			return true;
		}

		-->
		</script>

		<div id="admin_quick_menu">
			<ul>
				<li class="title_menu"><?php echo isset($this->_var['L_NEWS_MANAGEMENT']) ? $this->_var['L_NEWS_MANAGEMENT'] : ''; ?></li>
				<li>
					<a href="admin_news.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news.php" class="quick_link"><?php echo isset($this->_var['L_NEWS_MANAGEMENT']) ? $this->_var['L_NEWS_MANAGEMENT'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_news_add.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_add.php" class="quick_link"><?php echo isset($this->_var['L_ADD_NEWS']) ? $this->_var['L_ADD_NEWS'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_news_cat.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_cat.php" class="quick_link"><?php echo isset($this->_var['L_CAT_NEWS']) ? $this->_var['L_CAT_NEWS'] : ''; ?></a>
				</li>
				<li>
					<a href="admin_news_config.php"><img src="news.png" alt="" /></a>
					<br />
					<a href="admin_news_config.php" class="quick_link"><?php echo isset($this->_var['L_CONFIG_NEWS']) ? $this->_var['L_CONFIG_NEWS'] : ''; ?></a>
				</li>
			</ul>
		</div>
		<div id="admin_contents">
			<?php if( !isset($this->_block['news']) || !is_array($this->_block['news']) ) $this->_block['news'] = array();
foreach($this->_block['news'] as $news_key => $news_value) {
$_tmpb_news = &$this->_block['news'][$news_key]; ?>

			<table class="module_table">
					<tr> 
						<th colspan="2">
							<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>
						</th>
					</tr>

					<tr> 
						<td class="row1">
							<div class="news_container">
								<div class="msg_top_l"></div>			
								<div class="msg_top_r"></div>
								<div class="msg_top">
									<div style="float:left"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a> <h3 class="title valign_middle"><?php echo isset($_tmpb_news['TITLE']) ? $_tmpb_news['TITLE'] : ''; ?></h3></div>
									<div style="float:right"></div>
								</div>												
								<div class="news_content">
									<?php echo isset($_tmpb_news['IMG']) ? $_tmpb_news['IMG'] : ''; ?>
									<?php echo isset($_tmpb_news['CONTENTS']) ? $_tmpb_news['CONTENTS'] : ''; ?>
									<br /><br />	
									<?php echo isset($_tmpb_news['EXTEND_CONTENTS']) ? $_tmpb_news['EXTEND_CONTENTS'] : ''; ?>	
								</div>								
								<div class="news_bottom_l"></div>		
								<div class="news_bottom_r"></div>
								<div class="news_bottom">
									<span style="float:left"><a class="small_link" href="../member/member<?php echo isset($_tmpb_news['U_MEMBER_ID']) ? $_tmpb_news['U_MEMBER_ID'] : ''; ?>"><?php echo isset($_tmpb_news['PSEUDO']) ? $_tmpb_news['PSEUDO'] : ''; ?></a></span>
									<span style="float:right"><?php echo isset($this->_var['L_ON']) ? $this->_var['L_ON'] : ''; ?>: <?php echo isset($_tmpb_news['DATE']) ? $_tmpb_news['DATE'] : ''; ?></span>
								</div>
							</div>				
						</td>
					</tr>
			</table>	

			<br /><br /><br />
			<?php } ?>
			
			<?php if( !isset($this->_block['error_handler']) || !is_array($this->_block['error_handler']) ) $this->_block['error_handler'] = array();
foreach($this->_block['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$this->_block['error_handler'][$error_handler_key]; ?>
			<div class="error_handler_position">
				<span id="errorh"></span>
				<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
					<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
					<br />	
				</div>
			</div>
			<?php } ?>	
			
			<form action="admin_news_add.php" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
				<fieldset>
					<legend><?php echo isset($this->_var['L_ADD_NEWS']) ? $this->_var['L_ADD_NEWS'] : ''; ?></legend>
					<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
					<dl>
						<dt><label for="title">* <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label></dt>
						<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="<?php echo isset($this->_var['TITLE']) ? $this->_var['TITLE'] : ''; ?>" class="text" /></label></dd>
					</dl>
					<dl>
						<dt><label for="idcat">* <?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?></label></dt>
						<dd><label>
							<select id="idcat" name="idcat">				
							<?php if( !isset($this->_block['select']) || !is_array($this->_block['select']) ) $this->_block['select'] = array();
foreach($this->_block['select'] as $select_key => $select_value) {
$_tmpb_select = &$this->_block['select'][$select_key]; ?>				
								<?php echo isset($_tmpb_select['CAT']) ? $_tmpb_select['CAT'] : ''; ?>				
							<?php } ?>				
							</select>
						</label></dd>
					</dl>
					<br />
						<label for="contents">* <?php echo isset($this->_var['L_TEXT']) ? $this->_var['L_TEXT'] : ''; ?></label></dt>
						<?php $this->tpl_include('handle_bbcode'); ?>
						<label><textarea type="text" rows="15" cols="86" id="contents" name="contents"><?php echo isset($this->_var['CONTENTS']) ? $this->_var['CONTENTS'] : ''; ?></textarea></label>
					<br />
					<br />