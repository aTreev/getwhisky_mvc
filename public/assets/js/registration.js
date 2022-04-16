function prepareRegistration()
{
    const location = (window.location.href.includes("checkout")) ? "checkout" : "registrationPage";

    const regForm = $("#regForm");
    const passwordField = $("#password");
    const repeatPasswordField = $("#repeat_password");
    const submitBtn = $("#reg_submit");

    passwordField.on("keyup", function() { testPasswordStrength($(this)[0]) } );

    regForm.submit(function(ev){
        ev.preventDefault();

        const detailsValid = validate(regForm[0], {
            'email' : {'required': true, 'type': "email", 'max':80},
            'firstname' : {'required': true, 'type': 'text', 'max':40},
            'surname': {'required': true, 'type': 'text', 'max': 40},
            'dob': {'required': true, 'type': 'date', 'max': 10},
        });
        
        const passwordValid = checkPassword(passwordField[0], repeatPasswordField[0]);

  
        if (!detailsValid || !passwordValid) return

        submitBtn.val("Please wait...");
        
        register(new FormData(regForm[0]), location).then(function(result){

            if (result.invalid) {
                Object.entries(result.invalid).forEach(entry => {
                    const [key, value] = entry;
                    feedback(regForm[0].querySelector(`[name=${value.input}]`), value.message);
                });
                submitBtn.val("Submit");   
            }

            if (result.success == 0) {
                new Notification(false, result.message);
                submitBtn.val("Submit");
            }

            if (result.success == 1) {
                window.location.href = result.redirectLocation;
            }
        });
    
    });
    
}

prepareRegistration();