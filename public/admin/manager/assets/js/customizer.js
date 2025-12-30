// Customizer


//*** Light & Dark action  ***//
$('.action-dark').on('click', function () {
    "use strict";
    $(this).toggleClass('action-light');
    $('.icon-dark').toggle('');
    $('.icon-light').toggle('');
    $('body').toggleClass('darkmode');
});


//*** Customizer Action ***//
$('.customizer-action').on('click', function () {
    "use strict";
    $('.theme-cutomizer , .customizer-layer').toggleClass('active');
});


$('.customizer-header').on('click', function () {
    "use strict";
    $('.theme-cutomizer , .customizer-layer').toggleClass('active');
});


$('.customizer-layer').on('click', function () {
    "use strict";
    $(this).removeClass('active');
    $('.theme-cutomizer').removeClass('active');
});

//*** Dark Mode ***//

$('.dark-action').on('click', function () {
    "use strict";
    $('body').addClass('darkmode');
    $('#layout_mode').val('darkmode');
});


$('.light-action').on('click', function () {
    "use strict";
    $('body').removeClass('darkmode');
    $('#layout_mode').val('lightmode');
});


$('.customizeoption-list li').on('click', function () {
    "use strict";
    $(this).addClass('active-mode')
    $(this).siblings().removeClass('active-mode');
});


//*** Direction Mode ***//

$('.ltr-action').on('click', function () {
    "use strict";
    $('body').removeClass('rtlmode');
    $('#layout_direction').val('ltrmode');
});

$('.rtl-action').on('click', function () {
    "use strict";
    $('body').addClass('rtlmode');
    $('#layout_direction').val('rtlmode');
});


//*** Sidebar Mode ***//

$('.sidebardark-action').on('click', function () {
    "use strict";
    $('.codex-sidebar').addClass('sidebar-dark');
    $('.codex-sidebar').removeClass('sidebar-gradient');
    $('#sidebar_mode').val('dark');
});

$('.sidebarlight-action').on('click', function () {
    "use strict";
    $('.codex-sidebar').removeClass('sidebar-dark');
    $('.codex-sidebar').removeClass('sidebar-gradient');
    $('#sidebar_mode').val('light');
});

$('.sidebargradient-action').on('click', function () {
    "use strict";
    $('.codex-sidebar').addClass('sidebar-gradient');
    $('#sidebar_mode').val('gradient');
});


//** Theme color mode  ***//
$('.themecolor-list').on('click', '.color1', function () {
    "use strict";
    $("#customstyle").attr("href", "assets/css/style.css");
    $('#theme_color').val('color1');
    return false;
});
$('.themecolor-list').on('click', '.color2', function () {
    "use strict";
    $("#customstyle").attr("href", "assets/css/color2.css");
    $('#theme_color').val('color2');
    return false;
});
$('.themecolor-list').on('click', '.color3', function () {
    "use strict";
    $("#customstyle").attr("href", "assets/css/color3.css");
    $('#theme_color').val('color3');
    return false;
});
$('.themecolor-list').on('click', '.color4', function () {
    "use strict";
    $("#customstyle").attr("href", "assets/css/color4.css");
    $('#theme_color').val('color4');
    return false;
});
$('.themecolor-list').on('click', '.color5', function () {
    "use strict";
    $("#customstyle").attr("href", "assets/css/color5.css");
    $('#theme_color').val('color5');
    return false;
});
$('.themecolor-list').on('click', '.color6', function () {
    "use strict";
    $("#customstyle").attr("href", "assets/css/color6.css");
    $('#theme_color').val('color6');
    return false;
});
