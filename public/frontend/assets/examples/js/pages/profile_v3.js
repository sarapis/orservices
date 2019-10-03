/*!
 * remark (http://getbootstrapadmin.com/remark)
 * Copyright 2017 amazingsurge
 * Licensed under the Themeforest Standard Licenses
 */
(function(document, window, $) {
  'use strict';

  var Site = window.Site;

  $(document).ready(function($) {
    Site.run();
  });

  // Example Popup Gallery
  // ---------------------
  var galleryNum = $('.imgs-gallery').length;
  for (var i = 0; i < galleryNum; i++) {

    $($('.imgs-gallery')[i]).magnificPopup({
      delegate: 'a',
      type: 'image',
      tLoading: 'Loading image #%curr%...',
      mainClass: 'mfp-img-mobile',
      gallery: {
        enabled: true,
        navigateByImgClick: true,
        preload: [0, 1] // Will preload 0 - before current, and 1 after the current image
      },
      image: {
        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
        titleSrc: function(item) {
          return item.el.attr('title') + '<small>by amazingSurge</small>';
        }
      }
    });
  }

  //bind input focus
  $('.wall-comment-reply .form-control').on('focus', function(event) {
    var $this = $(this);
    var $reply_operation = $this.closest('form').find('.reply-operation'),
      $reply_cancel = $this.closest('form').find('.reply-operation .reply-cancel');

    if (!$reply_operation.hasClass('active')) {
      $reply_operation.addClass('active');
    }

    $reply_cancel.on('click', function() {
      $reply_operation.removeClass('active');
    });

    event.stopPropagation();
  });

})(document, window, jQuery);
