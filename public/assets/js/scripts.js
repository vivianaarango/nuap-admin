/*
Author       : Aminul Islam.
Template Name: Appsland - Appsland Landing Page Template
Version      : 1.0
*/

(function(jQuery) {
	'use strict';
	
	jQuery(document).on('ready', function(){
		
		/*PRELOADER JS*/
		$(window).on('load', function() {  
			$('.spinner').fadeOut();
			$('.preloader').delay(350).fadeOut('slow'); 
		}); 
		/*END PRELOADER JS*/
		// jQuery Video magnificPopup
				jQuery('.video-popup').magnificPopup({
				  type: 'iframe',
				});
			
		
		/*START MENU JS*/
			$('.navbar-fixed-top a').on('click', function(e){
				var anchor = $(this);
				$('html, body').stop().animate({
					scrollTop: $(anchor.attr('href')).offset().top - 50
				}, 1500);
				e.preventDefault();
			});	
			
			$(window).on('scroll', function() {
			  if ($(this).scrollTop() > 100) {
				$('.menu-top').addClass('menu-shrink');
			  } else {
				$('.menu-top').removeClass('menu-shrink');
			  }
			});

			$(document).on('click','.navbar-collapse.in',function(e) {
			if( $(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle' ) {
				$(this).collapse('hide');
			}
			});			
		/*END MENU JS*/ 
		
		// jQuery About 
			const tilt = $('.js-sm').tilt({
				maxTilt:        20,
				perspective:    1000
			});
		
		/* START APP-SCREENS */		
		$('.app_screens_slider').slick({
			slidesToShow: 5,
			slidesToScroll: 1,
			infinite: true,
			centerMode: true,
			centerPadding: '0px',
			autoplay: true,
			dots: true,
			autoplaySpeed: 5000,		
			responsive: [
				{
				breakpoint: 991,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint: 767,
					settings: {
						slidesToShow: 1,
					}
				}
			]
		});		
		/* START APP-SCREENS */
		
		/* START TESTIMONIAL JS */
			 $("#testimonial-slider").owlCarousel({
				items:1,
				itemsDesktop:[1000,1],
				itemsDesktopSmall:[979,1],
				itemsTablet:[768,1],
				pagination: false,
				slideSpeed:1000,
				singleItem:true,
				transitionStyle:"fadeUp",
				autoPlay:true
			});
		/* END TESTIMONIAL JS */
		
		/* START BLOG JS */
			  $("#blog-slider").owlCarousel({
				items : 3,
				itemsDesktop:[1199,3],
				itemsDesktopSmall:[980,2],
				itemsMobile : [600,1],
				pagination:false,
				navigationText:false,
				slideSpeed:1000,
				autoPlay:true
			});
		/* END BLOG JS */
		
		/*START COUNTER JS*/
			$('.counter').counterUp({
				delay: 10,
				time: 1000
			});
		/*END COUNTER JS*/

	});
		
	/*START WOW ANIMATION JS*/
	  new WOW().init();	
	/*END WOW ANIMATION JS*/
				
})(jQuery);


  

