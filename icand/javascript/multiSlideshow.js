/* 
 * version 2012-10-10	JT
 */

// Extending jQuery Cycle plugin for eHub
// to be able to have multiple instances of slideshows on one page - JT - 29/06/2012
$(document).ready(function(){

	// possible effects
	var effectsList = ["fade","scrollDown","cover","blindX","scrollUp","curtainX","growX","blindY","blindZ","scrollRight","scrollLeft"];
	
	// looping through all divs to get slideshows ids
	$("div").each(function(i){
		// store current ids
		var effectId = this.id;
		// generating a random number to be use for timeout below
		//var randTimeOut = Math.random()*10000;
		// looping thought the effects list and compare it to the current id
		for(i=0; i<effectsList.length; i++)
			{
				// if it is a slideshow then go ahead and apply effects
				if(effectId==effectsList[i]){
				// adding counter to this id
				var slideshowId = this.id + "_" + i;
				// amending new id's & start slideshow
				$(this).attr("id", slideshowId).cycle({
				// type of effect
				fx: effectId,
				// loading images
				random: 1,
				// effect speed
				speed:'1000',
				// automation of slideshow, timeout:0 means manual
				//timeout: randTimeOut,
				// make this id unique so that multiple instances can exist on one page
				next:   $("#next").attr("id", slideshowId),
				// make this id unique so that multiple instances can exist on one page
				prev:   $("#prev").attr("id", slideshowId)
				});
			}
			}
	});
});