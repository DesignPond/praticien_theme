/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can 
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Google CDN, Latest jQuery
 * To use the default WordPress version of jQuery, go to lib/config.php and
 * remove or comment out: add_theme_support('jquery-cdn');
 * ======================================================================== */

(function($) {

	// Position of body when selecting categorie in resumes list
	if(window.location.hash) {
		var position = $("#anchorTitle").position();
	
		$('html, body').animate({
			scrollTop: 600
		}, 600);
	}
	
	// Tooltips
	$('#infoTip').tooltip({
		'html': true
	});
	
	// Form placeholder css	
	$('.default-value').each(function() {
	
		var default_value = this.value;
		
		$(this).css('color', '#aeaeae'); // this could be in the style sheet instead
		
		$(this).focus(function()
		{
			if(this.value === default_value) {
				this.value = '';
				$(this).css('color', '#0f4060');
			}
		});
		
		$(this).blur(function()
		{
			if(this.value === '') {
				$(this).css('color', '#aeaeae');
				this.value = default_value;
			}
		});
		
	});
	
	// Cloning form
	$("#cloneForm").click(function(e){
	
		e.preventDefault();
		var clones = $('.newForms .formAtf').length;
		
		if(clones === 0)
		{
			$("#deleteForm").show();
		}
		
		$('#containerFormAtf .formAtf').clone().appendTo('.newForms');
	});
	
	$("#deleteForm").click(function(e) {
	
		e.preventDefault();
		
		$(".newForms div:last-child").remove();
		
		var clones = $('.newForms .formAtf').length;
		
		if(clones === 0)
		{
			$("#deleteForm").hide();
		}
	});
	
	$( "#choixAnnee" ).datepicker({ dateFormat: "yy-mm-dd" });
	$( "#choixAnnee2" ).datepicker({ dateFormat: "yy-mm-dd" });


	// New arrets list
	$('#arrets').dataTable({
		"aaSorting": [[ 0, "desc" ]],"iDisplayLength": 25,"bAutoWidth": false , "aoColumns" : [{ "sWidth": "10%"},{ "sWidth": "10%"},{ "sWidth": "10%"},{ "sWidth": "25%"},{ "sWidth": "35%"},{ "sWidth": "10%"}],
		"oLanguage": {
		"sLengthMenu": "Montrer _MENU_ lignes par page",
		"sZeroRecords": "Rien trouv&eacute;",
		"sInfo": "_START_ &agrave; _END_ sur _TOTAL_ lignes",
		"sInfoEmpty": "0 &agrave; 0 sur 0 lignes",
		"sInfoFiltered": "(filtre de _MAX_ total lignes)",
		"oPaginate": {
			"sFirst":"Premier",
			"sPrevious":"Pr&eacute;c&eacute;dent",
			"sNext":"Suivant",
			"sLast":"Dernier"
			}
		},"bFilter": false,"bStateSave": true });
		
	
	// Date filter for new arrets
	$( "#dateStart" ).datepicker({ dateFormat: "yy-mm-dd" });
	$( "#dateEnd" ).datepicker({ dateFormat: "yy-mm-dd" });
	
	// alertes tabs
	$( "#tabs" ).tabs();
	

	/**
	 * Rythm function
	*/
		$('select#rythme').change(function(){
			var rythme = $(this).val();

			$.ajax({
				url:"wp-admin/admin-ajax.php",
				type:'POST',
				data:'action=set_rythme&rythme=' + rythme,
				success:function(results)
				{
					console.log('rythm changed');
				}
			});
		});
					
	/**
	 * End Rythm function
	*/
		
	/**
	 * Alertes functions
	*/
		// Input for keywords
		$("a.addKeywords").click(function(e) {
			e.preventDefault();
			var $wrapper   = $('<p />',{'class': 'new_input' });
			var $icon      = $('<i />',{'class': 'remove_input' });
			var $input     = $('<input class="key" type="text" name="selectKey[]" placeholder="Mots clés séparés par virgules" value="" />');
			var $container =  $(this).parent().closest('li').find('.check-choice');
			 
			$icon.appendTo($wrapper);
			$input.appendTo($wrapper);
			$wrapper.appendTo($container);
			 
		});
				
		$('body').on('click', 'i.remove_input', function (event) {
			event.preventDefault();
			$(this).parent().remove();
		});
		
		// Checkboxes
		$(".checklist input:checked").parent().addClass("selected");
		$('.check-choice input[class="key"]:empty').remove();
		

		/**
		* Checkbox alertes select functions 
		*/
		$(".checklist .checkbox-select").click( function(event) {
		
			// Prevent default behavior
			event.preventDefault();
			
			// Cleaning empty inputs
			$(":input.key").each(function() {
				if($(this).val() === ""){
					$(this).remove();
				}
			});
			
			// Data
			var textArray = [];
			
			// Retrive all keywords from inputs and put in array
			$(this).parent()
				.closest('li')
				.find(".check-choice input[class='key']")
				.each(function(){
					textArray.push($(this).val());
				}
			);
			
			// Filter empty values
			textArray.filter(function(e){return e;});
			
			// Get categorie id
			var catid = $(this).parent().closest('li').data("id");
			
			// only publications checked?
			var $pub = $(this).parent().closest('li').find(".check-ispub input[class='ispub']");
			
			var ispub = ($pub.is(':checked')) ? 1 : 0;
			
			// Preparing data to pass via ajax
			var data = {
				action   : 'set_abos',
				catid    : catid,
				keywords : textArray,
				ispub    : ispub
			};
			
			// reference to this checkbox for inside ajax succes callbak
			var self = $(this);
			
						// Ajax call						 
			$.ajax({
				url     : "wp-admin/admin-ajax.php",
				type    : 'POST',
				data    : data,
				success : function(results)
				{
					// Hide or show limit-pub
					var limit = self.parent().closest('li').find('i.limite-pub');
					
					if(ispub === 0) {limit.hide();}
					else {limit.show();}
					
					// empty keywords from list
					var p = self.parent().closest('li').find('div.keywords div.listeCles');
					p.empty();
					
					// add all "new keywords"
					$.each( textArray, function( key, value )
					{
						var $keyp = $('<p>'+value+'</p>');
						$keyp.appendTo(p);
					});
					
					// Add class select to checkbox, checked and flash succes message
					self.parent().parent().addClass("selected");
					self.parent().parent().find(":checkbox").attr("checked","checked");
					self.parent().parent().find(".successMsg").fadeIn(900).fadeOut(1800);
				}
			}); // end ajax call
			
		});

		/**
		 * Checkbox alertes deselect functions
		*/
		$(".checklist .checkbox-deselect").click(function(event) {
		
			// Prevent default behavior
			event.preventDefault();
			
			// Get id to send
			var catid = $(this).parent().closest('li').data("id");
			
			// Nouveau tableau
			var bakArray = [];
			
			// Recupère les entrées des inputs
			$(this).parent()
				.closest('li')
				.find("div.keywords div.listeCles p")
				.each(function()
				{
					bakArray.push($(this).text());
				}
			);
			
			// FIltre le tableau pour enlever les chaines vides
			bakArray.filter(function(value) {
				return value !== "" && value !== null;
			});
			
			// reference to this checkbox for inside ajax succes callbak
			var self = $(this);
			
			// Enleve les inputs restants
			var $container2 =  $(this).parent().closest('li').find('.check-choice');
			$container2.find('.new_input').remove();
			
			
			$.ajax({
				url    : "wp-admin/admin-ajax.php",
				type   : 'POST',
				data   : 'action=delete_abos&catid=' + catid,
				success: function(results)
				{
					// empty keywords from list
					var p = self.parent().closest('li').find('div.keywords div.listeCles');
					p.empty();
					
					// Add keywords in inputs
					$.each( bakArray, function( key, value )
					{
					var $wrapper = $('<p />',{'class': 'new_input' });
					var $icon    = $('<i />',{'class': 'remove_input' });
					
					var $input2  = $('<input />',{
						'type' : 'text',
						'value': value,
						'name' : 'selectKey[]',
						'placeholder' : 'Mots clés séparés par virgules',
						'class': 'key'
					});
					
					$icon.appendTo($wrapper);
					$input2.appendTo($wrapper);
					$wrapper.appendTo($container2);
					});
					
					// Remove class select to checkbox, checked and hide limit pub icon
					self.parent().parent().removeClass("selected");
					self.parent().parent().find(":checkbox").removeAttr("checked");
					self.parent().closest('li').find('i.limite-pub').hide();
				}
			}); // end ajax call
		
		});
						
	/**
	 * End alertes functions
	*/
	
	

// Use this variable to set up the common and page specific functions. If you 
// rename this variable, you will also need to rename the namespace below.
var Roots = {
  // All pages
  common: {
    init: function() {

    }
  },
  // Home page
  home: {
    init: function() {
      // JavaScript to be fired on the home page
    }
  },
  // About us page, note the change from about-us to about_us.
  about_us: {
    init: function() {
      // JavaScript to be fired on the about us page
    }
  }
};

// The routing fires all common scripts, followed by the page specific scripts.
// Add additional events for more control over timing e.g. a finalize event
var UTIL = {
  fire: function(func, funcname, args) {
    var namespace = Roots;
    funcname = (funcname === undefined) ? 'init' : funcname;
    if (func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function') {
      namespace[func][funcname](args);
    }
  },
  loadEvents: function() {
    UTIL.fire('common');

    $.each(document.body.className.replace(/-/g, '_').split(/\s+/),function(i,classnm) {
      UTIL.fire(classnm);
    });
  }
};

$(document).ready(UTIL.loadEvents);

})(jQuery); // Fully reference jQuery after this point.
