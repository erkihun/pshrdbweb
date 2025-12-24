function initializeTopbarDropdowns() {
    const triggers = Array.from(document.querySelectorAll('[data-dropdown-trigger]'));
    const panels = Array.from(document.querySelectorAll('[data-dropdown-panel]'));

    if (!triggers.length || !panels.length) {
        return;
    }

    const triggerMap = new Map();
    const panelMap = new Map();

    triggers.forEach(trigger => {
        const key = trigger.dataset.dropdownTrigger;
        triggerMap.set(key, trigger);
        const panel = document.querySelector(`[data-dropdown-panel="${key}"]`);
        if (panel) {
            panelMap.set(key, panel);
            panel.setAttribute('aria-hidden', 'true');
            panel.classList.add('hidden');
        }
    });

    let activeDropdown = null;

    const closeAll = () => {
        activeDropdown = null;
        triggerMap.forEach(trigger => trigger.setAttribute('aria-expanded', 'false'));
        panelMap.forEach(panel => {
            panel.setAttribute('aria-hidden', 'true');
            panel.classList.add('hidden');
        });
    };

    const openDropdown = (key) => {
        closeAll();
        const trigger = triggerMap.get(key);
        const panel = panelMap.get(key);
        if (!trigger || !panel) {
            return;
        }
        trigger.setAttribute('aria-expanded', 'true');
        panel.setAttribute('aria-hidden', 'false');
        panel.classList.remove('hidden');
        activeDropdown = key;
    };

    triggers.forEach(trigger => {
        const key = trigger.dataset.dropdownTrigger;
        trigger.addEventListener('click', (event) => {
            event.stopPropagation();
            if (activeDropdown === key) {
                closeAll();
            } else {
                openDropdown(key);
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-dropdown-trigger]') && !event.target.closest('[data-dropdown-panel]')) {
            closeAll();
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAll();
        }
    });

    closeAll();
}

document.addEventListener('DOMContentLoaded', initializeTopbarDropdowns);
