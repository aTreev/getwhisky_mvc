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
    });

    $(".product-menu-close").click(function(){
        $(".product-menu").removeClass("product-menu-mobile-show");
        $(".page-overlay").hide();
    })
    
    $(window).resize(function(){
        if ($(".product-menu").hasClass("product-menu-mobile-show")) {
            $(".product-menu").removeClass("product-menu-mobile-show");  
        }
        
    })
    
   
}