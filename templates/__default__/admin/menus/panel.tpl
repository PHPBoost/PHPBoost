<nav id="admin-quick-menu">
    <a href="#" class="js-menu-button" onclick="open_submenu('admin-quick-menu');return false;">
        <i class="fa fa-bars" aria-hidden="true"></i> {@menu.menus.management}
    </a>
	<ul>
        <li>
            <a href="menus.php" class="quick-link">{@menu.menus.management}</a>
        </li>
        <li>
            <a href="links.php" class="quick-link">{@menu.links.menu}</a>
        </li>
        <li>
            <a href="content.php" class="quick-link">{@menu.content.menu}</a>
        </li>
        <li>
            <a href="feed.php" class="quick-link">{@menu.feed.menu}</a>
        </li>
    </ul>
</nav>
