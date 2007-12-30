		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("<?php echo isset($this->_var['L_ALERT_DELETE_NEWS']) ? $this->_var['L_ALERT_DELETE_NEWS'] : ''; ?>");
		}
		-->
		</script>
		
		
		<?php if( !isset($this->_block['edito']) || !is_array($this->_block['edito']) ) $this->_block['edito'] = array();
foreach($this->_block['edito'] as $edito_key => $edito_value) {
$_tmpb_edito = &$this->_block['edito'][$edito_key]; ?>
		<div class="edito_top">
			<div style="float:left;padding-left:30px;padding-top:5px;"><h3 class="title"><?php echo isset($_tmpb_edito['TITLE']) ? $_tmpb_edito['TITLE'] : ''; ?></h3></div>
			<div style="float:right"><?php echo isset($_tmpb_edito['EDIT']) ? $_tmpb_edito['EDIT'] : ''; ?></div>
		</div>
		<div class="news_container">
			<div class="news_content">
				<img src="../templates/phpboost/news/images/phpboost_box_v2_mini.jpg" alt="PHPBoost 2.0" class="float_right" />
				<img src="../templates/phpboost/news/images/phpboost_version.jpg" alt="PHPBoost 2.0" />
				<br />
				&nbsp;&nbsp;<?php echo isset($_tmpb_edito['CONTENTS']) ? $_tmpb_edito['CONTENTS'] : ''; ?> 

				<div style="width:350px;height:98px;margin:auto;background:url(../templates/phpboost/news/images/phpboost_download.jpg) no-repeat;">
					<div style="position:relative;width:230px;left:90px;top:35px;text-indent:5px;">
						<a href="http://www.phpboost.com/download/download-2-52+phpboost-2-0.php" style="color:#2A3B6C;font-size:16px;" title="Télécharger PHPBoost">Téléchargement gratuit et immédiat</a>
						
						<p class="text_small">Version 2.0, Multilingue, 3.5Mo</p>
					</div>
				</div>
				<div style="width:350px;margin:auto;text-align:center"><a href="http://www.phpboost.com/wiki/installation" class="small_link" title="Aide installation">Aide installation</a> | <a href="http://www.phpboost.com/wiki/wiki.php" class="small_link" title="Documentation">Documentation</a> | <a href="http://www.phpboost.com/forum/index.php" class="small_link" title="Support PHPBoost">Support</a> | <a href="http://demo.phpboost.com" class="small_link" title="Démonstration">Démonstration</a></div>
			</div>
			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		<?php } ?>
		
		
		<?php if( !isset($this->_block['no_news_available']) || !is_array($this->_block['no_news_available']) ) $this->_block['no_news_available'] = array();
foreach($this->_block['no_news_available'] as $no_news_available_key => $no_news_available_value) {
$_tmpb_no_news_available = &$this->_block['no_news_available'][$no_news_available_key]; ?>
		<div class="news_container">
			<div class="news_top_l"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<h3 class="title valign_middle"><?php echo isset($this->_var['L_LAST_NEWS']) ? $this->_var['L_LAST_NEWS'] : ''; ?></h3>
			</div>	
						
			<div class="news_content">
				<p class="text_strong" style="text-align:center"><?php echo isset($_tmpb_no_news_available['L_NO_NEWS_AVAILABLE']) ? $_tmpb_no_news_available['L_NO_NEWS_AVAILABLE'] : ''; ?></p>
			</div>
			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		<?php } ?>

		<?php echo isset($this->_var['START_TABLE_NEWS']) ? $this->_var['START_TABLE_NEWS'] : ''; ?>		
		<?php if( !isset($this->_block['news']) || !is_array($this->_block['news']) ) $this->_block['news'] = array();
