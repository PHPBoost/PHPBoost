/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2026 05 19
 * @since       PHPBoost 6.0 - 2025 03 06
*/

class TabsBoost
{
    constructor(el)
    {
        this.nav = document.getElementById(el);
        this.tabLinks = this.nav.querySelectorAll('.tab-item');
        this.containerId = el;
        this.hashTarget = window.location.hash.substring(1);

        this.init();
    }

    init() {
        this.tabLinks.forEach(link => {
            link.addEventListener('click', (event) => {
                // Si l'élément cliqué est un enfant d'un .tab-item, on remonte au parent
                let target = event.target;
                while (target && !target.classList.contains('tab-item')) {
                    target = target.parentNode;
                }
                if (target) {
                    this.handleTabClick.call(this, { target: target });
                }
            });
        });

        this.displayFirstTab();

        // Restore active tab on popstate (back/forward browser navigation)
        window.addEventListener('popstate', () => {
            this.hashTarget = window.location.hash.substring(1);
            this.displayFirstTab();
        });
    }

    /** On Page load
     * Priority order:
     * 1. URL hash → ouvre l'onglet correspondant
     * 2. sessionStorage → restaure le dernier onglet actif après refresh
     * 3. Classe '.first-tab' → onglet par défaut déclaré dans le HTML
     * 4. Premier élément .tab-item de la liste
    */
    displayFirstTab() {
        if (this.tabLinks.length > 0) {
            this.tabLinks.forEach(link => { link.classList.remove('current-tab') });

            const nav = document.querySelectorAll('.tabs-nav');
            const firstTabLinks = [];

            nav.forEach(ul => {
                const storedTab = sessionStorage.getItem('activeTab_' + this.containerId);
                const hashLink   = ul.querySelector('.--' + this.hashTarget);
                const storedLink = storedTab ? ul.querySelector('.--' + storedTab) : null;
                const firstLink  = ul.querySelector('.first-tab');

                if (hashLink) {
                    firstTabLinks.push(hashLink);
                } else if (storedLink) {
                    firstTabLinks.push(storedLink);
                } else if (firstLink) {
                    firstTabLinks.push(firstLink);
                } else {
                    const fallback = ul.querySelector('.tab-item');
                    if (fallback) firstTabLinks.push(fallback);
                }
            });

            firstTabLinks.forEach(link => {
                link.classList.add('current-tab');
                const firstTabId = this.getTabId(link);
                this.switchTab(firstTabId);

                // Persist active tab in sessionStorage
                sessionStorage.setItem('activeTab_' + this.containerId, firstTabId);

                // Restore hash in URL without pushing a new history entry
                if (this.hashTarget && firstTabId === this.hashTarget) {
                    history.replaceState('', '', '#' + firstTabId);
                }
            });
        }
    }

    handleTabClick(event)
    {
        let siblings = this.getSiblingLinks(event.target);
        siblings.forEach(link => {
            link.classList.remove('current-tab');
        });
        event.target.classList.add('current-tab');
        const tabId = this.getTabId(event.target);

        // Persist in sessionStorage AND update URL hash
        sessionStorage.setItem('activeTab_' + this.containerId, tabId);
        history.pushState('', '', '#' + tabId);

        this.switchTab(tabId);
    }

    switchTab(tabId) {
        let current = document.getElementById(tabId);
        if (!current) return;
        let siblings = this.getSiblingContents(current);
        siblings.forEach(content => {
            content.classList.remove('current-tab');
        });
        current.classList.add('current-tab');
    }

    getSiblingContents(el)
    {
        let siblings = [];
        if (!el.parentNode) {
            return siblings;
        }
        let sibling = el.parentNode.firstChild;
        while (sibling) {
            if (sibling.nodeType === 1 && sibling !== el) {
                siblings.push(sibling);
            }
            sibling = sibling.nextSibling;
        }
        return siblings;
    }

    getSiblingLinks(el)
    {
        let siblings = [];
        if (!el.parentNode) {
            return siblings;
        }
        let nav = el.closest('.tabs-nav');
        let ul = nav.querySelectorAll('.tabs-items');
        ul.forEach(ul => {
            let items = ul.querySelectorAll('.tab-item');
            items.forEach(item => {
                if (item.nodeType === 1 && item !== el) {
                    siblings.push(item);
                }
            });
        });
        return siblings;
    }

    getTabId(el)
    {
        const classList = el.classList;
        for (let className of classList) {
            if (className.startsWith('--')) {
                className = className.replace('--', '');
                return className;
            }
        }
        return null;
    }
}

// Instantiation
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tabs-container');
    tabs.forEach(tab => {
        new TabsBoost(tab.id);
    });
});