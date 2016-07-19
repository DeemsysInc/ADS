/* =========================================================
 Sharing Buttons
 ============================================================ */
jQuery(window).load(function() {
    if (jQuery('.share_block').length === 1) {
        jQuery.ajax({
            type: 'POST',
            url: kopa_front_variable.ajax.url,
            dataType: 'html',
            async: true,
            data: {
                action: 'kopa_sharing_button',
                wpnonce: jQuery('#kopa_sharing_button_wpnonce').val(),
                pid: kopa_front_variable.template.post_id
            },
            beforeSend: function(XMLHttpRequest, settings) {
            },
            complete: function(XMLHttpRequest, textStatus) {
            },
            success: function(data) {
                if (data.length > 0) {
                    jQuery('.share_block').html(data).show();
                } else {
                    jQuery('.share_block').remove();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    }
});
/* =========================================================
 Flickr Feed
 ============================================================ */

jQuery(document).ready(function() {
    var flickrs = jQuery('.flickr-wrap');
    if (flickrs.length > 0) {
        jQuery.each(flickrs, function() {
            var ID = jQuery(this).find('.flickr_id').val();
            var limit = parseInt(jQuery(this).find('.flickr_limit').val());
            jQuery(this).jflickrfeed({
                limit: limit,
                qstrings: {
                    id: ID
                },
                itemTemplate:
                        '<li class="flickr-badge-image">' +
                        '<a target="_blank" href="{{link}}" title="{{title}}">' +
                        '<img src="{{image_s}}" alt="{{title}}" width="96px" height="96px" />' +
                        '</a>' +
                        '</li>'
            }, function(data) {
            });
        });
    }
});
/* =========================================================
 Like Button
 ============================================================ */
function kopa_like_button_click(obj, pid) {
    if (!obj.hasClass('inprocess')) {
        var status = obj.hasClass('kopa_like_button_enable') ? 'enable' : 'disable';
        jQuery.ajax({
            type: 'POST',
            url: kopa_front_variable.ajax.url,
            dataType: 'json',
            async: false,
            data: {
                action: 'kopa_change_like_status',
                wpnonce: jQuery('#kopa_change_like_status_wpnonce').val(),
                pid: pid,
                status: status
            },
            beforeSend: function(XMLHttpRequest, settings) {
                obj.addClass('inprocess');
            },
            complete: function(XMLHttpRequest, textStatus) {
            },
            success: function(data) {
                obj.parent().find('.kopa_like_count').html(data.total);
                obj.removeClass('kopa_like_button_' + status);
                obj.addClass('kopa_like_button_' + data.status);
                obj.removeClass('inprocess');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    }
    return false;
}


/* =========================================================
 Remove empty paragraph
 ============================================================ */
jQuery(document).ready(function() {
    /*    
     jQuery('p').filter(function() {       
     return jQuery.trim(jQuery(this).text()) === '' && jQuery(this).children().length == 0        
     }).remove();
     */
});
/* =========================================================
 Set view count (post, page, portfolio)
 ============================================================ */
jQuery(document).ready(function() {
    if (kopa_front_variable.template.post_id > 0) {
        jQuery.ajax({
            type: 'POST',
            url: kopa_front_variable.ajax.url,
            dataType: 'json',
            async: true,
            timeout: 5000,
            data: {
                action: 'kopa_set_view_count',
                wpnonce: jQuery('#kopa_set_view_count_wpnonce').val(),
                post_id: kopa_front_variable.template.post_id
            },
            beforeSend: function(XMLHttpRequest, settings) {
            },
            complete: function(XMLHttpRequest, textStatus) {
            },
            success: function(data) {
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    }
});
/* =========================================================
 Comment Form
 ============================================================ */
jQuery(document).ready(function() {
    if (jQuery("#comments-form").length > 0) {
        // get front validate localization
        var validateLocalization = kopa_custom_front_localization.validate;

        jQuery('#comments-form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                message: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: validateLocalization.name.required,
                    minlength: jQuery.format(validateLocalization.name.minlength)
                },
                email: {
                    required: validateLocalization.email.required,
                    email: validateLocalization.email.email
                },
                url: {
                    required: validateLocalization.url.required,
                    url: validateLocalization.url.url
                },
                message: {
                    required: validateLocalization.message.required,
                    minlength: jQuery.format(validateLocalization.message.minlength)
                }
            }
        });
    }

    if (jQuery("#contact-form").length > 0) {
        // get front validate localization
        var validateLocalization = kopa_custom_front_localization.validate;

        jQuery('#contact-form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                message: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                name: {
                    required: validateLocalization.name.required,
                    minlength: jQuery.format(validateLocalization.name.minlength)
                },
                email: {
                    required: validateLocalization.email.required,
                    email: validateLocalization.email.email
                },
                url: {
                    required: validateLocalization.url.required,
                    url: validateLocalization.url.url
                },
                message: {
                    required: validateLocalization.message.required,
                    minlength: jQuery.format(validateLocalization.message.minlength)
                }
            },
            submitHandler: function(form) {
                jQuery("#submit-contact").attr("value", validateLocalization.form.sending);
                jQuery(form).ajaxSubmit({
                    success: function(responseText, statusText, xhr, $form) {
                        jQuery("#response").html(responseText).hide().slideDown("fast");
                        jQuery("#submit-contact").attr("value", validateLocalization.form.submit);
                        jQuery(form).find('[name=name]').val('');
                        jQuery(form).find('[name=email]').val('');
                        jQuery(form).find('[name=url]').val('');
                        jQuery(form).find('[name=message]').val('');
                    }
                });
                return false;
            }
        });
    }
});
/* =========================================================
 Sub menu
 ==========================================================*/
/*jQuery(document).ready(function() {
 jQuery('#main-menu').superfish();
 });
 */
/* =========================================================
 Mobile menu
 ============================================================ */
jQuery(document).ready(function() {

    jQuery('#mobile-menu > span').click(function() {
        var mobile_menu = jQuery('#toggle-view-menu');
        if (mobile_menu.is(':hidden')) {
            mobile_menu.slideDown('300');
            jQuery(this).children('span').html('-');
        } else {
            mobile_menu.slideUp('300');
            jQuery(this).children('span').html('+');
        }
        jQuery(this).toggleClass('active');
    });
    jQuery('#toggle-view-menu li').click(function() {
        var text = jQuery(this).children('div.menu-panel');
        if (text.is(':hidden')) {
            text.slideDown('300');
            jQuery(this).children('span').html('-');
        } else {
            text.slideUp('300');
            jQuery(this).children('span').html('+');
        }
    });
});
/* =========================================================
 Home page slider
 ============================================================ */
jQuery(function() {
    var eislider = jQuery('.ei-slider'),
        sliderAnimation = eislider.data('animation'),
        sliderAutoplay = eislider.data('autoplay') ? true : false,
        sliderSlidershowInterval = eislider.data('slideshow_interval'),
        sliderSpeed = eislider.data('speed'),
        sliderTitlesFactor = eislider.data('titlesfactor'),
        sliderTitleSpeed = eislider.data('titlespeed');

    if (eislider.length > 0) {
        jQuery.each(eislider, function() {
            jQuery(this).eislideshow({
                animation: sliderAnimation,
                autoplay: sliderAutoplay,
                slideshow_interval: sliderSlidershowInterval,
                speed: sliderSpeed,
                titlesFactor: sliderTitlesFactor,
                titlespeed: sliderTitleSpeed
            });
        });
    }
});
jQuery(document).ready(function() {
    init_image_effect();
});
jQuery(window).resize(function() {
    init_image_effect();
});
function init_image_effect() {

    var view_p_w = jQuery(window).width();
    var pp_w = 500;
    var pp_h = 344;
    if (view_p_w <= 479) {
        pp_w = '120%';
        pp_h = '100%';
    }
    else if (view_p_w >= 480 && view_p_w <= 599) {
        pp_w = '100%';
        pp_h = '170%';
    }

    jQuery("a[rel^='prettyPhoto']").prettyPhoto({
        show_title: false,
        deeplinking: false,
        social_tools: false,
        default_width: pp_w,
        default_height: pp_h
    });
}

// moving gallery preview on eislider when mouse hover
jQuery(document).ready(function() {
    jQuery('.gallery-eislider-preview').each(function() {
        var $this = jQuery(this),
            $imageHover = $this.find('.hover-effect');

        $this.css({
            width: $imageHover.length * 63
        })
        
    });
});

/* =========================================================
 Timeline Filter
 ============================================================ */

function kp_filter_click(obj) {
    var timeline_filter = jQuery('#ss-links');
    if (timeline_filter.is(':hidden')) {
        timeline_filter.slideDown('300');
    } else {
        timeline_filter.slideUp('300');
    }
    jQuery(obj).toggleClass('active');
}
function kp_filter_li_click(obj) {
    jQuery('#ss-links').slideUp('300');

    var idName = jQuery(obj).attr('href');
    var idNameFixTop = jQuery(idName).offset().top;
    jQuery('html, body').animate({scrollTop: idNameFixTop}, 1000);
}
/* =========================================================
 Fix css
 ============================================================ */
jQuery(document).ready(function() {
    jQuery(".list-container-1 ul li:last-child").css("margin-right", 0);
    jQuery("#sidebar .widget ul li:last-child").css("margin-bottom", 0);
    jQuery(".pagination ul > li:last-child").css("margin-right", 0);
    jQuery("#main-col .widget .older-post li:last-child").css("margin-bottom", 0);
    jQuery("#sidebar .widget .list-container-2 ul li:last-child").css({"margin-right": 0, "width": 100});
    jQuery("#sidebar .widget .tab-content-2 ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0});
    jQuery("#bottom-sidebar ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".article-list li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 40});
    jQuery(".kp-cat-2 .article-list li:last-child").css("margin-bottom", 10);
    jQuery("#main-col .widget-area-5 .widget ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".kp-filter ul.ss-links li:last-child a").css("border-bottom", 'none');
    jQuery(".isotop-header #filters li:last-child a").css("border-bottom", 'none');
    jQuery(".sidebar .widget ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-2 .widget_archive ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-2 .widget_categories ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-2 .widget_recent_comments ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-2 .widget_recent_entries ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-2 .widget_rss ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-2 .widget_pages ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-2 .widget_meta ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-2 .widget_nav_menu ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-3 .widget_archive ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-3 .widget_categories ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-3 .widget_recent_comments ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-3 .widget_recent_entries ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-3 .widget_rss ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-3 .widget_pages ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-3 .widget_meta ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-3 .widget_nav_menu ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-4 .widget_archive ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-4 .widget_categories ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-4 .widget_recent_comments ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-4 .widget_recent_entries ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-4 .widget_rss ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-4 .widget_pages ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-4 .widget_meta ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-4 .widget_nav_menu ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-5 .widget_archive ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-5 .widget_categories ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-5 .widget_recent_comments ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-5 .widget_recent_entries ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-5 .widget_rss ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-5 .widget_pages ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-5 .widget_meta ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
    jQuery(".widget-area-5 .widget_nav_menu ul li:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
	jQuery(".widget-area-1 .entry-item:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
	jQuery(".widget-area-10 .entry-item:last-child").css({"border-bottom": "none", "padding-bottom": 0, "margin-bottom": 0});
	});
/* ---------------------------------------------------------------------- */
/*	Portfolio Filter
 /* ---------------------------------------------------------------------- */
// modified Isotope methods for gutters in masonry
jQuery.Isotope.prototype._getMasonryGutterColumns = function() {
    var gutter = this.options.masonry && this.options.masonry.gutterWidth || 0;
    containerWidth = this.element.width();
    this.masonry.columnWidth = this.options.masonry && this.options.masonry.columnWidth ||
            // or use the size of the first item
            this.jQueryfilteredAtoms.outerWidth(true) ||
            // if there's no items, use size of container
            containerWidth;
    this.masonry.columnWidth += gutter;
    this.masonry.cols = Math.floor((containerWidth + gutter) / this.masonry.columnWidth);
    this.masonry.cols = Math.max(this.masonry.cols, 1);
};
jQuery.Isotope.prototype._masonryReset = function() {
    // layout-specific props
    this.masonry = {};
    // FIXME shouldn't have to call this again
    this._getMasonryGutterColumns();
    var i = this.masonry.cols;
    this.masonry.colYs = [];
    while (i--) {
        this.masonry.colYs.push(0);
    }
};
jQuery.Isotope.prototype._masonryResizeChanged = function() {
    var prevSegments = this.masonry.cols;
    // update cols/rows
    this._getMasonryGutterColumns();
    // return if updated cols/rows is not equal to previous
    return (this.masonry.cols !== prevSegments);
};
jQuery(function() {
    // cache container
    var kp_columnWidth_4 = get_colunm_width_4();
    var jQuerycontainer = jQuery('.kp-pf-3col #pf-items');
// initialize isotope
    jQuerycontainer.isotope({
        itemSelector: '.element',
        resizable: false,
        masonry: {
            columnWidth: kp_columnWidth_4,
            gutterWidth: 25
        }
    });
});
jQuery(function() {
    // cache container
    var kp_columnWidth_3 = get_colunm_width_3();
    var jQuerycontainer = jQuery('.kp-pf-2col #pf-items');
// initialize isotope
    jQuerycontainer.isotope({
        itemSelector: '.element',
        resizable: false,
        masonry: {
            columnWidth: kp_columnWidth_3,
            gutterWidth: 25
        }
    });
});
jQuery(function() {
    // cache container
    var kp_columnWidth_2 = get_colunm_width_2();
    var jQuerycontainer = jQuery('.kp-pf-1col #pf-items');
// initialize isotope
    jQuerycontainer.isotope({
        itemSelector: '.element',
        resizable: false,
        masonry: {
            columnWidth: kp_columnWidth_2,
            gutterWidth: 25
        }
    });
});
jQuery(function() {
    var kp_columnWidth_1 = get_colunm_width_1();
    var jQuerycontainer_1 = jQuery('#portfolio-items');
    // initialize isotope
    jQuerycontainer_1.isotope({
        itemSelector: '.element',
        resizable: false,
        masonry: {
            columnWidth: kp_columnWidth_1,
            gutterWidth: 15
        }
    });
});
jQuery(window).smartresize(function() {
    var kp_columnWidth_4 = get_colunm_width_4();
    var jQuerycontainer_4 = jQuery('.kp-pf-3col #pf-items');
    jQuerycontainer_4.isotope({
        // update columnWidth to a percentage of container width
        masonry: {columnWidth: kp_columnWidth_4}
    });
});
jQuery(window).smartresize(function() {
    var kp_columnWidth_3 = get_colunm_width_3();
    var jQuerycontainer_3 = jQuery('.kp-pf-2col #pf-items');
    jQuerycontainer_3.isotope({
        // update columnWidth to a percentage of container width
        masonry: {columnWidth: kp_columnWidth_3}
    });
});
jQuery(window).smartresize(function() {
    var kp_columnWidth_2 = get_colunm_width_2();
    var jQuerycontainer_2 = jQuery('.kp-pf-1col #pf-items');
    jQuerycontainer_2.isotope({
        // update columnWidth to a percentage of container width
        masonry: {columnWidth: kp_columnWidth_2}
    });
});
function get_colunm_width_1() {
    var view_port_w;
    var kp_colunm_width_1 = 255;
    if (self.innerWidth !== undefined)
        view_port_w = self.innerWidth;
    else {
        var D = document.documentElement;
        if (D)
            view_port_w = D.clientWidth;
    }

    if (view_port_w >= 1024 && view_port_w <= 1043) {
        kp_colunm_width_1 = 210;
    }
    else if (view_port_w >= 980 && view_port_w < 1023) {
        kp_colunm_width_1 = 196;
    }
    else if (view_port_w >= 768 && view_port_w < 979) {
        kp_colunm_width_1 = 237;
    }
    else if (view_port_w >= 640 && view_port_w < 767) {
        kp_colunm_width_1 = 189;
    }
    else if (view_port_w >= 600 && view_port_w < 639) {
        kp_colunm_width_1 = 271;
    }
    else if (view_port_w >= 480 && view_port_w < 599) {
        kp_colunm_width_1 = 212;
    }
    else if (view_port_w >= 360 && view_port_w < 479) {
        kp_colunm_width_1 = 152;
    }
    else if (view_port_w <= 359) {
        kp_colunm_width_1 = 142;
    }
    return kp_colunm_width_1;
}
function get_colunm_width_4() {
    var view_port_w;
    var kp_colunm_width_4 = 248;
    if (self.innerWidth !== undefined)
        view_port_w = self.innerWidth;
    else {
        var D = document.documentElement;
        if (D)
            view_port_w = D.clientWidth;
    }

    if (view_port_w >= 1024 && view_port_w <= 1043) {
        kp_colunm_width_4 = 203;
    }
    else if (view_port_w >= 980 && view_port_w < 1023) {
        kp_colunm_width_4 = 190;
    }
    else if (view_port_w >= 768 && view_port_w < 979) {
        kp_colunm_width_4 = 232;
    }
    else if (view_port_w >= 640 && view_port_w < 767) {
        kp_colunm_width_4 = 183;
    }
    else if (view_port_w >= 600 && view_port_w < 639) {
        kp_colunm_width_4 = 267;
    }
    else if (view_port_w >= 480 && view_port_w < 599) {
        kp_colunm_width_4 = 207;
    }
    else if (view_port_w >= 360 && view_port_w < 479) {
        kp_colunm_width_4 = 147;
    }
    else if (view_port_w <= 359) {
        kp_colunm_width_4 = 130;
    }
    return kp_colunm_width_4;
}

function get_colunm_width_3() {
    var view_port_w;
    var kp_colunm_width_3 = 385;
    if (self.innerWidth !== undefined)
        view_port_w = self.innerWidth;
    else {
        var D = document.documentElement;
        if (D)
            view_port_w = D.clientWidth;
    }

    if (view_port_w >= 1024 && view_port_w <= 1043) {
        kp_colunm_width_3 = 317;
    }
    else if (view_port_w >= 980 && view_port_w < 1023) {
        kp_colunm_width_3 = 297;
    }
    else if (view_port_w >= 768 && view_port_w < 979) {
        kp_colunm_width_3 = 232;
    }
    else if (view_port_w >= 640 && view_port_w < 767) {
        kp_colunm_width_3 = 287;
    }
    else if (view_port_w >= 600 && view_port_w < 639) {
        kp_colunm_width_3 = 267;
    }
    else if (view_port_w >= 480 && view_port_w < 599) {
        kp_colunm_width_3 = 207;
    }
    else if (view_port_w >= 360 && view_port_w < 479) {
        kp_colunm_width_3 = 147;
    }
    else if (view_port_w <= 359) {
        kp_colunm_width_3 = 130;
    }
    return kp_colunm_width_3;
}

function get_colunm_width_2() {
    var view_port_w;
    var kp_colunm_width_2 = 795;
    if (self.innerWidth !== undefined)
        view_port_w = self.innerWidth;
    else {
        var D = document.documentElement;
        if (D)
            view_port_w = D.clientWidth;
    }

    if (view_port_w >= 1024 && view_port_w <= 1043) {
        kp_colunm_width_2 = 660;
    }
    else if (view_port_w >= 980 && view_port_w < 1023) {
        kp_colunm_width_2 = 620;
    }
    else if (view_port_w >= 768 && view_port_w < 979) {
        kp_colunm_width_2 = 490;
    }
    else if (view_port_w >= 640 && view_port_w < 767) {
        kp_colunm_width_2 = 600;
    }
    else if (view_port_w >= 600 && view_port_w < 639) {
        kp_colunm_width_2 = 560;
    }
    else if (view_port_w >= 480 && view_port_w < 599) {
        kp_colunm_width_2 = 440;
    }
    else if (view_port_w >= 360 && view_port_w < 479) {
        kp_colunm_width_2 = 320;
    }
    else if (view_port_w <= 359) {
        kp_colunm_width_2 = 300;
    }
    return kp_colunm_width_2;
}

// filter items when filter link is clicked
var optionSets = jQuery('#options .option-set'),
        optionLinks = optionSets.find('a');
var jQuerycontainer = jQuery('#portfolio-items');
optionLinks.click(function() {
    // don't proceed if already selected
    if (jQuery(this).hasClass('selected')) {
        return false;
    }
    var optionSet = jQuery(this).parents('.option-set');
    optionSet.find('.selected').removeClass('selected');
    jQuery(this).addClass('selected');
    // make option object dynamically, i.e. { filter: '.my-filter-class' }
    var options = {},
            key = optionSet.attr('data-option-key'),
            value = jQuery(this).attr('data-option-value');
    // parse 'false' as false boolean
    value = value === 'false' ? false : value;
    options[ key ] = value;
    if (key === 'layoutMode' && typeof changeLayoutMode === 'function') {
        // changes in layout modes need extra logic
        changeLayoutMode($this, options)
    } else {
        // otherwise, apply new options
        jQuerycontainer.isotope(options);
    }

    return false;
});
var optionSets = jQuery('#pf-options .pf-option-set'),
        optionLinks = optionSets.find('a');
var jQuerycontainer_pf = jQuery('#pf-items');
optionLinks.click(function() {
    // don't proceed if already selected
    if (jQuery(this).hasClass('selected')) {
        return false;
    }
    var optionSet = jQuery(this).parents('.pf-option-set');
    optionSet.find('.selected').removeClass('selected');
    jQuery(this).addClass('selected');
    // make option object dynamically, i.e. { filter: '.my-filter-class' }
    var options = {},
            key = optionSet.attr('data-option-key'),
            value = jQuery(this).attr('data-option-value');
    // parse 'false' as false boolean
    value = value === 'false' ? false : value;
    options[ key ] = value;
    if (key === 'layoutMode' && typeof changeLayoutMode === 'function') {
        // changes in layout modes need extra logic
        changeLayoutMode($this, options)
    } else {
        // otherwise, apply new options
        jQuerycontainer_pf.isotope(options);
    }

    return false;
});
var optionSets = jQuery('#m-pf-options .m-pf-option-set'),
        optionLinks = optionSets.find('a');
var jQuerycontainer_pf = jQuery('#pf-items');
optionLinks.click(function() {
    // don't proceed if already selected
    if (jQuery(this).hasClass('selected')) {
        return false;
    }
    var optionSet = jQuery(this).parents('.m-pf-option-set');
    optionSet.find('.selected').removeClass('selected');
    jQuery(this).addClass('selected');
    // make option object dynamically, i.e. { filter: '.my-filter-class' }
    var options = {},
            key = optionSet.attr('data-option-key'),
            value = jQuery(this).attr('data-option-value');
    // parse 'false' as false boolean
    value = value === 'false' ? false : value;
    options[ key ] = value;
    if (key === 'layoutMode' && typeof changeLayoutMode === 'function') {
        // changes in layout modes need extra logic
        changeLayoutMode($this, options)
    } else {
        // otherwise, apply new options
        jQuerycontainer_pf.isotope(options);
    }

    return false;
});
/* end Portfolio Filter */

/* =========================================================
 Tabs
 ============================================================ */
jQuery(document).ready(function() {
    if (jQuery(".tab-content-1").length > 0) {
        //Default Action Product Tab
        jQuery(".tab-content-1").hide(); //Hide all content
        jQuery("ul.tabs-1 li:first").addClass("active").show(); //Activate first tab
        jQuery(".tab-content-1:first").show(); //Show first tab content
        //On Click Event Product Tab
        jQuery("ul.tabs-1 li").click(function() {
            jQuery("ul.tabs-1 li").removeClass("active"); //Remove any "active" class
            jQuery(this).addClass("active"); //Add "active" class to selected tab
            jQuery(".tab-content-1").hide(); //Hide all tab content
            var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            jQuery(activeTab).fadeIn(); //Fade in the active content            
            return false;
        });
    }

    if (jQuery(".tab-content-2").length > 0) {
        //Default Action Product Tab
        jQuery(".tab-content-2").hide(); //Hide all content
        jQuery("ul.tabs-2 li:first").addClass("active").show(); //Activate first tab
        jQuery(".tab-content-2:first").show(); //Show first tab content
        //On Click Event Product Tab
        jQuery("ul.tabs-2 li").click(function() {
            jQuery("ul.tabs-2 li").removeClass("active"); //Remove any "active" class
            jQuery(this).addClass("active"); //Add "active" class to selected tab
            jQuery(".tab-content-2").hide(); //Hide all tab content
            var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            jQuery(activeTab).fadeIn(); //Fade in the active content
            return false;
        });
    }

    if (jQuery(".tab-content-3").length > 0) {
        //Default Action Product Tab
        jQuery(".tab-content-3").hide(); //Hide all content
        jQuery("ul.tabs-3 li:first").addClass("active").show(); //Activate first tab
        jQuery(".tab-content-3:first").show(); //Show first tab content
        //On Click Event Product Tab
        jQuery("ul.tabs-3 li").click(function() {
            jQuery("ul.tabs-3 li").removeClass("active"); //Remove any "active" class
            jQuery(this).addClass("active"); //Add "active" class to selected tab
            jQuery(".tab-content-3").hide(); //Hide all tab content
            var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            jQuery(activeTab).fadeIn(); //Fade in the active content
            return false;
        });
    }

    if (jQuery(".about-tab-content").length > 0) {
        //Default Action Product Tab
        jQuery(".about-tab-content").hide(); //Hide all content
        jQuery("ul.about-tabs li:first").addClass("active").show(); //Activate first tab
        jQuery(".about-tab-content:first").show(); //Show first tab content
        //On Click Event Product Tab
        jQuery("ul.about-tabs li").click(function() {
            jQuery("ul.about-tabs li").removeClass("active"); //Remove any "active" class
            jQuery(this).addClass("active"); //Add "active" class to selected tab
            jQuery(".about-tab-content").hide(); //Hide all tab content
            var activeTab = jQuery(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
            jQuery(activeTab).fadeIn(); //Fade in the active content
            return false;
        });
    }
});
/* =========================================================
 Portfolio Filter
 ============================================================ */
jQuery(document).ready(function() {

    jQuery('.isotop-header').click(function() {

        var portfolio_filter = jQuery('#filters');
        if (portfolio_filter.is(':hidden')) {
            portfolio_filter.slideDown('300');
        } else {
            portfolio_filter.slideUp('300');
        }

        jQuery(this).toggleClass('active');
    });
    jQuery('#filters li a').click(function() {

        jQuery('#filters').slideUp('300');
        jQuery('.isotop-header span').text(jQuery(this).text());
    });
});
jQuery(document).ready(function() {

    jQuery('.m-isotop-header').click(function() {

        var m_portfolio_filter = jQuery('#m-pf-filters');
        if (m_portfolio_filter.is(':hidden')) {
            m_portfolio_filter.slideDown('300');
        } else {
            m_portfolio_filter.slideUp('300');
        }

        jQuery(this).toggleClass('active');
    });
    jQuery('#m-pf-filters li a').click(function() {

        jQuery('#m-pf-filters').slideUp('300');
        jQuery('.m-isotop-header span').text(jQuery(this).text());
    });
});
/* =========================================================
 Toggle Boxes
 ============================================================ */
jQuery(document).ready(function() {

    jQuery('#toggle-view li').click(function(event) {

        var text = jQuery(this).children('div.panel');
        if (text.is(':hidden')) {
            jQuery(this).addClass('active');
            text.slideDown('300');
            jQuery(this).children('span').html('-');
        } else {
            jQuery(this).removeClass('active');
            text.slideUp('300');
            jQuery(this).children('span').html('+');
        }

    });
});
/* =========================================================
 ToolTip
 ============================================================ */
jQuery(window).load(function() {
    jQuery('.kp-tooltip').tooltip('hide');
});

/* =========================================================
 Scroll to top
 ============================================================ */
jQuery(document).ready(function() {
    // hide #back-top first
    jQuery("#back-top").hide();
    // fade in #back-top
    jQuery(function() {
        jQuery(window).scroll(function() {
            if (jQuery(this).scrollTop() > 200) {
                jQuery('#back-top').fadeIn();
            } else {
                jQuery('#back-top').fadeOut();
            }
        });
        // scroll body to 0px on click
        jQuery('#back-top a').click(function() {
            jQuery('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });
    });
});
/* =========================================================
 Accordion
 ========================================================= */
jQuery(document).ready(function() {
    (function() {
        var acc_wrapper = jQuery('.acc-wrapper');
        if (acc_wrapper.length > 0)
        {
            jQuery('.acc-wrapper .accordion-container').hide();
            jQuery.each(acc_wrapper, function(index, item) {
                jQuery(this).find(jQuery('.accordion-title')).first().addClass('active').next().show();
            });
            jQuery('.accordion-title').on('click', function(e) {
                if (jQuery(this).next().is(':hidden')) {
                    jQuery(this).parent().find(jQuery('.active')).removeClass('active').next().slideUp(300);
                    jQuery(this).toggleClass('active').next().slideDown(300);
                }
                e.preventDefault();
            });
        }
    })();
});
/* =========================================================
 Carousel
 ============================================================ */
jQuery(window).load(function() {
    if (jQuery("#related-widget").length > 0) {
        jQuery('#related-widget').carouFredSel({
            responsive: true,
            prev: '#prev-1',
            next: '#next-1',
            width: '100%',
            scroll: 1,
            auto: false,
            items: {
                width: 245,
                height: 'auto',
                visible: {
                    min: 1,
                    max: 3
                }
            }
        });
    }
    jQuery('.kopa_widget_articlelist_carousel').each(function() {
        var jQuery_this = jQuery(this),
            prevID = jQuery_this.data('prev-id'),
            nextID = jQuery_this.data('next-id'),
            paginationID = jQuery_this.data('pagination-id');

        jQuery_this.carouFredSel({
            responsive: true,
            prev: '#' + prevID,
            next: '#' + nextID,
            pagination: "#" + paginationID,
            width: '100%',
            scroll: 1,
            auto: false,
            items: {
                width: 250,
                height: 'auto',
                visible: {
                    min: 1,
                    max: 3
                }
            }
        });
    });
    /*
     * Carousel Posts Slider
     */
    jQuery('.our-work-widget').each(function() {
        var jQuery_this = jQuery(this),
                carouselNav = jQuery_this.siblings('.carousel-nav'),
                prevID = carouselNav.find('.carousel-prev').attr('id'),
                nextID = carouselNav.find('.carousel-next').attr('id');

        jQuery_this.carouFredSel({
            responsive: true,
            prev: '#' + prevID,
            next: '#' + nextID,
            width: '100%',
            scroll: 1,
            auto: false,
            items: {
                width: 260,
                height: 'auto',
                visible: {
                    min: 1,
                    max: 4
                }
            }
        });
    });

    /**
     * Testimonial slider
     */
    jQuery('.testimonial-slider').each(function() {
        var jQuery_this = jQuery(this),
                carouselNav = jQuery_this.siblings('.carousel-nav'),
                prevID = carouselNav.find('.carousel-prev').attr('id'),
                nextID = carouselNav.find('.carousel-next').attr('id');

        jQuery_this.carouFredSel({
            responsive: true,
            prev: '#' + prevID,
            next: '#' + nextID,
            width: '100%',
            scroll: 1,
            auto: false,
            items: {
                width: 355,
                height: 'auto',
                visible: {
                    min: 1,
                    max: 3
                }
            }
        });
    });

});
/* =========================================================
 Flex slider
 ============================================================ */
jQuery(window).load(function() {

    jQuery('.pf-detail-slider').flexslider({
        animation: "slide",
        start: function(slider) {
            jQuery('.flexslider').removeClass('loading');
        }
    });
    jQuery('.blogpost-slider').flexslider({
        animation: "slide",
        start: function(slider) {
            jQuery('.flexslider').removeClass('loading');
        }
    });
    jQuery('.kp-single-carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 195,
        itemMargin: 5,
        asNavFor: '.kp-single-slider'
    });
    jQuery('.kp-single-slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        sync: ".kp-single-carousel",
        start: function(slider) {
            jQuery('body').removeClass('loading');
        }
    });
});
/* ============================================================
 Load more gallery
 ============================================================ */

function more_gallery(obj){
    var more_photo = jQuery(obj).parent().find(".more-panel");
        if (jQuery(obj).hasClass('arrow-up')) {
            more_photo.slideUp('300');
            jQuery(obj).removeClass('arrow-up');
        } else {
             more_photo.slideDown('300');
            jQuery(obj).addClass('arrow-up');
        }       
}
/* =============================================================
 Portfolio hover effect
 ============================================================ */
jQuery(window).load(function() {
    jQuery('.bwWrapper').BlackAndWhite({
        hoverEffect: true, // default true
        // set the path to BnWWorker.js for a superfast implementation
        webworkerPath: false,
        // for the images with a fluid width and height 
        responsive: true,
        speed: {//this property could also be just speed: value for both fadeIn and fadeOut
            fadeIn: 200, // 200ms for fadeIn animations
            fadeOut: 800 // 800ms for fadeOut animations
        }
    });
});
/*===============================================================
 * Load more
 ===============================================================*/
function loadmore_articles(obj) {
    jQuery(".kp-loading").removeClass("hidden");
    kopa_post_id_string = jQuery("#post_id_array").val();

    kopa_categories_arg = [];
    jQuery(".kopa_categories_arg").each(function() {
        if (jQuery(this).val() != 'none') {
            kopa_categories_arg.push(jQuery(this).val());
        }
    });

    kopa_relation_arg = jQuery("#kopa_relation_arg").val();

    kopa_tags_arg = [];
    jQuery(".kopa_tags_arg").each(function() {
        if (jQuery(this).val() != 'none') {
            kopa_tags_arg.push(jQuery(this).val());
        }
    });

    kopa_number_of_article_arg = jQuery("#kopa_number_of_article_arg").val();
    kopa_orderbye_arg = jQuery("#kopa_orderbye_arg").val();
    stored_month_year = jQuery("#stored_month_year").val();
    if (jQuery("#no_post_found").val() == '0') {
        jQuery.ajax({
            type: 'POST',
            url: kopa_front_variable.ajax.url,
            dataType: 'html',
            data: {
                action: "load_more_articles",
                kopa_post_id_string: kopa_post_id_string,
                kopa_categories_arg: kopa_categories_arg,
                kopa_relation_arg: kopa_relation_arg,
                kopa_tags_arg: kopa_tags_arg,
                kopa_number_of_article_arg: kopa_number_of_article_arg,
                kopa_orderbye_arg: kopa_orderbye_arg,
                stored_month_year: stored_month_year,
                wpnonce: jQuery('#nonce_id_load_more').val()
            },
            beforeSend: function() {

            },
            success: function(data) {
                console.log(data);
                jQuery("#post_id_array").remove();
                temp = jQuery(".kp-filter").html();

                jQuery(".kp-filter").remove();
                jQuery(".timeline-container").append(data);
                if (jQuery("#no_post_found").val() == 1) {
                    jQuery(obj).addClass('hidden');
                    jQuery(".timeline-container").append('<div class="kp-filter clearfix">' + temp + '</div>');
                }
            },
            complete: function(data) {
                jQuery(".kp-loading").addClass("hidden");
            },
            error: function(errorThrown) {
                console.log(errorThrown);
            }
        });
    }
    else {
        jQuery(".kp-loading").addClass("hidden");
    }
    return false;
}
/*==============================================================================
 * after load Ajax
 =============================================================================*/
jQuery(document).ajaxComplete(function() {
    jQuery("a[rel^='prettyPhoto']").prettyPhoto();    
    jQuery('.blogpost-slider').flexslider({
        animation: "slide",
        start: function(slider) {
            jQuery('.flexslider').removeClass('loading');
        }
    });
});


/*==============================================================================
 * Sequence Slider
 =============================================================================*/
jQuery(document).ready(function() {
    jQuery('.sequence-slider').each(function() {
        var $this = jQuery(this),
                $sequence = $this.find('.sequence'),
                sequenceAutoPlay = $sequence.data('autoplay'),
                sequenceAutoPlayDelay = $sequence.data('slideshow_interval'),
                $sequenceNav = $this.find('.sequence-nav');

        var options = {
            autoPlay: sequenceAutoPlay,
            autoPlayDelay: sequenceAutoPlayDelay,
            fallback: {
                theme: 'slide',
                speed: 500
            },
            nextButton: true,
            prevButton: true,
            animateStartingFrameIn: true,
            preloader: true,
            pauseOnHover: true,
            preloadTheseFrames: [1]
        };

        var sequence = $sequence.sequence(options).data("sequence");

        sequence.afterLoaded = function() {
            $sequenceNav.fadeIn(100);
            $sequenceNav.find("li:nth-child(" + (sequence.settings.startingFrameID) + ") img").addClass("active");
        }

        sequence.beforeNextFrameAnimatesIn = function() {
            $sequenceNav.find("li:not(:nth-child(" + (sequence.nextFrameID) + ")) img").removeClass("active");
            $sequenceNav.find("li:nth-child(" + (sequence.nextFrameID) + ") img").addClass("active");
        }

        $sequenceNav.find("li").click(function() {
            jQuery(this).children("img").removeClass("active").children("img").addClass("active");
            sequence.nextFrameID = jQuery(this).index() + 1;
            sequence.goTo(sequence.nextFrameID);
        });
    });
});


/*==============================================================================
 * jQuery Pie Chart
 =============================================================================*/
