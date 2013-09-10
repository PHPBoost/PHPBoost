<fieldset>
	<legend>Typographie</legend>
	<strong>Texte en gras</strong><br />
	<em>Texte en italic</em><br />
	<span style="text-decoration: underline;">Texte souligné</span><br />
	<strike>Texte barré</strike><br /><br />
	<h1>h1. Titre 1</h1>
	<h2>h2. Titre 2</h2>
	<h3>h3. Titre 3</h3>
	<h4>h4. Titre 4</h4>
	<h5>h5. Titre 5</h5>
	<h6>h6. Titre 6</h6> <br>
	<a href="#">Lien</a> <br>
	<a href="#" class="smaller">Lien en très petit</a> <br>
	<a href="#" class="small">Lien en petit</a> <br>
	<a href="#" class="big">Lien en grand</a> <br>
	<a href="#" class="bigger">Lien en plus grand</a> <br>
	<a href="#" class="biggest">Lien très grand</a> <br>
	<a href="#" class="admin">Administrateur</a> <br>
	<a href="#" class="modo">Modérateur</a> <br>
	<a href="#" class="member">Membre</a> <br>
	
	
</fieldset>

<fieldset>
	<legend>Titres spécifiques (BBCode)</legend>
	<h3 class="title1">Titre 1</h3><br>
	<h3 class="title2">Titre 2</h3><br>
	<h4 class="stitle1">Titre 3</h4><br>
	<h4 class="stitle2">Titre 4</h4><br>
</fieldset>

