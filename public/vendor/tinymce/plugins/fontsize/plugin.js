(function () {
    const sizes = ['10px', '12px', '14px', '16px', '18px', '20px', '24px', '28px', '32px', '36px', '48px'];

    tinymce.PluginManager.add('fontsize', function (editor) {
        const getItems = () =>
            sizes.map((size) => ({
                type: 'menuitem',
                text: size,
                onAction: () => editor.execCommand('FontSize', false, size),
            }));

        editor.ui.registry.addMenuButton('fontsize', {
            tooltip: 'Font size',
            text: 'Size',
            fetch(callback) {
                callback(getItems());
            },
        });
    });
})();
