function prepareProductPage() 
{
    prepareSaleTimer();
    prepareTabs();
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


prepareProductPage();