import app from 'flarum/forum/app';
import {extend} from 'flarum/common/extend';
import extractText from 'flarum/common/utils/extractText';
import Post from 'flarum/common/models/Post';
import Link from 'flarum/common/components/Link';
import CommentPost from 'flarum/forum/components/CommentPost';
import PostPreview from 'flarum/forum/components/PostPreview';

function prominentPostNumber(post: Post) {
    return m('span.ProminentPostNumber', (app.forum.attribute<string>('prominentPostNumberFormat') || '').replace('{number}', post.number() + ''));
}

app.initializers.add('prominent-post-numbers', () => {
    extend(CommentPost.prototype, 'headerItems', function (items) {
        // @ts-ignore
        const post = this.attrs.post as Post;

        // We want to add it just before PostMeta, unfortunately PostMeta doesn't have a priority
        // Luckily it's the first item without a priority so priority 1 should put us just before
        items.add('number', prominentPostNumber(post), 1);
    });

    extend(PostPreview.prototype, 'view', function (vdom) {
        vdom.children.forEach(preview => {
            if (!preview || !preview.attrs || !preview.attrs.className || preview.attrs.className.indexOf('PostPreview-content') === -1) {
                return;
            }

            if (!this.attrs.post.user()) {
                preview.children.forEach((child, index) => {
                    if (child && child.tag === 'span' && child.text === extractText(app.translator.trans('core.lib.username.deleted_text'))) {
                        preview.children.splice(index, 1);
                    }
                });
            }

            preview.children.splice(1, 0, prominentPostNumber(this.attrs.post), {
                tag: '#', // The space must be inserted in vdom way to be valid
                children: ' ',
            });
        });
    });

    // There's no easy way to override Mention's template in addMentionedByList / CommentPost.prototype.footerItems
    // To avoid re-implementing the full method, we'll cheat by hooking into Link directly
    extend(Link.prototype, 'view', function (vdom) {
        // Links added by Mentions have a data-number attribute, skip if missing
        if (!('data-number' in this.attrs)) {
            return;
        }

        // It's simpler to retrieve the number from the data-attribute
        // Trying to read the href would be more complicated because first post link would not contain the number
        const number = parseInt(this.attrs['data-number']);

        const routePrefix = app.route('discussion', {
            id: '',
        });

        // Skip links that aren't to discussions/posts
        if (!this.attrs.href || this.attrs.href.indexOf(routePrefix) !== 0) {
            return;
        }

        const discussionSlug = this.attrs.href.substring(routePrefix.length).split('/')[0];

        const post = app.store.all<Post>('posts').find(post => {
            if (post.number() !== number) {
                return false;
            }

            const discussion = post.discussion();

            // Can't use ?. operator to call slug() below because the relation can also be false
            if (!discussion) {
                return false;
            }

            return discussion.slug() === discussionSlug;
        });

        // If the post can't be found, ignore
        if (!post) {
            return;
        }

        // Add number in front
        vdom.children.unshift(prominentPostNumber(post), ' ');

        // If the post has an author, don't change anything else
        if (post.user()) {
            return;
        }

        // Remove [deleted] text
        vdom.children.forEach((child, index) => {
            if (child && child.tag === 'span' && child.text === extractText(app.translator.trans('core.lib.username.deleted_text'))) {
                vdom.children.splice(index, 1);
            }
        });
    });
});

// We don't need to validate or invalidate the tag here because Mention's original filter still runs
// We'll just change the display name
export function filterPostMentions(tag: any) {
    const post = app.store.getById('posts', tag.getAttribute('id'));

    if (post) {
        const user = post.user();

        // Remove [deleted] text from mention
        if (!user) {
            tag.setAttribute('displayname', '');
        }
    }
}
