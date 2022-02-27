/**********
 * Global variables
 ********/
/*******
 * @offset integer
 *  used to retrieve products by pagination
 *  initial value is the number of elements with class='product-c' loaded on the page
 *********/
let offset = document.getElementsByClassName("product-c").length;

/*********
 * @category string
 *  Name of the current category
 *****/
const category = $("#category").val();

let filters = [];

let paginateFilteredProducts = false;

/**********
 * Initial function call - handles all functions necessary for the page
 *****************************/
function prepareCategoryPage()
{
    handlePagination();
    
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

    prepareProductFilters();
}

// resets the pagination, freshly retrieves products
//TODO: Change filters to limit to only products that match all inputs
function prepareProductFilters()
{
    $("[name=filter]").click(function(){
        // reset html and offset on filter click
        $("#product-root").html("");
        offset = 0;

        if ($(this).prop("checked")) {
            filters.push($(this).val())
        } else {
            let index = filters.indexOf($(this).val());
            filters.splice(index, 1);
        }


        // load filtered products
        if (filters.length > 0) {
            loadFilteredProducts(filters)
            .then(function(result){
                $("#product-root").html(result.html)
                $("#product-count").html(document.getElementsByClassName("product-c").length);
                offset = result.newOffset;
                // Set the pagination to load more filtered products
                paginateFilteredProducts = true;
            });
        }
        else {
            // Load non filter products
            loadMoreProducts().then(function(result){
                $("#product-root").append(result.html)
                $("#product-count").html(document.getElementsByClassName("product-c").length);
                offset = result.newOffset;
                // Set the pagination to load more non-filtered products
                paginateFilteredProducts = false;
            })
        }
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
            data: { function: 1, category: category, offset: offset}
        })
        .done(function(result){
            resolve(JSON.parse(result));
        });
    });

}

function loadFilteredProducts(filters)
{
    return new Promise(function(resolve){
        $.ajax({
            url: "/assets/js/ajax-scripts/category-handler.php",
            method: "POST",
            data: {function: 2, filters: filters, category: category, offset: offset}
        })
        .done(function(result){
            resolve(JSON.parse(result));
        });
    })
    
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
    $(window).scroll(function() {
        if((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight-100) {
            // disable listener until after ajax resolves
            $(window).off();

            // Check whether to retrieve filtered or non-filtered products
            if (!paginateFilteredProducts) {
                loadMoreProducts()
                .then(function(result){
                    // Append returned products view
                    $("#product-root").append(result.html);
                    // Update count to number of products on page
                    $("#product-count").html(document.getElementsByClassName("product-c").length);
                     // update offset to new offset supplied via backend   
                    offset = result.newOffset;
                    // recursive call to add the listener again once ajax request resolved
                    if(result.html)handlePagination();
                });
            } else {
                loadFilteredProducts(filters)
                .then(function(result){
                    $("#product-root").append(result.html)
                    $("#product-count").html(document.getElementsByClassName("product-c").length);
                    // update offset to new offset supplied via backend
                    offset = result.newOffset;
                    // recursive call to add the listener again once ajax request resolved
                    if(result.html)handlePagination();
                });
            }
            
        }
     });
}

prepareCategoryPage();
