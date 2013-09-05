<fieldset>
	<legend>Typographie</legend>
	<h1>h1. Titre 1</h1>
	<h2>h2. Titre 2</h2>
	<h3>h.. Titre 3</h3>
	<h4>h4. Titre 4</h4>
	<h5>h5. Titre 5</h5>
	<h6>h6. Titre 6</h6> <br>
	<a href="#">Lien</a> <br>
	<a href="#" class="small_link">Lien en petit</a> <br>
	<a href="#" class="big_link">Lien en grand</a> <br>
	<a href="#" class="admin">Administrateur</a> <br>
	<a href="#" class="modo">Modérateur</a> <br>
	<a href="#" class="member">Membre</a> <br>
</fieldset>

<fieldset>
	<legend>Autre</legend>
	<span class="text_blockquote">Citation</span><div class="blockquote">Bonjour</div>
</fieldset>

<div class="text_center"># INCLUDE PAGINATION #</div>

<div id="comments_list">
	<h1>Commentaires</h1><br>
	<div id="com2" class="comment" itemscope="itemscope" itemtype="http://schema.org/Comment">
		<div class="comment-user_infos">
			<div id="comment-pseudo">
				
					<a itemprop="author" href="http://localhost/trunk/user/?url=/profile/1" class="admin" >
						reidlos
					</a>
				
			</div>
			<div class="comment-level">Administrateur</div>
			<img src="/trunk/templates/base/images/no_avatar.png" class="comment-avatar" />
		</div>
		<div class="comment-content">
			<div class="comment-date">
				<div style="float:right;">
					<a itemprop="url" href="#com2">#2</a>
					
						<a href="#comments_message">
							<img src="/trunk/templates/base/images/french/edit.png" alt="Modifier" title="Modifier" class="valign_middle" />
						</a> 
						<a href="#comments_message" onclick="javascript:return Confirm_del_comment();">
							<img src="/trunk/templates/base/images/french/delete.png" alt="Supprimer" title="Supprimer" class="valign_middle" />
						</a>
					
				</div>
				<span itemprop="datePublished" content="2013-09-05T15:37:01+00:00">05/09/2013 à 15h37</span>
			</div>
			<div class="comment-message">
				<div itemprop="text" class="message-containt" class="content">Ceci est un commentaire</div>
			</div>
		</div>
	</div>

	<div id="com3" class="comment" itemscope="itemscope" itemtype="http://schema.org/Comment">
		<div class="comment-user_infos">
			<div id="comment-pseudo">
				
					<a itemprop="author" href="http://localhost/trunk/user/?url=/profile/1" class="admin" >
						reidlos
					</a>
				
			</div>
			<div class="comment-level">Administrateur</div>
			<img src="/trunk/templates/base/images/no_avatar.png" class="comment-avatar" />
		</div>
		<div class="comment-content">
			<div class="comment-date">
				<div style="float:right;">
					<a itemprop="url" href="#com3">#3</a>
					
						<a href="#comments_message">
							<img src="/trunk/templates/base/images/french/edit.png" alt="Modifier" title="Modifier" class="valign_middle" />
						</a> 
						<a href="#comments_message" onclick="javascript:return Confirm_del_comment();">
							<img src="/trunk/templates/base/images/french/delete.png" alt="Supprimer" title="Supprimer" class="valign_middle" />
						</a>
					
				</div>
				<span itemprop="datePublished" content="2013-09-05T15:37:07+00:00">05/09/2013 à 15h37</span>
			</div>
			<div class="comment-message">
				<div itemprop="text" class="message-containt" class="content">Voici le deuxième</div>
			</div>
		</div>
	</div>
</div>

<table class="module_table">
	<tr> 
		<th colspan="3">
			Tableau
		</th>
	</tr>
	<tr style="text-align:center;">
		<td class="row1">
			Nom
		</td>
		<td class="row1">
			Description
		</td>
		<td class="row1">
			Auteur
		</td>
	</tr>
	<tr style="text-align:center;">
		<td class="row2"> 
			Test
		</td>
		<td class="row2">
			Description
		</td>
		<td class="row2">
			Auteur
		</td>
	</tr>
	<tr style="text-align:center;">
		<td class="row2"> 
			Test
		</td>
		<td class="row2">
			Description
		</td>
		<td class="row2">
			Auteur
		</td>
	</tr>
	<tr>
		
	</tr>
</table>








<fieldset>
	<legend>Messages d'erreurs</legend>
	# START messages # # INCLUDE messages.VIEW # # END messages #
</fieldset>