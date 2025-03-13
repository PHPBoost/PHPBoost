class ModalBoost {
    constructor(el) {
        const dataId = this.getModalId(el);
        const targetPanel = document.getElementById(dataId);

        if (!targetPanel) return;

        // Move panel to body
        document.body.appendChild(targetPanel);

        // Trigger click event
        el.addEventListener('click', (e) => {
            e.preventDefault();

            // Remove active panel from siblings
            targetPanel.parentElement.querySelectorAll('.modal').forEach(panel => {
                panel.classList.remove('active-modal');
            });

            // Add active panel to target
            targetPanel.classList.add('active-modal');

            // Set URL hash
            window.history.pushState('', '', '#' + dataId);

            // Close modal events
            this.setupModalCloseEvents(targetPanel);
        });

        // Check URL hash on page load
        this.checkUrlHash();

        // Listen for hash changes
        window.addEventListener('hashchange', () => {
            this.checkUrlHash();
        });
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
