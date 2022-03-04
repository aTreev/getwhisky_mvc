
const subcategoryId = $("#subcategoryval-id").val();
let offset = document.getElementsByClassName("product-c").length;
let sortOption = "";

function prepareSubcategoryPage()
{
    handlePagination();
    handleFiltersDisplay();
    handleSortingOptions();

}


function handleFiltersDisplay()
{
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


/*********
 * Handles the asynchronous pagination
 * Calls the ajax script when bottom of page is reached
 * shuts off once no more products are available using the end_of_products flag
 *******************/
function handlePagination()
{
    $(window).off();
    $(window).scroll(function() {
        if((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight-100) {
            // disable listener until after ajax resolves
            $(window).off();

            loadMoreProducts()
            .then(function(result){
                // append product html
                $("#product-root").append(result.html);
                // update offset
                offset = result.offset;
                $("#product-count").text($(".product-c").length);
                // If more products to load, recursive call
                if (result.end_of_products == false) {
                    handlePagination();
                }
            });
            
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
                offset = result.offset;
                $("#product-count").text($(".product-c").length);
                // Recursive call to add listeners
                handleSortingOptions();
                handlePagination();
            });    
        }, 500);
    });
}

/*******
 * Loads products using the file's global values
 *****************************/
function loadMoreProducts()
{
    return new Promise(function(resolve){
        $.ajax({
            url: "/assets/js/ajax-scripts/subcategory-handler.php",
            method: "POST",
            data: {function: 1, subcatvalueid: subcategoryId, offset: offset, sortOption: sortOption}
        })
        .done(function(result){
            resolve(JSON.parse(result));
        });
    })
    
}
prepareSubcategoryPage();