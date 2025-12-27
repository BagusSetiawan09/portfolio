(function ($) {
    "use strict";
    // DOM Ready

    var effectCursor = function () {
        document.addEventListener("mousemove", function e(t) {
            try {
                t.target;
                gsap.timeline({
                    defaults: {
                        x: t.clientX,
                        y: t.clientY,
                    },
                })
                    .to(".cursor1", {
                        ease: "power2.out",
                    })
                    .to(
                        ".cursor2",
                        {
                            ease: "power2.out",
                        },
                        "-=0.4"
                    );
            } catch (o) {
                console.log(o);
            }
        });
    };

    var changetext = function () {
        if ($(".reveal-type").length > 0) {
            const splitTypes = document.querySelectorAll(".reveal-type");
            splitTypes.forEach((e, t) => {
                const a = new SplitText(e, {
                    types: "words, chars",
                });
                gsap.from(a.chars, {
                    scrollTrigger: {
                        trigger: e,
                        start: "top 80%",
                        end: "top 20%",
                        scrub: !0,
                        markers: !1,
                    },
                    opacity: 0.2,
                    stagger: 0.1,
                });
            });
        }
    };

    var animation_text = function () {
        if ($(".split-text").length > 0) {
            var st = $(".split-text");
            if (st.length === 0) return;
            gsap.registerPlugin(SplitText, ScrollTrigger);
            st.each(function (index, el) {
                const $el = $(el);
                const $target =
                    $el.find("p, a").length > 0 ? $el.find("p, a")[0] : el;
                const hasClass = $el.hasClass.bind($el);
                const pxl_split = new SplitText($target, {
                    type: "words, chars",
                    lineThreshold: 0.5,
                    linesClass: "split-line",
                });
                let split_type_set = pxl_split.chars;
                gsap.set($target, { perspective: 400 });

                const settings = {
                    scrollTrigger: {
                        trigger: $target,
                        start: "top 86%",
                        toggleActions: "play none none reverse",
                    },
                    duration: 0.9,
                    stagger: 0.02,
                    ease: "power3.out",
                };

                if (hasClass("effect-fade")) settings.opacity = 0;
                if (hasClass("effect-right")) {
                    settings.opacity = 0;
                    settings.x = "50";
                }
                if (hasClass("effect-left")) {
                    settings.opacity = 0;
                    settings.x = "-50";
                }
                if (hasClass("effect-up")) {
                    settings.opacity = 0;
                    settings.y = "80";
                }
                if (hasClass("effect-down")) {
                    settings.opacity = 0;
                    settings.y = "-80";
                }
                if (hasClass("effect-rotate")) {
                    settings.opacity = 0;
                    settings.rotateX = "50deg";
                }
                if (hasClass("effect-scale")) {
                    settings.opacity = 0;
                    settings.scale = "0.5";
                }

                if (
                    hasClass("split-lines-transform") ||
                    hasClass("split-lines-rotation-x")
                ) {
                    pxl_split.split({
                        type: "lines",
                        lineThreshold: 0.5,
                        linesClass: "split-line",
                    });
                    split_type_set = pxl_split.lines;
                    settings.opacity = 0;
                    settings.stagger = 0.5;
                    if (hasClass("split-lines-rotation-x")) {
                        settings.rotationX = -120;
                        settings.transformOrigin = "top center -50";
                    } else {
                        settings.yPercent = 100;
                        settings.autoAlpha = 0;
                    }
                }

                if (hasClass("split-words-scale")) {
                    pxl_split.split({ type: "words" });
                    split_type_set = pxl_split.words;
                    split_type_set.forEach((elw, index) => {
                        gsap.set(
                            elw,
                            {
                                opacity: 0,
                                scale: index % 2 === 0 ? 0 : 2,
                                force3D: true,
                                duration: 0.1,
                                ease: "power3.out",
                                stagger: 0.02,
                            },
                            index * 0.01
                        );
                    });
                    gsap.to(split_type_set, {
                        scrollTrigger: {
                            trigger: el,
                            start: "top 86%",
                        },
                        rotateX: "0",
                        scale: 1,
                        opacity: 1,
                    });
                } else if (hasClass("effect-blur-fade")) {
                    pxl_split.split({ type: "words" });
                    split_type_set = pxl_split.words;
                    gsap.fromTo(
                        split_type_set,
                        { opacity: 0, filter: "blur(10px)", y: 20 },
                        {
                            opacity: 1,
                            filter: "blur(0px)",
                            y: 0,
                            duration: 1,
                            stagger: 0.1,
                            ease: "power3.out",
                            scrollTrigger: {
                                trigger: $target,
                                start: "top 86%",
                                toggleActions: "play none none reverse",
                            },
                        }
                    );
                } else {
                    gsap.from(split_type_set, settings);
                }
            });
        }
    };

    var animationScaleImg = function () {
        if ($(".scale-img").length > 0) {
            var scale = document.querySelectorAll(".scale-img");
            var image = document.querySelectorAll(".scale-img img");
            scale.forEach((item) => {
                gsap.to(item, {
                    scale: 1,
                    duration: 1,
                    ease: "power1.out",
                    scrollTrigger: {
                        trigger: item,
                        start: "top bottom",
                        end: "bottom top",
                        toggleActions: "play reverse play reverse",
                    },
                });
            });
            image.forEach((image) => {
                gsap.set(image, {
                    scale: 1.3,
                });
                gsap.to(image, {
                    scale: 1,
                    duration: 1,
                    scrollTrigger: {
                        trigger: image,
                        start: "top bottom",
                        end: "bottom top",
                        toggleActions: "play reverse play reverse",
                    },
                });
            });
        }
    };

    var animateImgItem = function () {
        const isSmallScreen = window.matchMedia("(max-width: 991px)").matches;

        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const delay =
                            parseFloat(
                                entry.target.getAttribute("data-delay")
                            ) || 0;
                        setTimeout(() => {
                            $(entry.target).addClass("active-animate");
                        }, delay * 1000);
                    }
                });
            },
            {
                threshold: isSmallScreen ? 0.1 : 0.1,
            }
        );

        const elements = $(
            ".tf-animate-1, .tf-animate-2, .tf-animate-3, .tf-animate-4"
        );
        elements.each(function () {
            observer.observe(this);
        });

        const checkVisible = () => {
            elements.each(function () {
                const sectionOffsetTop = $(this).offset().top;
                const sectionHeight = $(this).outerHeight();
                const scrollPosition = $(window).scrollTop();
                const windowHeight = $(window).height();

                if (
                    scrollPosition + windowHeight * 0.9 > sectionOffsetTop &&
                    scrollPosition < sectionOffsetTop + sectionHeight
                ) {
                    const delay = parseFloat($(this).attr("data-delay")) || 0;
                    setTimeout(() => {
                        $(this).addClass("active-animate");
                    }, delay * 1000);
                }
            });
        };

        $(document).ready(checkVisible);
        $(window).on("scroll", checkVisible);
    };

    var fadeAnimation = function () {
        let fadeArray_items = document.querySelectorAll(
            ".animateFade:not(.animated)"
        );

        if (fadeArray_items.length > 0) {
            const fadeArray = gsap.utils.toArray(fadeArray_items);

            fadeArray.forEach((item) => {
                let fade_direction =
                    item.getAttribute("data-fade-from") || "bottom";
                let onscroll_value = item.getAttribute("data-on-scroll") || 1;
                let duration_value =
                    parseFloat(item.getAttribute("data-duration")) || 1.15;
                let fade_offset =
                    parseFloat(item.getAttribute("data-fade-offset")) || 50;
                let delay_value =
                    parseFloat(item.getAttribute("data-delay")) || 0.15;
                let ease_value = item.getAttribute("data-ease") || "power2.out";

                let animation_settings = {
                    opacity: 0,
                    ease: ease_value,
                    duration: duration_value,
                    delay: delay_value,
                };

                if (fade_direction === "top")
                    animation_settings.y = -fade_offset;
                if (fade_direction === "bottom")
                    animation_settings.y = fade_offset;
                if (fade_direction === "left")
                    animation_settings.x = -fade_offset;
                if (fade_direction === "right")
                    animation_settings.x = fade_offset;

                if (onscroll_value === 1) {
                    animation_settings.scrollTrigger = {
                        trigger: item,
                        start: "top 85%",
                    };
                }

                gsap.from(item, animation_settings);

                // Đánh dấu đã animate để không lặp
                item.classList.add("animated");
            });
        }
    };

    var loader = function () {
        var innerBars = document.querySelectorAll(".inner-bar");
        var increment = 0;

         if (!preloader || innerBars.length === 0) return;

        function animateBars() {
            for (var i = 0; i < 2; i++) {
                var randomWidth = Math.floor(Math.random() * 101);
                if (!bar) continue;
                gsap.to(innerBars[i + increment], {
                    width: randomWidth + "%",
                    duration: 0.5,
                    ease: "none",
                });
            }

            setTimeout(function () {
                for (var i = 0; i < 2; i++) {
                    gsap.to(innerBars[i + increment], {
                        width: "100%",
                        duration: 0.5,
                        ease: "none",
                    });
                }

                increment += 2;

                if (increment < innerBars.length) {
                    animateBars();
                } else {
                    var preloaderTL = gsap.timeline();
                    preloaderTL.to(".preloader", {
                        "--preloader-clip": "100%",
                        duration: 0.4,
                        ease: "none",
                        delay: 0.8,
                    });
                    preloaderTL.set(".preloader", {
                        display: "none",
                    });
                }
            }, 200);
        }

        $(window).on("load", function () {
            animateBars();
            setTimeout(function () {
                $(".preloader").remove();
            }, 3000);
        });
    };

    $(function () {
        loader();
        setTimeout(() => {
            changetext();
            animation_text();
            animationScaleImg();
            animateImgItem();
            effectCursor();
            fadeAnimation();
        }, 1700);
    });
})(jQuery);
