prepreLoginPage();

function prepreLoginPage()
{
    const location = (window.location.href.includes("checkout")) ? "checkout" : "loginPage";
    const loginForm = $("#login-form");
    const emailField = $("#login_email");
    const passwordField = $("#login_password");

    loginForm.submit(function(ev){
        ev.preventDefault();
        
        const valid = validate($(this)[0], {
            'email': { 'required' : true, 'type' : "email", 'max' : 80 },
            'password': { 'required' : true, 'type' : "text", 'max' : 72} 
        });

        if (!valid) return;

        login(new FormData(loginForm[0]), location).then(function(result){
            if (result.authenticated == 0) {
                feedback(emailField[0], "");
                feedback(passwordField[0], result.message);
            }

            if (result.authenticated == 1) {
                window.location.href = result.redirectLocation;
            }
        });
    });
}