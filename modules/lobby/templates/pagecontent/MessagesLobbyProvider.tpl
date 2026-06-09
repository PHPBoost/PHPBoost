<div class="sub-section" style="order: {MODULE_POSITION};">
	<div class="content-container">
		<article id="{MODULE_NAME}-panel">
			<header class="module-header flex-between">
				<h2>{L_MODULE_TITLE}</h2>
				# IF C_MODULE_LINK #
					<div class="controls align-right">
						<a class="offload" href="{PATH_TO_ROOT}/{MODULE_NAME}" aria-label="{@lobby.see.module}"><i class="fa fa-share-square" aria-hidden="true"></i></a>
					</div>
				# ENDIF #
			</header>
			# IF C_NO_ITEM #
				<div class="content">
					<div class="message-helper bgc notice">
						{@common.no.item.now}
					</div>
				</div>
			# ELSE #
				<div class="content">
					# START items #
						<div class="message-container message-small" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
							<div class="message-header-container">
								# IF C_AVATAR_IMG #<img class="message-user-avatar" src="{items.U_AVATAR_IMG}" alt="{items.AUTHOR_DISPLAY_NAME}" /># ENDIF #
								<div class="message-header-infos">
									<div class="message-user-container">
										<h4>
											# IF items.C_AUTHOR_EXISTS #
												<a itemprop="author" class="{items.AUTHOR_LEVEL_CLASS} offload" href="{items.U_AUTHOR_PROFILE}"# IF items.C_AUTHOR_GROUP_COLOR # style="{items.AUTHOR_GROUP_COLOR}"# ENDIF #>{items.AUTHOR_DISPLAY_NAME}</a>
											# ELSE #
												{items.AUTHOR_DISPLAY_NAME}
											# ENDIF #
										</h4>
										# IF C_PARENT #
											<div class="controls message-user-infos-preview" aria-label="# IF C_TOPIC #{@lobby.posted.in.topic}# ELSE #{@lobby.posted.in.module}# ENDIF #">
												<a class="offload" href="{items.U_TOPIC}"><i class="fa fa-fw # IF C_TOPIC #fa-file# ELSE #fa-cube# ENDIF #" aria-hidden="true"></i> {items.TOPIC}</a>
											</div>
										# ENDIF #
									</div>
									<div class="message-infos">
										<time datetime="{items.DATE_ISO8601}" itemprop="datePublished">{items.DATE}</time>
										<div class="message-action">
											<a href="{items.U_ITEM}" class="pinned bgc-full link-color offload"><i class="fa fa-share"></i> {@common.read.more}</a>
										</div>
									</div>
								</div>
							</div>
							<div class="message-content flex-between">
								{items.CONTENT} # IF items.C_READ_MORE #... # ENDIF #
							</div>
						</div>
					# END items #
				</div>
			# ENDIF #
		</article>
	</div>
</div>
