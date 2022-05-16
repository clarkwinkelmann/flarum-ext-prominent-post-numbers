<?php

namespace ClarkWinkelmann\ProminentPostNumbers;

use Flarum\Locale\Translator;
use Flarum\Post\CommentPost;
use Flarum\Settings\SettingsRepositoryInterface;
use s9e\TextFormatter\Configurator;

class ConfigureMentions
{
    public function __invoke(Configurator $configurator)
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

        $tag->setTemplate(str_replace(
            '<xsl:value-of select="@displayname"/>',
            '<xsl:value-of select="$MENTION_NUMBER_PREFIX"/><xsl:value-of select="@number"/><xsl:value-of select="$MENTION_NUMBER_SUFFIX"/> <xsl:value-of select="@displayname"/>',
            $originalTemplate));

        $tag->filterChain
            // This filter must run after the original for the javascript side to work as expected, so we place it in second place in the array
            ->insert(1, [static::class, 'addPostIdEvenWhenNoAuthor'])
            ->setJS('function(tag) { return flarum.extensions["clarkwinkelmann-prominent-post-numbers"].filterPostMentions(tag); }');
    }

    public static function addPostIdEvenWhenNoAuthor($tag)
    {
        $post = CommentPost::find($tag->getAttribute('id'));

        // Workaround for https://github.com/flarum/framework/issues/3427
        // Flarum currently doesn't save a mention if it has no authors
        if ($post && !$post->user) {
            $tag->setAttribute('discussionid', (int)$post->discussion_id);
            $tag->setAttribute('number', (int)$post->number);
            $tag->setAttribute('displayname', '');

            return true;
        }
    }
}
