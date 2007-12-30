			<?php if( !isset($this->_block['news']) || !is_array($this->_block['news']) ) $this->_block['news'] = array();
foreach($this->_block['news'] as $news_key => $news_value) {
$_tmpb_news = &$this->_block['news'][$news_key]; ?>
					<label for="extend_contents"><?php echo isset($this->_var['L_EXTENDED_NEWS']) ? $this->_var['L_EXTENDED_NEWS'] : ''; ?></label></dt>
							<?php $this->tpl_include('handle_bbcode'); ?>
							<label><textarea type="text" rows="15" cols="86" id="extend_contents" name="extend_contents"><?php echo isset($_tmpb_news['EXTEND_CONTENTS']) ? $_tmpb_news['EXTEND_CONTENTS'] : ''; ?></textarea> </label>
						<br />
						<dl class="overflow_visible">
							<dt><label for="release_date">* <?php echo isset($this->_var['L_RELEASE_DATE']) ? $this->_var['L_RELEASE_DATE'] : ''; ?></label></dt>
							<dd><label>
								<label><input type="radio" value="2" name="visible" <?php echo isset($_tmpb_news['VISIBLE_WAITING']) ? $_tmpb_news['VISIBLE_WAITING'] : ''; ?> /> 
								<input type="text" size="8" maxlength="8" id="start" name="start" value="<?php echo isset($this->_var['START']) ? $this->_var['START'] : ''; ?>" class="text" /> 				
								<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar1">
									<div id="start_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);">							
									</div>
								</div>
								<a onClick="xmlhttprequest_calendar('start_date', '?input_field=start&amp;field=start_date&amp;d=<?php echo isset($_tmpb_news['DAY_RELEASE_S']) ? $_tmpb_news['DAY_RELEASE_S'] : ''; ?>&amp;m=<?php echo isset($_tmpb_news['MONTH_RELEASE_S']) ? $_tmpb_news['MONTH_RELEASE_S'] : ''; ?>&amp;y=<?php echo isset($_tmpb_news['YEAR_RELEASE_S']) ? $_tmpb_news['YEAR_RELEASE_S'] : ''; ?>');display_calendar(1);" onmouseover="hide_calendar(1, 1);" onmouseout="hide_calendar(1, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
												
								<?php echo isset($this->_var['L_UNTIL']) ? $this->_var['L_UNTIL'] : ''; ?>&nbsp;
								
								<input type="text" size="8" maxlength="8" id="end" name="end" value="<?php echo isset($this->_var['END']) ? $this->_var['END'] : ''; ?>" class="text" /> 

								<div style="position:relative;z-index:100;top:6px;margin-left:155px;float:left;display:none;" id="calendar2">
									<div id="end_date" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);">							
									</div>
								</div>
								<a onClick="xmlhttprequest_calendar('end_date', '?input_field=end&amp;field=end_date&amp;d=<?php echo isset($this->_var['DAY_RELEASE_S']) ? $this->_var['DAY_RELEASE_S'] : ''; ?>&amp;m=<?php echo isset($this->_var['MONTH_RELEASE_S']) ? $this->_var['MONTH_RELEASE_S'] : ''; ?>&amp;y=<?php echo isset($this->_var['YEAR_RELEASE_S']) ? $this->_var['YEAR_RELEASE_S'] : ''; ?>');display_calendar(2, 'end_date');" onmouseover="hide_calendar(2, 1);" onmouseout="hide_calendar(2, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a></label>

								<br />
								<label><input type="radio" value="1" name="visible" <?php echo isset($_tmpb_news['VISIBLE_ENABLED']) ? $_tmpb_news['VISIBLE_ENABLED'] : ''; ?> id="release_date" /> <?php echo isset($this->_var['L_IMMEDIATE']) ? $this->_var['L_IMMEDIATE'] : ''; ?></label>
								<br />
								<label><input type="radio" value="0" name="visible" <?php echo isset($_tmpb_news['VISIBLE_UNAPROB']) ? $_tmpb_news['VISIBLE_UNAPROB'] : ''; ?> /> <?php echo isset($this->_var['L_UNAPROB']) ? $this->_var['L_UNAPROB'] : ''; ?></label>
							</label></dd>
						</dl>
						<dl class="overflow_visible">
							<dt><label for="current_date">* <?php echo isset($this->_var['L_NEWS_DATE']) ? $this->_var['L_NEWS_DATE'] : ''; ?></label></dt>
							<dd><label>
								<input type="text" size="8" maxlength="8" id="current_date" name="current_date" value="<?php echo isset($_tmpb_news['CURRENT_DATE']) ? $_tmpb_news['CURRENT_DATE'] : ''; ?>" class="text" /> 			
								<div style="position:relative;z-index:100;top:6px;float:left;display:none;" id="calendar3">
									<div id="current" class="calendar_block" style="width:204px;" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);">							
									</div>
								</div>
								<a onClick="xmlhttprequest_calendar('current', '?input_field=current_date&amp;field=current&amp;d=<?php echo isset($_tmpb_news['DAY_DATE']) ? $_tmpb_news['DAY_DATE'] : ''; ?>&amp;m=<?php echo isset($_tmpb_news['MONTH_DATE']) ? $_tmpb_news['MONTH_DATE'] : ''; ?>&amp;y=<?php echo isset($_tmpb_news['YEAR_DATE']) ? $_tmpb_news['YEAR_DATE'] : ''; ?>');display_calendar(3);" onmouseover="hide_calendar(3, 1);" onmouseout="hide_calendar(3, 0);" style="cursor:pointer;"><img style="vertical-align:middle;" src="../templates/<?php echo isset($this->_var['THEME']) ? $this->_var['THEME'] : ''; ?>/images/calendar.png" alt="" /></a>
								
								<?php echo isset($this->_var['L_AT']) ? $this->_var['L_AT'] : ''; ?>
								<input type="text" size="2" maxlength="2" name="hour" value="<?php echo isset($_tmpb_news['HOUR']) ? $_tmpb_news['HOUR'] : ''; ?>" class="text" /> H <input type="text" size="2" maxlength="2" name="min" value="<?php echo isset($_tmpb_news['MIN']) ? $_tmpb_news['MIN'] : ''; ?>" class="text" />
							</label></dd>
						</dl>
						<dl>
							<dt><label for="archive">* <?php echo isset($this->_var['L_NEWS_ARCHIVE']) ? $this->_var['L_NEWS_ARCHIVE'] : ''; ?></label></dt>
							<dd>
								<label><input type="radio" value="1" name="archive" <?php echo isset($_tmpb_news['ARCHIVE_ENABLED']) ? $_tmpb_news['ARCHIVE_ENABLED'] : ''; ?> /> <?php echo isset($this->_var['L_YES']) ? $this->_var['L_YES'] : ''; ?></label>
								<label><input type="radio" value="0" name="archive" <?php echo isset($_tmpb_news['ARCHIVE_DISABLED']) ? $_tmpb_news['ARCHIVE_DISABLED'] : ''; ?> id="archive" /> <?php echo isset($this->_var['L_NO']) ? $this->_var['L_NO'] : ''; ?></label>
							</dd>
						</dl>
					</fieldset>	
					
					<fieldset>
						<legend><?php echo isset($this->_var['L_IMG_MANAGEMENT']) ? $this->_var['L_IMG_MANAGEMENT'] : ''; ?></legend>
						<dl>
							<dt><label><?php echo isset($this->_var['L_PREVIEW_IMG']) ? $this->_var['L_PREVIEW_IMG'] : ''; ?></label><br /><span><?php echo isset($this->_var['L_PREVIEW_IMG_EXPLAIN']) ? $this->_var['L_PREVIEW_IMG_EXPLAIN'] : ''; ?></span></dt>
							<dd><?php echo isset($this->_var['IMG_PREVIEW']) ? $this->_var['IMG_PREVIEW'] : ''; ?></dd>
						</dl>
						<dl>
							<dt><label for="img_field"><?php echo isset($this->_var['L_IMG_LINK']) ? $this->_var['L_IMG_LINK'] : ''; ?></label></dt>
							<dd><label><input type="text" size="60" id="img_field" name="img" value="<?php echo isset($_tmpb_news['IMG']) ? $_tmpb_news['IMG'] : ''; ?>" class="text" /></label></dd>
						</dl>
						<dl>
							<dt><label for="alt"><?php echo isset($this->_var['L_IMG_DESC']) ? $this->_var['L_IMG_DESC'] : ''; ?></label></dt>
							<dd><label><input type="text" size="60" name="alt" id="alt" value="<?php echo isset($_tmpb_news['ALT']) ? $_tmpb_news['ALT'] : ''; ?>" class="text" /></label></dd>
						</dl>
					</fieldset>		
					
					<fieldset class="fieldset_submit">
						<legend><?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?></legend>
						<input type="hidden" name="id" value="<?php echo isset($_tmpb_news['IDNEWS']) ? $_tmpb_news['IDNEWS'] : ''; ?>" class="submit" />
						<input type="submit" name="valid" value="<?php echo isset($this->_var['L_UPDATE']) ? $this->_var['L_UPDATE'] : ''; ?>" class="submit" />
						&nbsp;&nbsp; 
						<input type="submit" name="previs" value="<?php echo isset($this->_var['L_PREVIEW']) ? $this->_var['L_PREVIEW'] : ''; ?>" class="submit" />
						&nbsp;&nbsp; 
						<input type="reset" value="<?php echo isset($this->_var['L_RESET']) ? $this->_var['L_RESET'] : ''; ?>" class="reset" />				
					</fieldset>	
				</form>

			<?php } ?>
		</div>	
