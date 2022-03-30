prepreLoginPage();

function prepreLoginPage()
{
    const loginForm = $("#login-form");

    loginForm.submit(function(ev){

        const valid = validate($(this)[0], {
            
            'email': { 'required' : true, 'type' : "email", 'max' : 80 },
            'password': { 'required' : true, 'type' : "text", 'max' : 72} 
        });

        console.log(valid);
        if (!valid) ev.preventDefault();;

    });
}