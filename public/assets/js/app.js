function prepareApp() 
{
    prepareMenu();
}



/*************
 * Minimal JavaScript solution for a responsive menu
 **********************/
function prepareMenu()
{
    $("#product-menu-open").click(function(){
        $(".product-menu").addClass("product-menu-mobile-show");
        $(".page-overlay").show();
        $("body").addClass("disable-scroll-y");
    });

    $(".product-menu-close, .page-overlay").click(function(){
        $(".product-menu").removeClass("product-menu-mobile-show");
        $(".page-overlay").hide();
        $("body").removeClass("disable-scroll-y");
    });
    
    $(window).resize(function(){
        if ($(".product-menu").hasClass("product-menu-mobile-show")) {
            $(".product-menu").removeClass("product-menu-mobile-show");  
            $(".page-overlay").hide();
            $("body").removeClass("disable-scroll-y");
        } 
    });
}