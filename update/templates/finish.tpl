<header></header>

<div class="content">
    <div id="finish" class="tabs-container">
        <nav class="tabs-nav">
            <ul class="tabs-items">
                <li><span class="tab-item --congrats">{@update.tab.congrats}</span></li>
                <li><span class="tab-item --thanks">{@update.tab.thanks}</span></li>
                <li><span class="tab-item --projects">{@update.tab.projects}</span></li>
                <li><span class="tab-item --credits">{@update.tab.credits}</span></li>
            </ul>
        </nav>
        <div class="tabs-wrapper">
            <div id="congrats" class="tab-content">
                {@H|update.tab.content.congrats}
            </div>
            <div id="thanks" class="tab-content">
                {@H|update.tab.content.thanks}
            </div>
            <div id="projects" class="tab-content">
                {@H|update.tab.content.projects}
            </div>
            <div id="credits" class="tab-content">
                {@H|update.tab.content.credits}
            </div>
        </div>
    </div>
</div>

<footer class="cell-flex cell-columns-2">
    <div class="content">{@H|update.donate}</div>
    <nav class="finish-menu">
        <ul>
            <li>
                <a href="{PATH_TO_ROOT}/" class="offload moderator">
                    <i class="fa fa-home fa-2x" aria-hidden="true"></i>
                    <span>{@update.site.index}</span>
                </a>
            </li>
            <li>
                <a href="{PATH_TO_ROOT}/admin" class="offload member">
                    <i class="fa fa-cogs fa-2x" aria-hidden="true"></i>
                    <span>{@update.admin.index}</span>
                </a>
            </li>
        </ul>
    </nav>
</footer>