foreach($this->_block['news'] as $news_key => $news_value) {
$_tmpb_news = &$this->_block['news'][$news_key]; ?>
		
		<?php echo isset($_tmpb_news['NEW_ROW']) ? $_tmpb_news['NEW_ROW'] : ''; ?>
		<div class="news_container">
			<div class="news_top_l"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left"><h3 class="title valign_middle"><?php echo isset($_tmpb_news['TITLE']) ? $_tmpb_news['TITLE'] : ''; ?></h3></div>
				<div style="float:right"><?php echo isset($_tmpb_news['COM']) ? $_tmpb_news['COM'] : '';  echo isset($_tmpb_news['EDIT']) ? $_tmpb_news['EDIT'] : '';  echo isset($_tmpb_news['DEL']) ? $_tmpb_news['DEL'] : ''; ?></div>
			</div>							
			<div class="news_content">
				<?php echo isset($_tmpb_news['IMG']) ? $_tmpb_news['IMG'] : ''; ?>
				<?php echo isset($_tmpb_news['ICON']) ? $_tmpb_news['ICON'] : ''; ?> 
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
		
		<?php $this->tpl_include('handle_com'); ?>
		
		<?php } ?>			
		<?php echo isset($this->_var['END_TABLE_NEWS']) ? $this->_var['END_TABLE_NEWS'] : ''; ?>
		
		
		<?php if( !isset($this->_block['news_link']) || !is_array($this->_block['news_link']) ) $this->_block['news_link'] = array();
foreach($this->_block['news_link'] as $news_link_key => $news_link_value) {
$_tmpb_news_link = &$this->_block['news_link'][$news_link_key]; ?>
			
		<div class="news_container">
			<div class="news_top_l"></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<div style="float:left"><a href="rss.php" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a> <h3 class="title valign_middle"><?php echo isset($this->_var['L_LAST_NEWS']) ? $this->_var['L_LAST_NEWS'] : ''; ?></h3></div>
				<div style="float:right"><?php echo isset($_tmpb_news['COM']) ? $_tmpb_news['COM'] : '';  echo isset($_tmpb_news['EDIT']) ? $_tmpb_news['EDIT'] : '';  echo isset($_tmpb_news['DEL']) ? $_tmpb_news['DEL'] : ''; ?></div>
			</div>	
						
			<div class="news_content">
				<?php echo isset($_tmpb_news_link['START_TABLE_NEWS']) ? $_tmpb_news_link['START_TABLE_NEWS'] : ''; ?>
				<?php if( !isset($_tmpb_news_link['list']) || !is_array($_tmpb_news_link['list']) ) $_tmpb_news_link['list'] = array();
foreach($_tmpb_news_link['list'] as $list_key => $list_value) {
$_tmpb_list = &$_tmpb_news_link['list'][$list_key]; ?>
					<?php echo isset($_tmpb_list['NEW_ROW']) ? $_tmpb_list['NEW_ROW'] : ''; ?>
						<li><img src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/li.png" alt="" /> <?php echo isset($_tmpb_list['ICON']) ? $_tmpb_list['ICON'] : ''; ?> <span style="font-weight:bold;font-size:11px;"><?php echo isset($_tmpb_list['DATE']) ? $_tmpb_list['DATE'] : ''; ?>:</span> <a href="<?php echo isset($_tmpb_list['U_NEWS']) ? $_tmpb_list['U_NEWS'] : ''; ?>" style="font-size:11px;"><?php echo isset($_tmpb_list['TITLE']) ? $_tmpb_list['TITLE'] : ''; ?></a></li>
				<?php } ?>
				<?php echo isset($_tmpb_news_link['END_TABLE_NEWS']) ? $_tmpb_news_link['END_TABLE_NEWS'] : ''; ?>	
			</div>
			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>	
		
		<?php } ?>
		
		<div style="text-align: center;"><?php echo isset($this->_var['PAGINATION']) ? $this->_var['PAGINATION'] : ''; ?></div>
		<div style="text-align: center;"><?php echo isset($this->_var['ARCHIVES']) ? $this->_var['ARCHIVES'] : ''; ?></div>
		
		<div class="news_container" style="width:500px;margin-top:20px;">
			<div class="news_top_l"><a href="../forum/rss.php" title="Rss"><img class="valign_middle" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/rss.gif" alt="Rss" title="Rss" /></a></div>			
			<div class="news_top_r"></div>
			<div class="news_top">
				<h3 class="title valign_middle">Derniers sujets du forum</h3>
			</div>						
			<div class="news_content" style="text-align:center">
				<script type="text/javascript" src="../cache/rss_forum.html"></script>  
			</div>			
			<div class="news_bottom_l"></div>		
			<div class="news_bottom_r"></div>
			<div class="news_bottom"></div>
		</div>
		