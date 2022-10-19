<section id="module-sandbox-lang">
	<header class="section-header">
		<h1>{@sandbox.module.title}</h1>
	</header>
	# INCLUDE SANDBOX_SUBMENU #

	<p>
		<input type="text" id="filtersearch" value="" placeholder="{@sandbox.lang.search}"  />
	</p>

	<div class="sub-section">
		<div class="content-container">
			<article class="all-files cell">
				<header class="cell-header root-folder trigger-folder bgc success">
					<h2 class="cell-name folder-name">{@sandbox.lang.kernel}</h2>
					<a class="folder-trigger" href="#" aria-label="voir les variables"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
				</header>
				<div class="cell-list">
					<ul class="lang-files">
						# START lang_file #
							<li class="parent">
								<div class="flex-between bgc question lang-file-name">
									<span>{lang_file.LANG_FILE_NAME}</span>
									<a class="file-trigger" href="#" aria-label="voir les variables"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
								</div>
								<ul>
									# START lang_file.items #
										<li class="search-text lang-file">
											<span class="text-strong">{lang_file.items.VAR}</span>
											<span class="search-target">{lang_file.items.DESC}</span>
										</li>
									# END lang_file.items #
								</ul>
							</li>
						# END lang_file #
					</ul>
				</div>
			</article>
			<article class="all-files cell">
				<header class="cell-header bgc success">
					<h2 class="cell-name">{@sandbox.lang.modules}</h2>
					<a href="#" class="folder-trigger" aria-label="voir les variables"><i class="fa fa-chevron-right fa-chevron-down" aria-hidden="true"></i></a>
				</header>
				<div class="cell-list">
					<ul>
						# START module #
							<li>
								<div class="flex-between bgc moderator module-name">
									<span>{module.MODULE_NAME}</span>
									<a class="folder-module-trigger" href="#" aria-label="voir les variables"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
								</div>
								<ul>
									# START module.module_file #
										<li class="module-{module.MODULE_ID} parent">
											<div class="flex-between bgc question module-file-name">
												<span>{module.module_file.MODULE_FILE_NAME}</span>
												<a class="file-trigger" href="#" aria-label="voir les variables"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>
											</div>
											<ul>
												# START module.module_file.items #
													<li class="search-text lang-file">
														<span class="text-strong">{module.module_file.items.VAR}</span>
														<span class="search-target">{module.module_file.items.DESC}</span>
													</li>
												# END module.module_file.items #
											</ul>
										</li>
									# END module.module_file #
								</ul>
							</li>
						# END module #
					</ul>
				</div>
			</article>
		</div>
	</div>
	<footer></footer>
</section>
<script>
	jQuery(document).ready(function(){
		// Hide file content on load
		jQuery('.search-text').hide();
		// Change icon
		jQuery('.folder-trigger, .folder-module-trigger, .file-trigger').each(function() {
			jQuery(this).on('click', function(e) {
				e.preventDefault();
				iconDirection = jQuery(this).find('i');
				rightClass = 'fa-chevron-right';
				downClass = 'fa-chevron-down';
				if(iconDirection.hasClass(rightClass))
					iconDirection.removeClass(rightClass).addClass(downClass);
				else
					iconDirection.removeClass(downClass).addClass(rightClass);
			})
		})
		// All lang/module files
		jQuery('.folder-trigger').each(function() {
			jQuery(this).on('click', function(e) {
				e.preventDefault();
				jQuery(this).closest('.all-files').find('.search-text').toggle();
			})
		})
		// All module files
		jQuery('.folder-module-trigger').each(function(){
			jQuery(this).on('click', function(e) {
				e.preventDefault();
				jQuery(this).closest('li').find('ul .search-text').toggle();
			})
		})
		// Lang/module file
		jQuery('.file-trigger').each(function(){
			jQuery(this).on('click', function(e) {
				e.preventDefault();
				jQuery(this).closest('li').find('.search-text').toggle();
			})
		})

		// Reveal searched text
		jQuery('input#filtersearch').bind('keyup change', function () {
			if (jQuery(this).val().trim().length !== 0) {
				jQuery('.search-text').show().hide().each(function () {
					if (jQuery(this).children('.search-target').is(':icontains(' + jQuery('input#filtersearch').val() + ')'))
					{
						jQuery(this).show();
					}
				});
			}
			else {
				jQuery('.search-text').show().hide().each(function () {
					jQuery(this).show();
				});
			}
		});

		jQuery.expr[':'].icontains = function (obj, index, meta, stack) {
			return (obj.textContent || obj.innerText || jQuery(obj).text() || '').toLowerCase().indexOf(meta[3].toLowerCase()) >= 0;
		};
	});
</script>
