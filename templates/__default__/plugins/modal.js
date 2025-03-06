class ModalBoost {
    constructor() {
        const triggers = document.querySelectorAll('.modal-button');

        triggers.forEach(trigger => {
            const dataId = this.getModalId(trigger);
            const targetPanel = document.getElementById(dataId);

            if (!targetPanel) return;

            // Move panel to body
            document.body.appendChild(targetPanel);

            // Trigger click event
            trigger.addEventListener('click', (e) => {
                e.preventDefault();

                // Remove active panel from siblings
                targetPanel.parentElement.querySelectorAll('.modal').forEach(panel => {
                    panel.classList.remove('active-modal');
                });

                // Add active panel to target
                targetPanel.classList.add('active-modal');

                // Close modal events
                this.setupModalCloseEvents(targetPanel);
            });
        });
    }

    getModalId (el)
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

    setupModalCloseEvents(targetPanel) {
        const closeButtons = targetPanel.querySelectorAll('.close-modal');

        closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                targetPanel.classList.remove('active-modal');

                return false;
            });
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal-button').forEach(() => {
        new ModalBoost();
    });
});