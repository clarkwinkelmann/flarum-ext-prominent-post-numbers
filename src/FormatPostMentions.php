<?php

namespace ClarkWinkelmann\ProminentPostNumbers;

use Psr\Http\Message\ServerRequestInterface;
use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

class FormatPostMentions
{
    public function __invoke(Renderer $renderer, $context, $xml, ServerRequestInterface $request = null): string
    {
        $post = $context;

        return Utils::replaceAttributes($xml, 'POSTMENTION', function ($attributes) use ($post) {
            $post = $post->mentionsPosts->find($attributes['id']);

            if (!$post) {
                return $attributes;
            }

            // Flarum doesn't save the number if the post has no user in ConfigureMentions::addPostId
            $attributes['number'] = $post->number;

            // Remove [deleted] text from mention labels
            if (!$post->user) {
                $attributes['displayname'] = '';
            }

            return $attributes;
        });
    }
}
