$(document).ready(function () {
    $('.mobileB').on('click',function () {
        $('.navButtons').fadeToggle();
    });

    $('.exitButton').on('click',function () {
        $(this).parent().fadeOut(150);
    });

    let nav = $('.nMenu');
    $(window).scroll(function () {
        $(this).scrollTop() > 100 ? nav.addClass('f-nav') : nav.removeClass('f-nav');
    });

    checkMobileM();
    slideLine();

    $(window).resize(function () {
        checkMobileM();
        slideLine();
    });
    function checkMobileM() {
			$('.navButtons')[$(window).width() < 820 ?'hide':'show']();
			console.log($(window).width() < 820);
			console.log('hi');
    }
    // ----------------------slidershow!---------------------------
    let $imgW = $('.imgW'),
        $singleImgW = $imgW.children('.sildepage'),
        $slideLength = $singleImgW.length - 1,
        $img = $('.imgA'),
        $imgAW = $img.width(),
        $imgAH = $img.height(),
        orgX = $imgW.css("margin-right");

    $singleImgW.width($imgAW).height($imgAH);
    let $sildeTotalWidth = $imgAW * $slideLength * -1 + "px";
        //$imgWM = $imgW.css('margin-right');

    $('.back').on('click',function () {
        let $imgWM = $imgW.css('margin-right');
        if ($imgWM === '0px') {
            $imgW.css("margin-right", $sildeTotalWidth);
            $imgW.animate({
                marginRight: '+=' + $imgAW
            }, 500);

        } else {
            $imgW.animate({
                marginRight: '+=' + $imgAW
            }, 500);
        }
    });
    $('.next').on('click',function () {
        let $imgWM = $imgW.css('margin-right');
        if ($imgWM === $sildeTotalWidth) {
            $imgW.css("margin-right", orgX);
            $imgW.animate({
                marginRight: '-=' + $imgAW
            }, 500);
        } else {
            console.log("forward");
            $imgW.animate({
                marginRight: '-=' + $imgAW
            }, 500);
        }
    });
    /*------------slideLine---------------*/
    function slideLine() {
        $('.slideLine').each(function () {
            let slideLineWidth = $(this).width(),
                oneItemWidth = slideLineWidth / 3;
            $(this).find('.slideLItem').css('min-width', oneItemWidth);

            let lineItemsNumber = $(this).find('.slideLItem').length,
                allSlideWidth = oneItemWidth * lineItemsNumber,
                centerMoving = -1 * (allSlideWidth / 3);
            $(this).children('.slideMoving').css('margin-right', centerMoving);
            $(this).children('.slideMoving').css('min-width', allSlideWidth);
            $(this).children('.sLPcontrolsR').on('click',function () {



                let slideMovingMar = $(this).parent().children('.slideMoving').css('margin-right'),
                    slideMovingMarsinPX = parseInt(slideMovingMar, 10) * -1;
                if (slideMovingMarsinPX !== slideLineWidth * 2) {
                    $(this).parent().children('.slideMoving').animate({
                        marginRight: '-=' + oneItemWidth
                    }, 500);
                }

            });
            $(this).children('.sLPcontrolsL').on('click',function () {

                let slideMovingMar = $(this).parent().children('.slideMoving').css('margin-right');
                let slideMovingMarsinPX = parseInt(slideMovingMar, 10) * -1;

                if (slideMovingMarsinPX !== 0) {
                    $(this).parent().children('.slideMoving').animate({
                        marginRight: '+=' + oneItemWidth
                    }, 500);
                }
            });

        });

    }

});
