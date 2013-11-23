<section>
	<header>
		<h1>Typographie</h1>
	</header>
	<div class="content">
	
		<h5>Titres</h5><br />
		<div class="content">
			<h1>h1. Titre 1</h1>
			<h2>h2. Titre 2</h2>
			<h3>h3. Titre 3</h3>
			<h4>h4. Titre 4</h4>
			<h5>h5. Titre 5</h5>
			<h6>h6. Titre 6</h6><br />
		</div>
		
		<h5>Styles</h5><br />
		<div class="content">
			<strong>Texte en gras</strong><br />
			<em>Texte en italic</em><br />
			<span style="text-decoration: underline;">Texte souligné</span><br />
			<strike>Texte barré</strike><br /><br />
		</div>
		
		
		
		<h5>Tailles</h5><br />
		<div class="content">
			<a href="#">Lien</a> <br />
			<a href="#" class="smaller">Lien en trés petit</a> <br />
			<a href="#" class="small">Lien en petit</a> <br />
			<a href="#" class="big">Lien en grand</a> <br />
			<a href="#" class="bigger">Lien en plus grand</a> <br />
			<a href="#" class="biggest">Lien trés grand</a> <br /><br />
		</div>
		
		<h5>Couleur selon rang de l'utilisateur</h5><br />
		<div class="content">
			<a href="#" class="admin">Administrateur</a> <br />
			<a href="#" class="modo">Modérateur</a> <br />
			<a href="#" class="member">Membre</a> <br />
		</div>
		
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h1>Divers</h1>
	</header>
	
	<div class="content">
		<h5>Icônes des principales actions</h5><br />
		<div class="content">
			<ul>
				<li>Flux RSS : <a href="#" class="icon-syndication"></a></li>
				<li>Editer : <a href="#" class="icon-edit"></a></li>
				<li>Supprimer : <a href="#" class="icon-delete"></a></li>
				<li>Supprimer (contrôle automatique JS avec confirmation de suppression) : <a href="#" class="icon-delete" data-confirmation="delete-element"></a></li>
				<li>Supprimer (contrôe automatique JS avec confirmation personnalisée) : <a href="#" class="icon-delete" data-confirmation="Message personnalisÃ©"></a></li>
			</ul>
		</div>
		
		<br />
		<h5>Listes</h5><br />
		<div class="content">
			<ul>
				<li>Elément 1</li>
				<li>Elément 2</li>
				<li>Elément 3</li>
			</ul>
		
			<ol>
				<li>Elément 1</li>
				<li>Elément 2</li>
				<li>Elément 3</li>
			</ol>
		</div>
		<br />
		
		<h5>Barre de progression</h5><br />
		<div class="content">
			<h6>25%</h6> 
			<div class="progressbar-container" style="width:35%">
				<div class="progressbar" style="width:25%"></div>
			</div><br />
			
			<h6>50%</h6>
			<div class="progressbar-container" style="width:35%">
				<div class="progressbar" style="width:50%"></div>
			</div><br />
			
			<h6>75%</h6> 
			<div class="progressbar-container" style="width:35%">
				<span class="progressbar-infos">Informations utiles</span>
				<div class="progressbar" style="width:75%"></div>
			</div><br />
			
			<h6>100%</h6> 
			<div class="progressbar-container" style="width:35%">
				<span class="progressbar-infos">3 votes</span>
				<div class="progressbar" style="width:100%"></div>
			</div><br />
			
		<h5>Menus des modules</h5><br />
		<div class="content" style="height:200px;">
			<menu class="dynamic_menu">
				<ul>
					<li><a><i class="icon-bars"></i></a>
						<ul>
							<li class="extend"><a><i class="icon-eye"></i> Afficher</a>
								<ul>
									<li><a href="#"><i class="icon-eye"></i> Les plus vues</a></li>
									<li><a href="#"><i class="icon-star-half-empty"></i> Les mieux notées</a></li>
								</ul>
							</li>
							<li class="extend"><a><i class="icon-sort"></i> Ordonner par</a>
								<ul>
									<li><a href="#"><i class="icon-tag"></i> Nom</a></li>
									<li><a href="#"><i class="icon-clock-o"></i> Date</a></li>
									<li><a href="#"><i class="icon-eye"></i> Vues</a></li>
								</ul>
							</li>
							<li class="separator"></li>
							<li><a href="#"><i class="icon-plus"></i> Ajout d'images</a></li>
							<li><a href="#"><i class="icon-pencil"></i> Edition de la catégorie</a></li>
						</ul>
					</li>
				</ul>
			</menu>
			<br />
			<menu class="dynamic_menu right">
				<ul>
					<li><a><i class="icon-bars"></i></a>
						<ul>
							<li class="extend"><a><i class="icon-eye"></i> Afficher</a>
								<ul>
									<li><a href="#"><i class="icon-eye"></i> Les plus vues</a></li>
									<li><a href="#"><i class="icon-star-half-empty"></i> Les mieux notées</a></li>
								</ul>
							</li>
							<li class="extend"><a><i class="icon-sort"></i> Ordonner par</a>
								<ul>
									<li><a href="#"><i class="icon-tag"></i> Nom</a></li>
									<li><a href="#"><i class="icon-clock-o"></i> Date</a></li>
									<li><a href="#"><i class="icon-eye"></i> Vues</a></li>
								</ul>
							</li>
							<li class="separator"></li>
							<li><a href="#"><i class="icon-plus"></i> Ajout d'images</a></li>
							<li><a href="#"><i class="icon-pencil"></i> Edition de la catégorie</a></li>
						</ul>
					</li>
				</ul>
			</menu>
			<br /><br />
			<menu class="dynamic_menu group center">
				<ul>
					<li>
						<a href="#">Bugs non-résolus</a> 
					</li>
					<li class="current">
						<a href="#">Bugs résolus</a>
					</li>
					<li>
						<a href="#">Feuille de route</a>
					</li>
					<li>
						<a href="#">Statistiques</a>
					</li>
					<li>
						<a href="#"><i class="icon-plus"></i> Nouveau bug</a>
					</li>
				</ul>
			</menu>
		</div>
		</div>
		
		<h5>Explorateur</h5>
		<div class="content">
			<div class="explorer">
				<div class="cats">
						<h1>Explorateur</h1>
					<div class="contents">
						<ul>
							<li><a id="class_0" href="#"><i class="icon-folder"></i>Racine</a>
								<ul>
									<li class="sub"><a id="class_1" href="#"><i class="icon-folder"></i>Catégorie 1</a><span id="cat_1"></span></li>
									<li class="sub"><a class="parent" href="javascript:show_cat_contents(2, 0);"><span class="icon-minus-square-o" id="img2_2"></span><span class="icon-folder-open" id ="img_2"></span></a><a class="selected" id="class_2" href="#">Catégorie 2</a>
									<span id="cat_2">
										<ul>
											<li class="sub"><a href="#"><i class="icon-folder"></i>Catégorie 3</a></li>
											<li class="sub"><a href="#"><i class="icon-folder"></i>Catégorie 4</a></li>
										</ul>
									</span></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
				<div class="files">
						<h1>Arborescence</h1>
					<div class="contents" id="cat_contents">
						<ul>
							<li><a href="#"><i class="icon-folder"></i>Catégorie 3</a></li>
							<li><a href="javascript:open_cat(2); show_cat_contents(0, 0);"><i class="icon-folder"></i>Catégorie 4</a></li>
							<li><a href="wiki.php?title=hgfhgf"><i class="icon-file"></i>Fichier 1</a></li>
							<li><a href="wiki.php?title=qvsgfqgq"><i class="icon-file"></i>Fichier 2</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<br/></br>
		<h5>Options</h5>
		<div class="options">
			<h6>Trier selon</h6>
			<select>
				<option>Alphabétique</option>
				<option>Taille</option>
				<option>Date</option>
				<option>Popularité</option>
				<option>Note</option>
			</select>
			<select>
				<option>Croissant</option>
				<option>Décroissant</option>
			</select>
		</div>

	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h1>Citation, Code, Texte caché</h1>
	</header>
	<div class="content">
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
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h1>Pagination</h1>
	</header>
	<div class="content">
		<div class="center"># INCLUDE PAGINATION #</div>
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h1>Tableau</h1>
	</header>
	<div class="content">
		<table>
			<caption>
				Description du tableau
			</caption>
			<thead>
				<tr> 
					<th>
						<a href="#" class="icon-table-sort-up"></a>
						Nom
						<a href="#" class="icon-table-sort-down"></a>
					</th>
					<th>
						Description
					</th>
					<th>
						Auteur
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="5">
						# INCLUDE PAGINATION #
					</th>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td> 
						Test
					</td>
					<td>
						Description
					</td>
					<td>
						Auteur
					</td>
				</tr>
				<tr>
					<td> 
						Test
					</td>
					<td>
						Description
					</td>
					<td>
						Auteur
					</td>
				</tr>
				<tr>
					<td> 
						Test
					</td>
					<td>
						Description
					</td>
					<td>
						Auteur
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h1>Messages et commentaires</h1>
	</header>
	<div class="content"><br />
		<div id="com2" class="message" itemscope="itemscope" itemtype="http://schema.org/Comment">
			<div class="message-user_infos">
				<div class="message-pseudo">
						<a itemprop="author" href="{PATH_TO_ROOT}/user/?url=/profile/1" class="admin" >admin</a>
				</div>
				<div class="message-level">Administrateur</div>
				<img src="{PATH_TO_ROOT}/templates/{THEME}/images/no_avatar.png" class="message-avatar" />
			</div>
			<div class="message-content">
				<div class="message-date">
					<span class="actions">
						<a itemprop="url" href="#com2">#2</a>
							<a href="#comments_message" class="icon-edit"></a> 
							<a href="#comments_message" class="icon-delete" data-confirmation="delete-element"></a>
					</span>
					<span itemprop="datePublished" content="2013-09-05T15:37:01+00:00">05/09/2013 à 15h37</span>
				</div>
				<div class="message-message">
					<div itemprop="text" class="message-containt" class="content">Ceci est un commentaire</div>
				</div>
			</div>
		</div>
	</div>
	<footer></footer>
