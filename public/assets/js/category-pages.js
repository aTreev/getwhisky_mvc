/**********
 * Global variables
 ********/
/*******
 * @offset integer
 *  used to retrieve products by pagination
 *  initial value is the number of elements with class='product-c' loaded on the page
 *********/
var offset = document.getElementsByClassName("product-c").length;

/*********
 * @category || @subcategory string
 *  id of the current category / subcategory
 *****/
if ($("#category-id").get(0)) {
    var cat_subcat_id = $("#category-id").val();
    var ajaxFunction = 1;
}
if ($("#subcategory-id").get(0)) {
    var cat_subcat_id = $("#subcategory-id").val();
    var ajaxFunction = 2;
}
    


var sortOption = null;

/**********
 * Initial function call - handles all functions necessary for the page
 *****************************/
function prepareCategoryPage()
{
    handlePagination();
    handleSortingOptions();


    $("body").click(function(ev){
        if ($(ev.target).is("#open-filters")) {
            $("#filter-root").addClass("show-filters");
            $(".page-overlay").show();
            $("body").addClass("disable-scroll-y")
        }
        if ($(ev.target).is(".page-overlay") || $(ev.target).is("#close-filters")) {
            $("#filter-root").removeClass("show-filters");
            $(".page-overlay").hide();
            $("body").removeClass("disable-scroll-y")
        }
    });

}

/**********
 * Adds event listeners to the sort buttons
 * Sets the global sorting option when a sort button is click
 * Resets product offset and performs fresh products retrieval.  
 ******/
 function handleSortingOptions()
 {
     // Sort button clicked
     $("[name=sort-option]").click(function(){
         // set global sort option
         sortOption = $(this).attr("id");
         // Reset product offest to 0
         offset = 0;
         
         // show loader image
         $("#product-root").html("<img src='/assets/loader.gif' style='display:block;margin:auto;' />");
 
         // Disable buttons until minimum 500ms delay
         $("[name=sort-option]").off();
 
         setTimeout(() => {
             // Load products
             loadMoreProducts()
             .then(function(result){
                 $("#product-root").html(result.html);
                 offset = result.newOffset;
                 $("#product-count").text($(".product-c").length);
                 // Recursive call to add listeners
                 handleSortingOptions();
                 handlePagination();
             });    
         }, (Math.random() * 500));
     });
 }


/**************
 * retrieves additional products with pagination
 * via AJAX
********/
function loadMoreProducts()
{
    return new Promise(function(resolve){
        $.ajax({
            url: "/assets/js/ajax-scripts/category-handler.php",
            method: "POST",
            data: { function: ajaxFunction, catOrSubcatId: cat_subcat_id, offset: offset, sortOption: sortOption}
        })
        .done(function(result){
            console.log(result);
            resolve(JSON.parse(result));
        });
    });

}


/**********
 * Detects scroll to bottom of page
 * Makes an AJAX call to retrieve additional
 * products when the user reaches bottom of the page
 * Appends html, updates product display count
 * and updates the pagination offset
 ***********/
function handlePagination()
{
    // Add listener to the page
    $(window).off();
    $(window).scroll(function() {
        if((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight-100) {
            // disable listener until after ajax resolves
            $(window).off();


                loadMoreProducts()
                .then(function(result){
                    $("#product-root").append(result.html)
                    $("#product-count").html(document.getElementsByClassName("product-c").length);
                    // update offset to new offset supplied via backend
                    offset = result.newOffset;
                    // recursive call to add the listener again once ajax request resolved
                    if(!result.end_of_products)handlePagination();
                });
            
        }
     });
}

prepareCategoryPage();
