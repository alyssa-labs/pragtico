/**
 * Rebuild table tbody adding breakDowns rows.
 */
var buildTable = function(clickedRowId, url, table) {
	var breakDownRowId = "breakdown_row" + url.replace(/\/\.\./g, "").replace(/\//g, "_");
	var newTbody = jQuery("<tbody/>");

	if (table == undefined) {
		table = "table";
		jQuery(table + " > tbody > tr").each(
			function() {
				newTbody.append(this);
				if (clickedRowId == jQuery(this).attr("charoff")) {
					var td = jQuery("<td/>").attr("colspan", "20");
					td.append(jQuery("<div/>").attr("class", "desglose").load(url,
						function() {
							jQuery("img.breakdown_icon", this).bind("click", breakdown);
                            jQuery("img.seleccionar", this).hide();
						}
					));
					var tr = jQuery("<tr/>").addClass(breakDownRowId).addClass("breakdown_row").append(td);
					newTbody.append(tr);
				}
			}
		);
		jQuery(table + " > tbody").remove();
		jQuery(table).append(newTbody);
	} else {
		table.parent().find("table:first > tbody > tr").each(
			function() {
				newTbody.append(this);

				if (clickedRowId == jQuery(this).attr("charoff")) {
					var td = jQuery("<td/>").attr("colspan", "20");
					td.append(jQuery("<div/>").attr("class", "desglose").load(url,
						function() {
							jQuery("img.breakdown_icon", this).bind("click", breakdown);
                            jQuery("img.seleccionar", this).hide();
						}
					));
					var tr = jQuery("<tr/>").addClass(breakDownRowId).addClass("breakdown_row").append(td);
					newTbody.append(tr);
				}
			}
		);
		table.find("tbody").remove();
		table.append(newTbody);
	}

	return false;
}


/**
 * Delete cookies and hide all breakdown rows.
 */
var closeAllBreakdowns = function() {
	jQuery.cookie("breakDownsCookie", null);
	jQuery(".breakdown_row").hide();
	return false;
}


jQuery(document).ready(function($) {
    /**
    * Binds event.
    */
    jQuery("#closeAllBreakdowns").bind("click", closeAllBreakdowns);
    
    /**
    * If exist in cookie, must re-open breakdown.
    */
    var breakDownsCookie = jQuery.cookie("breakDownsCookie");
    if (breakDownsCookie != null) {
        breakDowns = breakDownsCookie.split("|").clean("");
        var openedBreakDowns = 0;
        var openedBreakDownLongDesc = "";
        jQuery(".breakdown_icon").each(
            function(index) {
                if (jQuery.inArray(this.getAttribute("longdesc"), breakDowns) >= 0) {
                    openedBreakDowns++;
                    openedBreakDownLongDesc = this.getAttribute("longdesc");
                    clickedRowId = this.getAttribute("longdesc").split("/").pop();
                    buildTable(clickedRowId, this.getAttribute("longdesc"));
                }
            }
        );

        jQuery(".bread_crumb_class").remove();
        if (openedBreakDowns == 1) {
            var span = jQuery("<span/>").addClass("bread_crumb_class").text(" » " + jQuery("img[longdesc=\'" + openedBreakDownLongDesc + "\']").attr("alt"));
            jQuery("div.banda_izquierda > p").append(span);
        } else if (openedBreakDowns > 1){
            var span = jQuery("<span/>").addClass("bread_crumb_class").text(" » " + breakDowns.length + " Desgloses abiertos");
            jQuery("div.banda_izquierda > p").append(span);
        }
    }
});

/**
 * Binds click event to breakdown icons.
 */
var breakdown = function() {

	var clickedRowId = jQuery(this).parent().parent().attr("charoff");
	var url = this.getAttribute("longdesc");

	var breakDownsCookie = jQuery.cookie("breakDownsCookie");
	if (breakDownsCookie != null) {
		breakDowns = breakDownsCookie.split("|").clean("");
	} else {
		breakDowns = Array();
	}


	var breakDownRowId = "breakdown_row" + url.replace(/\/\.\./g, "").replace(/\//g, "_");
	if (jQuery("." + breakDownRowId).length) {
		jQuery("." + breakDownRowId).toggle();
		if (!jQuery("." + breakDownRowId).is(":visible")) {
			delete breakDowns[jQuery.inArray(url, breakDowns)];
			breakDowns = breakDowns.clean("").clean(undefined);
			jQuery.cookie("breakDownsCookie", breakDowns.join("|"));
		}
	} else {
		breakDowns.push(url);
		jQuery.cookie("breakDownsCookie", breakDowns.join("|"));
		var table = jQuery(this).parent().parent().parent().parent();
		buildTable(clickedRowId, url, table);
	}


	jQuery(".bread_crumb_class").remove();
	if (breakDowns.length == 1) {
		var span = jQuery("<span/>").addClass("bread_crumb_class").text(" » " + jQuery("img[longdesc=\'" + breakDowns[0] + "\']").attr("alt"));
		jQuery("div.banda_izquierda > p").append(span);
	} else if (breakDowns.length > 1){
		var span = jQuery("<span/>").addClass("bread_crumb_class").text(" » " + breakDowns.length + " Desgloses abiertos");
		jQuery("div.banda_izquierda > p").append(span);
	}

}