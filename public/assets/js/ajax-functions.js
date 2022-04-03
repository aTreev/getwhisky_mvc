/******************
 * Address functions
 *******************/
 function updateAddress(formData)
 {
     return new Promise(function(resolve){
         $.ajax({
             url: "/assets/js/ajax-scripts/address-handler.php",
             method: "POST",
             data: {
                 function: 1, address_id: formData.get("address-id"), identifier: formData.get("identifier"), 
                 recipient: formData.get("recipient"), postcode: formData.get("postcode"),
                 line1: formData.get("line1"), line2: formData.get("line2"), city: formData.get("city"), county: formData.get("county")
             },
             
         })
         .done(function(result){
             resolve(JSON.parse(result));
         });
     });
 }
 
 function deleteAddress(addressid)
 {
     return new Promise(function(resolve){
         $.ajax({
             url: "/assets/js/ajax-scripts/address-handler.php",
             method: "POST",
             data: {
                 function: 2,
                 address_id: addressid
             }
         })
         .done(function(result){
             resolve(JSON.parse(result));
         });
     });
 }

 /*********
  * Takes formData and the calling page
  */
 function addAddress(formData, page)
 {
    //
    formData.append("page", page);

    return new Promise(function(resolve){
        $.ajax({
            url: "/assets/js/ajax-scripts/address-handler.php",
            method: "POST",
            data: {
                function: 3, address_id: formData.get("address-id"), identifier: formData.get("identifier"), 
                recipient: formData.get("recipient"), postcode: formData.get("postcode"), 
                line1: formData.get("line1"), line2: formData.get("line2"), city: formData.get("city"), county: formData.get("county"), page: page
            },
            
        })
        .done(function(result){
            console.log(result);
            resolve(JSON.parse(result));
        });
    });
 }