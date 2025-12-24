function initializeAdminSidebar() {
    const storageKey = 'admin_sidebar_open_group';
    const toggles = Array.from(document.querySelectorAll('[data-acc-trigger]'));
    const panels = Array.from(document.querySelectorAll('[data-acc-panel]'));

    if (!toggles.length || !panels.length) {
        return;
    }

    const defaultToggle = toggles.find(toggle => toggle.dataset.groupActive === 'true') ?? toggles[0];
    const storedGroup = (function () {
        try {
            return window.localStorage.getItem(storageKey);
        } catch (error) {
            return null;
        }
    })();

    const initialGroup = storedGroup && toggles.some(toggle => toggle.dataset.group === storedGroup)
        ? storedGroup
        : defaultToggle?.dataset.group ?? toggles[0]?.dataset.group;

    function setActiveGroup(groupId, persist = true) {
        if (!groupId) {
            return;
        }

        toggles.forEach(toggle => {
            const isActive = toggle.dataset.group === groupId;
            toggle.setAttribute('aria-expanded', isActive ? 'true' : 'false');
        toggle.dataset.isActive = isActive ? 'true' : 'false';

            const chevron = toggle.querySelector('[data-acc-chevron]');
            if (chevron) {
                chevron.classList.toggle('rotate-180', isActive);
            }
        });

        panels.forEach(panel => {
            const matches = panel.dataset.groupPanel === groupId;
            panel.classList.toggle('hidden', !matches);
        });

        if (persist && typeof window !== 'undefined' && window.localStorage) {
            try {
                window.localStorage.setItem(storageKey, groupId);
            } catch (error) {
                // ignore storage errors
            }
        }
    }

    toggles.forEach(toggle => {
        toggle.addEventListener('click', () => setActiveGroup(toggle.dataset.group));
        toggle.addEventListener('keydown', event => {
            if (event.key === ' ' || event.key === 'Enter') {
                event.preventDefault();
                toggle.click();
            }
        });
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') {
            setActiveGroup(defaultToggle?.dataset.group ?? toggles[0]?.dataset.group);
        }
    });

    setActiveGroup(initialGroup);
}

document.addEventListener('DOMContentLoaded', initializeAdminSidebar);
