$(window).on("load", function () {
  sal({
    once: true,
  });

  // $("body").addClass("overflow");
  // $(".modal-overlay, .hs-modal-cont").fadeIn(200);
  // $(".hs-modal").addClass("active");
});
$(document).ready(function () {
  var swiper = new Swiper(".reviews-slider .reviewSwiper", {
    spaceBetween: 16,
    pagination: {
      el: ".reviews-slider .swiper-pagination",
      type: "fraction",
    },
    navigation: {
      nextEl: ".reviews-slider .swiper-btn-next",
      prevEl: ".reviews-slider .swiper-btn-prev",
    },
    loop: true,
    breakpoints: {
      0: {
        slidesPerView: 1,
      },
      768: {
        slidesPerView: 2,
      },
      992: {
        slidesPerView: 3,
      },
      1200: {
        slidesPerView: 4,
      },
    },
  });

  $("a[href^='#'].animated-scroll").on("click", function (e) {
    e.preventDefault();
    var hash = this.hash;
    $("html, body").animate(
      {
        scrollTop: $(this.hash).offset().top,
      },
      500,
      function () {
        window.location.hash = hash;
      }
    );
    if ($(window).width() <= 767) {
      $(".menu-icon").removeClass("active");
      $(".navbar").slideUp(300);
      $("body").removeClass("overflow");
    }
  });

  if ($(window).width() <= 767) {
    $(".footer-head").click(function () {
      $(".footer-head").not(this).removeClass("active");
      $(this).toggleClass("active");
      if ($(this).siblings().css("display") == "none") {
        $(this).siblings().slideDown(500);
      } else {
        $(this).siblings().slideUp(500);
      }
      $(".footer-head").not(this).siblings().slideUp(500);
    });
  }

  $(".modal-overlay, .modal-btn").on("click", function (e) {
    $("body").removeClass("overflow");
    $(".modal-overlay, .hs-modal-cont").fadeOut(400);
    $(".hs-modal").removeClass("active");
  });
});
