		<div id="admin-quick-menu">			<ul>				<li class="title-menu">{L_PAGES}</li>				<li>					<a href="admin_pages.php?token={TOKEN}"><img src="pages.png" alt="" /></a>					<br />					<a href="admin_pages.php" class="quick-link">{L_PAGES_CONGIG}</a>				</li>				<li>					<a href="pages.php"><img src="pages.png" alt="" /></a>					<br />					<a href="pages.php" class="quick-link">{L_PAGES_MANAGEMENT}</a>				</li>			</ul>		</div>				<div id="admin-contents">					<form action="admin_pages.php?token={TOKEN}" method="post">				<fieldset>					<legend>						{L_PAGES_CONGIG}					</legend>					<div class="form-element">						<label for="count_hits">							{L_COUNT_HITS}							<span class="field-description">({L_COUNT_HITS_EXPLAIN})</span>						</label>						<div class="form-field">							<input type="checkbox" name="count_hits" {HITS_CHECKED}>						</div>					</div>					<div class="form-element"> 						<label for="comments_activated">							{L_COMMENTS_ACTIVATED}						</label>						<div class="form-field"> 							<input type="checkbox" name="comments_activated" {COM_CHECKED}>						</div>					</div>				</fieldset>				<fieldset>					<legend>						{L_AUTH}					</legend>					<div class="form-element">						<label>{L_READ_PAGE}</label>						<div class="form-field">							{SELECT_READ_PAGE}						</div>					</div>					<div class="form-element">						<label>{L_EDIT_PAGE}</label>						<div class="form-field">							{SELECT_EDIT_PAGE}						</div>					</div>					<div class="form-element">						<label>{L_READ_COM}</label>						<div class="form-field">							{SELECT_READ_COM}						</div>					</div>				</fieldset>								<fieldset class="fieldset-submit">					<legend>{L_UPDATE}</legend>					<button type="submit" name="update" value="true" class="submit">{L_UPDATE}</button>					<button type="reset" value="true">{L_RESET}</button>								</fieldset>				</form>		</div>