</section>

<section>
	<header>
		<h1>Titres spécifiques (BBCode)</h1>
	</header>
	<br />
	<div class="content">
		<h3 class="title1">Titre 1</h3>
		<h3 class="title2">Titre 2</h3>
		<h4 class="stitle1">Titre 3</h4>
		<h4 class="stitle2">Titre 4</h4>
	</div>
	<footer></footer>
</section>

<br />

<section>
	<header>
		<h1>Messages d'erreurs</h1>
	</header>
	<div class="content">
		# START messages # # INCLUDE messages.VIEW # # END messages #
	</div>
	<footer></footer>
</section>

<br /><hr><br />

<h2>Page</h2><br />

<section>
	<header>
		<h1>Titre de la page</h1>
	</header>
	<div class="content">
		Huic Arabia est conserta, ex alio latere Nabataeis contigua; opima varietate conmerciorum castrisque oppleta validis et castellis, quae ad repellendos gentium vicinarum excursus sollicitudo pervigil veterum per oportunos saltus erexit et cautos. haec quoque civitates habet inter oppida quaedam ingentes Bostram et Gerasam atque Philadelphiam murorum firmitate cautissimas. hanc provinciae inposito nomine rectoreque adtributo obtemperare legibus nostris Traianus conpulit imperator incolarum tumore saepe contunso cum glorioso marte Mediam urgeret et Parthos.<br/>
		<section>
			<header>
				<h2>Sous-Titre</h2>
			</header>
			<div class="content">
				Huic Arabia est conserta, ex alio latere Nabataeis contigua; opima varietate conmerciorum castrisque oppleta validis et castellis, quae ad repellendos gentium vicinarum excursus sollicitudo pervigil veterum per oportunos saltus erexit et cautos. haec quoque civitates habet inter oppida quaedam ingentes Bostram et Gerasam atque Philadelphiam murorum firmitate cautissimas. hanc provinciae inposito nomine rectoreque adtributo obtemperare legibus nostris Traianus conpulit imperator incolarum tumore saepe contunso cum glorioso marte Mediam urgeret et Parthos.<br/>
				<section>
					<header>
						<h3>Sous-Sous-Titre</h3>
					</header>
					<div class="content">
						Huic Arabia est conserta, ex alio latere Nabataeis contigua; opima varietate conmerciorum castrisque oppleta validis et castellis, quae ad repellendos gentium vicinarum excursus sollicitudo pervigil veterum per oportunos saltus erexit et cautos. haec quoque civitates habet inter oppida quaedam ingentes Bostram et Gerasam atque Philadelphiam murorum firmitate cautissimas. hanc provinciae inposito nomine rectoreque adtributo obtemperare legibus nostris Traianus conpulit imperator incolarum tumore saepe contunso cum glorioso marte Mediam urgeret et Parthos.
					</div>
					<footer></footer>
				</section>
			</div>
			<footer></footer>
		</section>
	</div>
	<footer></footer>
