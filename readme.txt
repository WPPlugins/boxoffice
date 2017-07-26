=== BoxOffice ===
Contributors: woodymood
Donate link: http://www.woodymood-dev.net/cms/wordpress/fr/faire-un-don/
Tags: popular, post, rank, boxoffice, view, statistic
Requires at least: 1.5
Tested up to: 2.7.1
Stable tag: 0.2

This plugin will display all your articles ordered by their count views.

== Description ==

This plugin requires <a href="http://wordpress.org/extend/plugins/popular-posts-plugin/" target="_blank">Popular Posts</a> because it uses its count system. Boxoffice doesn't store data itself.

Boxoffice will display a list of all your published articles, ordered by their count views, 
most viewed on top, with their title, date and rank (since 0.2). 

A template tag `boffice()` (since 0.2) may be used also for displaying anywhere.

See a demonstration <a href="http://www.woodymood-dev.net/cms/wordpress/fr/box-office/">here</a>.

French translation provided and .pot file.

== Installation ==

Remember to install before <a href="http://wordpress.org/extend/plugins/popular-posts-plugin/" target="_blank">Popular Posts</a> and Post-Plugin Library.

Then:

1. Upload the directory `boxoffice` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Create a new page or post called `BoxOffice`, or what ever you want
1. Note somewhere the new ID of the page or post you just created
1. Clic on the HTML tab to go in html mode
1. Copy the comment `<!-- box office replacement point -->` in the html code, in a standalone paragraph (important)
1. Go the option page
1. Enter the ID 
1. Change some options
1. Save the options
1. go and see your page / post


== Change Log ==


version 0.2

1. added some css classes to improve the look
1. boxoffice can be included in page or post
1. the ID of page or post must be told in the options page
1. new options page
1. rank displayed
1. possibility to turn on/off rank, visits, date
1. a template tag


== Frequently Asked Questions ==

= Does it show only posts ? =

Yes, no comment or page or something else...

= Can I include it in a post ? =

Yes, since version 0.2

= What happends if I don't have Popular Posts? =

Nothing wrong happends, the page stays blank without error.

= Does it works with others similar plugins ? =

Not yet, but if you let me know about a plugin and 
his get_post_views($post_id) function,  
I will add it in future versions of boxoffice

= Why is your english so bad ? =

Because I'm French!