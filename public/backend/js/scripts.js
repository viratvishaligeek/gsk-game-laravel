/*!
 * Start Bootstrap - SB Admin Pro v2.0.5
 * Copyright 2013-2023 Start Bootstrap
 * Licensed under SEE_LICENSE (https://github.com/StartBootstrap/sb-admin-pro/blob/master/LICENSE)
 */
(function ($, window, document) {
    'use strict';

    $(function () {
        if (window.feather && typeof window.feather.replace === 'function') {
            window.feather.replace();
        }

        // Initialize Bootstrap tooltips and popovers once.
        $('[data-bs-toggle="tooltip"]').each(function () {
            if (window.bootstrap && !window.bootstrap.Tooltip.getInstance(this)) {
                window.bootstrap.Tooltip.getOrCreateInstance(this);
            }
        });

        $('[data-bs-toggle="popover"]').each(function () {
            if (window.bootstrap && !window.bootstrap.Popover.getInstance(this)) {
                window.bootstrap.Popover.getOrCreateInstance(this);
            }
        });

        // Toggle the side navigation.
        $(document).on('click.adminLayout', '#sidebarToggle', function (event) {
            event.preventDefault();
            $('body').toggleClass('sidenav-toggled');
            try {
                window.localStorage.setItem(
                    'sb|sidebar-toggle',
                    $('body').hasClass('sidenav-toggled') ? 'true' : 'false'
                );
            } catch (error) {
                // Storage may be unavailable; the sidebar still works for this page.
            }
        });

        // Close the side navigation after selecting the content area on small screens.
        $('#layoutSidenav_content').on('click.adminLayout', function () {
            if (window.innerWidth < 992 && $('body').hasClass('sidenav-toggled')) {
                $('body').removeClass('sidenav-toggled');
            }
        });

        // Blade already marks route-aware navigation links as active. Retain the
        // original template's .html fallback for any legacy static admin links.
        var activatedPath = window.location.pathname.match(/([\w-]+\.html)/);
        activatedPath = activatedPath ? activatedPath[0] : 'index.html';

        $('.nav-link[href="' + activatedPath.replace(/"/g, '\\"') + '"]').each(function () {
            var $link = $(this);
            $link.addClass('active');

            $link.parents('.collapse').each(function () {
                var $collapse = $(this);
                $collapse.addClass('show');
                $('[data-bs-target="#' + $collapse.attr('id') + '"]')
                    .removeClass('collapsed')
                    .addClass('active');
            });
        });
    });
})(jQuery, window, document);
