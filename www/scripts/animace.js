$(document).ready(function() {

    // Nastavit pozici stránky na horní okraj
    window.scrollTo(0, 0);

    // Funkce pro zjištění, zda je blok viditelný na obrazovce
    function isElementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            (rect.top <= window.innerHeight && rect.bottom >= 0) &&
            (rect.left <= window.innerWidth && rect.right >= 0)
        );
    }

    var animationStartedBlock1 = false;
    var animationStartedBlock2 = false;
    var animationStartedBlock3 = false;
    var animationStartedBlock4 = false;
    var animationStartedBlock5 = false;
    
    function startAnimationTyping() {
        var i = 0;
        var text = 'Královská péče pro váš domov a zahradu';
        var speed = 50;
        var $element = $("#opening-cap");
        
        function typeWriter() {
            if (i < text.length) {
                $element.text(text.substring(0, i + 1));
                i++;
                setTimeout(typeWriter, speed);
            }
        }
        
        // Spustit animaci, když je dokument načten
        $(document).ready(function() {
            typeWriter();
        });
    }        

    // Funkce pro spuštění animace zprava
    function startAnimationServicesHeading() {
        $("#services-heading").css({
            transform: "scale(1)"
        });

        $("#services-heading").animate({
            opacity: 1
        }, 2000);
    }

    function startAnimationServicesCards() {
        $("#cards").css({
            transform: "scale(1)"
        });

        $("#cards").animate({
            opacity: 1
        }, 2000);
    }

    function startAnimationQualitiesImage() {
        $("#qualities-image").css({
            opacity: 1,
            transform: "scale(1)"
        }, 4000);
    }

    function startAnimationQualities() {
        $("#qualities").css({
            opacity: 1,
            transform: "scale(1)"
        }, 4000);
    }

    function startAnimationPillarsHeading() {
        $("#pillars-heading").css({
            opacity: 1,
            transform: "scale(1)"
        }, 3000);
    }

    function startAnimationLeftPillar() {
        $("#left-pillar").css({
            opacity: 1,
            transform: "scale(1)"
        }, 3000);
    }

    function startAnimationRightPillar() {
        $(".apostrofa").css("animation", "rotateApostrofa 1.5s linear infinite");

        $("#right-pillar").css({
            transform: "scale(1)"
        }, 2000);

        $("#right-pillar").animate({
            opacity: 1
        }, {
            duration: 3000,
            complete: function() {
                $(".apostrofa").css({
                    animation: "none"
                });
            }
        }, 2000);
    }

    function startAnimationCommentLeft() {
        $(".commentLeft").css({
            transform: "scale(1)"
        });

        $(".commentLeft").animate({
            opacity: 1,
            left: 0,
            bottom: 0
        }, 1000);
    }

    function startAnimationCommentRight() {
        setTimeout(function() {
            $(".commentRight").css({
                transform: "scale(1)"
            });

            $(".commentRight").animate({
                opacity: 1,
                bottom: 0
            }, {
                duration: 1000,
                complete:                     
                    function() {
                        $("#carouselExampleRide").attr("data-bs-ride", "carousel");
                        setTimeout(function() {
                        $("#carouselExampleRide").carousel(1);
                    }, 4000)
                }
            });
        }, 300);
    }

    function startAnimations() {
            if (isElementInViewport(document.getElementById('block3')) && !animationStartedBlock3) {
                startAnimationQualitiesImage();
                startAnimationQualities();
                animationStartedBlock3 = true;
            }
            if (isElementInViewport(document.getElementById('block4')) && !animationStartedBlock4) {
                startAnimationPillarsHeading();
                startAnimationLeftPillar();
                startAnimationRightPillar();
                animationStartedBlock4 = true;
            }
            if (isElementInViewport(document.getElementById('block5')) && !animationStartedBlock5) {
                startAnimationCommentLeft();
                startAnimationCommentRight();
                animationStartedBlock5 = true;
            }
    }

    setTimeout(function() {
        if (isElementInViewport(document.getElementById('block1')) && !animationStartedBlock1) {
            startAnimationTyping();
            animationStartedBlock1 = true;
        }
        if (isElementInViewport(document.getElementById('block2')) && !animationStartedBlock2) {
            startAnimationServicesHeading();
            startAnimationServicesCards()
            animationStartedBlock2 = true;
        }
        $(window).on('scroll', startAnimations);
    }, 720)
});
