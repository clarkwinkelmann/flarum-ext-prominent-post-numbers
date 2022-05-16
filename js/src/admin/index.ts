import app from 'flarum/admin/app';

app.initializers.add('prominent-post-numbers', () => {
    app.extensionData
        .for('clarkwinkelmann-prominent-post-numbers')
        .registerSetting({
            setting: 'prominentPostNumberPrefix',
            type: 'text',
            label: app.translator.trans('clarkwinkelmann-prominent-post-numbers.admin.settings.prefix'),
            placeholder: app.translator.trans('clarkwinkelmann-prominent-post-numbers.admin.settings.prefixPlaceholder'),
        });
});
