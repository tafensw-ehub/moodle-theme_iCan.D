/**
 * Licence 
 * 
 * This software is distributed under the terms and conditions of the GNU General Public Licence, v 3 (GPL). 
 * The complete terms and conditions of the GPL are available at http://www.gnu.org/licenses/gpl-3.0.txt
 * 
 * Copyright Notice 
 *
 * Copyright � NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012 
 *
 * Acknowledgements
 * - Renee Lance, project manager and lead designer 
 * - Glen Byram, lead programmer
 */
var icand_isDollarsExists = false; 
// set a flag if a javascript other than jQuery has declared "$"
if ((typeof  jQuery == 'undefined') 
	&& (typeof  ($) !== 'undefined') ) {
	icand_isDollarsExists = true;
}