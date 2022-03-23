function prepareAddressPage()
{
    const addresses = $(".address-item");

    addresses.each(function(){
        const id = $(this).attr("address-id");
        const editContainer = $(`#edit-${id}`)
        const editForm = document.querySelector(`#edit-form-${id}`);
        const editBtn = $(`#edit-btn-${id}`);
        const deleteBtn = $(`#delete-btn-${id}`);
        const cancelEditBtn = $(`#cancel-edit-btn-${id}`);
        const updateAddressBtn = $(`#update-btn-${id}`);

        editBtn.click(function(){
            editContainer.addClass("address-item-edit-show");
        });

        cancelEditBtn.click(function(ev){
            ev.preventDefault();
            editContainer.removeClass("address-item-edit-show");
        })

        updateAddressBtn.click(function(ev){
            ev.preventDefault();

            const formValid = validate(editForm, 
            {
                'identifier': { 'required' : true, 'type' : "text", 'max' : 90 },
                'recipient': { 'required' : true, 'type' : 'text', 'max' : 90 },
                'postcode' : { 'required' : true, 'type' : "postcode", 'max' : 10 },
                'mobile' : {'required' : false, 'type' : "mobile", 'max' : 12 },
                'line1' : { 'required' : true, 'type' : "text", 'max' : 100 },
                'line2' : { 'required' : false, 'type' : 'text', 'max' : 100 },
                'city' : { 'required' : true, 'type' : "text", 'max' : 50 },
                'county' : { 'required' : false, 'type' : "text", 'max' : 50 }
            });


            if (formValid) {
                const formData = new FormData(editForm);
                // attempt address update on valid form
                updateAddress(formData).then(function(result){
                    
                    if (result.success == 0) return new Notification (false, result.message);

                    if (result.invalid) {
                        Object.entries(result.invalid).forEach(entry => {
                            const [key, value] = entry;
                            feedback(editForm.querySelector(`[name=${value.input}]`), value.message);
                          });
                    }

                    if (result.success) {
                        new Notification(true, `Address "${formData.get("identifier")}" updated succesfully`);
                        $("#page-root").html(result.html);
                        prepareAddressPage();
                    }

                });
            }
        });

        deleteBtn.click(function(){
            const formData = new FormData(editForm);
            if (!confirm(`are you sure you want to delete address ${formData.get('identifier')}`)) return;
            console.log("delete");
        });
    });

    
}

function updateAddress(formData)
{
    return new Promise(function(resolve){
        formData.append("function", 1);
        $.ajax({
            url: "/assets/js/ajax-scripts/address-handler.php",
            method: "POST",
            data: {
                function: 1, address_id: formData.get("address-id"), identifier: formData.get("identifier"), 
                recipient: formData.get("recipient"), postcode: formData.get("postcode"), mobile: formData.get("mobile"), 
                line1: formData.get("line1"), line2: formData.get("line2"), city: formData.get("city"), county: formData.get("county")
            },
            
        })
        .done(function(result){
            resolve(JSON.parse(result));
        });
    });
}

prepareAddressPage();