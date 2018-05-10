(function ($) {
 "use strict";
/*========================= 
 Sidr
===========================*/ 
    $('#right-menu').sidr({
      name: 'sidr-right',
      side: 'right'
    });
    $('#simple-menu').sidr();
/*========================= 
 Active flickr
===========================*/  
  $('#flickr_feed').jflickrfeed({
    limit: 12,
    qstrings: {
      id: '95572727@N00'
    },
    itemTemplate:
    '' +
      '<a data-gal="fancybox[flickrgallery]" href="{{image}}" title="{{title}}">' +
        '<img src="{{image_s}}" alt="{{title}}" />' +
      '</a>' +
    ''
  }, function(data) {
    $('.flickr_feed a').fancybox();
  });     
/*========================= 
 fancybox
===========================*/
  $('.fancybox').fancybox();     
/*========================= 
 screenshot-image-meddin
===========================*/ 
  $(".screenshot-image-meddin").owlCarousel({
    autoPlay: 6000,
    slideSpeed : 1000,
    paginationSpeed : 1000,
    navigation: false,
    pagination: false,
    items : 1,
    itemsDesktop : [1199,1],
    itemsDesktopSmall : [979,1],
    itemsTablet: [767,1], 
    itemsMobile : [480,1]
  }); 
/*========================= 
 testimonial carosel 
===========================*/ 
  $(".testimonial-carosel").owlCarousel({
    autoPlay: 6000,
    slideSpeed : 1000,
    paginationSpeed : 1000,
    navigation: false,
    pagination: true,
    singleItem : true,
    transitionStyle : "fadeUp",
    items : 1,
    itemsDesktop : [1199,1],
    itemsDesktopSmall : [979,1]
  });
/*========================= 
 demane-slide 
===========================*/ 
  $(".demane-slide").owlCarousel({
    autoPlay: 6000000,
    slideSpeed : 1000,
    paginationSpeed : 1000,
    items : 3,
    itemsDesktop : [1199,3],
    itemsDesktopSmall : [979,3],
    itemsTablet: [767,2], 
    itemsMobile : [480,1],
  });
/*========================= 
 popular-slide6 
===========================*/ 
  $(".popular-slide6").owlCarousel({
    navigation : true,
    pagination : true,
    slideSpeed : 600,
    paginationSpeed : 400,
    items : 3,
    itemsDesktop : [1199,3],
    itemsDesktopSmall : [979,3], 
    itemsTablet: [767,2], 
    itemsMobile : [480,1],
    navigationText : ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'] 
  }); 
/*========================= 
 recent-post-slide6 
===========================*/ 
  $(".recent-post-slide6").owlCarousel({
    navigation : true,
    pagination : true,
    slideSpeed : 600,
    paginationSpeed : 400,
    items : 1,
    itemsDesktop : [1199,1],
    itemsDesktopSmall : [979,1], 
    itemsTablet: [767,1], 
    itemsMobile : [480,1],
    navigationText : ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'] 
  });   
/*========================= 
 testimonial4 
===========================*/ 
  $(".testimonial4").owlCarousel({
    autoPlay: 600000000,
    slideSpeed : 1000,
    paginationSpeed : 1500,
    navigation: false,
    pagination: false,
    items : 1,
    itemsDesktop : [1199,1],
    itemsDesktopSmall : [979,1],
    itemsTablet: [767,1], 
    itemsMobile : [480,1]
  });
/*========================= 
 testimonial4 
===========================*/ 
  $(".work-freame9").owlCarousel({
    autoPlay: 6000,
    slideSpeed : 1000,
    paginationSpeed : 1500,
    navigation: false,
    pagination: true,
    items : 1,
    itemsDesktop : [1199,1],
    itemsDesktopSmall : [979,1],
    itemsTablet: [767,1], 
    itemsMobile : [480,1]
  });
/*========================= 
 emargency-carosel 
===========================*/ 
  $(".emargency-carosel").owlCarousel({
    autoPlay: 6000000,
    slideSpeed : 1000,
    paginationSpeed : 1500,
    navigation: false,
    pagination: true,
    items : 3,
    itemsDesktop : [1199,3],
    itemsDesktopSmall : [979,3]
  });
/*========================= 
 owl carosel for Our Office page
===========================*/
  $("#owl-demo-two").owlCarousel({
    autoPlay: 6000000,
    slideSpeed : 1000,
    paginationSpeed : 1500,
    navigation: true,
    pagination: false,
    items : 1,
    itemsDesktop : [1199,1],
    itemsDesktopSmall : [979,1],
    itemsTablet: [767,1], 
    itemsMobile : [480,1]
  }); 
/*---------------------
 team-2-curosel
--------------------- */
  $(".team-2-curosel").owlCarousel({
    autoPlay: 6000000,
    slideSpeed : 1000,
    paginationSpeed : 1500,
    navigation: true,
    pagination: false,
    items : 5,
    itemsDesktop : [1199,5],
    itemsDesktopSmall : [979,5]
  });
  $('.owl-prev').html('<span class="glyphicon glyphicon-chevron-left owl-prev-icon" aria-hidden="true"></span>');
  $('.owl-next').html('<span class="glyphicon glyphicon-chevron-right owl-next-icon" aria-hidden="true"></span>');
/*========================= 
 about2-slide-baner 
===========================*/ 
  $(".about2-slide-baner").owlCarousel({
    autoPlay: 6000000,
    slideSpeed : 1000,
    paginationSpeed : 1500,
    navigation: false,
    pagination: false,
    items : 1,
    itemsDesktop : [1199,1],
    itemsDesktopSmall : [979,1],
    itemsTablet: [767,1], 
    itemsMobile : [480,1]
  });
/*========================= 
 testimonial-list 
===========================*/ 
  $(".testimonial-list").owlCarousel({
    autoPlay: 6000000,
    slideSpeed : 1000,
    paginationSpeed : 1500,
    navigation: false,
    pagination: false,
    items : 1,
    itemsDesktop : [1199,1],
    itemsDesktopSmall : [979,1],
    itemsTablet: [767,1], 
    itemsMobile : [480,1]
  });
/*========================= 
 owl carosel for Our Office page
===========================*/
  $(".showcase-slider").owlCarousel({
    autoPlay: 6000000,
    slideSpeed : 1000,
    paginationSpeed : 1500,
    navigation: false,
    pagination: true,
    items : 4,
    itemsDesktop : [1199,4],
    itemsDesktopSmall : [979,4]
  });
  $('.owl-prev').html('<span class="glyphicon glyphicon-chevron-left owl-prev-icon" aria-hidden="true"></span>');
  $('.owl-next').html('<span class="glyphicon glyphicon-chevron-right owl-next-icon" aria-hidden="true"></span>');
/*========================= 
 tooltip 
===========================*/   
  $('[data-toggle="tooltip"]').tooltip(); 
/*========================= 
 countdown 
===========================*/ 
  $('[data-countdown]').each(function() {
    var $this = $(this), finalDate = $(this).data('countdown');
    $this.countdown(finalDate, function(event) {
    $this.html(event.strftime('<span class="cdown days"><span class="time-count">%-D :</span> <p>Days</p></span> <span class="cdown hour"><span class="time-count">%-H :</span> <p>Hour</p></span> <span class="cdown minutes"><span class="time-count">%M :</span> <p>Min</p></span> <span class="cdown second"> <span><span class="time-count">%S</span> <p>Sec</p></span>'));
    });
  });      
/*========================= 
 mixitup 
===========================*/ 
  $(".work-item7").mixitup({
    effects: ['fade','rotateZ'],
    easing: 'snap'
  });
/*========================= 
 counterUp
===========================*/
  $('.counter').counterUp({
    delay: 10,
    time: 1000
  });
/*========================= 
 scrollUp
===========================*/ 
  $.scrollUp({
      scrollText: '<i class="fa fa-chevron-up"></i>',
      easingType: 'linear',
      scrollSpeed: 900,
      animation: 'fade'
  });
/*========================= 
 accordion
===========================*/ 
function toggleChevron(e) {
    $(e.target)
        .prev('.panel-heading')
        .find("i.indicator")
        .toggleClass('glyphicon glyphicon-plus glyphicon glyphicon-minus');
}
$('#accordion').on('hidden.bs.collapse', toggleChevron);
$('#accordion').on('shown.bs.collapse', toggleChevron);
/*========================= 
 Circular Bars - Knob
===========================*/  
    if(typeof($.fn.knob) != 'undefined') {
    $('.knob').each(function () {
      var $this = $(this),
        knobVal = $this.attr('data-rel');
  
      $this.knob({
      'draw' : function () { 
        $(this.i).val(this.cv + '%')
      }
      });
      
      $this.appear(function() {
      $({
        value: 0
      }).animate({
        value: knobVal
      }, {
        duration : 2000,
        easing   : 'swing',
        step     : function () {
        $this.val(Math.ceil(this.value)).trigger('change');
        }
      });
      }, {accX: 0, accY: -150});
    });
    }
/* --------------------------------------------------------
   faq-accordion
* -------------------------------------------------------*/ 
  $(".faq-accordion").collapse({
    accordion:true,
    open: function() {
    this.slideDown(550);
    },
    close: function() {
    this.slideUp(550);
    }   
  });
/* --------------------------------------------------------
   contact-accordion
* -------------------------------------------------------*/ 
  $(".contact-accordion").collapse({
    accordion:true,
    open: function() {
    this.slideDown(550);
    },
    close: function() {
    this.slideUp(550);
    }   
  });       
/* --------------------------------------------------------
   qa-accordion
* -------------------------------------------------------*/ 
  $(".qa-accordion").collapse({
    accordion:true,
    open: function() {
    this.slideDown(550);
    },
    close: function() {
    this.slideUp(550);
    }   
  });
})(jQuery);
   