import app from 'flarum/admin/app';
import Extend from 'flarum/common/extenders';

export default [
    new Extend.Admin()
        .setting(() => ({
            setting: 'prominentPostNumberFloating',
            type: 'switch',
            label: app.translator.trans('clarkwinkelmann-prominent-post-numbers.admin.settings.floating'),
        }))
        .setting(() => ({
            setting: 'prominentPostNumberPrefix',
            type: 'text',
            label: app.translator.trans('clarkwinkelmann-prominent-post-numbers.admin.settings.prefix'),
            placeholder: app.translator.trans('clarkwinkelmann-prominent-post-numbers.admin.settings.prefixPlaceholder'),
        })),
];
