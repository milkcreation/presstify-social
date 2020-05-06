'use strict';

import jQuery from 'jquery';

jQuery('document').ready(function ($) {
    $(document).on('click', '[data-control="social.deeplink"]', function (e) {
      e.preventDefault();

      let deeplink = $(this).data('deeplink') || null,
          fallback = $(this).attr('href');

      if (deeplink) {
        setTimeout(function () {
          window.location = fallback;
        }, 25);
        window.location = deeplink;
      } else {
        window.open(fallback, '_blank');
      }
    });
});