		<script type="text/javascript">
		<!--
		function Confirm() {
		return confirm("{L_CONFIRM_DEL_ENTRY}");
		}
		-->
		</script>

		<div id="admin_quick_menu">
				<ul>
					<li class="title_menu">{L_WIKI_MANAGEMENT}</li>
				<li>
					<a href="admin_wiki.php"><img src="wiki.png" alt="" /></a>
					<br />
					<a href="admin_wiki.php" class="quick_link">{L_CONFIG_WIKI}</a>
				</li>
				<li>
					<a href="admin_wiki_groups.php"><img src="wiki.png" alt="" /></a>
					<br />
					<a href="admin_wiki_groups.php" class="quick_link">{L_WIKI_GROUPS}</a>
				</li>
			</ul>
		</div>

		<div id="admin_contents">
			<form action="admin_wiki_groups.php?token={TOKEN}" method="post">
				<fieldset>
					<legend>
						{L_WIKI_GROUPS}
					</legend>
					{EXPLAIN_WIKI_GROUPS}
					
					<div class="form-element">
						<label>{L_CREATE_ARTICLE}</label>
						
						<div class="form-field">
							{SELECT_CREATE_ARTICLE}
						</div>
					</div>
					<div class="form-element">
						<label>{L_CREATE_CAT}</label>
						
						<div class="form-field">
							{SELECT_CREATE_CAT}
						</div>
					</div>
					<div class="form-element">
						<label>{L_RESTORE_ARCHIVE}</label>
						
						<div class="form-field">
							{SELECT_RESTORE_ARCHIVE}
						</div>
					</div>
					<div class="form-element">
						<label>{L_DELETE_ARCHIVE}</label>
						
						<div class="form-field">
							{SELECT_DELETE_ARCHIVE}
						</div>
					</div>
					<div class="form-element">
						<label>{L_EDIT}</label>
						
						<div class="form-field">
							{SELECT_EDIT}
						</div>
					</div>
					<div class="form-element">
						<label>{L_DELETE}</label>
						
						<div class="form-field">
							{SELECT_DELETE}
						</div>
					</div>
					<div class="form-element">
						<label>{L_RENAME}</label>
						
						<div class="form-field">
							{SELECT_RENAME}
						</div>
					</div>
					<div class="form-element">
						<label>{L_REDIRECT}</label>
						
						<div class="form-field">
							{SELECT_REDIRECT}
						</div>
					</div>
					<div class="form-element">
						<label>{L_MOVE}</label>
						
						<div class="form-field">
							{SELECT_MOVE}
						</div>
					</div>
					<div class="form-element">
						<label>{L_STATUS}</label>
						
						<div class="form-field">
							{SELECT_STATUS}
						</div>
					</div>
					<div class="form-element">
						<label>{L_COM}</label>
						
						<div class="form-field">
							{SELECT_COM}
						</div>
					</div>
					<div class="form-element">
						<label>{L_RESTRICTION}</label>
						
						<div class="form-field">
							{SELECT_RESTRICTION}
						</div>
					</div>
				</fieldset>
				
				<fieldset class="fieldset-submit">
					<legend>{L_UPDATE}</legend>
					<button type="submit" name="valid" value="true">{L_UPDATE}</button>
					&nbsp;&nbsp; 
					<button type="reset" value="true">{L_RESET}</button>
				</fieldset>
			</form>
		</div>