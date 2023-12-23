$(document).ready(function () {
    /**
     * Profile's checkbox returns nothing if it's false, so this function is going to fill the checkbox's value
     * and set the attribute "disabled" for the hidden input
     */
    function updateProfileContent() {
        $(':checkbox[id^="input-profile[]"]').each(function (index) {
            $(':checkbox[id^="input-profile"]')[index].value = this.checked ? "true" : "false";
        })
    }
    updateProfileContent();
    /**
     * for every change on the profile-check class(which is the checkbox input), the updateProfileContent is going to be
     * called. Upwards it is called again because when the page is first loaded, there is no change on the checkbox inputs
     */
    $(document).on("change", ".profile-check" , function() {
        updateProfileContent();
    });
});
(function ($) {
    $(document).on("click", ".js-delete-modal", function(e){
        e.preventDefault();
        const $this = $(this);
        let _content = $this.data('content');
        $.confirm({
            title: 'Atenção!',
            content: _content,
            type: 'red',
            icon: 'fa fas-exclamation-triangle',
            animateFromElement: false,
            buttons: {
                confirm: {
                    text: 'Sim, remover',
                    keys: [
                        'enter'
                    ],
                    btnClass: 'btn-danger',
                    action: function () {
                        loading(true);
                        window.location = $this.data("url");
                    }
                },
                cancel: {
                    text: 'Cancelar',
                    keys: [
                        'esc'
                    ],
                    action: function(){}
                }
            }
        });
    });
})(jQuery);

(function ($) {
    $(function () {
        //dark mode
        $(document).on("click", ".dark-mode", function (e) {
            e.preventDefault();
            var $this = $(this);
            if ($this.attr("aria-describedby")) {
                const tooltip = bootstrap.Tooltip.getInstance('.dark-mode') // Returns a Bootstrap tooltip instance
                let text = $this.attr("data-change");
                let textoAtual = $this.attr("aria-label");
                $this.attr("data-change", textoAtual).attr("aria-label", text);
                tooltip.setContent({ '.tooltip-inner': text });// setContent tooltip
            }
            if ($this.hasClass("active")) {
                deleteCookie("dark");
                $("html").removeClass("dark");
                $this.removeClass("active");
            } else {
                setCookie("dark", true);
                $("html").addClass("dark");
                $this.addClass("active");
            }
        });

        //abre left
        $(document).on("click", ".abre-left", function (e) {
            e.preventDefault();
            e.stopPropagation();
            if ($(this).hasClass("active")) {
                $("#left").removeClass("active");
                $(this).removeClass("active");
                deleteCookie("menu-left");
            } else {
                $("#left").addClass("active");
                $(this).addClass("active");
                setCookie("menu-left", true);
            }
            $(this).find("i").toggleClass("fa-bars fa-angle-left");
        });

        //abre menu
        $(document).on("click", ".abre-menu", function (e) {
            e.preventDefault();
            e.stopPropagation();

            if ($("html").hasClass("left-menu")) {
                $("html").removeClass("left-menu");
                $("#menu ul li a").each(function(){
                    $(this).addClass("tip");
                    const tooltip = bootstrap.Tooltip.getOrCreateInstance(this);
                    tooltip.enable();
                });
                deleteCookie("abre-menu");
            } else {
                $("html").addClass("left-menu");
                $("#menu ul li a").each(function(){
                    $(this).removeClass("tip");
                    const tooltip = bootstrap.Tooltip.getOrCreateInstance(this);
                    tooltip.disable();
                });
                setCookie("abre-menu", true);
            }
        });

        //fecha menu aberto
        $(document).on("click", "body", function () {
            if ($(window).width() <= 480 && $("html").hasClass("left-menu")) {
                $(".abre-menu").trigger("click");
            }
            if ($(window).width() <= 576 && $(".abre-left").hasClass("active")) {
                $(".abre-left.active").trigger("click");
            }
        });
        $(window).on("resize", function () {
            if ($(window).width() <= 480 && $("html").hasClass("left-menu")) {
                $(".abre-menu").trigger("click");
            }
            if ($(window).width() <= 576 && $(".abre-left").hasClass("active")) {
                $(".abre-left.active").trigger("click");
            }
        });

        //ABRE MODAL
        $(document).on("click", ".btn-fullscreen", function () {
            if ($(this).closest(".modal").find(".modal-dialog").hasClass('modal-fullscreen')) {
                $(this).closest(".modal").find(".modal-dialog").removeClass('modal-fullscreen');
            } else {
                $(this).closest(".modal").find(".modal-dialog").addClass('modal-fullscreen');
            }
        });
        $(document).on("click", ".abre-modal", function () {
            var $this = $(this);
            let url = $this.attr("data-url") || "";
            let target = $this.attr("data-bs-target") || "";
            let size = ($this.attr("data-size")) ? ($this.attr("data-size")).split(' ') : [];
            let onshow = eval($this.attr("data-onshow")) || function () { };
            let onshown = eval($this.attr("data-onshown")) || function () { };
            let onhide = eval($this.attr("data-onhide")) || function () { };
            let onhidden = eval($this.attr("data-onhidden")) || function () { };
            var $modalWrap;
            function popModal(){
                if($modalWrap.length > 0){
                    $modalWrap.attr("tabIndex", -1);

                    $.each(size, function (k, classname) {
                        $modalWrap.find(".modal-dialog").addClass(classname);
                    });

                    var modal = new bootstrap.Modal($modalWrap);

                    $modalWrap.on('show.bs.modal', function (event) {
                        onshow();
                    });
                    $modalWrap.on('shown.bs.modal', function (event) {
                        onshown();
                    });
                    $modalWrap.on('hide.bs.modal', function (event) {
                        onhide();
                    });
                    $modalWrap.on('hidden.bs.modal', function (event) {
                        onhidden();
                        if($modalWrap.hasClass("remove-on-close")){
                            $modalWrap.remove();
                            modal.dispose();
                        }
                    });
                    modal.show();
                    loading(false);
                }
            }

            if (
                url != "") {
                loading(true);
                fetch(url, {
                    method: 'GET'
                }).then(function (response) {
                    return response.text();
                }).then(function (content) {
                    if ($(content).hasClass("modal")) {
                        $modalWrap = $(content);
                    } else {
                        $modalWrap = $(`<div id="modal-${Math.floor(1000 + Math.random() * 90000)}" class="modal remove-on-close">${content}</div>`);
                    }
                    $("body").append($modalWrap);
                    popModal();

                }).catch(function (err) {
                    loading(false);
                    console.warn('Something went wrong with the modal.', err);
                });
            }else if(target !== ""){
                $modalWrap = $(target);
                popModal();
            }
        });

        // Hide header
        $(window).scroll(function (event) {
            didScroll = true;
            st = $(this).scrollTop();
        });
        hasScrolled('#header');
        setInterval(function () {
            if (didScroll) {
                hasScrolled('#header');
                didScroll = false;
            }
        }, 200);

        //autoresize textarea
        var resizeTextarea = function (el, offset) {
            $(el).css('height', 'auto').css('height', el.scrollHeight + offset);
        };
        $(document).on('keyup input', 'textarea.autoresize', function () {
            var offset = this.offsetHeight - this.clientHeight;
            resizeTextarea(this, offset);
        });

        //tooltip para text com ellipsis
        $(document).on('mouseenter', '.text-truncate', function () {
            var $this = $(this);
            if (this.offsetWidth < this.scrollWidth) {
                $this.attr('title', $.trim($this.text()));
                var tooltip = bootstrap.Tooltip.getOrCreateInstance(this, {
                    placement: 'bottom'
                });
                tooltip.show();
            } else {
                $this.removeAttr('title');
                var tooltip = bootstrap.Tooltip.getOrCreateInstance(this);
                tooltip.dispose();
            }
        });
        start();

    });
})(jQuery);


