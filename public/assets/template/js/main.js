(function ($) {
    "use strict";
    /* preventDefault
  -------------------------------------------------------------------------*/
    const preventDefault = () => {
        $(".link-no-action").on("click", function (e) {
            e.preventDefault();
        });
    };

    /* headerFixed
  -------------------------------------------------------------------------*/
    const headerFixed = () => {
        const header = document.querySelector(".header-fixed");
        if (!header) return;
        let isFixed = false;
        const scrollThreshold = 1;
        const handleScroll = () => {
            const shouldBeFixed = window.scrollY >= scrollThreshold;
            if (shouldBeFixed !== isFixed) {
                header.classList.toggle("is-fixed", shouldBeFixed);
                isFixed = shouldBeFixed;
            }
        };
        window.addEventListener("scroll", handleScroll, { passive: true });
        handleScroll();
    };

    /* infiniteslide
    ------------------------------------------------------------------------------------- */
    const infiniteslide = () => {
        if ($(".infiniteslide").length > 0) {
            $(".infiniteslide").each(function () {
                var $this = $(this);
                var style = $this.data("style") || "left";
                var clone = $this.data("clone") || 4;
                var speed = $this.data("speed") || 100;
                $this.infiniteslide({
                    speed: speed,
                    direction: style,
                    clone: clone,
                });
            });
        }
    };

    /* Counter Animation
    ------------------------------------------------------------------------------------- */
    const animateCounters = () => {
        $(window).on("scroll", function () {
            $(".counter-item").each(function () {
                const $item = $(this);
                if ($item.data("animated")) return;
                const oTop = $item.offset().top - window.innerHeight;
                if ($(window).scrollTop() > oTop) {
                    $item.find(".numberCount").each(function () {
                        const $this = $(this);
                        const countTo = parseInt($this.data("count"), 10) || 0;
                        const duration =
                            parseInt($this.data("duration"), 10) || 2000;
                        $({ countNum: 0 }).animate(
                            { countNum: countTo },
                            {
                                duration: duration,
                                easing: "swing",
                                step: function () {
                                    const num = Math.floor(this.countNum);
                                    $this.text(String(num).padStart(2, "0"));
                                },
                                complete: function () {
                                    const num = this.countNum;
                                    $this.text(String(num).padStart(2, "0"));
                                },
                            }
                        );
                    });
                    $item.data("animated", true);
                }
            });
        });
    };

    /* effect_button
  -------------------------------------------------------------------------*/
    var effect_button = () => {
        $(".btn-hover-animation-fill").each(function () {
            var button_width = $(this).outerWidth();
            $(this).css("--button-width", button_width + "px");
        });
        $(".btn-hover-animation-fill")
            .on("mouseenter", function (e) {
                var parentOffset = $(this).offset(),
                    relX = e.pageX - parentOffset.left,
                    relY = e.pageY - parentOffset.top;
                $(this).find(".bg-effect").css({ top: relY, left: relX });
            })
            .on("mouseout", function (e) {
                var parentOffset = $(this).offset(),
                    relX = e.pageX - parentOffset.left,
                    relY = e.pageY - parentOffset.top;
                $(this).find(".bg-effect").css({ top: relY, left: relX });
            });
    };

    /* effect_button
  -------------------------------------------------------------------------*/
    var animation = function () {
        if ($(".wow").length > 0) {
            var wow = new WOW({
                boxClass: "wow",
                animateClass: "animated",
                offset: 0,
                mobile: false,
                live: true,
            });
            wow.init();
        }
    };

    /* effectHoverButton
  ------------------------------------------------------------------------------------- */
    const effectHoverButton = () => {
        if (!$(".btn-hover").length) return;
        const all_btns = gsap.utils.toArray(".btn_wrapper");
        if (all_btns.length > 0) {
            var all_btn = gsap.utils.toArray(".btn_wrapper");
        }
        const all_btn_cirlce = gsap.utils.toArray(".btn-item");
        all_btn.forEach((btn, i) => {
            $(btn).mousemove(function (e) {
                callParallax(e);
            });
            function callParallax(e) {
                parallaxIt(e, all_btn_cirlce[i], 80);
            }

            function parallaxIt(e, target, movement) {
                var $this = $(btn);
                var relX = e.pageX - $this.offset().left;
                var relY = e.pageY - $this.offset().top;

                gsap.to(target, 0.5, {
                    x: ((relX - $this.width() / 2) / $this.width()) * movement,
                    y:
                        ((relY - $this.height() / 2) / $this.height()) *
                        movement,
                    ease: Power2.easeOut,
                });
            }
            $(btn).mouseleave(function (e) {
                gsap.to(all_btn_cirlce[i], 0.5, {
                    x: 0,
                    y: 0,
                    ease: Power2.easeOut,
                });
            });
        });
    };

    /* oneNavOnePage
  -------------------------------------------------------------------------------------*/
    const oneNavOnePage = () => {
        if (!$(".section-onepage").length) return;

        const $navLinks = $(".nav_link");
        const $sections = $(".section");

        $navLinks.on("click", function (e) {
            e.preventDefault();

            const target = $(this).attr("href");
            const $target = $(target);
            if (!$target.length) return;

            const headerHeight = $(".header-fixed").outerHeight() || 0;
            const offsetTop = $target.offset().top - headerHeight;

            $("html, body").animate({ scrollTop: offsetTop }, 0);

            $(".side-menu-mobile,.offcanvas-backdrop,.offcanvas").removeClass(
                "show"
            );
            $(".side-toggle ").removeClass("open");
            $(".overlay-popup").removeClass("show");
            $("body").removeAttr("style");

            if ($(this).hasClass("open-popup")) {
                openYourPopup();
            }
        });

        const updateActiveMenu = () => {
            const scrollTop = $(window).scrollTop();
            const headerHeight = $(".header-fixed").outerHeight() || 0;
            let current = "";
            let currentIndex = -1;

            $sections.each(function (index) {
                const $section = $(this);
                const top = $section.offset().top - headerHeight;
                const bottom = top + $section.outerHeight();

                if (scrollTop >= top && scrollTop < bottom) {
                    current = $section.attr("id");
                    currentIndex = index;
                }
            });
            $navLinks
                .removeClass("active")
                .filter(`[href="#${current}"]`)
                .addClass("active");

            $sections.removeClass("dimmed");

            if (currentIndex !== -1) {
                $sections.each(function (index) {
                    if (index < currentIndex) {
                        $(this).addClass("dimmed");
                    }
                });
            }
        };

        $(window).on("scroll", updateActiveMenu);
        $(window).on("resize", updateActiveMenu);
        updateActiveMenu();
    };

    /* effectParallax
  -------------------------------------------------------------------------------------*/
    const effectParallax = () => {
        if (!$(".parallax-img").length) return;
        const images = document.querySelectorAll(".parallax-img");
        new Ukiyo(images, {
            scale: 1.5,
            speed: 1.5,
            externalRAF: !1,
        });
    };

    /* openMenu
  -------------------------------------------------------------------------------------*/
    const openMenu = () => {
        if (!$(".side-menu-mobile").length) return;

        $(".side-info-close,.offcanvas-overlay").on("click", function () {
            $(".side-menu-mobile").removeClass("show");
            $(".offcanvas-overlay").removeClass("overlay-open");
        });

        $(".side-toggle").on("click", function () {
            $(".side-toggle").toggleClass("open");
            $(".side-menu-mobile").toggleClass("show");
            $(".offcanvas-overlay").toggleClass("overlay-open");
        });
    };

    /* goTop
  -------------------------------------------------------------------------------------*/
    const goTop = () => {
        if ($("div").hasClass("progress-wrap")) {
            var progressPath = document.querySelector(".progress-wrap path");
            var pathLength = progressPath.getTotalLength();
            progressPath.style.transition =
                progressPath.style.WebkitTransition = "none";
            progressPath.style.strokeDasharray = pathLength + " " + pathLength;
            progressPath.style.strokeDashoffset = pathLength;
            progressPath.getBoundingClientRect();
            progressPath.style.transition =
                progressPath.style.WebkitTransition =
                    "stroke-dashoffset 10ms linear";
            var updateprogress = function () {
                var scroll = $(window).scrollTop();
                var height = $(document).height() - $(window).height();
                var progress = pathLength - (scroll * pathLength) / height;
                progressPath.style.strokeDashoffset = progress;
            };
            updateprogress();
            $(window).scroll(updateprogress);
            var offset = 200;
            var duration = 0;
            jQuery(window).on("scroll", function () {
                if (jQuery(this).scrollTop() > offset) {
                    jQuery(".progress-wrap").addClass("active-progress");
                } else {
                    jQuery(".progress-wrap").removeClass("active-progress");
                }
            });
            jQuery(".progress-wrap").on("click", function (event) {
                event.preventDefault();
                jQuery("html, body").animate({ scrollTop: 0 }, duration);
                return false;
            });
        }
    };



    // DOM Ready
    $(function () {
        preventDefault();
        headerFixed();
        infiniteslide();
        animateCounters();
        effect_button();
        animation();
        effectHoverButton();
        oneNavOnePage();
        effectParallax();
        openMenu();
        goTop();
    });
})(jQuery);
