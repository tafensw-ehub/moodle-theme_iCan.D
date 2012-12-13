/**
 * Licence 
 * 
 * This software is distributed under the terms and conditions of the GNU General Public Licence, v 3 (GPL). 
 * The complete terms and conditions of the GPL are available at http://www.gnu.org/licenses/gpl-3.0.txt
 * 
 * Copyright Notice 
 *
 * Copyright © NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012 
 *
 * Acknowledgements
 * - Renee Lance, project manager and lead designer 
 * - Glen Byram, lead programmer
 */
function setCookie(c_name,value,exdays) {
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value + "; path=/"  
	// path is important, otherwise > 1 cookie set 
//alert ( document.cookie );	
}

function getCookie(c_name) {
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++){ //loop, search for our cookie
	  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
	  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
	  x=x.replace(/^\s+|\s+$/g,"");
	  if (x==c_name) {
		return unescape(y);
	  }//if
	}//foor
}//getCookie

var HIRES_COOKIE_NAME = "ehubhires";

function checkHiresCookie() {
	var l_cookievalue=getCookie(HIRES_COOKIE_NAME);
	if (l_cookievalue!=null) {
		set_hires_on();
	}//if
}//checkHiresCookie()
//======================================

function getStyleOfSFirstTag(el,styleProp)
{
    var x = document.getElementsByTagName(el)[0];
    if (x.currentStyle) {
        var y = x.currentStyle[styleProp];
    } else if (window.getComputedStyle) {
        var y = document.defaultView.getComputedStyle(x,null).getPropertyValue(styleProp);
	}
    return y;
}

function set_hires_on() {
	var dom_body = document.getElementsByTagName("body")[0];
	dom_body.className += ' icand_hires';
	document.getElementById("HiRes").value = "HiRes off"; // change button text
}

function set_hires_off() {
	var dom_body = document.getElementsByTagName("body")[0];
	dom_body.className = dom_body.className.replace(" icand_hires","");	// remove the class string	
	document.getElementById("HiRes").value = "HiRes on"; // change button text
}

function toggle_hires(p_caller) {
	var dom_body = document.getElementsByTagName("body")[0];
	if ( p_caller.value == "HiRes on" ) { // switch on
		set_hires_on( );
		setCookie(HIRES_COOKIE_NAME,"on",7); //save for 7 days
	} else { // switch off
		set_hires_off();
		setCookie(HIRES_COOKIE_NAME,"off",0); // set expiry to now, means cookie disappears
	}
	return true;
}

function change_fontsize(p_caller,p_addorminus) {
// DOESN'T WORK IN MSIE7
	var p_add = 3;
    var dom_body = document.getElementsByTagName("body")[0];
	var styleProp = 'font-size' ;
	var l_iswithinrange = false;
	var y;
    if (dom_body.currentStyle) { // an older API which IE previously supported
        y = dom_body.currentStyle[styleProp];
    } else if (window.getComputedStyle) {
        y = document.defaultView.getComputedStyle(dom_body,null).getPropertyValue(styleProp);
	} 
	if ( y == undefined && dom_body.style ) {
		y = dom_body.style[styleProp];
	}
	
	if ( y == undefined ) {		//can never find size in MSIE 
		y = "13px";
	}
	y = +(y.replace("px",""));	// remove px, convert to number
	if ( p_addorminus == '+' ) {
		l_iswithinrange = y < 30;
		y += p_add;
	} else { // making font small
		l_iswithinrange = y > 10;
		y -= p_add;
	}
	if ( l_iswithinrange ) {
		y += "px";
		//dom_body.style[ styleProp ] = y;
		//if (dom_body.style.fontSize) {
			dom_body.style.fontSize = y;
		//} else {
//			alert('bad');
//		}
	}
	return true;
}

//==============================================

function positionfooter(){
var ver = navigator.appVersion;
	if (ver.indexOf("MSIE") != -1) {
		return;
	}
	var footer = document.getElementById("footer");
	if ( footer != undefined ) {
		var l_page = document.getElementById("icand_nonfooter");
		  var footerHeight = footer.clientHeight; //style.height;
			l_page.style.paddingBottom = (footerHeight + "px");		//!!! this resizes the page!!
			footer.style.marginTop = ("-" + footerHeight + "px");
		//  alert( l_page.style.paddingBottom );
		}	
}

/*
function setmenuzindex(p_idName, p_zindex) {
	var l_targetelement = document.getElementById(p_idName);
	if ( l_targetelement != undefined ) {
		var k = l_targetelement.style;
			k.display = "block";
			k.zIndex = p_zindex;
	}
	return true;
}
*/

function setitemdisplay(p_idName, p_displayvalue) {
// p_displayvalue={'none','block'} to hide/reveal
	var l_targetelement = document.getElementById(p_idName);
	if ( l_targetelement != undefined ) {
		var k = l_targetelement.style;
			k.display = p_displayvalue;
	}
	return true;
}

function processthememenu(p_form) {
    for (Count = 0; Count < 3; Count++) {
        if (p_form.color[Count].checked)
            break;
    }
    var l_color  = p_form.color[Count].value;
	// var l_colortext = p_form.color[Count].text; can't do it!!!
	
    Item = p_form.layout.selectedIndex;
    var l_layout = p_form.layout.options[Item].value; // text;
	var l_layouttext = p_form.layout.options[Item].text;
	
    //alert ("You typed: " + l_color + " " + l_layout);
	alert ("You typed: " + l_color + " " + l_layouttext + "(" + l_layout + ")" );
	
	setmenuzindex( -1 );
	return true;
}
//=================================================
var maxtextsize = 2.0;
var defaultsize = 1.0; //
var mintextsize = 0.8; //.8em
var DAYS_TO_SAVECOOKIE = 7;

var TEXTSIZE_COOKIE_NAME = "ehubtextzoom";

/*function initialiseDefaultSizeEm( p_size ) {
	defaultsize = p_size;
}*/

function changeTextSize( p_multiplier) {
// REQUIRES: font-size to be defined on html element
	if ( p_multiplier == 0 ) {
		document.body.style.fontSize = defaultsize + "em";
		setCookie(TEXTSIZE_COOKIE_NAME,document.body.style.fontSize,0); // make cookie expire
	} else {
		l_currentsize = (document.body.style.fontSize == "") 
					? defaultsize
					: parseFloat(document.body.style.fontSize);
		if ( p_multiplier > 0 && l_currentsize >= maxtextsize )
			return;
		if ( p_multiplier < 0 && l_currentsize <= mintextsize )
			return;	
		document.body.style.fontSize = l_currentsize + (p_multiplier * 0.2) + "em";
		setCookie(TEXTSIZE_COOKIE_NAME, document.body.style.fontSize, DAYS_TO_SAVECOOKIE); //save for x days
	}
positionfooter();
}

function upSize() {
	changeTextSize( +1 );
}

function downSize() {
	changeTextSize( -1 );
} 

function defaultSize() {
	changeTextSize( 0 );
}

function checkTextSizeCookie() {
	var l_cookievalue=getCookie(TEXTSIZE_COOKIE_NAME);
	if (l_cookievalue!=null) {
		// alert( l_cookievalue );
		//alert ( document.cookie );
		document.body.style.fontSize = l_cookievalue;
	}//if
}//checkHiresCookie()


//=================================================

// top level. Run when the page is loaded.
checkHiresCookie(); 
checkTextSizeCookie();
positionfooter();