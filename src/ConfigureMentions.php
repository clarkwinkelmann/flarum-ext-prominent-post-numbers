<?php

namespace ClarkWinkelmann\ProminentPostNumbers;

use Flarum\Locale\Translator;
use Flarum\Settings\SettingsRepositoryInterface;
use s9e\TextFormatter\Configurator;

class ConfigureMentions
{
    public function __invoke(Configurator $configurator): void
    {
        if (!$configurator->tags->exists('POSTMENTION')) {
            return;
        }

        $prefix = resolve(SettingsRepositoryInterface::class)->get('prominentPostNumberPrefix');
        $format = $prefix ? ($prefix . '{number}') : resolve(Translator::class)->trans('clarkwinkelmann-prominent-post-numbers.views.format');
        $formatParts = explode('{number}', $format);

        $configurator->rendering->parameters['MENTION_NUMBER_PREFIX'] = $formatParts[0];
        $configurator->rendering->parameters['MENTION_NUMBER_SUFFIX'] = $formatParts[1] ?? '';

        /**
         * @var Configurator\Items\Tag $tag
         */
        $tag = $configurator->tags->get('POSTMENTION');

        $originalTemplate = (string)$tag->getTemplate();

        // Add rendering points to display our new prefix/suffix parameters, and the number itself
        // The prefix/suffix is set above using renderer parameters
        // The number is already part of the attributes set in TextFormatter by the Mentions extension
        // Only the displayname will need to be customized during rendering to remove the [deleted] placeholder
        $tag->setTemplate(str_replace(
            '<xsl:value-of select="@displayname"/>',
            '<xsl:value-of select="$MENTION_NUMBER_PREFIX"/><xsl:value-of select="@number"/><xsl:value-of select="$MENTION_NUMBER_SUFFIX"/> <xsl:value-of select="@displayname"/>',
            $originalTemplate));

        $tag->filterChain
            // This filter must run after the original for the javascript side to work as expected, so we place it in second place in the array
            // Because there's nothing to do on the PHP side, we use a dummy callback
            ->insert(1, [static::class, 'dummyFilter'])
            ->setJS('function(tag) { return flarum.extensions["clarkwinkelmann-prominent-post-numbers"].filterPostMentions(tag); }');
    }

    public static function dummyFilter(): bool
    {
        return true;
    }
}
