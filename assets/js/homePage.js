import $ from 'jquery'
import Swiper from 'swiper'
import 'swiper/css/swiper.css'

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