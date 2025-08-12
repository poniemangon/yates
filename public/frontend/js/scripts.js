$(document).ready(function() {

    function toggleNavbarBackground() {
        if ($('.mobile-menu').css('display') !== 'none') {
            $('.navbar-section').addClass('scrolled');
        } else {
            $(window).trigger('scroll');
        }
    }

    $(window).scroll(function() {
        if ($('.mobile-menu').css('display') === 'none') { 
            if ($(this).scrollTop() > 50) {
                $('.navbar-section').addClass('scrolled');
            } else {
                $('.navbar-section').removeClass('scrolled');
            }
        }
    });

    // Function to paint the background before toggling the menu
    function paintAndToggleMenu() {
        $('.navbar-section').addClass('scrolled');
        $('.mobile-menu').slideToggle(toggleNavbarBackground);
    }

    // Bind the paintAndToggleMenu function to the burger menu icons
    $('.fa-bars').click(function() {
        paintAndToggleMenu();
    });

    $('.fa-x').click(function() {
        paintAndToggleMenu();
    });


        function checkScreenSize() {
            if ($(window).width() > 992) {
                $('.mobile-menu').hide(); 
                $('.navbar-section').removeClass('scrolled'); 
            }
        }
    

        checkScreenSize(); 
        $(window).resize(function() {
            checkScreenSize();
        });
});

$('.carousel-banner').slick({
    dots: true,
    arrows: false,
    infinite: true,
    speed: 300,
    slidesToShow: 1,
    adaptiveHeight: true,
    initialSlide: 1,
  });



