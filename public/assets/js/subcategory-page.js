
const subcategoryId = $("#subcategoryval-id").val();
let offset = document.getElementsByClassName("product-c").length;
function prepareSubcategoryPage()
{
    handlePagination();
}


function handlePagination()
{
    $(window).scroll(function() {
        if((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight-100) {
            // disable listener until after ajax resolves
            $(window).off();

            loadMoreProducts().then(function(result){
                // append product html
                $("#product-root").append(result.html);
                // update offset
                offset = result.offset;
                // If more products to load, recursive call
                if (result.end_of_products == false) {
                    handlePagination();
                }
            });
            
        }
    });
}

function loadMoreProducts()
{
    return new Promise(function(resolve){
        $.ajax({
            url: "/assets/js/ajax-scripts/subcategory-handler.php",
            method: "POST",
            data: {function: 1, subcatvalueid: subcategoryId, offset: offset}
        })
        .done(function(result){
            resolve(JSON.parse(result));
        });
    })
    
}
prepareSubcategoryPage();