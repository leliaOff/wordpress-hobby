jQuery(window).mousemove(function(e) {
    let xpos = e.clientX * 2;
    let ypos = e.clientY * 2;
    if(window.window > 1366) {
        jQuery('#logo').css('top', ((30 + (ypos / 600)) + "px"));
        jQuery('#logo').css('left', ((50 + (xpos / 300)) + "px"));    
        jQuery('#header-arrow').css('bottom', ((20 + (ypos / 20)) + "px"));
        jQuery('#sky-1').css('bottom', ((30 - (ypos / 400)) + "px"));
        jQuery('#sky-1').css('right', ((40 - (xpos / 200)) + "px"));
        jQuery('#sky-2').css('bottom', ((230 + (ypos / 400)) + "px"));
        jQuery('#sky-2').css('right', ((0 + (xpos / 200)) + "px"));
        jQuery('#sky-3').css('bottom', ((80 + (xpos / 600)) + "px"));
        jQuery('#sky-3').css('right', ((340 + (ypos / 300)) + "px"));
    } else {
        jQuery('#logo').css('top', ((10 + (ypos / 400)) + "px"));
        jQuery('#logo').css('left', ((20 + (xpos / 200)) + "px"));    
        jQuery('#header-arrow').css('bottom', ((10 + (ypos / 140)) + "px"));
        jQuery('#sky-1').css('bottom', ((30 - (ypos / 200)) + "px"));
        jQuery('#sky-1').css('right', ((40 - (xpos / 100)) + "px"));
        jQuery('#sky-2').css('bottom', ((120 + (ypos / 200)) + "px"));
        jQuery('#sky-2').css('right', ((0 + (xpos / 100)) + "px"));
        jQuery('#sky-3').css('bottom', ((50 + (xpos / 300)) + "px"));
        jQuery('#sky-3').css('right', ((200 + (ypos / 150)) + "px"));
    } 
});

function scrollToElement(selector) {
    jQuery("html, body").animate({ scrollTop: jQuery(selector).offset().top }, 500);
}