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
											<span>{lang_file.items.DESC}</span>
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
														<span>{module.module_file.items.DESC}</span>
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
	                if (jQuery(this).is(':icontains(' + jQuery('input#filtersearch').val() + ')'))
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

		jQuery.fn.highlightFilter = function (a) {
			a = jQuery.extend(
				{
					highlightBgColor: "var(--main-tone)",
					highlightTxtColor: "var(--txt-alt)",
					caseSensitive: false,
					targetClass: "search-text",
				},
				a
			);
			jQuery.expr[":"].icontains = function (a, b, c) {
				return jQuery(a).text().toUpperCase().indexOf(c[3].toUpperCase()) >= 0;
			};
			var b = /(<em.+?>)(.+?)(<\/em>)/g;
			var c = "g";
			if (!a.caseSensitive) {
				b = /(<em.+?>)(.+?)(<\/em>)/gi;
				c = "gi";
			}
			return this.each(function () {
				jQuery(this).keyup(function (d) {
					if ((d.which && d.which == 13) || (d.keyCode && d.keyCode == 13)) {
						return false;
					} else {
						var e = jQuery(this).val();
						if (e.length > 0) {
							var f = "icontains";
							if (a.caseSensitive) {
								f = "contains";
							}
							jQuery.each(jQuery("." + a.targetClass), function (a, c) {
								jQuery(c).html(jQuery(c).html().replace(new RegExp(b), "$2"));
							});
							jQuery.each(jQuery("." + a.targetClass + ":" + f + "(" + e + ")"), function (b, d) {
								var f = jQuery(d).html();
								jQuery(d).html(
									f.replace(new RegExp(e, c), function (b) {
										return ["<em style='background-color:" + a.highlightBgColor + ";color:" + a.highlightTxtColor + "'>", b, "</em>"].join("");
									})
								);
							});
						} else {
							jQuery.each(jQuery("." + a.targetClass), function (a, c) {
								var d = jQuery(c).html().replace(new RegExp(b), "$2");
								jQuery(c).html(d);
							});
						}
					}
				});
			});
		};

	   jQuery("#filtersearch").highlightFilter();
	});
</script>
