function nocache() {
    function e(e) {
        var t = Math.round(65536 * Math.random());
        return -1 !== e.indexOf("?") ? e + "&_=" + t : e + "?_=" + t
    }
    var t, i = window.document.getElementsByTagName("SCRIPT"),
        n = i[i.length - 1],
        r = Array.prototype.slice.call(arguments);
    return -1 !== n.src.indexOf("_=") ? t = r.shift().apply(null, r) : (window.document.write(''), window.document.write('')), t
}

function setParameters(e) {
    param_list = jQuery(".bit4id-signReq > [class^=bit4id]");
    for (var t = 0; t < param_list.length; ++t) 
        "div" == param_list[t].tagName.toLowerCase() && (e[param_list[t].className.replace("-", "_")] = param_list[t].innerHTML);
    return e
}

nocache(function(e) {
    UniversalKeychain(function(t) {
        var i = jQuery("form.bit4id-sign input[type=submit]");
        i.hide();
        var n = jQuery('');
        n.attr("href", "keychain:?" + jQuery.param(t)), n.text(jQuery("form.bit4id-sign input[type=submit]").val()), n.insertAfter(i), jQuery("body").bind("raise.keychain", function(e, t) {
            "rejected" === t.message && jQuery("#bit4id-status").html("refused"), "Disconnected" === t.name && jQuery("#bit4id-status").html("disconnected"), jQuery("#bit4id-status").html(t.name + ":" + t.message)
        }), jQuery("body").bind("received.keychain", function(t, i) {
            return n.addClass("disabled").text("Firma...").attr("disabled", !0), "changestatus" === i.event && jQuery("#bit4id-status").html(i.message), "finalstatus" === i.event && (jQuery("#bit4id-status").html(i.message), jQuery("body").unbind("raise.keychain")), "accepted" === i.event ? (e.setInterval(function() {
                jQuery("body").trigger("send.keychain", {
                    event: "keep_alive"
                })
            }, 5e3), jQuery("#bit4id-status").html("connected"), void jQuery("body").trigger("send.keychain", setParameters({
                event: "finalize_sign",
                origin: e.document.location.protocol + "//" + e.document.location.host,
                cookiejar: e.document.cookie,
                form_action: jQuery("form.bit4id-sign").attr("action")
            }))) : void("redirect" === i.event && (e.document.location.href = i.location))
        })
    })
}, window);