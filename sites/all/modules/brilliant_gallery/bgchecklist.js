$(document).ready(
		function () {
				// get the node id from where we left it in the header
				//var nodeid=$("div#nodeid/").val();
    //var nodeid=$("input#edit-nid").val(); # WORKED but we actually don't need node ID - so let's use some static number:
      var nodeid=9999999;
				//alert(nodeid);
				// load the current state of the checkboxes once on loading page
				$.getJSON("/bgchecklist/loadall/"+nodeid, 
	function(json) {
										//alert(json);
										for( i=0; i < json.length; i++ ) {
													if (json[i].state == "1") {
															$("#"+json[i].qid).attr("checked","true").next().html('<font color=red>invisible</font>');
													} else {
															$("#"+json[i].qid).removeAttr("checked").next().html('<font color=green>visible</font>');
													}
										}
								}); 

				// setup an onclick for each checkbox that writes it state back to the database
				// when toggled. The label text is turned red while writing to the db.
				//$("div.bgchecklist/div.form-item/>").each(
				$("input.form-brilliant_gallery_checklist-checkbox").each(
	function () {
											$(this).click( 
														function () {
																//var thislabel=$(this).parent();
                var thislabel=$(this).next();
																var colorbefore=$(thislabel).css("color");
																//alert("/bgchecklist/save/"+nodeid+"/"+$(this).attr("id")+"/1");
																//if ( $(this).attr("checked") == undefined ) {
																if ( $(this).attr("checked") == false ) {
																			//$(thislabel).css("color","red"); 
                   $(thislabel).html("saving...").css("color","red");
																			$.get("/bgchecklist/save/"+nodeid+"/"+$(this).attr("id")+"/0",
			function() {
																										//$(thislabel).css("color",colorbefore); 
                          $(thislabel).html('<font color=green>visible</font>').css("color","green");
																								});
																} else {
																			//$(thislabel).css("color","green"); 
                   $(thislabel).html("saving...").css("color","red");
																			$.get("/bgchecklist/save/"+nodeid+"/"+$(this).attr("id")+"/1",
																					function() {
  		 																					//$(thislabel).css("color",colorbefore); 
                          $(thislabel).html('<font color=red>invisible</font>').css("color","red");
																					});
																}
														});
								});
		}
);
