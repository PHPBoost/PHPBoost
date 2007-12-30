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
			<?php if( !isset($this->_block['list']) || !is_array($this->_block['list']) ) $this->_block['list'] = array();
foreach($this->_block['list'] as $list_key => $list_value) {
$_tmpb_list = &$this->_block['list'][$list_key]; ?>
				
				<script type="text/javascript">
				<!--
				function Confirm() {
					return confirm("<?php echo isset($this->_var['L_CONFIRM_DEL_NEWS']) ? $this->_var['L_CONFIRM_DEL_NEWS'] : ''; ?>");
				}
				-->
				</script>
				<table class="module_table">
					<tr style="text-align:center;">
						<th>
							<?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_PSEUDO']) ? $this->_var['L_PSEUDO'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_DATE']) ? $this->_var['L_DATE'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_APROB']) ? $this->_var['L_APROB'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_ARCHIVE']) ? $this->_var['L_ARCHIVE'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>
						</th>
						<th>
							<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>
						</th>
					</tr>
					
					<?php if( !isset($_tmpb_list['news']) || !is_array($_tmpb_list['news']) ) $_tmpb_list['news'] = array();
foreach($_tmpb_list['news'] as $news_key => $news_value) {
$_tmpb_news = &$_tmpb_list['news'][$news_key]; ?>
					<tr style="text-align:center;"> 
						<td class="row2"> 
							<a href="news.php?id=<?php echo isset($_tmpb_news['IDNEWS']) ? $_tmpb_news['IDNEWS'] : ''; ?>"><?php echo isset($_tmpb_news['TITLE']) ? $_tmpb_news['TITLE'] : ''; ?></a>
						</td>
						<td class="row2"> 
							<?php echo isset($_tmpb_news['CATEGORY']) ? $_tmpb_news['CATEGORY'] : ''; ?>
						</td>
						<td class="row2"> 
							<?php echo isset($_tmpb_news['PSEUDO']) ? $_tmpb_news['PSEUDO'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_news['DATE']) ? $_tmpb_news['DATE'] : ''; ?>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_news['APROBATION']) ? $_tmpb_news['APROBATION'] : ''; ?> 
							<br />
							<span class="text_small"><?php echo isset($_tmpb_news['VISIBLE']) ? $_tmpb_news['VISIBLE'] : ''; ?></span>
						</td>
						<td class="row2">
							<?php echo isset($_tmpb_news['ARCHIVE']) ? $_tmpb_news['ARCHIVE'] : ''; ?> 
						</td>
						<td class="row2"> 
							<a href="admin_news.php?id=<?php echo isset($_tmpb_news['IDNEWS']) ? $_tmpb_news['IDNEWS'] : ''; ?>"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/edit.png" alt="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" title="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" /></a>
						</td>
						<td class="row2">
							<a href="admin_news.php?delete=true&amp;id=<?php echo isset($_tmpb_news['IDNEWS']) ? $_tmpb_news['IDNEWS'] : ''; ?>" onClick="javascript:return Confirm();"><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($this->_var['LANG']) ? $this->_var['LANG'] : ''; ?>/delete.png" alt="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" title="<?php echo isset($this->_var['L_DELETE']) ? $this->_var['L_DELETE'] : ''; ?>" /></a>
						</td>
					</tr>
					<?php } ?>
				</table>

				<br /><br />
				<p style="text-align: center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></p>	
		</div>	
			<?php } ?>


			<?php if( !isset($this->_block['news']) || !is_array($this->_block['news']) ) $this->_block['news'] = array();
