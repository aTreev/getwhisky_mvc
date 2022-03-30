/**********
 * Global variables
 ********/
/*******
 * @offset integer
 *  used to retrieve products by pagination
 *  initial value is the number of elements with class='product-c' loaded on the page
 *********/
 var offset = document.getElementsByClassName("product-c").length;

 /*********
  * @category || @subcategory string
  *  id of the current category / subcategory
  *****/
 if ($("#category-id").get(0)) {
     var cat_subcat_id = $("#category-id").val();
     var ajaxFunction = 1;
 }
 if ($("#subcategory-id").get(0)) {
     var cat_subcat_id = $("#subcategory-id").val();
     var ajaxFunction = 2;
 }
     
 
 
 var sortOption = null;
 
 /**********
  * Initial function call - handles all functions necessary for the page
  *****************************/
 function prepareCategoryPage()
 {
     handlePagination();
     handleSortingOptions();
     handleFilterBarPosition();
 
     $("body").click(function(ev){
         if ($(ev.target).is("#open-filters")) {
            openFilters();
         }
         if ($(ev.target).is(".page-overlay") || $(ev.target).is("#close-filters")) {
            closeFilters();
         }
     });
 
     $(window).scroll(handlePagination);
     $(window).scroll(handleFilterBarPosition);
 }
 
 function openFilters()
 {
    $("#filter-root").addClass("show-filters");
    $(".page-overlay").show();
    $("body").addClass("disable-scroll-y")
 }

 function closeFilters()
 {
    $("#filter-root").removeClass("show-filters");
    $(".page-overlay").hide();
    $("body").removeClass("disable-scroll-y")
 }

 /************
  * Positions filter bar as either fixed or relative depending on
  * device width and scroll
  ************************************/
 function handleFilterBarPosition() {
    var filterBar = $('#filter-bar'); 
    var isPositionFixed = (filterBar.css('position') == 'fixed');

    if ($(window).width() < 735) {
        if ($(this).scrollTop() > 400 && !isPositionFixed){ 
            filterBar.css({'position': 'fixed', 'top': '0px', 'z-index': '14'}); 
        }       
    }
    if ($(this).scrollTop() < 400 && isPositionFixed){
        filterBar.css({'position': 'relative'}); 
    } 
 }

 
 /**********
  * Adds event listeners to the sort buttons
  * Sets the global sorting option when a sort button is click
  * Resets product offset and performs fresh products retrieval.  
  ******/
  function handleSortingOptions()
  {
      $("[name=sort-option]").click(function() {
          // Hide filters
          closeFilters();
          // set global sort option
          sortOption = $(this).attr("id");
          // Reset product offest to 0
          offset = 0;
          // Disable scroll function in case of multiple button clicks
          $(window).off('scroll', handlePagination);

          // Disable buttons until products loaded
          $("[name=sort-option]").off();
  
          // show loader image
          $("#product-root").html("<img src='/assets/loader.gif' style='display:block;margin:auto;' />");

          // set random delay max 500ms
          setTimeout(() => {
              // Load products
              loadMoreProducts()
              .then(function(result){
                  $("#product-root").html(result.html);
                  offset = result.newOffset;
                  $("#product-count").text($(".product-c").length);
                  // Re-enable sorting button
                  handleSortingOptions();
                  // Enable
                  $(window).scroll(handlePagination);
                });    
          }, (Math.random() * 500));
      });
  }
 
 
 /**************
  * retrieves additional products with pagination
  * via AJAX
 ********/
 function loadMoreProducts()
 {
     return new Promise(function(resolve){
         $.ajax({
             url: "/assets/js/ajax-scripts/category-handler.php",
             method: "POST",
             data: { function: ajaxFunction, catOrSubcatId: cat_subcat_id, offset: offset, sortOption: sortOption}
         })
         .done(function(result){
             //console.log(result);
             resolve(JSON.parse(result));
         });
     });
 
 }
 
 
 /**********
  * Detects scroll to bottom of page
  * Makes an AJAX call to retrieve additional
  * products when the user reaches bottom of the page
  * Appends html, updates product display count
  * and updates the pagination offset
  ***********/
 function handlePagination()
 {
   
    if((window.innerHeight + window.pageYOffset) >= document.body.offsetHeight-100) {
        // disable listener until after ajax resolves
        $(window).off('scroll', handlePagination);

        loadMoreProducts()
        .then(function(result){
            // Set html to retrieved
            $("#product-root").append(result.html)
            $("#product-count").html(document.getElementsByClassName("product-c").length);
            
            // update offset to new offset supplied via backend
            offset = result.newOffset;

            // Re-enable the listener if more products available
            if(!result.end_of_products) $(window).scroll(handlePagination());
        });
    }
 }
 
 prepareCategoryPage();
 