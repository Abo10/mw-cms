
(function ($) {
    "use strict";
    $('.fadeInOnLoad').css('opacity', 0);
    $('#loading').on('click', function () {
        $("#loading").fadeOut();
    });
    $(window).load(function () {
        $("#loading").fadeOut();
        $("#loading .object").delay(700).fadeOut("slow");
        $('.fadeInOnLoad').delay(700).fadeTo("slow", 1);
        bodyScrollAnimation()
    })
    function bodyScrollAnimation() {
        var scrollAnimate = $('body').data('scroll-animation');
        if (scrollAnimate === true) {
            new WOW().init()
        }
    }

    $('body').scrollspy({target: '#main-navbar', offset: 100});
    $('nav a[href^="#"]:not([href="#"]), .back_to_top, .explore').on('click', function (event) {
        var $anchor = $(this);
        $('html, body').stop().animate({scrollTop: $($anchor.attr('href')).offset().top - 70}, 1500);
        event.preventDefault();
    });
    $(window).on('scroll', function (e) {
        var scroll = $(window).scrollTop();
        // alert(1)
        if (scroll >= 90) {
            $(".navbar-default").addClass("is-scrolling");
        } else {
            $(".navbar-default").removeClass("is-scrolling");
        }

        // if (scroll >= 500) {
        //     $(".navbar-default").addClass("is-scrolling-2");
        // } else {
        //     $(".navbar-default").removeClass("is-scrolling-2");
        // }
    });

    if ($('#BGVideo').length) {
        $("#BGVideo").mb_YTPlayer();
    }
    if ($('.video').length) {
        $('.video').magnificPopup({
            type: 'iframe',
            iframe: {
                markup: '<div class="mfp-iframe-scaler">' + '<div class="mfp-close"></div>' + '<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>' + '</div>',
                patterns: {
                    youtube: {index: 'youtube.com/', id: 'v=', src: '//www.youtube.com/embed/%id%?autoplay=1'},
                    vimeo: {index: 'vimeo.com/', id: '/', src: '//player.vimeo.com/video/%id%?autoplay=1'},
                    gmaps: {index: '//maps.google.', src: '%id%&output=embed'}
                },
                srcAction: 'iframe_src',
            }
        });
    }

    $(document).on('click', '#packages .featured-img', function () {
        var html = $(this).closest('.item').find('.popup_item').html()
        $('#myModal .modal-body').html(html)
        $('#myModal').modal()
    })

    $('.gallery').each(function () {
        $('.gallery').magnificPopup({delegate: 'a', type: 'image', gallery: {enabled: true}, mainClass: 'mfp-fade'});
    });
    if ($('.quanity').length) {
        $('.quanity').TouchSpin({
            verticalbuttons: true,
            verticalupclass: 'glyphicon glyphicon-plus',
            verticaldownclass: 'glyphicon glyphicon-minus'
        });
    }
    if ($('.selectpicker').length) {
        $('.selectpicker').selectpicker();
    }
    $('.feature-note .plus-icon .plus').on('click', function () {
        if ($(this).parents('.feature-note').hasClass('show-cont')) {
            $(this).parents('.feature-note').removeClass('show-cont')
        } else {
            $(this).parents('.feature-note').addClass('show-cont')
        }
    });
    $('.flip-contact-box').on('click', function () {
        if (!$('.flip-box-container').hasClass('show-form')) {
            $('.flip-box-container').addClass('show-form')
        }
    });
    $('.js-close-flip').on('click', function () {
        $('.flip-box-container').removeClass('show-form');
    });
    if ($.fn.validator) {
        $.validator.setDefaults({
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            }, unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            }, errorPlacement: function (error, element) {
            }
        });
    }
    if ($.fn.validator) {
        $("#paypal-regn").validate({
            rules: {
                first_name: "required",
                last_name: "required",
                email: {required: true, email: true},
                os0: "required",
                quantity: "required",
                agree: "required"
            },
            messages: {
                first_name: "Your first name",
                last_name: "Your last name",
                email: "We need your email address",
                os0: "Choose your Pass",
                quantity: "How many seats",
                agree: "Please accept our terms and privacy policy"
            },
            submitHandler: function (form) {
                $("#reserve-btn").attr("disabled", true);
                form.submit();
            }
        });
    }
    var dataexitpopuop = $('body').data('exit-modal');
    if ($('#exit-modal').length && dataexitpopuop === true) {
        var _ouibounce = ouibounce($('#exit-modal')[0], {
            aggressive: true, timer: 0, callback: function () {
            }
        });
        $('body').on('click', function () {
            $('#exit-modal').hide();
        });
        $('#exit-modal .modal-footer').on('click', function () {
            $('#exit-modal').hide();
        });
        $('#exit-modal .exit-modal').on('click', function (e) {
            e.stopPropagation();
        });
    }



    var owl = $('#pack_carousel')
    owl.owlCarousel({
        nav: true,
        navRewind: false,
        loop: true,
        responsive: {
            0: {
                items: 1,
                dots: true,
                nav: false
            },
            760: {
                items: 3,
                dots: false
            },
            1030: {
                items: 3,
                dots: false
            }
        }
    })
    var owl = $('#colours_carousel')
    owl.owlCarousel({
        nav: false,
        navRewind: false,
        loop: false,
        responsive: {
            0: {
                items: 1,
                dots: true,
                nav: false
            },
            760: {
                items: 3,
                dots: false
            }
        }
    })

    $('#slide_prev').on('click', function () {
        $('#pack_carousel').trigger('owl.prev')
        console.log(owl)
    })
    $('#slide_next').on('click', function () {
        $('#pack_carousel').trigger('owl.next')
        console.log(owl)
    })
    $('.ft-block').hover(function () {
        var block = this;
        $(this).find('.n-hover').fadeOut(300, function () {
            $(block).find('.ft-hover').fadeIn(300)
        })
    }, function () {
        var block = this;
        $(this).find('.ft-hover').fadeOut(300, function () {
            $(block).find('.n-hover').fadeIn(300)
        })
    })

    // var block_coords = [
    //     0,
    //     $('#features').offset().top - 80,
    //     $('#use_cases').offset().top - 80,
    //     $('#specs').offset().top - 80,
    //     $('#faq').offset().top - 80,
    //     $('#contact').offset().top - 80
    // ]
    // var coord_maping = [
    //     'undef_class',
    //     'features',
    //     'use_cases',
    //     'specs',
    //     'faq',
    //     'undef_class'
    // ]
    // console.log(block_coords);
    var changed = 0;
    // $(window).scroll(function () {
    //
    //     console.log(block_coords)
    //     var coord = $(window).scrollTop();
    //     var active = null;
    //     console.log(coord)
    //     $.each(block_coords, function (i, v) {
    //         if (coord >= v && coord < block_coords[i+1])
    //         {
    //             active  = i;
    //             return false
    //         }
    //     })
    //     if (typeof active === 'number' && active != changed) {
    //         changed = active;
    //         block_coords = [
    //             0,
    //             $('#features').offset().top-80,
    //             $('#use_cases').offset().top-80,
    //             $('#specs').offset().top-80,
    //             $('#faq').offset().top-80,
    //             $('#contact').offset().top-80
    //
    //         ]
    //         $('#main-navbar').find('.nav a').removeClass('bordered');
    //         $('a[href="#'+coord_maping[active]+'"]').addClass('bordered')
    //     }
    //
    // })
    $('#packages .featured-img').on('click', function () {
        // alert(1)
    })
    $('#packages .item a').on('click', function (e) {
        e.preventDefault();
        $('#subscr_modal').modal('show')
    })
    $('.subscribe-block button').on('click', function () {
        var email = $(this).closest('.subscribe-block').find('input').val();
        var callback = $(this).closest('.subscribe-block').data('callback');
        $('#subscr_modal').modal('hide')
        $.ajax({
            url: location.origin + '/ajax',
            type: 'POST',
            data: {
                email: email,
                action: 'save_subscription'
            },
            success: function (data) {
                if (data == '1') {
                    // $('#subscr_modal').modal('hide')
                    $('#subscr_success_modal').modal('show')
                } else {
                    $('#err_modal').modal()
                    $('#err_modal .msg').html(data)
                    if(callback == '1'){
                        $('#err_modal').on('hidden.bs.modal', function () {
                            $('#subscr_modal').modal('show')
                        })
                    }
                }
            }
        })
    })
    $('#js-contact-btn').on('click', function () {
        var your_name = $("#org_c_form").find('[name=your-name]').val();
        var your_email = $("#org_c_form").find('[name=your-email]').val();
        var your_message = $("#org_c_form").find('[name=your-message]').val();
        $.ajax({
            url: location.origin + '/ajax',
            type: 'POST',
            data: {
                your_name: your_name,
                your_email: your_email,
                your_message: your_message,
                action: 'send_contact_form'
            },
            success: function (data) {
                if (data == '1') {
                    // $('#subscr_modal').modal('hide')
                    $('#contact_modal').modal('show')
                    $("#org_c_form").find('[name=your-name]').val('');
                    $("#org_c_form").find('[name=your-email]').val('');
                    $("#org_c_form").find('[name=your-message]').val('');
                } else {
                    $('#err_modal').modal()
                    $('#err_modal .msg').html(data)
                }
            }
        })
    })
    setTimeout(function () {
        if(window.location.hash) {
            var scr = document.body.scrollTop;
            document.body.scrollTop = scr-70;
            console.log(scr)
        }
    }, 1000)


})(jQuery);

