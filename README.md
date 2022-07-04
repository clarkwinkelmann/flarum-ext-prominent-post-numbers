# Prominent Post Numbers

[![MIT license](https://img.shields.io/badge/license-MIT-blue.svg)](https://github.com/clarkwinkelmann/flarum-ext-prominent-post-numbers/blob/main/LICENSE.txt) [![Latest Stable Version](https://img.shields.io/packagist/v/clarkwinkelmann/flarum-ext-prominent-post-numbers.svg)](https://packagist.org/packages/clarkwinkelmann/flarum-ext-prominent-post-numbers) [![Total Downloads](https://img.shields.io/packagist/dt/clarkwinkelmann/flarum-ext-prominent-post-numbers.svg)](https://packagist.org/packages/clarkwinkelmann/flarum-ext-prominent-post-numbers) [![Donate](https://img.shields.io/badge/paypal-donate-yellow.svg)](https://www.paypal.me/clarkwinkelmann)

This extension makes post numbers visible on the post and in mentions.

It keeps the user display name but removes the `[deleted]` text if present.

You can configure the prefix to be put in front of the number in the settings (default: `#`).

If you want to translate the prefix or use a suffix, you can leave the prefix field blank and instead customize the `clarkwinkelmann-prominent-post-numbers.views.format` translation value that contains the `{number}` replacement pattern.

You need to clear Flarum's cache after changing the prefix or translation because the value is copied in the TextFormatter optimized parser.

## Installation

    composer require clarkwinkelmann/flarum-ext-prominent-post-numbers

## Support

This extension is under **minimal maintenance**.

It was developed for a client and released as open-source for the benefit of the community.
I might publish simple bugfixes or compatibility updates for free.

You can [contact me](https://clarkwinkelmann.com/flarum) to sponsor additional features or updates.

Support is offered on a "best effort" basis through the Flarum community thread.

**Sponsors**: [andyli0123](https://andyli.tw/)

## Links

- [GitHub](https://github.com/clarkwinkelmann/flarum-ext-prominent-post-numbers)
- [Packagist](https://packagist.org/packages/clarkwinkelmann/flarum-ext-prominent-post-numbers)
- [Discuss](https://discuss.flarum.org/d/31150)