</section>

<h2>Blocs</h2><br />

<section>
	<header>
		<h1>Titre de la page</h1>
	</header>
	<div class="content">
		<article class="block">
			<header>
				<h1>Titre du bloc</h1>
			</header>
			<div class="contents">
				Inpares parte deviis scirent diffuso petivere idem sed deviis documentis idem stataria omnia latrones undique.				
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>

<h2>Blocs (3 sur une ligne)</h2><br />

<section>
	<header>
		<h1>Titre de la page</h1>
	</header>
	<div class="content">
		<article class="small_block">
			<header>
				<h1>Titre du bloc</h1>
			</header>
			<div class="contents">
				Inpares parte deviis scirent diffuso petivere idem sed deviis documentis idem stataria omnia latrones undique.				
			</div>
			<footer></footer>
		</article>
		<article class="small_block">
			<header>
				<h1>Titre du bloc</h1>
			</header>
			<div class="contents">
				Inpares parte deviis scirent diffuso petivere idem sed deviis documentis idem stataria omnia latrones undique.				
			</div>
			<footer></footer>
		</article>
		<article class="small_block">
			<header>
				<h1>Titre du bloc</h1>
			</header>
			<div class="contents">
				Inpares parte deviis scirent diffuso petivere idem sed deviis documentis idem stataria omnia latrones undique.				
			</div>
			<footer></footer>
		</article>
	</div>
	<footer></footer>
</section>