window.mobilecheck = function () {
    var check = false;
    // (function (a) {
    //     if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true;
    // })(navigator.userAgent || navigator.vendor || window.opera);
    if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        check = true;
    }
    return check;
};

// 2. This code loads the IFrame Player API code asynchronously.
var tag = document.createElement('script');
//    tag.setAttribute('async', true);
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

// 3. This function creates an <iframe> (and YouTube player)
//    after the API code downloads.
var player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        height: screen.width * (3/4),
        playerVars: { 'showinfo': 0 , 'autohide':1, 'controls': 0 },
        width: screen.width,
//            height: 349,
//            width: 560,
        videoId: 'lkRVw3Aslg4',
//            events: {
//                'onReady': onPlayerReady,
//                'onStateChange': onPlayerStateChange
//            }
    });

}
if (!window.mobilecheck()) {
    $(window).scroll(function (e) {
        var header_height = $('#header').height();
        var video_block = $('#video_block').height();
        var scroll = $(window).scrollTop();
        if (scroll >= header_height * 0.6 && scroll < header_height + video_block * 0.6) {
            triggered_video = true;
            setTimeout(function () {
                if (triggered_video)
                    player.playVideo();
            }, 500)
        } else {
            triggered_video = false;
            player.pauseVideo();
        }
    })
} else {
    $('#video-cover').hide();
}