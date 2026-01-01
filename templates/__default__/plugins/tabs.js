/**
 * @copyright   &copy; 2005-2026 PHPBoost
 * @license     https://www.gnu.org/licenses/gpl-3.0.html GNU/GPL-3.0
 * @author      Sebastien LARTIGUE <babsolune@phpboost.com>
 * @version     PHPBoost 6.1 - last update: 2025 03 06
 * @since       PHPBoost 6.0 - 2025 03 06
*/

class TabsBoost
{
    constructor(el)
    {
        this.nav = document.getElementById(el);
        this.tabLinks = this.nav.querySelectorAll('.tab-item');
        this.hashTarget = window.location.hash.substring(1);

        this.init();

        // Add event listener to remove hash on outside click
        document.addEventListener('click', this.handleOutsideClick.bind(this));
    }

    init()
    {
        this.tabLinks.forEach(link => {
            link.addEventListener('click', this.handleTabClick.bind(this));
        });

        this.displayFirstTab();
    }

    /** On Page load
     * if url has hash then open the corresponding tab 
     * else if, check if ul children has '.first-tab' class then open the corresponding tab  
     * else open the tab corresponding to the first li of ul
    */
    displayFirstTab() {
        if (this.tabLinks.length > 0) {
            this.tabLinks.forEach(link => {link.classList.remove('current-tab')});
            const firstTabLinks = [];
            const nav = document.querySelectorAll('.tabs-nav');
            nav.forEach(ul => {
                const hashLink = ul.querySelector('.--' + this.hashTarget);
                const firstLink = ul.querySelector('.first-tab');
                if (hashLink) {
                    firstTabLinks.push(hashLink);
                } else if (firstLink) {
                    firstTabLinks.push(firstLink)
                } else {
                    firstTabLinks.push(ul.querySelector('.tab-item'))
                }
            });

            firstTabLinks.forEach(link => {
                link.classList.add('current-tab');
                const firstTabId = this.getTabId(link);
                this.switchTab(firstTabId);
            });
        }
    }

    handleTabClick(event)
    {
        let siblings = this.getSiblingLinks(event.target);
        siblings.forEach(link => {
            link.classList.remove('current-tab')
        });
        event.target.classList.add('current-tab');
        const tabId = this.getTabId(event.target);
        history.pushState('', '', '#' + tabId);
        this.switchTab(tabId);
    }

    handleOutsideClick(event) {
        if (!event.target.closest('.tabsboost')) {
            history.pushState('', '', ' ');
        }
    }

    switchTab(tabId) {
        let current = document.getElementById(tabId)
        let siblings = this.getSiblingContents(current);
        siblings.forEach(content => {
            content.classList.remove('current-tab')
        });
        current.classList.add('current-tab');
    }

    getSiblingContents(el)
    {
        let siblings = [];
        if(!el.parentNode) {
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
        if(!el.parentNode) {
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

    getTabId (el)
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