foreach($this->_block['news'] as $news_key => $news_value) {
$_tmpb_news = &$this->_block['news'][$news_key]; ?>

			<link href="<?php echo isset($_tmpb_news['MODULE_DATA_PATH']) ? $_tmpb_news['MODULE_DATA_PATH'] : ''; ?>/news.css" rel="stylesheet" type="text/css" media="screen, handheld">

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

				<?php if( !isset($_tmpb_news['preview']) || !is_array($_tmpb_news['preview']) ) $_tmpb_news['preview'] = array();
foreach($_tmpb_news['preview'] as $preview_key => $preview_value) {
$_tmpb_preview = &$_tmpb_news['preview'][$preview_key]; ?>
				<table  class="module_table">
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
										<div style="float:left"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a> <h3 class="title valign_middle"><?php echo isset($_tmpb_preview['TITLE']) ? $_tmpb_preview['TITLE'] : ''; ?></h3></div>
										<div style="float:right"></div>
									</div>													
									<div class="news_content">
										<?php echo isset($_tmpb_preview['IMG']) ? $_tmpb_preview['IMG'] : ''; ?>
										<?php echo isset($_tmpb_preview['CONTENTS']) ? $_tmpb_preview['CONTENTS'] : ''; ?>
										<br /><br />	
										<?php echo isset($_tmpb_preview['EXTEND_CONTENTS']) ? $_tmpb_preview['EXTEND_CONTENTS'] : ''; ?>	
									</div>									
									<div class="news_bottom_l"></div>		
									<div class="news_bottom_r"></div>
									<div class="news_bottom">
										<span style="float:left"><a class="small_link" href="../member/member<?php echo isset($_tmpb_news['U_MEMBER_ID']) ? $_tmpb_news['U_MEMBER_ID'] : ''; ?>"><?php echo isset($_tmpb_preview['PSEUDO']) ? $_tmpb_preview['PSEUDO'] : ''; ?></a></span>
										<span style="float:right"><?php echo isset($this->_var['L_ON']) ? $this->_var['L_ON'] : ''; ?>: <?php echo isset($_tmpb_preview['DATE']) ? $_tmpb_preview['DATE'] : ''; ?></span>
									</div>
								</div>		
							</td>
						</tr>
				</table>	
				<br /><br /><br />
				<?php } ?>

				
				<?php if( !isset($_tmpb_news['error_handler']) || !is_array($_tmpb_news['error_handler']) ) $_tmpb_news['error_handler'] = array();
foreach($_tmpb_news['error_handler'] as $error_handler_key => $error_handler_value) {
$_tmpb_error_handler = &$_tmpb_news['error_handler'][$error_handler_key]; ?>
				<div class="error_handler_position">
					<span id="errorh"></span>
					<div class="<?php echo isset($_tmpb_error_handler['CLASS']) ? $_tmpb_error_handler['CLASS'] : ''; ?>" style="width:500px;margin:auto;padding:15px;">
						<img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/<?php echo isset($_tmpb_error_handler['IMG']) ? $_tmpb_error_handler['IMG'] : ''; ?>.png" alt="" style="float:left;padding-right:6px;" /> <?php echo isset($_tmpb_error_handler['L_ERROR']) ? $_tmpb_error_handler['L_ERROR'] : ''; ?>
						<br />	
					</div>
				</div>
				<?php } ?>
			
				<form action="admin_news.php" name="form" method="post" style="margin:auto;" onsubmit="return check_form();" class="fieldset_content">
					<fieldset>
						<legend><?php echo isset($this->_var['L_ADD_NEWS']) ? $this->_var['L_ADD_NEWS'] : ''; ?></legend>
						<p><?php echo isset($this->_var['L_REQUIRE']) ? $this->_var['L_REQUIRE'] : ''; ?></p>
						<dl>
							<dt><label for="title">* <?php echo isset($this->_var['L_TITLE']) ? $this->_var['L_TITLE'] : ''; ?></label></dt>
							<dd><label><input type="text" size="65" maxlength="100" id="title" name="title" value="<?php echo isset($_tmpb_news['TITLE']) ? $_tmpb_news['TITLE'] : ''; ?>" class="text" /></label></dd>
						</dl>
						<dl>
							<dt><label for="idcat">* <?php echo isset($this->_var['L_CATEGORY']) ? $this->_var['L_CATEGORY'] : ''; ?></label></dt>
							<dd><label>
								<select id="idcat" name="idcat">				
								<?php if( !isset($_tmpb_news['select']) || !is_array($_tmpb_news['select']) ) $_tmpb_news['select'] = array();
foreach($_tmpb_news['select'] as $select_key => $select_value) {
$_tmpb_select = &$_tmpb_news['select'][$select_key]; ?>				
									<?php echo isset($_tmpb_select['CAT']) ? $_tmpb_select['CAT'] : ''; ?>				
								<?php } ?>				
								</select>
							</label></dd>
						</dl>
						<br />
							<label for="contents">* <?php echo isset($this->_var['L_TEXT']) ? $this->_var['L_TEXT'] : ''; ?></label></dt>
							<?php $this->tpl_include('handle_bbcode'); ?>
							<label><textarea type="text" rows="15" cols="86" id="contents" name="contents"><?php echo isset($_tmpb_news['CONTENTS']) ? $_tmpb_news['CONTENTS'] : ''; ?></textarea></label>
						<br />
						<br />

			<?php } ?>
