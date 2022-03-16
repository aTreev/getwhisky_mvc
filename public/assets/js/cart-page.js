function prepareCartPage()
{
    prepareRemoveFromCart();
}


function prepareRemoveFromCart()
{
    $(".cart-item").each(function(){
        const productid = $(this).attr("product-id");
        const removeFromCartBtn = $(`#remove-item-${productid}`);

        removeFromCartBtn.click(function(){
            removeFromCart(productid)
            .then(function(result) {
                $("#page-root").html(result.html);
                new Notification(result.result, result.message);
                prepareCartPage();
            });
        });
    });
}

function removeFromCart(productid)
{
    return new Promise(function(resolve){
        $.ajax({
            url: "/assets/js/ajax-scripts/cart-handler.php",
            method: "POST",
            data: {function: 2, productid: productid}
        })
        .done(function(result){
            resolve(JSON.parse(result));
        })
    });
}

prepareCartPage();