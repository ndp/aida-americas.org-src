// $Id: nice_menus.js,v 1.17 2009/03/24 16:22:28 add1sun Exp $

// This uses Superfish 1.4.8
// (http://users.tpg.com.au/j_birch/plugins/superfish)

// Add Superfish to all Nice menus with some basic options.
$(document).ready(function() {
  $('ul.nice-menu').superfish({
    // Add the legacy hover class added for IE.
    hoverClass: 'ie-over',
    // Disable generation of arrow mark-up.
    autoArrows: false,
    // Disable drop shadows.
    dropShadows: false,
    // Mouse delay.
    delay: Drupal.settings.nice_menus_options.delay,
    // Animation speed.
    speed: Drupal.settings.nice_menus_options.speed
  });
  // Add in Brandon Aaron’s bgIframe plugin for IE select issues.
  // http://plugins.jquery.com/node/46/release
  $('ul.nice-menu').superfish().find('ul').bgIframe({opacity:false}); 
});
