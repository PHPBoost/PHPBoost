		<div id="admin_quick_menu">				<ul>					<li class="title_menu">{L_WIKI_MANAGEMENT}</li>				<li>					<a href="admin_wiki.php"><img src="wiki.png" alt="" /></a>					<br />					<a href="admin_wiki.php" class="quick_link">{L_CONFIG_WIKI}</a>				</li>				<li>					<a href="admin_wiki_groups.php"><img src="wiki.png" alt="" /></a>					<br />					<a href="admin_wiki_groups.php" class="quick_link">{L_WIKI_GROUPS}</a>				</li>			</ul>		</div>		<div id="admin_contents">					<form action="admin_wiki.php?token={TOKEN}" method="post" class="fieldset_content">				<fieldset>					<legend>{L_WHOLE_WIKI}</legend>					<div class="form-element">						<label for="hits_counter">{L_HITS_COUNTER}</label>						<div class="form-field"><label><input type="checkbox" name="hits_counter" id="hits_counter" {HITS_SELECTED}></label></div>					</div>					<div class="form-element">						<label for="wiki_name">{L_WIKI_NAME}</label>						<div class="form-field"><label><input type="text" maxlength="255" size="40" id="wiki_name" name="wiki_name" value="{WIKI_NAME}"></label></div>					</div>				</fieldset>					<fieldset>					<legend>{L_INDEX_WIKI}</legend>					<div class="form-element">						<label for="display_cats">{L_DISPLAY_CATEGORIES_ON_INDEX}</label>						<div class="form-field">							<label><input type="radio" {HIDE_CATEGORIES_ON_INDEX} name="display_categories_on_index" id="display_cats" value="0"> {L_NOT_DISPLAY}</label>							<label><input type="radio" {DISPLAY_CATEGORIES_ON_INDEX} name="display_categories_on_index" value="1"> {L_DISPLAY}</label>						</div>					</div>					<div class="form-element">						<label for="number_articles_on_index">{L_NUMBER_ARTICLES_ON_INDEX}</label><br /><span>{L_NUMBER_ARTICLES_ON_INDEX_EXPLAIN}</span>						<div class="form-field"><label><input type="text" size="3" name="number_articles_on_index" id="number_articles_on_index" value="{NUMBER_ARTICLES_ON_INDEX}"></label></div>					</div>					<div class="form-element-textarea">						<label for="contents">{L_DESCRIPTION}</label>						<div style="position:relative;display:none;" id="loading_previewcontents">							<div style="margin:auto;margin-top:90px;width:100%;text-align:center;position:absolute;"><i class="icon-spinner icon-2x icon-spin"></i></div>						</div>						<div style="display:none;" class="xmlhttprequest_preview" id="xmlhttprequest_previewcontents"></div>						{KERNEL_EDITOR}						<textarea rows="10" cols="60" id="contents" name="contents">{DESCRIPTION}</textarea>						<div style="text-align:center;"><button type="button" onclick="XMLHttpRequest_preview('contents');">{L_PREVIEW}</button></div>					</div>				</fieldset>									<fieldset class="fieldset-submit">					<legend>{L_UPDATE}</legend>					<button type="submit" name="update" value="true">{L_UPDATE}</button>					&nbsp;&nbsp; 					<button type="reset" value="true">{L_RESET}</button>								</fieldset>				</form>		</div>		