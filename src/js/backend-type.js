/* jshint esversion: 6 */
/* jshint browser: true, devel: true */
/* global jQuery, ajaxurl, wp, console */

(function () {

    // marker icon selector
    "use strict";
    jQuery('.marker_icons input[type=radio]').on('change', function () {
        jQuery('.marker_icons label').removeClass('checked');
        jQuery(this).parent('label').addClass('checked');
    });

})();
