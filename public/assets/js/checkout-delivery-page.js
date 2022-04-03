
prepareDeliverypage();
function prepareDeliverypage()
{
    prepareAddAddress();
    prepareAddressSelection();
}



function prepareAddAddress()
{
    const openAddFormBtn = $("#add-address-show");
    const cancelAddBtn = $("#cancel-add");
    const addForm = $("#add-address-form");
    
    openAddFormBtn.click(function(){
        $(".delivery-root").hide();
        $(".add-address-root").addClass("show");
    });

    cancelAddBtn.click(function(ev){
        ev.preventDefault();
        
        $(".add-address-root").removeClass("show");
        setTimeout(() => {
            $(".delivery-root").show();
            addForm[0].reset();
            resetFeedback(addForm[0]);
        }, 500);
    });

    addForm.submit(function(ev){
        ev.preventDefault();
        const data = new FormData($(this)[0]);

        const formValid = validate($(this)[0], 
        {
            'identifier': { 'required' : true, 'type' : "text", 'max' : 90 },
            'recipient': { 'required' : true, 'type' : 'text', 'max' : 90 },
            'postcode' : { 'required' : true, 'type' : "postcode", 'max' : 10 },
            'line1' : { 'required' : true, 'type' : "text", 'max' : 100 },
            'line2' : { 'required' : false, 'type' : 'text', 'max' : 100 },
            'city' : { 'required' : true, 'type' : "text", 'max' : 50 },
            'county' : { 'required' : false, 'type' : "text", 'max' : 50 }
        });
 
        if (!formValid) return;

        addAddress(data, "cart").then(function(result){
            if (result.success == 0) return new Notification (false, result.message);

            if (result.success == 1) {
                new Notification(true, result.message);
                $(".add-address-root").removeClass("show");
                setTimeout(() => {
                    $("#page-root").html(result.html);
                    $(".delivery-root").show();
                    prepareDeliverypage();
                    addForm[0].reset();
                    resetFeedback(addForm[0]);
                }, 500);
            }

            if (result.invalid) {
                Object.entries(result.invalid).forEach(entry => {
                    const [key, value] = entry;
                    feedback(addForm[0].querySelector(`[name=${value.input}]`), value.message);
                });
            }
        });
    });
 
}

/**************
 * Prepares the address selection functionality by highlighting the selected address,
 * transferring the address id to the form input and creating the submit button to 
 * proceed to checkout
 **************************************/
function prepareAddressSelection()
{
    const addresses = $(".address-item");
    const proceedForm = $("#address-form");
    const addressInputField = $("#selected-address");

    //
    addresses.each(function(){
        const addressid = $(this).attr("address-id");

        $(this).click(function(){
            // highlight selected address
            addresses.removeClass("selected");
            $(this).addClass("selected");
            // transfer id to form field
            addressInputField.val(addressid);
            // append submit button
            $("#proceed-to-payment").remove();
            proceedForm.append("<button class='btn btn-success' id='proceed-to-payment' style='font-weight:600;font-size:14px;'>Proceed to payment &#62;</button>")
        })
    });
}