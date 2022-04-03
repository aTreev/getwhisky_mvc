prepareAddressPage();
function prepareAddressPage()
{
    const addresses = $(".address-item");

    addresses.each(function(){
        const id = $(this).attr("address-id");
        const editContainer = $(`#edit-${id}`)
        const editForm = $(`#edit-form-${id}`);
        const editBtn = $(`#edit-btn-${id}`);
        const deleteBtn = $(`#delete-btn-${id}`);
        const cancelEditBtn = $(`#cancel-edit-btn-${id}`);
        const updateAddressBtn = $(`#update-btn-${id}`);

        editBtn.click(function(){
            if (editContainer.hasClass("show")) {
                editContainer.removeClass("show");
                $(this).text("EDIT")
            } else {
                editContainer.addClass("show");
                $(this).text("CANCEL");
            }
        });

        cancelEditBtn.click(function(ev){
            ev.preventDefault();
            editContainer.removeClass("show");
            editBtn.text("EDIT");
        })

        updateAddressBtn.click(function(ev){
            ev.preventDefault();

            const formValid = validate(editForm[0], 
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
            const formData = new FormData(editForm[0]);
            // attempt address update on valid form
            updateAddress(formData).then(function(result){
                if (result.success == 0) return new Notification (false, result.message);

                if (result.success == 1) {
                    $("#page-root").html(result.html);
                    prepareAddressPage();
                    new Notification(true, `Address "${formData.get("identifier")}" updated succesfully`);
                }

                if (result.invalid) {
                    Object.entries(result.invalid).forEach(entry => {
                        const [key, value] = entry;
                        feedback(editForm[0].querySelector(`[name=${value.input}]`), value.message);
                    });
                }
            });
            
        });

        deleteBtn.click(function(){
            const formData = new FormData(editForm[0]);
            if (!confirm(`are you sure you want to delete address ${formData.get('identifier')}`)) return;

            deleteAddress(id).then(function(result){
                if (result.success = 0) return new Notification(false, result.message);
                if (result.success = 1) {
                    new Notification(true, "Address deleted successfully");
                    $("#page-root").html(result.html);
                    prepareAddressPage();
                }
            });
        });
    });

    prepareAddAddress();
}




function prepareAddAddress()
{
    const openAddFormBtn = $(".add-address-btn");
    const cancelAddBtn = $("#cancel-add");
    const addForm = $("#add-address-form");

    openAddFormBtn.click(function(){
        $(".user-root").hide();
        $(".add-address-root").addClass("show");
    });

    cancelAddBtn.click(function(ev){
        ev.preventDefault();
        
        $(".add-address-root").removeClass("show");
        setTimeout(() => {
            $(".user-root").show();
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

        addAddress(data, "user").then(function(result){
            if (result.success == 0) return new Notification (false, result.message);

            if (result.success == 1) {
                new Notification(true, result.message);
                $(".add-address-root").removeClass("show");
                setTimeout(() => {
                    $("#page-root").html(result.html);
                    $(".user-root").show();
                    prepareAddressPage();
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
        
    })
}


