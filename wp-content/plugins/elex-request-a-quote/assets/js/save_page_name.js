jQuery(window).on("load", function () {
    //store the current page url in redirect_page key to use it when close popup button is clicked
    if(location.href !== raq_save_page_obj.quote_page){
        localStorage.setItem('redirect_page' , location.href);
    }
});