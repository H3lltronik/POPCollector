import $ from 'jquery'
import Swiper from 'swiper'
import 'swiper/css/swiper.css'
window.Swiper = Swiper;
$(document).ready( function () {

    new Swiper('.main-carousel', {
        effect: 'fade',
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
            clickable: true,
            renderBullet: function (index, className) {
                return `<span class="${className}"><span></span></span>`;
            }
        },
    });

    $('.owl-carousel').owlCarousel({
        loop: false,
        items: 4,
        slideBy: 4,
        autoplay:true,
        autoplayTimeout:4000,
        responsiveClass:true,
        responsive:{
            0:{
                items:2,
            },
            600:{
                items:3,
                nav:false
            },
            1000:{
                items:4,
                nav:true,
                loop:false,
            },
            2000:{
                items:6,
                loop:false,
            },
        }
    })
    // Bug de resize con el owl carousel, no muestra contenido correctamente a menos que se resizee xd
    window.dispatchEvent(new Event('resize'));
})

$(window).on({
    load: function(){
        setTimeout(() => {
            window.dispatchEvent(new Event('resize'));
        }, 1000);
    } 
});