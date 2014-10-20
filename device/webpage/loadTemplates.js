
function urlizeStr(url) {
    url = url.replace(/ /g, "+");
    url = url.replace(/\\/g, "%5C");
    url = url.replace(/\//g, "%2F");
    // url = url.replace(/\:/g, "%3A");
    return url;
}

function unUrlizeStr(url) {
    url = url.replace(/\+/g, " ");
    url = url.replace(/%5C/g, "\\");
    url = url.replace(/%2F/g, "\/");
    // url = url.replace(/\:/g, "%3A");
    return url;
}

function stripNewLines(result) {
    return result.replace(/\n\s+/g, "");
}

/**
 * converts the list of statuses returned from Redmine java into a array.
 * 
 * @param statuses
 */
function xmlToArray(xml, id) {
    var arr = new Array();
    var i = 0;
    while (xml != "") {
        arr[i] = getRetVal(xml, id);
        var xmlTag = "</" + id + ">";
        var loc = xml.indexOf(xmlTag);
        var str = xml.substr(loc + xmlTag.length);
        xml = str;
        i++;
    }
    return arr;
}

/**
 * generates html for a select form given an array of values, an id, and
 * multiple as a boolean.
 * 
 * @param values
 *            An array of values to select from.
 * @param id
 *            The id of the document element.
 * @param multiple
 *            True if the user can select multiple, and false for single select.
 * @returns Html code for a select list.
 */
function genSelectHtml(values, id, multiple) {
    var html = "<select ";
    if (multiple) {
        html += "multiple style='height:300px' ";
    }
    html += "id='" + id + "'>";
    for ( var i = 0; i < values.length; i++) {
        html += "<option value='" + values[i] + "'>" + values[i] + "</option>";
    }
    html += "</select>";
    return html;
}
/**
 * convert selected values from a html generated select box from the function
 * above genSelectHtml to a parameter that can be passed in a url
 * 
 * @param releases
 * @returns
 */
function convertSelectHtmlToUrlParam(selectVal, attributeTag) {
    var str = "";
    for ( var i = 0; i < selectVal.length; i++) {
        str += "&" + attributeTag + "='" + selectVal[i] + "'";
    }
    return str; // remove first comma
}















// load templates.js
var HandleTmpls = new Object();

function loadTemplate(templateName) {
    $.ajax({
        url : '' + templateName + '.html',
        dataType : 'text',
        async : false,
        success : function(resp) {
            var temp = Handlebars.compile(resp);
            HandleTmpls[templateName] = temp;
        }
    });
}

function loadTemplates() {
    // define templates below
    loadTemplate('mainPageSingleEnvDisplayTmpl');
    loadTemplate('editEnvInfoTmpl');
    loadTemplate('buildTransFormTmpl');
}
























