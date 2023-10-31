$("#a_block, #cname_block, #srv_block, #mx_block, #txt_block")["hide"]();
$("#add_type")["on"]("change", function () {
    var _0xbdc5x1 = $(this)["val"]();
    switch (_0xbdc5x1) {
        case "none":
            $("#a_block, #cname_block, #srv_block, #mx_block, #txt_block")["hide"]();
            $(".adddnsform")["remove"]();
            break;;
        case "A":
            $("#cname_block, #srv_block, #mx_block, #txt_block")["hide"]();
            $(".adddnsform")["remove"]();
            $("#a_block")["show"]()["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Name</label><input type=\"text\" class=\"form-control adddnsform\" name=\"a_name\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Address</label><input type=\"text\" class=\"form-control adddnsform\" name=\"a_address\" /></div>")["append"]("<input type=\"submit\" value=\"Create\" class=\"btn btn-success adddnsform\" /><br class=\"adddnsform\" /><br class=\"adddnsform\" />");
            break;;
        case "CNAME":
            $("#a_block, #srv_block, #mx_block, #txt_block")["hide"]();
            $(".adddnsform")["remove"]();
            $("#cname_block")["show"]()["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Name</label><input type=\"text\" class=\"form-control adddnsform\" name=\"cname_name\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">CNAME</label><input type=\"text\" class=\"form-control adddnsform\" name=\"cname_cname\" /></div>")["append"]("<input type=\"submit\" value=\"Create\" class=\"btn btn-success adddnsform\" /><br class=\"adddnsform\" /><br class=\"adddnsform\" />");
            break;;
        case "SRV":
            $("#a_block, #cname_block, #mx_block, #txt_block")["hide"]();
            $(".adddnsform")["remove"]();
            $("#srv_block")["show"]()["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Service</label><input type=\"text\" class=\"form-control adddnsform\" name=\"srv_service\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Protocol</label><select class=\"form-control adddnsform\" name=\"srv_protocol\"><option class=\"adddnsform\" value=\"_tcp\">TCP</option><option class=\"adddnsform\" value=\"_udp\">UDP</option></select></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Name</label><input type=\"text\" class=\"form-control adddnsform\" name=\"srv_name\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Priority</label><input type=\"text\" class=\"form-control adddnsform\" name=\"srv_priority\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Weight</label><input type=\"text\" class=\"form-control adddnsform\" name=\"srv_weight\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Port</label><input type=\"text\" class=\"form-control adddnsform\" name=\"srv_port\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Target</label><input type=\"text\" class=\"form-control adddnsform\" name=\"srv_target\" /><p class=\"adddnsform help-block\">Must be a matching A record</p></div>")["append"]("<input type=\"submit\" value=\"Create\" class=\"btn btn-success adddnsform\" /><br class=\"adddnsform\" /><br class=\"adddnsform\" />");
            break;;
        case "MX":
            $("#a_block, #cname_block, #srv_block, #txt_block")["hide"]();
            $(".adddnsform")["remove"]();
            $("#mx_block")["show"]()["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Name</label><input type=\"text\" class=\"form-control adddnsform\" name=\"mx_name\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Exchange</label><input type=\"text\" class=\"form-control adddnsform\" name=\"mx_exchange\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Preference</label><input type=\"text\" class=\"form-control adddnsform\" name=\"mx_preference\" /></div>")["append"]("<input type=\"submit\" value=\"Create\" class=\"btn btn-success adddnsform\" /><br class=\"adddnsform\" /><br class=\"adddnsform\" />");
            break;;
        case "TXT":
            $("#a_block, #cname_block, #srv_block, #mx_block")["hide"]();
            $(".adddnsform")["remove"]();
            $("#txt_block")["show"]()["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">Name</label><input type=\"text\" class=\"form-control adddnsform\" name=\"txt_name\" /></div>")["append"]("<div class=\"form-group adddnsform\"><label class=\"adddnsform\">TXT Value</label><input type=\"text\" class=\"form-control adddnsform\" name=\"txt_value\" /></div>")["append"]("<input type=\"submit\" value=\"Create\" class=\"btn btn-success adddnsform\" /><br class=\"adddnsform\" /><br class=\"adddnsform\" />");
            break;;
        default:
            $("#a_block, #cname_block, #srv_block, #mx_block, #txt_block")["hide"]();
            $(".adddnsform")["remove"]();
            break;;
    };
});
