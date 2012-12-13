/*
Function: jQuery initialisation of accordion and tabs widgets
Purpose : initialise multiple accordian and tab widgets on the same page

Notes:
each Accordian widget must have the class of accordion to be processed
each Tabs      widget must have the class of tabbed    to be processed

The .accordon and .tabbed classes are not defined anywhere as they are
just placeholders for the jQuery initialisation process

Prefix the function with jQuery incase other libraries are used.

Copyright: OTEN

Date		Author	Reason
--------------------------
2012-03-08	JN		Create function
2012-08-25	JT		enhance accessibility for Tabs and Accordion as per Andrew Downie advice
2012-10-10	JT
*/
// jQuery(function(){ 
$(document).ready(function(){ 
   //--- working variables
	var x;		//--- loop counter
	var id;		//--- holds unique id for each widget
	var tabList = $(".tabbed");		//--- number of tab widgets on the page
	var accList = $(".accordion");	//--- number of accordion widgets on the page
	
	// Tabs	
	for(x=0; x<tabList.length; x++ ) {
		//--- create and assign a unique id to each tabbed class
		id = "tabs_"+x;
		tabList[x].id = id;
		// initialise tabs
		$('#'+id).tabs({
			show: function(event, ui){
				$('ul li.ui-state-default div#accessTab').empty().append('Tab Hidden');
				$('ul li.ui-state-active div#accessTab').empty().append('Tab Revealed');
			} 
		});
}
		$('ul li.ui-state-default').append('<div id="accessTab" style="position: absolute; left: -10000px;">Tab Hidden</div>');
		$('ul li.ui-state-active div#accessTab').empty().append('Tab Revealed');
	
	// Accordion
	for(x=0; x<accList.length; x++ ) {
		//--- create and assign a unique id to each accordion class
		id = "accordion_"+x;
		accList[x].id = id;
		$("#"+id).accordion(
			{
				heading: 'h4',
				collapsible : true,
				active : 'none',
				clearStyle: true,
				change: function(event, ui){	
					$('h4.ui-state-default div#accessAcc').empty().append('Collapsed');
					$('h4.ui-state-active div#accessAcc').empty().append('Expanded');
				}
			}
	);
	}
	// Once accordion initialise, go through and add this div and apply style
	$('.accordion h4').append('<div id="accessAcc" style="position: absolute; left: -10000px;">Collapsed</div>');
});