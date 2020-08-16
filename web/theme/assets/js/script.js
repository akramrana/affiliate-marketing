;
(function () {
  "use strict";
  $(window).load(function () {
    $('#loader-wrapper').fadeOut();
  });
  $(document).ready(function () {
    if ($('.rating-input') > 0) {
    }
    if ($('.menuzord').length > 0) {
      $(".menuzord").menuzord({align: "left", effect: "slide"});
    }
    (function () {
      function getIEVersion() {
        var match = navigator.userAgent.match(/(?:MSIE |Trident\/.*; rv:)(\d+)/);
        return match ? parseInt(match[1], 10) : false;
      }
      if (getIEVersion()) {
        $('html').addClass('ie' + getIEVersion());
      }
    }());
    if ($('.scroll-to-target').length) {
      $(".scroll-to-target").on('click', function () {
        var target = $(this).attr('data-target');
        $('html, body').animate({scrollTop: $(target).offset().top}, 1000);
      });
    }
    if ($('.main-navigation').length > 0) {
      $('.main-navigation').sticky({topSpacing: 0});
    }
    $('#contactForm').on('submit', function (e) {
      e.preventDefault();
      var $action = $(this).prop('action');
      var $data = $(this).serialize();
      var $this = $(this);
      $this.prevAll('.alert').remove();
      $.post($action, $data, function (data) {
        if (data.response == 'error') {
          $this.before('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <i class="fa fa-times-circle"></i> ' + data.message + '</div>');
        }
        if (data.response == 'success') {
          $this.before('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><i class="fa fa-thumbs-o-up"></i> ' + data.message + '</div>');
          $this.find('input, textarea').val('');
        }
      }, "json");
    });
  });
})(jQuery);
function setActiveStyleSheet(cssName) {
  var scheme = $('link[href*="css/colors/color"]');
  scheme.attr('href', 'assets/css/colors/' + cssName + '.css');
}
$("#hide, #show").click(function (e) {
  e.preventDefault();
  if ($("#show").is(":visible")) {
    $("#show").animate({"margin-right": "-300px"}, 300, function () {
      $(this).hide();
    });
    $("#switch").animate({"margin-right": "0px"}, 300).show();
  } else {
    $("#switch").animate({"margin-right": "-300px"}, 300, function () {
      $(this).hide();
    });
    $("#show").show().animate({"margin-right": "0"}, 300);
  }
});
var site = {
  openRemoteUrl: function (url, coupon, did) {
    if (coupon == "") {
      coupon = "Redeem";
    } else {
      $("#myModal").modal("show");
      $("#modal-coupon-txt").html(coupon);
    }
    $("#d" + did).html(coupon);
    document.getElementById("link" + did).click();
  },
  openRemoteUrlSingle: function (url, coupon, did) {
    if (coupon == "") {
      coupon = "Redeem";
    } else {
      $("#myModal").modal("show");
      $("#modal-coupon-txt").html(coupon);
    }
    $("#s" + did).html(coupon);
    document.getElementById("slink" + did).click();
  }
}