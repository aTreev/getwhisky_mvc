let offset = 0;
const category = $("#category").val();

function prepareCategoryPage()
{
    handlePagination();
}


function loadMoreProducts()
{
    offset += 20
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
                $("#product-root").append(result);
                // recursive call to add the listener again
                handlePagination();
            });
        }
     });
}

prepareCategoryPage();