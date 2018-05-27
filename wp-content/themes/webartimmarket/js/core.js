jQuery(window).mousemove(function(e) {
    let xpos = e.clientX * 2;
    let ypos = e.clientY * 2;
    jQuery('#logo').css('top', ((0 + (ypos / 150)) + "px"));
    jQuery('#logo').css('left', ((0 + (xpos / 180)) + "px"));    
});