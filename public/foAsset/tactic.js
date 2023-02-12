$(function() {
    
    /* --------------------------------------------------------------------------
        Manage active menu 
    -------------------------------------------------------------------------- */
    setActiveMenu()

    $(".has-sub-menu").click(function () {
        localStorage.setItem("main_menu_id", $(this).find('div').attr("id"));
    });
    
    $(".sub-menu").click(function () {
        localStorage.setItem("menu_sub_id", $(this).attr("id"));
    });
    
    $(".single-menu a").click(function () {
        localStorage.setItem("menu_sub_id", $(this).attr("id"));
        localStorage.removeItem("main_menu_id");
    });

    /* Fetch & active current menu from localstorage */
    function setActiveMenu()
    {
         const main_link = $("a").find("[aria-controls='" + localStorage.getItem("main_menu_id") + "']");
         const submenu = $("div").find("[id='" + localStorage.getItem("main_menu_id") + "']");
         const sub_link = $("#" + localStorage.getItem("menu_sub_id"));
         main_link.removeClass("collapsed").attr("aria-expanded", true);
         submenu.addClass("show");
         sub_link.addClass("active");
     }
     
}) ;