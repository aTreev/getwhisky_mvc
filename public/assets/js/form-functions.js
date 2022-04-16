const UK_POSTCODE_REGEX = /^[A-Z]{1,2}\d[A-Z\d]? ?\d[A-Z]{2}$/;
const UK_MOBILE_REGEX = /((\+44(\s\(0\)\s|\s0\s|\s)?)|0)7\d{3}(\s)?\d{6}/;
const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
const DATE_REGEX =  /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;


/*************
 * Function to be called when validating the form
 * @form form the form to be validated.
 * @options object object of options required|type|max
 * 
 * 
 * Example usage.
 * validate(form, {
 * email: {required: true, type: email, max: 80},
 * mobile: {required: true, type: mobile, max:10},
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
            feedback(form.querySelector(`[name=${inputName}]`), valid.message);
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
    
    if ((type == "mobile" && !value.match(UK_MOBILE_REGEX)) && ((required && value != "") || (!required && value != ""))) return {'inputName' : inputName, 'valid': false, 'message' : "Mobile number must be a valid UK mobile number"}
    
    if ((type == "email" && !value.match(EMAIL_REGEX)) && ((required && value != "") || (!required && value != ""))) return {'inputName' : inputName, 'valid' : false, 'message' : "Valid email format required"}

    if (type == "date" && !value.match(DATE_REGEX)) return {'inputName': inputName, 'valid': false, 'message': "Valid date format required"}

    // Max check
    if (value.length > max) return {'valid' : false, 'message' : `value must be less than ${max} characters`}
    
    // Additional checks here


    return {'valid' : true, 'message' : ""};
}





/**********
 * Adds feedback to a form input.
 * Requires the input to have a unique ID
 * @input input - the input receiving feedback
 * @message string - the message appended to the input
 **********/
function feedback(input, message)
{
    $(`#feedback-${input.getAttribute("id")}`).remove();
    input.style.borderColor = "red";
    input.insertAdjacentHTML("afterend", `<div class='form-feedback text-danger' id='feedback-${input.getAttribute("id")}'>${message}</div>`);
    $(".form-feedback")[0].scrollIntoView({block: "end", inline: "nearest"});
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
    form.querySelectorAll("input[type=email]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=password]").forEach(e => e.style.borderColor = "#ced4da");
    form.querySelectorAll("input[type=date]").forEach(e => e.style.borderColor = "#ced4da");

}


function checkPassword(passwordField, repeatPasswordField)
{
    const password = passwordField.value;
    const repeatPassword = repeatPasswordField.value;
    let valid = true;

    if (password == "") {
        feedback(passwordField, "Password is required");
        valid = false;
    }
    if (repeatPassword == "") {
        feedback(repeatPasswordField, "Repeat password is required");
        valid = false;    
    }

    if (password != repeatPassword) {
        feedback(passwordField, "");
        feedback(repeatPasswordField, "Passwords must match");
        valid = false;
    }

    if (password != "" && testPasswordStrength(passwordField) < 14) {
        feedback(passwordField, "Password does not meet minimum complexity");
        feedback(repeatPasswordField, "");
        valid = false;
    }

    return valid;
}

/**************************
 * Tests a password against regex to ensure that
 * a minimum complexity is met, takes in the password
 * input element as an argument.
 * Appends a password strength indicator to the password input
 * returns the password strength as float.
 ************************************************************/
 function testPasswordStrength(passwordField) {
    let password = passwordField.value;
    let feedbackcolour;
	let feedbackText;
	let textColour;
    // Get the total length of password, baseline strength of 1 for a 8 character password
    let passtr=(password.length<8)?1:password.length/2;
    // Use Regex to find what types of characters, symbols and numbers used
    let hassymbol=((/[-!£$%^&*()_+|~=`{}\[\]:";'<>?,.\/]/).test(password))?1.5:0;
    let hasnumeric=((/[0-9]/).test(password))?1.3:0;
    let hasupper=((/[A-Z]/).test(password))?1.2:0;
    let haslower=((/[a-z]/).test(password))?1.1:0;
    // Calculate the overall relative strength of the password
    passwordStrength=passtr*(hassymbol+hasnumeric+hasupper+haslower);
    
	// Cap for strong passwords
    if(passwordStrength>60) { passwordStrength=60; }
    // Yellow colour for medium strength passwords
    if(passwordStrength<22) { 
		feedbackcolour="#ffff8c"; 
		textColour="#929502";
		feedbackText="Better";
	}
    // Green colour for strong passwords
    if(passwordStrength>22) { 
		feedbackcolour="lightgreen"; 
		textColour="darkgreen";
		feedbackText = "Good";
	}
    // Red for weak
    if(passwordStrength<14) { 
		feedbackcolour="rgb(255, 110, 110)";
		textColour = "darkred";
		feedbackText="Weak";
	}

	$("#password-feedback").remove();
    if(password.length > 0) {
        passwordField.  insertAdjacentHTML("afterend", `<div id='password-feedback' style='margin-top:5px;margin-bottom:10px;padding: 10px;background-color:${feedbackcolour};width:${passwordStrength}em;max-width:100%;'><p style='color:${textColour};font-weight:600;font-size:15px;margin-bottom:0;'>${feedbackText}</p></div>`)
    }
    return passwordStrength;
}