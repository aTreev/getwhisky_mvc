const UK_POSTCODE_REGEX = /^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/;
const UK_MOBILE_REGEX = /((\+44(\s\(0\)\s|\s0\s|\s)?)|0)7\d{3}(\s)?\d{6}/;


/*************
 * Function to be called when validating the form
 * @form form - The form to be validated
 * @options object - Object of options required|type|max
 * 
 * 
 * Example usage
 * validate(form,
 *  {email: {required: true, type: email, max: 80},
 * });
 */
function validate(form, options={})
{
    const formData = new FormData(form);
    let formValid = true;
    


    // Reset the invalidity marks
    resetFeedback(form);
   

    // Iterate through the first level of the options object
    for (const [input, validationOptions] of Object.entries(options)) {
        const inputName = input;

        const required = (validationOptions.required) ? validationOptions.required : false;
        const type = (validationOptions.type) ? validationOptions.type : "text";
        const max = (validationOptions.max) ? validationOptions.max : 9999;
       
        
        // Check the field for validity
        const valid = check(inputName, formData.get(inputName), required, type, max);

        // If field not valid add feedback to form and set valid to false
        if (!valid.valid) {
            feedback(form.querySelector(`[name=${inputName}`), valid.message);
            formValid = false;
        }
    }

    // Return whether form is valid or not
    return formValid;
}


/************
 * Checks the value against the specified options
 * constructs an error message and returns invalid if any fail
 */
function check(inputName, value, required, type, max)
{
    // Required check
    if (required == true && (value == null || value == "")) return {'inputName': inputName,'valid': false, 'message': `${inputName} is required` }

    // Type checks
    if (type == "text" && typeof(value) != "string") return {'inputName': inputName,'valid' : false, 'message' : `${inputName} must be text`}

    if (type == "number" && typeof(value) != "number") return {'inputName': inputName,'valid' : false, 'message' : `${inputName} must be a number`}

    if (type == "postcode" && !value.toUpperCase().match(UK_POSTCODE_REGEX)) return {'inputName' : inputName, 'valid': false, 'message': `Postcode must be a valid UK postcode`}
    
    if ((type == "mobile" && !value.match(UK_MOBILE_REGEX)) && ((required && value == "") || (!required && value != ""))) return {'inputName' : inputName, 'valid': false, 'message' : "Mobile number must be a valid UK mobile number"}
    
    // Max check
    if (value.length > max) return {'valid' : false, 'message' : `${inputName} must be less than ${max} characters`}
    
    // Additional checks here


    return {'valid' : true, 'message' : ""};
}





/**********
 * @input input - the input receiving feedback
 * @message string - the message appended to the input
 **********/
function feedback(input, message)
{
    input.style.borderColor = "red";
    input.insertAdjacentHTML("afterend", `<div class='form-feedback text-danger'>${message}</div>`);
}


/***********
 * Removes feedback marks from a form
 * @form form - the form to be reset
 *********************/
function resetFeedback(form)
{
    // Remove feedback messages
    form.querySelectorAll(".form-feedback").forEach(e => e.remove());

    // Remove red border from all form fields
    form.querySelectorAll("input[type=text]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=number]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=name]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=tel]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=street]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=postcode]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=city]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=county]").forEach(e => e.style.borderColor = "#ced4da");
}