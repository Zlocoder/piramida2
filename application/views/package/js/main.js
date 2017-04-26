$(function() {
    $('body.main').vegas({
        slides: [
            { src: "/images/bg/mainBg1.png" },
            { src: "/images/bg/bg3.jpg" },
            { src: "/images/bg/bg2.jpg" },
            { src: "/images/bg/bg1.jpg" }
        ],
        timer: false,
        delay: 8000,
        transitionDuration: 2000
    });
});
