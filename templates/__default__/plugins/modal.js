class ModalBoost {
    constructor(el) {
        this.button = el;
        const dataId = this.getModalId(this.button);
        this.targetPanel = document.getElementById(dataId);

        if (!this.targetPanel) {
            return;
        }
        document.body.appendChild(this.targetPanel);

        if (this.hasClass('modal-pop') && window.innerWidth > 768) {
                this.parent = document.createElement('div');
                this.parent.classList.add('modal-enclosure');
                this.button.parentNode.insertBefore(this.parent, this.button);
                this.parent.appendChild(this.button);
                this.parent.appendChild(this.targetPanel);
                this.targetPanel.classList.add('modal-pop', this.getClassName('modal-pop'));
        } else {
            this.targetPanel.classList.add('modal-full');
        }

        // Trigger click event
        this.button.addEventListener('click', (e) => {
            e.preventDefault();

            // Remove active panel from siblings
            document.querySelectorAll('.modal').forEach(panel => {
                panel.classList.remove('active-modal');
            });

            // Add active panel to target
            this.targetPanel.classList.add('active-modal');

            // Set URL hash
            window.history.pushState('', '', '#' + dataId);

            // Close modal events
            this.setupModalCloseEvents(this.targetPanel);
        });

        // Check URL hash on page load
        this.checkUrlHash();

        // Listen for hash changes
        window.addEventListener('hashchange', () => {
            this.checkUrlHash();
        });
    }

    /** Checks if the slider has a specific class */
    hasClass(e) {
        const classList = this.button.classList;
        for (let className of classList) {
            if (className === e || className.startsWith(e)) {
                return true;
            }
        }
        return false;
    }

    /** Gets the complete classname of a specific class */
    getClassName(e)
    {
        const classList = this.button.classList;
        for (let className of classList) {
            if (className === e || className.startsWith(e)) {
                return className;
            }
        }
        return null;
    }

    getModalId(el) {
        const classList = el.classList;
        for (let className of classList) {
            if (className.startsWith('--')) {
                className = className.replace('--', '');
                return className;
            }
        }
        return null;
    }

    setupModalCloseEvents(targetPanel) {
        const closeButtons = targetPanel.querySelectorAll('.close-modal');

        closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                targetPanel.classList.remove('active-modal');

                // Remove URL hash
                window.history.pushState('', '', ' ');

                return false;
            });
        });
    }

    checkUrlHash() {
        const hash = window.location.hash.substring(1);
        if (hash) {
            const targetPanel = document.getElementById(hash);
            if (targetPanel) {
                // Remove active panel from siblings
                targetPanel.parentElement.querySelectorAll('.modal').forEach(panel => {
                    panel.classList.remove('active-modal');
                });

                // Add active panel to target
                targetPanel.classList.add('active-modal');

                // Close modal events
                this.setupModalCloseEvents(targetPanel);
            }
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal-button');
    modals.forEach(modal => {
        new ModalBoost(modal);
    });
});
