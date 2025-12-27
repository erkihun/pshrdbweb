(function () {
    const fonts = [
        { text: 'Default', value: 'Inter, system-ui, sans-serif' },
        { text: 'Abyssinica SIL', value: 'Abyssinica SIL, serif' },
        { text: 'Nyala', value: 'Nyala, serif' },
        { text: 'Arial', value: 'Arial, Helvetica, sans-serif' },
        { text: 'Times New Roman', value: 'Times New Roman, Times, serif' },
    ];

    tinymce.PluginManager.add('fontfamily', function (editor) {
        const getItems = () =>
            fonts.map((font) => ({
                type: 'menuitem',
                text: font.text,
                onAction: () => editor.execCommand('FontName', false, font.value),
            }));

        editor.ui.registry.addMenuButton('fontfamily', {
            tooltip: 'Font family',
            text: 'Font',
            fetch(callback) {
                callback(getItems());
            },
        });
    });
})();
