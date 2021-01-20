var stui = {
    browser: {
        url: document.URL,
        domain: document.domain,
        title: document.title,
        language: (navigator.browserLanguage || navigator.language).toLowerCase(),
        canvas: function () {
            return !!document.createElement("canvas").getContext
        }(),
        useragent: function () {
            var a = navigator.userAgent;
            return {
                mobile: !!a.match(/AppleWebKit.*Mobile.*/),
                ios: !!a.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
                android: -1 < a.indexOf("Android") || -1 < a.indexOf("Linux"),
                iPhone: -1 < a.indexOf("iPhone") || -1 < a.indexOf("Mac"),
                iPad: -1 < a.indexOf("iPad"),
                trident: -1 < a.indexOf("Trident"),
                presto: -1 < a.indexOf("Presto"),
                webKit: -1 < a.indexOf("AppleWebKit"),
                gecko: -1 < a.indexOf("Gecko") && -1 == a.indexOf("KHTML"),
                weixin: -1 < a.indexOf("MicroMessenger")
            }
        }()
    },
    mobile: {
        popup: function () {
            $popblock = $(".popup");
            $(".open-popup").click(function () {
                $popblock.addClass("popup-visible");
                $("body").append('<div class="mask"></div>');
                $(".close-popup").click(function () {
                    $popblock.removeClass("popup-visible");
                    $(".mask").remove();
                    $("body").removeClass("modal-open")
                });
                $(".mask").click(function () {
                    $popblock.removeClass("popup-visible");
                    $(this).remove();
                    $("body").removeClass("modal-open")
                })
            })
        },
        slide: function () {
            $.getScript("/templates/default/js/flickity.pkgd.min.js", function () {
                $(".type-slide").each(function (a) {
                    $index = $(this).find('.active').index() * 1;
                    if ($index > 3) {
                        $index = $index - 3;
                    } else {
                        $index = 0;
                    }
                    $(this).flickity({
                        cellAlign: 'left',
                        freeScroll: true,
                        contain: true,
                        prevNextButtons: false,
                        pageDots: false,
                        initialIndex: $index
                    });
                })
            })
        },
        share: function () {
            $(".open-share").click(function () {
                stui.browser.useragent.weixin ? $("body").append('<div class="mobile-share share-weixin"></div>') : $("body").append('<div class="mobile-share share-other"></div>');
                $(".mobile-share").click(function () {
                    $(".mobile-share").remove();
                    $("body").removeClass("modal-open")
                })
            })
        }
    },
    flickity: {
        carousel: function () {

            $.getScript("/templates/default/js/flickity.pkgd.min.js", function () {
                $('.carousel_default').flickity({
                    cellAlign: 'left',
                    contain: true,
                    wrapAround: true,
                    autoPlay: true,
                    prevNextButtons: false
                });
                $('.carousel_wide').flickity({
                    cellAlign: 'center',
                    contain: true,
                    wrapAround: true,
                    autoPlay: true,
                });
                $('.carousel_center').flickity({
                    cellAlign: 'center',
                    contain: true,
                    wrapAround: true,
                    autoPlay: true,
                    prevNextButtons: false
                });
                $('.carousel_right').flickity({
                    cellAlign: 'left',
                    wrapAround: true,
                    contain: true,
                    pageDots: false
                });
            })
        }
    },
    images: {
        lazyload: function () {
            $.getScript("/templates/default/js/jquery.lazyload.js", function () {
                $(".lazyload").lazyload({
                    effect: "fadeIn",
                    threshold: 200,
                    failurelimit: 15,
                    skip_invisible: !1
                })
            })
        },
    },
    common: {
        bootstrap: function () {
            $.getScript("/templates/default/js/bootstrap.min.js", function () {
                $('a[data-toggle="tab"]').on("shown.bs.tab", function (a) {
                    var b = $(a.target).text();
                    $(a.relatedTarget).text();
                    $("span.active-tab").html(b)
                })
            })
        },
        headroom: function () {
            $.getScript("/templates/default/js/headroom.min.js", function () {
                $("#header-top", function () {
                    (new Headroom(document.querySelector("#header-top"), {
                        tolerance: 5,
                        offset: 205,
                        classes: {
                            initial: "top-fixed",
                            pinned: "top-fixed-up",
                            unpinned: "top-fixed-down"
                        }
                    })).init()
                });
            })
        },
        autocomplete: function () {
            $('#wd').keyup(function () {
                var keywords = $(this).val();
                if (keywords == '') {
                    $('#word').hide();
                    return
                };
                $.ajax({
                    url: '/searchcomplete/video?keyword=' + keywords,
                    dataType: 'json',
                    beforeSend: function () {
                        $('#word').append('<div class="autocomplete-suggestion">正在加载。。。</div>');
                    },
                    success: function (data) {
                        $('#word').empty().show();
                        var item = data.suglist;
                        if (item.length==0) {
                            $('#word').append('<div class="autocomplete-suggestion">未找到与 "' + keywords + '"相关的结果</div>');
                        }
                        $.each(item,function (i,v) {
                            $('#word').append('<div class="autocomplete-suggestion">' + v + '</div>');
                        })
                    },
                    error: function () {
                        $('#word').empty().show();
                        $('#word').append('<div class="autocomplete-suggestion">查找"' + keywords + '"失败</div>');
                    }
                })
            })

            $(document).on('click', '.autocomplete-suggestion', function () {
                var word = $(this).text();
                $('#wd').val(word);
                $('#word').hide();
                $('#submit').trigger('click');
            })

            var clear = function () {
                $('#word').hide();
            }
            $("input").blur(function () {
                setTimeout(clear, 500);
            })
        },
        collapse: function () {
            $(".detail").on("click", ".detail-more", function () {
                $detailContent = $(".detail-content");
                $detailSketch = $(".detail-sketch");
                "none" == $detailContent.css("display") ? ($(this).html('\u6536\u8d77 <i class="icon iconfont icon-less"></i>'), $detailContent.show(), $detailSketch.hide()) : ($(this).html('\u8be6\u60c5 <i class="icon iconfont icon-moreunfold"></i>'), $detailContent.hide(), $detailSketch.show())
            })
        },
        scrolltop: function () {
            var a = $(window);
            $scrollTopLink = $("a.backtop");
            a.scroll(function () {
                500 < $(this).scrollTop() ? $scrollTopLink.css("display", "block") : $scrollTopLink.css("display", "none")
            });
            $scrollTopLink.on("click", function () {
                $("html, body").animate({
                    scrollTop: 0
                }, 400);
                return !1
            })
        }
    }
};

$(document).ready(function () {
    $.ajaxSetup({
        cache: true
    });
    if (stui.browser.useragent.mobile) {
        stui.mobile.slide();
        stui.mobile.popup();
        stui.mobile.share();
    }
    stui.flickity.carousel();
    stui.images.lazyload();
    stui.common.bootstrap();
    stui.common.headroom();
    stui.common.collapse();
    stui.common.autocomplete();
    stui.common.scrolltop();
    $('#submit').click(function () {
        let wd = $('#wd').val();
        if(wd==""){
            alert("请输入关键字");
            return false;
        }
        let url = "/search/video/" + wd + ".html";
        window.location = url;
    })
    $('#wd').keypress(function (e) {
        if (e.which == 13) {
            let wd = $('#wd').val();
            if(wd==""){
                alert("请输入关键字");
                return false;
            }
            let url = "/search/video/" + wd + ".html";
            window.location = url;
        }
    });
});
