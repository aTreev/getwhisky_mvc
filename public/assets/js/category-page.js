/**********
 * Global variables
 ********/


/*******
 * @offset integer
 *  used to retrieve products by pagination
 *  initial value is the number of products loaded on the page
 *********/
let offset = document.getElementsByClassName("product").length;

/*********
 * @category string
 *  Name of the current category
 *****/
const category = $("#category").val();

function prepareCategoryPage()
{
    handlePagination();
}


function loadMoreProducts()
{
    console.log("fired");
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

function handlePagination()
{
    // Add listener to the page
    $(window).scroll(function() {
        if((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight-100) {
            // disable listener until after ajax resolves
            $(window).off();

            loadMoreProducts().then(function(result){
                // Append returned products view
                $("#product-root").append(result.html);
                // Update count to number of products on page
                $("#product-count").html(document.getElementsByClassName("product").length);
                offset = result.newOffset;
                // recursive call to add the listener again
                
                handlePagination();
            });
        }
     });
}

prepareCategoryPage();