
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.swiper').forEach((slider) => {

        new Swiper(slider, {
            slidesPerView: 4,
            spaceBetween: 16,
            loop: false,

            navigation: {
                nextEl: slider.querySelector('.swiper-button-next'),
                prevEl: slider.querySelector('.swiper-button-prev'),
            },

            breakpoints: {
                640: { slidesPerView: 2 },
                1240: { slidesPerView: 4 }
            }
        });

    });
});