<fieldset>
	<legend>Autre</legend>
	<span class="text_blockquote">Citation:</span><div class="blockquote">Dein Syria per speciosam interpatet diffusa planitiem. hanc nobilitat Antiochia, mundo cognita civitas, cui non certaverit alia advecticiis ita adfluere copiis et internis, et Laodicia et Apamia itidemque Seleucia iam inde a primis auspiciis florentissimae.</div><br />
	<span class="text_hide">Caché:</span><div class="hide" onclick="bb_hide(this)"><div class="hide2">Dein Syria per speciosam interpatet diffusa planitiem. hanc nobilitat Antiochia, mundo cognita civitas, cui non certaverit alia advecticiis ita adfluere copiis et internis, et Laodicia et Apamia itidemque Seleucia iam inde a primis auspiciis florentissimae.</div></div><br />
	<span class="text_code">Code PHP :</span><div class="code"><pre style="display:inline;"><pre class="php" style="font-family:monospace;"><a href="http://www.php.net/%26amp%3Blt%3CSEMI%3E%3Fphp"><span style="color: #FF0000; font-weight: normal;">&lt;?php</span></a>
	&nbsp;
	<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">class</span> CategoriesCache <span style="color: #0000FF; font-weight: bold;">implements</span> CacheData
	<span style="color: #8000FF;">&#123;</span>
		<span style="color: #0000FF; font-weight: bold;">private</span> <span style="color: #000080;">$categories</span><span style="color: #8000FF;">;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> synchronize<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
		<span style="color: #8000FF;">&#123;</span>
			<span style="color: #000080;">$categories_cache</span> <span style="color: #8000FF;">=</span> <span style="color: #0000FF; font-weight: bold;">self</span><span style="color: #8000FF;">::</span><span style="color: #000000;">get_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
			<span style="color: #000080;">$category_class</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$categories_cache</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_category_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
	&nbsp;
			<span style="color: #000080;">$root_category</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$categories_cache</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_root_category</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
			<span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">&#91;</span>Category<span style="color: #8000FF;">::</span><span style="color: #000000;">ROOT_CATEGORY</span><span style="color: #8000FF;">&#93;</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$root_category</span><span style="color: #8000FF;">;</span>
			<span style="color: #000080;">$result</span> <span style="color: #8000FF;">=</span> PersistenceContext<span style="color: #8000FF;">::</span><span style="color: #000000;">get_querier</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">select_rows</span><span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$categories_cache</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_table_name</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #0000FF; font-weight: bold;">array</span><span style="color: #8000FF;">&#40;</span><span style="color: #808080;">'*'</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #808080;">'ORDER BY id_parent, c_order'</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
			<span style="color: #0000FF; font-weight: bold;">while</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$row</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$result</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">fetch</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">&#41;</span>
			<span style="color: #8000FF;">&#123;</span>
				<span style="color: #000080;">$category</span> <span style="color: #8000FF;">=</span> <span style="color: #0000FF; font-weight: bold;">new</span> <span style="color: #000080;">$category_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
				<span style="color: #000080;">$category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">set_properties</span><span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$row</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
				<span style="color: #0000FF; font-weight: bold;">if</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">auth_is_empty</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">&#41;</span>
				<span style="color: #8000FF;">&#123;</span>
					<span style="color: #000080;">$category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">set_authorizations</span><span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$root_category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_authorizations</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
				<span style="color: #8000FF;">&#125;</span>
				<span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">&#91;</span><span style="color: #000080;">$row</span><span style="color: #8000FF;">&#91;</span><span style="color: #808080;">'id'</span><span style="color: #8000FF;">&#93;</span><span style="color: #8000FF;">&#93;</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$category</span><span style="color: #8000FF;">;</span>
			<span style="color: #8000FF;">&#125;</span>
		<span style="color: #8000FF;">&#125;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_table_name<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_category_class<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_module_identifier<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">abstract</span> <span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_root_category<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_categories<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
		<span style="color: #8000FF;">&#123;</span>
			<span style="color: #0000FF; font-weight: bold;">return</span> <span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">;</span>
		<span style="color: #8000FF;">&#125;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_childrens<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id_category</span><span style="color: #8000FF;">&#41;</span>
		<span style="color: #8000FF;">&#123;</span>
			<span style="color: #000080;">$childrens</span> <span style="color: #8000FF;">=</span> <span style="color: #0000FF; font-weight: bold;">array</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
			<span style="color: #0000FF; font-weight: bold;">foreach</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span> <span style="color: #0000FF; font-weight: bold;">as</span> <span style="color: #000080;">$id</span> <span style="color: #8000FF;">=&gt;</span> <span style="color: #000080;">$category</span><span style="color: #8000FF;">&#41;</span>
			<span style="color: #8000FF;">&#123;</span>
				<span style="color: #0000FF; font-weight: bold;">if</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$category</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_id_parent</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span> <span style="color: #8000FF;">==</span> <span style="color: #000080;">$id_category</span><span style="color: #8000FF;">&#41;</span>
				<span style="color: #8000FF;">&#123;</span>
					<span style="color: #000080;">$childrens</span><span style="color: #8000FF;">&#91;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#93;</span> <span style="color: #8000FF;">=</span> <span style="color: #000080;">$category</span><span style="color: #8000FF;">;</span>
				<span style="color: #8000FF;">&#125;</span>
			<span style="color: #8000FF;">&#125;</span>
			<span style="color: #0000FF; font-weight: bold;">return</span> <span style="color: #000080;">$childrens</span><span style="color: #8000FF;">;</span>
		<span style="color: #8000FF;">&#125;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> category_exists<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#41;</span>
		<span style="color: #8000FF;">&#123;</span>
			<span style="color: #0000FF; font-weight: bold;">return</span> array_key_exists<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">,</span> <span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		<span style="color: #8000FF;">&#125;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_category<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#41;</span>
		<span style="color: #8000FF;">&#123;</span>
			<span style="color: #0000FF; font-weight: bold;">if</span> <span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">category_exists</span><span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">&#41;</span>
			<span style="color: #8000FF;">&#123;</span>
				<span style="color: #0000FF; font-weight: bold;">return</span> <span style="color: #000080;">$this</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">categories</span><span style="color: #8000FF;">&#91;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#93;</span><span style="color: #8000FF;">;</span>
			<span style="color: #8000FF;">&#125;</span>
			throw <span style="color: #0000FF; font-weight: bold;">new</span> CategoryNotFoundException<span style="color: #8000FF;">&#40;</span><span style="color: #000080;">$id</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		<span style="color: #8000FF;">&#125;</span>
	&nbsp;
		<span style="color: #008000; font-style: italic;">/**
		 * Loads and returns the categories cached data.
		 * @return CategoriesCache The cached data
		 */</span>
		<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">static</span> <span style="color: #0000FF; font-weight: bold;">function</span> load<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
		<span style="color: #8000FF;">&#123;</span>
			<span style="color: #0000FF; font-weight: bold;">return</span> CacheManager<span style="color: #8000FF;">::</span><span style="color: #000000;">load</span><span style="color: #8000FF;">&#40;</span>get_called_class<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #0000FF; font-weight: bold;">self</span><span style="color: #8000FF;">::</span><span style="color: #000000;">get_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_module_identifier</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #808080;">'categories'</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		<span style="color: #8000FF;">&#125;</span>
	&nbsp;
		<span style="color: #008000; font-style: italic;">/**
		 * Invalidates categories cached data.
		 */</span>
		<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">static</span> <span style="color: #0000FF; font-weight: bold;">function</span> invalidate<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
		<span style="color: #8000FF;">&#123;</span>
			CacheManager<span style="color: #8000FF;">::</span><span style="color: #000000;">invalidate</span><span style="color: #8000FF;">&#40;</span><span style="color: #0000FF; font-weight: bold;">self</span><span style="color: #8000FF;">::</span><span style="color: #000000;">get_class</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">-&gt;</span><span style="color: #000000;">get_module_identifier</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">,</span> <span style="color: #808080;">'categories'</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		<span style="color: #8000FF;">&#125;</span>
	&nbsp;
		<span style="color: #0000FF; font-weight: bold;">public</span> <span style="color: #0000FF; font-weight: bold;">static</span> <span style="color: #0000FF; font-weight: bold;">function</span> get_class<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span>
		<span style="color: #8000FF;">&#123;</span>
			<span style="color: #000080;">$class_name</span> <span style="color: #8000FF;">=</span> get_called_class<span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
			<span style="color: #0000FF; font-weight: bold;">return</span> <span style="color: #0000FF; font-weight: bold;">new</span> <span style="color: #000080;">$class_name</span><span style="color: #8000FF;">&#40;</span><span style="color: #8000FF;">&#41;</span><span style="color: #8000FF;">;</span>
		<span style="color: #8000FF;">&#125;</span>
	<span style="color: #8000FF;">&#125;</span>
	<a href="http://www.php.net/%3F%26amp%3Bgt%3CSEMI%3E"><span style="color: #FF0000; font-weight: normal;">?&gt;</span></a></pre></pre></div><br /><br />
	
	
	<h2>Listes</h2><br>
	<ul>
		<li> Élément 1</li>
		<li> Élément 2</li>
		<li> Élément 3</li>
	</ul>

	<ol>
		<li> Élément 1</li>
		<li> Élément 2</li>
		<li> Élément 3</li>
	</ol>
