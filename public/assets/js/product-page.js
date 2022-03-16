function prepareProductPage() 
{
    prepareSaleTimer();
    prepareTabs();
    prepareProductImages();
    prepareAddToCart();
}

function prepareSaleTimer()
{
    if ($("#discount-endtime").length == 0) return;
    const discountEndDatetime = new Date(parseInt($("#discount-endtime").attr("end"))).getTime()*1000;

    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = discountEndDatetime - now;
        // Time calculations for days, hours, minutes and seconds
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
          
        // Output the result in an element with id="demo"
        document.getElementById("discount-endtime").innerHTML = days + "d : " + hours + "h : "
        + minutes + "m : " + seconds + "s ";
          
        // If the count down is over, write some text 
        if (distance < 0) {
          clearInterval(x);
          document.getElementById("discount-endtime").innerHTML = "EXPIRED";
        }
      }, 1000);
}

function prepareTabs() 
{
    // Show first tab by default
    $("#tab-details").show();

    // Collect all tab buttons
    const tabButtons = $(".tabs button");

    // Iterate through them
    tabButtons.each(function(){
        // Add click event
        $(this).click(function() {
            // Get ID of tab to display
            const tabToDisplay = $(this).attr("tab");
            // Add active class to clicked tab
            tabButtons.removeClass("active");
            $(this).addClass("active");
            // Show correct content
            $(".tab-content").hide();
            $(`#${tabToDisplay}`).show();
        });
    });
}


/**********
 * Adds the interactive functionality to product page images
 * 
 */
function prepareProductImages()
{
    // Get gallery images
    const images = $("[name=gallery-image]");

    // Add click events to each
    images.each(function() {
        $(this).click(function() {
            // Remove selected class from all and add to clicked
            images.removeClass("gallery-image-selected");
            $(this).addClass("gallery-image-selected");
            // Swap main img src with clicked image src
            $("#main-image").attr("src", $(this).attr("src"));
        });
    });


    // Main image click
    $("#main-image").click(function() {
        // Populate the modal img src
        $("#popup-image").attr("src", $(this).attr("src"));
        // Add transition class
        $("#popup-image").addClass("popup-image-show");
        // show overlay
        $(".page-overlay").show();
        

        // Overlay click remove src to hide modal
        $(".page-overlay").click(function(){
            $("#popup-image").removeClass("popup-image-show");
            setTimeout(() => {
                $("#popup-image").attr("src", "");
                $(this).hide();
                $(this).off();
            }, 300);
            
        });
        // Trigger overlay click on escape
        $(document).on("keydown",function(ev){
            if (ev.keyCode == 27) {
                $(".page-overlay").click();
                $(this).off();
            }
        });
    });
}


/**************
 * Prepares the page's add to cart button
 */
function prepareAddToCart()
{
    const productid = $("#product-id").val();
    const addToCartBtn = $("#add-to-cart");

    if (addToCartBtn.hasClass("out-of-stock")) return console.log("out of stock");

    addToCartBtn.click(function(){
        const quantity = $("#quantity").val();

        addToCart(productid, quantity)
        .then(function(result){
            new Notification(result.result, result.message);
            if (result.cartCount) $("#cart-count-number").html(result.cartCount);
        });        
    });
}


function addToCart(productid, quantity)
{
    return new Promise(function(resolve){
        $.ajax({
            url: "/assets/js/ajax-scripts/cart-handler.php",
            method: "POST",
            data: { function: 1, productid: productid, quantity: quantity}
        })
        .done(function(result){
            resolve(JSON.parse(result));
        });
    });
}
prepareProductPage();