<?php

namespace ClarkWinkelmann\ProminentPostNumbers;

use s9e\TextFormatter\Renderer;
use s9e\TextFormatter\Utils;

class FormatPostMentions
{
    public function __invoke(Renderer $renderer, mixed $context, string $xml): string
    {
        $post = $context;

        return Utils::replaceAttributes($xml, 'POSTMENTION', function ($attributes) use ($post) {
            // This code could be replaced by Flarum\Mentions\Formatter\LooksUpMentionedModels::mentionedModels once released in Flarum 2.x
            // Accessing the relation directly shouldn't incur any performance cost because the Mentions extension should already have loaded it
            $post = $post->mentionsPosts->find($attributes['id']);

            if (!$post) {
                return $attributes;
            }

            // Remove [deleted] text from mention labels
            if (!$post->user) {
                $attributes['displayname'] = '';
            }

            return $attributes;
        });
    }
}