</fieldset>

<div class="text_center"># INCLUDE PAGINATION #</div>

<div id="comments_list">
	<h2>Commentaires</h2><br>
	<div id="com2" class="comment" itemscope="itemscope" itemtype="http://schema.org/Comment">
		<div class="comment-user_infos">
			<div id="comment-pseudo">
				
					<a itemprop="author" href="{PATH_TO_ROOT}/user/?url=/profile/1" class="admin" >
						reidlos
					</a>
				
			</div>
			<div class="comment-level">Administrateur</div>
			<img src="{PATH_TO_ROOT}/templates/base/images/no_avatar.png" class="comment-avatar" />
		</div>
		<div class="comment-content">
			<div class="comment-date">
				<div style="float:right;">
					<a itemprop="url" href="#com2">#2</a>
					
						<a href="#comments_message">
							<img src="{PATH_TO_ROOT}/templates/base/images/french/edit.png" alt="Modifier" title="Modifier" class="valign_middle" />
						</a> 
						<a href="#comments_message" onclick="javascript:return Confirm_del_comment();">
							<img src="{PATH_TO_ROOT}/templates/base/images/french/delete.png" alt="Supprimer" title="Supprimer" class="valign_middle" />
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
				
					<a itemprop="author" href="{PATH_TO_ROOT}/user/?url=/profile/1" class="admin" >
						reidlos
					</a>
				
			</div>
			<div class="comment-level">Administrateur</div>
			<img src="{PATH_TO_ROOT}/templates/base/images/no_avatar.png" class="comment-avatar" />
		</div>
		<div class="comment-content">
			<div class="comment-date">
				<div style="float:right;">
					<a itemprop="url" href="#com3">#3</a>
					
						<a href="#comments_message">
							<img src="{PATH_TO_ROOT}/templates/base/images/french/edit.png" alt="Modifier" title="Modifier" class="valign_middle" />
						</a> 
						<a href="#comments_message" onclick="javascript:return Confirm_del_comment();">
							<img src="{PATH_TO_ROOT}/templates/base/images/french/delete.png" alt="Supprimer" title="Supprimer" class="valign_middle" />
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