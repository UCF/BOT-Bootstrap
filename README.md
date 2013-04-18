# Board of Trustees Website - Bootstrap Rebuild

## Required Plugins
* Gravity Forms

## Deployment

This theme relies on Twitter's Bootstrap framework. The Boostrap project (http://github.com/twitter/bootstrap) is added as submodule in static/bootstrap. When deploying this theme, the submodule must be initialized and the correct branch of the Bootstrap repo should be checked out.

1. If this is a brand new clone, run `git submodule update --init static/bootstrap` from the theme's root directory.  Alternatively when cloning, you can instead run the command `git clone --recursive` to clone the theme and its submodule(s).
2. `cd static/bootstrap`, then checkout the `bot` branch of the Bootstrap repo; `git checkout bot`.  This branch's bootstrap resources should already be compiled.

### Upgrading from Thematic BOT Theme

When activating this theme for the first time, the following pieces of content should be updated to accomodate the new theme:
* Single Committee pages should be updated to remove all Blueprint-related markup; the page content should use no starting or ending `<div>` tags, and should only include header tags, committee-related shortcode, and horizontal rule tags.
* Make sure that no single Committee pages use the shortcode `[latest-minutes-and-agenda]`; this should be replaced with `[minutes-and-agendas]`.
* A new Gravity Forms contact form should be created to replace the old Contact page form. (Name, Email, Subject, Message)
* The Contact page content should be replaced with a Gravity Forms shortcode.
* Make sure that responsive styles are turned off (Theme Options > Styles).
* Reassign header/footer menus.

## Development

This theme relies on Twitter's Bootstrap framework. The Boostrap project (http://github.com/twitter/bootstrap) is added as submodule in static/bootstrap. To compile bootstrap:

1. If this is a brand new clone, run `git submodule update --init static/bootstrap`
2. Install the depenencies in the Developers section of the Boostrap README
3. Checkout the latest tag of Bootstrap
4. Run `make bootstrap` from the static/bootstrap directory