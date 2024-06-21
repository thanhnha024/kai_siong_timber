"use strict";
$ = jQuery;

$(document).ready(function () {
  $(".cat-item a").each(function (index, element) {
    let currentUrl = window.location.href;
    let categoryUrl = $(element).attr("href");
    if (currentUrl == categoryUrl) {
      $(element).addClass("current");
      return false;
    }
  });
});
