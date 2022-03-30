function prepareCartPage()
{
    prepareRemoveFromCart();
    prepareUpdateCartQuantity();

    $("#clear-notifications").click(function(){
        $(".cart-notifications").remove();
    });
}


/***********
 * Prepares each cart item's remove button to remove the item.
 * Receives updated html from backend
 ****************/
function prepareRemoveFromCart()
{
    $(".cart-item").each(function(){
        const productid = $(this).attr("product-id");
        const removeFromCartBtn = $(`#remove-item-${productid}`);

        removeFromCartBtn.click(function(){
            removeFromCart(productid)
            .then(function(result) {
                if (result.html) $("#page-root").html(result.html);
                if (result.cartCount || result.cartCount == 0) $("#cart-count-number").html(result.cartCount);
                if (result.message) new Notification(result.result, result.message);
                prepareCartPage();
            });
        });
    });
}

/**********
 * Prepares each cart item's select field to update the item quantity
 * on change.
 * Receieves updated html via backend
 *****************/
function prepareUpdateCartQuantity()
{
    $(".cart-item").each(function(){
        const productid = $(this).attr("product-id");
        const updateQuantitySelect = $(`#quantity-selector-${productid}`);

        updateQuantitySelect.change(function(){
            updateCartItemQuantity(productid, $(this).val())
            .then(function(result){
                if (result.html) $("#page-root").html(result.html);
                if (result.cartCount) $("#cart-count-number").html(result.cartCount);
                if (result.message) new Notification(result.result, result.message);
                prepareCartPage();
            })
        })
    });
}

function updateCartItemQuantity(productid, quantity)
{
    return new Promise(function(resolve){
        $.ajax({
            url: "/assets/js/ajax-scripts/cart-handler.php",
            method: "POST",
            data: {function: 3, productid: productid, quantity: quantity}
        })
        .done(function(result){
            console.log(result);
            resolve(JSON.parse(result));
        })
    })
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