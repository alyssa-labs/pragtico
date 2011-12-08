jQuery(document).ready(function($) {

    /** Creates the menu */
    jQuery(".menu").accordion({
        header: "a.header",
        active: parseInt(jQuery.cookie("menu_cookie"))
    });
    
    /** Binds click event to select/deselect/invert classes for manipulating checkboxes */
    jQuery.bindMultipleCheckBoxManipulation();
    
    
    /** Binds click to expand textarea control */
    jQuery(".expand_text_area").click(function() {
        var textarea = "#" + jQuery("textarea", jQuery(this).parent()).attr("id");
        if (jQuery(this).hasClass("colapse_text_area")) {
            jQuery(textarea).parent().css("width", "365px");
            jQuery(textarea).css("width", "196px").css("background-image", "url(" + jQuery.url("css/img/textarea.gif") + ")");
            jQuery(this).removeClass("colapse_text_area");
            jQuery(this).addClass("expand_text_area");
        } else {
            jQuery(textarea).parent().css("width", "720px");
            jQuery(textarea).css("width", "565px").css("background-image", "url(" + jQuery.url("css/img/wide_textarea.gif") + ")");
            jQuery(this).addClass("colapse_text_area");
            jQuery(this).removeClass("expand_text_area");
        }
    });


    /** Checks cookie to decide to hide conditions frameset */
    if (jQuery.cookie("conditionsFrameCookie") == "false") {
        jQuery(".conditions_frame").hide();
        jQuery("#hideConditions > img").attr("src", jQuery.url("img/") + "sin_pinchar.gif");
    }
    
    
    /** Binds click to Show / Hide conditions */
    jQuery("#hideConditions").bind("click",
        function() {
            jQuery(".conditions_frame").toggle();
            if (jQuery(".conditions_frame").is(":visible")) {
                jQuery.cookie("conditionsFrameCookie", "true");
                jQuery("#hideConditions > img").attr("src", jQuery.url("img/") + "pinchado.gif");
            } else {
                jQuery.cookie("conditionsFrameCookie", "false");
                jQuery("#hideConditions > img").attr("src", jQuery.url("img/") + "sin_pinchar.gif");
            }
        }
    );


    /** Binds event to every lov caller */
    jQuery(".lupa_lov").click(
        function() {
    
            jQuery("#opened_lov_options").val(jQuery(this).attr("longdesc"));
            var params = jQuery.makeObject(jQuery("#opened_lov_options").val());

            jQuery("#lov").load(jQuery.url(params["controller"] + "/" + params["action"])).modal({
                containerCss: {
                    height: 450,
                    width: 850,
                    position: "absolute",
                    paddingLeft: 4
                }
            });
        }
    );
    

    /** Hides select img when not in lov */
    jQuery(".seleccionar").hide();
    
});


/** Binds click event to select/deselect/invert classes for manipulating checkboxes */
jQuery.extend({
    bindMultipleCheckBoxManipulation: function(scope) {

        if (scope == undefined) {
            scope = "#index";
        }

        jQuery(scope + " table .seleccionarTodos").click(
            function() {
                jQuery(".tabla :checkbox").checkbox("seleccionar");
                return false;
            }
        );

        jQuery(scope + " table .deseleccionarTodos").click(
            function() {
                jQuery(".tabla :checkbox").checkbox("deseleccionar");
                return false;
            }
        );

        jQuery(scope + " table .invertir").click(
            function() {
                jQuery(".tabla :checkbox").checkbox("invertir");
                return false;
            }
        );
    }
});

    
/** Cretes an object (key => value) from a string
 * The form of the string should be:
 * str = "paramNameA: aaaaa; paramNameB: cccc";
*/
jQuery.makeObject = function(str, separator) {
    if (separator == undefined) {
        separator = ";";
    }

    var items = {};
    jQuery.each(str.split(separator),
        function() {
            var tmp = this.split(":");
            //items[tmp[0]] = tmp[1].trim();
            items[tmp[0]] = tmp[1];
        }
    );
    return items;
}
    
/** Useful function to avoid using Router::url everywere */
jQuery.url = function(url) {
    if (url == undefined) {
        url = "";
    }
    return jQuery("#base_url").val() + url;
}

    
Array.prototype.clean = function(to_delete)
{
   var a;
   for (a = 0; a < this.length; a++)
   {
      if (this[a] == to_delete)
      {         
         this.splice(a, 1);
         a--;
      }
   }
   return this;
};

var vOcultar = function() {
	jQuery('.session_flash').fadeOut('slow',
		function() {
			jQuery(this).remove();
		}
	);
}
	
var ajaxGet = function (url) {
	jQuery.ajax({
		type: 	'GET',
		async: 	false,
		url: 	url
	});
}

var ajaxPost = function(url) {
	jQuery.ajax({
		type: 	'POST',
		async: 	false,
		url: 	url
	});
}