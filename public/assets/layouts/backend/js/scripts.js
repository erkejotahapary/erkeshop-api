/*!
    * Start Bootstrap - SB Admin v6.0.3 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2021 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    (function($) {
    "use strict";

        // Add active state to sidbar nav links
        var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
        let breadcrumb = $('.my-breadcrumb');

        $("#layoutSidenav_nav .sb-sidenav a.nav-link").each(function() {

            const link      = this;
            const collapse   = $(link).parent().parent();
            const collapseId = $(collapse).attr('id');

            if(collapseId !== undefined) {
                if (link.href === path) {
                    const collapsed = $(`a[data-target="#${collapseId}"]`);
                    
                    $(collapsed).toggleClass('collapsed active');
                    $(collapse).addClass('show');
                    $(link).addClass("active");
                    
                    // Breadcrumb
                    const collapsedText = $(collapsed).text();
                    const linkText = $(link).text();
                    breadcrumb.append(`<li class="breadcrumb-item text-muted">${collapsedText}</li>`);
                    breadcrumb.append(`<li class="breadcrumb-item active" aria-current="page">${linkText}</li>`);
                }
            } else {
                if (link.href === path) {
                    $(link).addClass("active");
                }
            }
        });

    // Toggle the side navigation
    $("#sidebarToggle").on("click", function(e) {
        e.preventDefault();
        $("body").toggleClass("sb-sidenav-toggled");
    });


})(jQuery);