function start(container = 'body') {
    //validate forms, except .no-validate

    tooltip('[data-bs-toggle="tooltip"], .tip', container);

    toogleContent(".toggle-content")

    // aplica as mascaras dos inputs

}


/*---------------------------------------------------------------------
              LOADING
-----------------------------------------------------------------------*/
function loading(status, text = "Carregando...") {
    if (status) {
        if ($("#loading").length <= 0) {
            $("body").prepend('<div id="loading"><i class="fa fa-pulse"></i><p>' + text + '</p></div>');
        }
        $("#loading").fadeIn(200);
    } else {
        $("#loading").fadeOut(200);
    }
}

/*---------------------------------------------------------------------
              MASKINPUT
-----------------------------------------------------------------------*/
function inputMask() {
    $(".cpf").mask('000.000.000-00', { reverse: true });
    $('.cep').mask('00000-000');
    $(".data").mask("00/00/0000");
    $(".hora").mask("00:00");
    $('.mes-ano').mask('00/0000');
    $('.codigo-google').mask('000 000');
    $('.ano').mask('0000');
    $(".cnpj").mask("00.000.000/0000-00");
    $('.numero').mask("#.##0", { reverse: true });
    var maskPercent = function (val) {
            return val.replace(/\D/g, '').substring(0, 1) == 1 ? '000' : '00,00';
        },
        optionsPercent = {
            onKeyPress: function (val, e, field, options) {
                field.mask(maskPercent.apply({}, arguments), options);
            }
        };
    $('.porcentagem').mask(maskPercent, optionsPercent);
    $('.moeda').mask("#.##0,00", { reverse: true });
    var maskBehavior = function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        },
        options = {
            onKeyPress: function (val, e, field, options) {
                field.mask(maskBehavior.apply({}, arguments), options);
            }
        };
    $('.phone').mask(maskBehavior, options);
}

