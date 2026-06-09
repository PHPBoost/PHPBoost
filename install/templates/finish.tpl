<div class="content">
    <div id="finish" class="tabs-container">
        <nav class="tabs-nav">
            <ul class="tabs-items">
                <li><span class="tab-item --congrats">{@install.tab.congrats}</span></li>
                <li><span class="tab-item --thanks">{@install.tab.thanks}</span></li>
                <li><span class="tab-item --projects">{@install.tab.projects}</span></li>
                <li><span class="tab-item --credits">{@install.tab.credits}</span></li>
            </ul>
        </nav>
        <div class="tabs-wrapper">
            <div id="congrats" class="tab-content">
                {@H|install.tab.content.congrats}
            </div>
            <div id="thanks" class="tab-content">
                {@H|install.tab.content.thanks}
            </div>
            <div id="projects" class="tab-content">
                {@H|install.tab.content.project}
            </div>
            <div id="credits" class="tab-content">
                {@H|install.tab.content.credits}
            </div>
        </div>
    </div>
</div>

<footer class="cell-flex cell-columns-2">
    <div class="content">{@H|install.donate}</div>
    <nav class="finish-menu">
        <ul>
            <li>
                <a href="{PATH_TO_ROOT}/" class="offload moderator" onclick="location.reload(true);return true;">
                    <i class="fa fa-home fa-2x" aria-hidden="true"></i>
                    <span>{@install.site.index}</span>
                </a>
            </li>
            <li>
                <a href="{PATH_TO_ROOT}/admin" class="offload member" onclick="location.reload(true);return true;">
                    <i class="fa fa-cogs fa-2x" aria-hidden="true"></i>
                    <span>{@install.admin.index}</span>
                </a>
            </li>
        </ul>
    </nav>
</footer>
