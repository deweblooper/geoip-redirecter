
function set_cookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function get_cookie(name) {
    var name_eq = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(name_eq) == 0) return c.substring(name_eq.length,c.length);
    }
    return null;
}


jQuery(document).ready(function() {
	
	jQuery('.geoip-close').click(function() {
		jQuery('.geoip-redirecter').slideUp('slow');
		set_cookie("geoip-redirecter", "0", 7);	// name,value,days (in fnc set_cookie)
	});
	
});