/*---------------------------------------------------------------------
              Set  and geta Cookie
-----------------------------------------------------------------------*/
function setCookie(cName, cValue, expDays = 7) {
    let date = new Date();
    date.setTime(date.getTime() + (expDays * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = cName + "=" + cValue + "; " + expires + "; path=/";
}
function deleteCookie(cName) {
    let date = new Date();
    date.setTime(date.getTime() - (30 * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = cName + "=0; " + expires + "; path=/";
}
function getCookie(cName) {
    const name = cName + "=";
    const cDecoded = decodeURIComponent(document.cookie); //to be careful
    const cArr = cDecoded.split('; ');
    let res;
    cArr.forEach(val => {
        if (val.indexOf(name) === 0) res = val.substring(name.length);
    })
    return res;
}

/*---------------------------------------------------------------------
              Hide Header on on scroll down
-----------------------------------------------------------------------*/
var didScroll;
var lastScrollTop = 0;
var st = 0;
function hasScrolled(header) {
    var navbarHeight = 0;
    var delta = 1;
    if ($(header).length > 0) {
        navbarHeight = $(header).outerHeight();
    }
    st = $(this).scrollTop();
    // if (st > navbarHeight) {
    //    $(header).addClass("fixed");
    // } else {
    //    $(header).removeClass("fixed");
    // }
    // Make sure they scroll more than delta
    if (Math.abs(lastScrollTop - st) <= delta)
        return;
    // If they scrolled down and are past the navbar, add class .nav-up.
    // This is necessary so you never see what is "behind" the navbar.
    if (st > lastScrollTop && st > navbarHeight) {
        // Scroll Down
        $('body').removeClass('nav-down').addClass('nav-up');
        if ($(window).width() <= 480 && $("html").hasClass("left-menu")) {
            $(".abre-menu").trigger("click");
        }
    } else {
        // Scroll Up
        if (st + $(window).height() < $(document).height()) {
            $('body').removeClass('nav-up').addClass('nav-down');
        }
    }

    lastScrollTop = st;
}

/*---------------------------------------------------------------------
              TOOGLE CONTENT
-----------------------------------------------------------------------*/
function toogleContent(element, container = "body") {
    $.each(element.split(","), function (k, obj) {
        obj = $.trim(obj);
        $(container + " " + obj).each(function () {
            $(container).on("change", obj, function(){
                let $this = $(this);
                if ($this.is("select")) {
                    let show = $this.find(':selected').attr("data-show");
                    let hide = $this.attr("data-hide");
                    $(hide).slideUp(200, function(){$(this).addClass("d-none")});
                    if($(show).hasClass("d-none")){
                        $(show).hide().removeClass("d-none").stop().slideDown(200);
                    }
                } else if ($this.is("input[type='radio']") || $this.is("input[type='checkbox']")) {
                    let show = $this.attr("data-show");
                    let hide = $this.attr("data-hide");

                    if($this.prop("checked") == true){
                        $(hide).stop().slideUp(200, function(){$(this).addClass("d-none")});
                        if($(show).hasClass("d-none")){
                            $(show).hide().removeClass("d-none").stop().slideDown(200);
                        }
                    }else{
                        $(show).stop().slideUp(200, function(){$(this).addClass("d-none")});
                        if($(hide).hasClass("d-none")){
                            $(hide).hide().removeClass("d-none").stop().slideDown(200);
                        }
                    }
                } else {
                    console.info("//TODO")
                }
            });
        });
    });
}
/*---------------------------------------------------------------------
              Popover
-----------------------------------------------------------------------*/
var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
if (typeof bootstrap !== typeof undefined) {
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
}

$(document).on('show.bs.dropdown', '[data-bs-toggle=dropdown]', function () {
    // universal solution
    let dropdown = bootstrap.Dropdown.getInstance(this);
    $("body").append(dropdown._menu);

    // alternative for this particular layout
    // $(this).next('.dropdown-menu').insertAfter(carousel);
}).on('hide.bs.dropdown', '[data-bs-toggle=dropdown]', function () {
    // universal solution
    let dropdown = bootstrap.Dropdown.getInstance(this);
    console.log(dropdown);
    // $(dropdown._menu).insertAfter(carousel);
    $(dropdown._parent).append(dropdown._menu);

    // alternative for this particular layout
    // $(this).next('.dropdown-menu').insertAfter(carousel);
});

/*---------------------------------------------------------------------
                Tooltip
-----------------------------------------------------------------------*/
function tooltip(element, container = "body") {
    $.each(element.split(","), function (k, obj) {
        obj = $.trim(obj);
        $(container + " " + obj).tooltip({ container: 'body', animation: false, trigger: 'hover' });
    });
}



/*---------------------------------------------------------------------
              Copy To Clipboard
-----------------------------------------------------------------------*/
$(document).on("click", '[data-toggle="copy"]', function () {
    const target = $(this).attr("data-copy-target");
    let value = $(this).attr("data-copy-value");
    const container = $(target);
    if (container !== undefined && container !== null) {
        if (container.attr("value") !== undefined && container.attr("value") !== null) {
            value = container.attr("value");
        } else {
            value = container.html();
        }
    }
    if (value !== null) {
        var $temp = $("<textarea>");
        $("body").append($temp);
        $temp.val(value).select();
        document.execCommand("copy");
        $temp.remove();
    }
});


function goTop($obj, $parent = $(window)){
    var top = $obj.offset().top - ($("body").hasClass("nav-down") ? $("#header").height() : 0);
    $parent.scrollTop(top, 0);
}