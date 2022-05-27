<?php

namespace ClarkWinkelmann\ProminentPostNumbers;

use Flarum\Extend;
use Flarum\Locale\Translator;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js'),

    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/less/forum.less'),

    new Extend\Locales(__DIR__ . '/locale'),

    (new Extend\Settings())
        ->registerLessConfigVar('config-prominent-post-numbers-floating', 'prominentPostNumberFloating', function ($value) {
            return $value ? 'true' : 'false';
        })
        ->default('prominentPostNumberPrefix', '#')
        ->serializeToForum('prominentPostNumberFormat', 'prominentPostNumberPrefix', function ($prefix) {
            if (!$prefix) {
                return resolve(Translator::class)->trans('clarkwinkelmann-prominent-post-numbers.views.format');
            }

            return $prefix . '{number}';
        }),

    (new Extend\Formatter())
        ->configure(ConfigureMentions::class)
        ->render(FormatPostMentions::class),
];
