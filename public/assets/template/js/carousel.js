if ($(".sw-thumbs-pagi").length > 0) {
    var preview = $(".sw-thumbs-pagi").data("preview");
    var spacing = $(".sw-thumbs-pagi").data("space");
    var mobile = $(".sw-thumbs-pagi").data("mobile");
    var mobileSm = $(".sw-thumbs-pagi").data("mobile-sm");
    var pagithumbs = new Swiper(".sw-thumbs-pagi", {
        spaceBetween: spacing,
        slidesPerView: preview,
        freeMode: true,
        watchSlidesProgress: true,
        breakpoints: {
            375: {
                slidesPerView: 4,
                spaceBetween: spacing,
            },
            500: {
                slidesPerView: mobileSm,
            },
        },
    });
}

if ($(".flat-thumbs-tes").length > 0) {
    var spaceThumbLg = $(".tf-thumb-tes").data("space-lg");
    var spaceThumb = $(".tf-thumb-tes").data("space");
    var spaceTesLg = $(".tf-tes-main").data("space-lg");
    var spaceTes = $(".tf-tes-main").data("space");
    var effect = $(".flat-thumbs-tes").data("effect") || "slide";
    const swThumb = new Swiper(".tf-thumb-tes", {
        speed: 800,
        spaceBetween: spaceThumb,
        effect: effect,
        fadeEffect: effect === "fade" ? { crossFade: true } : undefined,
        breakpoints: {
            768: {
                spaceBetween: spaceThumbLg,
            },
        },
    });
    const swTesMain = new Swiper(".tf-tes-main", {
        speed: 800,
        navigation: {
            nextEl: ".nav-next-tes",
            prevEl: ".nav-prev-tes",
        },
        thumbs: {
            swiper: pagithumbs,
        },
        effect: effect,
        fadeEffect: effect === "fade" ? { crossFade: true } : undefined,
        pagination: {
            el: ".sw-pagination-tes",
            clickable: true,
        },
        spaceBetween: spaceTes,
        breakpoints: {
            768: {
                spaceBetween: spaceTesLg,
            },
        },
    });

    swThumb.controller.control = swTesMain;
    swTesMain.controller.control = swThumb;
}

$(window).on("load", function () {
    $(".tf-swiper").each(function (index, element) {
        var $this = $(element);
        var laptop = $this.data("laptop") || 1;
        var preview = $this.data("preview") || 1;
        var tablet = $this.data("tablet") || 1;
        var mobile = $this.data("mobile") || 1;
        var mobileSm =
            $this.data("mobile-sm") !== undefined
                ? $this.data("mobile-sm")
                : mobile;

        // Spacing
        var spacing = $this.data("space");
        var spacingMd = $this.data("space-md");
        var spacingLg = $this.data("space-lg");
        var spacingXxl = $this.data("space-xxl");

        if (
            spacing !== undefined &&
            spacingMd === undefined &&
            spacingLg === undefined
        ) {
            spacingMd = spacing;
            spacingLg = spacing;
        } else if (
            spacing === undefined &&
            spacingMd !== undefined &&
            spacingLg === undefined
        ) {
            spacing = 0;
            spacingLg = spacingMd;
        }
        spacing = spacing || 0;
        spacingMd = spacingMd || 0;
        spacingLg = spacingLg || 0;
        spacingXxl = spacingXxl || 1;

        var perGroup = $this.data("pagination") || 1;
        var perGroupSm = $this.data("pagination-sm") || 1;
        var perGroupMd = $this.data("pagination-md") || 1;
        var perGroupLg = $this.data("pagination-lg") || 1;
        var gridRows = $this.data("grid") || 1;
        var cursorType = $this.data("cursor") ?? false;
        var loop = $this.data("loop") ?? false;
        var loopMd = $this.data("loop-md") ?? false;
        var effect = $this.data("effect") || "slide";
        var atPlay = $this.data("auto"); // True || False
        var speed = $this.data("speed") || 800;
        var delay = $this.data("delay") || 1000;
        var direction = $this.data("direction") || "horizontal";
        var centered = $this.data("center") ?? false;
        var init = $this.data("init") || 0;

        var swiperT = new Swiper($this[0], {
            direction: direction,
            speed: speed,
            centeredSlides: centered,
            slidesPerView: mobile,
            spaceBetween: spacing,
            slidesPerGroup: perGroup,
            grabCursor: cursorType,
            loop: loop,
            effect: effect,
            initialSlide: init,
            // autoplay: atPlay
            //     ? {
            //           delay: delay,
            //           disableOnInteraction: false,
            //       }
            //     : false,
            grid: {
                rows: gridRows,
                fill: "row",
            },
            pagination: {
                el: [
                    $this.find(".tf-sw-pagination")[0],
                    $this
                        .closest(".tf-pag-swiper")
                        .find(".tf-sw-pagination")[0],
                ],
                clickable: true,
            },
            observer: true,
            observeParents: true,
            navigation: {
                nextEl: [
                    $this
                        .closest(".tf-btn-swiper-main")
                        .find(".nav-next-swiper")[0],
                    $this
                        .closest(".container")
                        .find(".group-btn-slider .nav-next-swiper")[0],
                ],
                prevEl: [
                    $this
                        .closest(".tf-btn-swiper-main")
                        .find(".nav-prev-swiper")[0],
                    $this
                        .closest(".container")
                        .find(".group-btn-slider .nav-prev-swiper")[0],
                ],
            },
            breakpoints: {
                575: {
                    slidesPerView: mobileSm,
                    spaceBetween: spacing,
                    slidesPerGroup: perGroupSm,
                    grid: {
                        rows: gridRows,
                        fill: "row",
                    },
                },
                768: {
                    slidesPerView: tablet,
                    spaceBetween: spacingMd,
                    slidesPerGroup: perGroupMd,
                    grid: {
                        rows: gridRows,
                        fill: "row",
                    },
                },
                1200: {
                    slidesPerView: preview,
                    spaceBetween: spacingLg,
                    slidesPerGroup: perGroupLg,
                    grid: {
                        rows: gridRows,
                        fill: "row",
                    },
                },
                1600: {
                    slidesPerView: laptop === 1 ? preview : laptop,
                    spaceBetween: spacingXxl === 1 ? spacingLg : spacingXxl,
                    slidesPerGroup: perGroupLg,
                    grid: {
                        rows: gridRows,
                        fill: "row",
                    },
                },
            },
        });
    });
});
