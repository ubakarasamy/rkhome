document.addEventListener( 'DOMContentLoaded', function () {
	 
	var parent_div = document.getElementById('shipping:firstname');
	 
	var isP = jQuery("#isP").val();
	if(isP == "1"){
	jQuery(parent_div).closest("form").before('<a onclick="javascript:pudoPopup()" href="javascript:void(0)" class="pick">Find nearest parcel pickup point</a>');
	
	jQuery('#co-billing-form li.control:last').after('<li class="control"> <input type="radio" class="radio validation-passed" onclick="$(#shipping:same_as_billing).checked = false;" title="Ship to different address" checked="checked" value="0" id="billing:use_for_shipping_no1" name="billing[use_for_shipping]"><label for="billing:use_for_shipping_no1">Find nearest Parcel pickup point</label></li>');
	
	var zip_input = document.getElementById('billing:postcode');
	jQuery(zip_input).blur(function(){ 
		jQuery('#address').val(zip_input.value);
	});
	
}
	 
	}, false );
	
function initialize()
	{  
		var icon_home = $('loc').value; 
		if($("shipping_agent_id")) {
		$("shipping_agent_id").remove();
		}  
		var latlong = jQuery('div.agent_latlong').html();  
		var myArr = '';
		if(latlong != 'undefined'){
		myArr = latlong.split(','); 
		}else{
		myArr = '';
		}
		var address;
		var latitude;
		var longitude;
		var geocoder = new google.maps.Geocoder(); 
        if($('criteria').value) {
            address = $('criteria').value;
        } else {
            if($('billing-address-select'))
                {
                if($('billing-address-select').value){
                    address = $('billing-address-select')[$('billing-address-select').selectedIndex].text;

                } else {
                    address = $('billing:company').value+', '+$('billing:street1').value+', '+$('billing:street2').value+', '+
                        $('billing:city').value+', '+$('billing:postcode').value+', '+$('billing:country_id')[$('billing:country_id').selectedIndex].text;
                }
            }
            else
            {
                address = $('billing:company').value+', '+$('billing:street1').value+', '+$('billing:street2').value+', '+
                    $('billing:city').value+', '+$('billing:postcode').value+', '+$('billing:country_id')[$('billing:country_id').selectedIndex].text;
            }
        }

		geocoder.geocode( { 'address': address}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			latitude = results[0].geometry.location.lat();
			longitude = results[0].geometry.location.lng();
		}
        }); 
        if(latitude && longitude) {
            var myCenter=new google.maps.LatLng(latitude,longitude);
        } else {
            var myCenter=new google.maps.LatLng(myArr[0],myArr[1]);
        } 
        var mapProp = {
            center:myCenter,
            zoom:14,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        }; 
        var map=new google.maps.Map(document.getElementById("googleMap"),mapProp); 
        var marker=new google.maps.Marker({
            position:myCenter,
		 	title: myArr[2],
			icon: icon_home
        }); 
        marker.setMap(map);
        var infowindow = new google.maps.InfoWindow();  
        $$('div.agent_item').each(function(item){  
            var subitem = item.down('div.agent_latlong');
            var latlong = subitem.innerHTML; 
            var myArr_1 = latlong.split(','); 
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(myArr_1[0], myArr_1[1]),
                map: map,
                title: myArr_1[2],
                icon: icon_home
            });

            google.maps.event.addListener(marker, 'click',
                function(){
                    infowindow.close();//hide the infowindow
                    var content = '<b>'+item.down('#agent_detail_tbl').innerHTML+'</b><br/>'+ item.down('div.agent_timing').innerHTML

                    infowindow.setContent(content);//update the content for this marker
                    infowindow.open(map, marker);//"move" the info window to the clicked marker and open it
                    item.down('input:radio').checked = true ;
				}
            ); 
            google.maps.event.addDomListener(item, 'click', function () {
                google.maps.event.trigger(marker, 'click'); 
            }); 
        });

    } 
function getlist(address){  
		address = address+",india"; 
		var trimaddress	= address.trim();
		var geocoder;
		geocoder = new google.maps.Geocoder();
		geocoder.geocode({'address': trimaddress}, function(results, status) { 
        if (status == google.maps.GeocoderStatus.OK) {
           var rawlatlong = jQuery('#coord').val(results[0].geometry.location);
           var latitude = results[0].geometry.location.lat();
           var longitude = results[0].geometry.location.lng();
           var lats =  latitude.toFixed(4);
           var longs  = longitude.toFixed(4); 
			jQuery.ajax({
			url: BASE_URL+"pudo/index/getlist",
			async:true,
            crossDomain:true,
			data: "query="+trimaddress+"&latitude="+lats+"&longitude="+longs,
			beforeSend: function() { 
		     jQuery('.loader').show(); 
			},
			context: document.body
			}).done(function(response) {
				if(response)
				jQuery('#agent-list').html(response);
				jQuery('.loader').hide();
				initialize();
			});
           
        } else {
            alert("Please fill City or Zip code");
        }
    });
	}
	 
	function close()
	{
		jQuery('#pudo_popup').hide();
	}

	function updateShippingAddress()
	{
	if($('shipping-address-select'))
	{
		var options = $$('select#shipping-address-select option');
		options[options.length-1].selected = true;
		shipping.newAddress(1);
	}
	var parent = $$('input:checked[type=radio][name=agent_checkbox]')[0].up();
	
	document.getElementById('shipping:street1').value = parent.down('div.agent_address').innerHTML;
	document.getElementById('shipping:street2').value = '';
	$('shipping:street1').readOnly = true;
	$('shipping:street2').readOnly = true;
	document.getElementById('shipping:city').value = parent.down('div.agent_city').innerHTML;
	$('shipping:city').readOnly = true;
	document.getElementById('shipping:postcode').value = parent.down('div.agent_postcode').innerHTML;
	$('shipping:postcode').readOnly = true;
	document.getElementById('shipping:telephone').value = document.getElementById('billing:telephone').value;
	document.getElementById('shipping:country_id').value ='IN';
	$('shipping:telephone').readOnly = true;
	$('shipping:same_as_billing').checked = false; 

	document.getElementById('shipping:firstname').value = document.getElementById('billing:firstname').value; 
	document.getElementById('shipping:lastname').value=document.getElementById('billing:lastname').value;
	var agent_id = parent.down('div.agent_id').innerHTML;
	if($("shipping_agent_id"))
	{
		$("shipping_agent_id").remove();
	}
	$('shipping-new-address-form').insert('<input type="hidden" id="shipping_agent_id" value="'+agent_id+'" name="shipping[agent_id]">'); 
	 document.getElementById('shipping:company').value =agent_id;
	$('shipping:company').readOnly = true;
  
	 
	close(); 
	}
	
	
function pudoPopup(){
	
	  jQuery('.loader').css('display','block'); 
	    new Ajax.Request(BASE_URL+'pudo/index/ajax/',
            {
                method:'post', 
                async:true,
            crossDomain:true,
                onSuccess: function(transport){ 
                    var response3 = transport.responseText; 
					jQuery('#pudo_popup').html(response3);   
                    new Ajax.Request(BASE_URL+'pudo/index/pickuplist/',
                        {
                            method:'post', 
                            async:true,
							crossDomain:true,
                            onSuccess: function(transport){
                                var response1 = transport.responseText; 
								jQuery('#agent-list').html(response1);
                                jQuery('#pudo_popup').css('display','block');
								jQuery('#agent-list').css('display','block');
                                jQuery('.loader').css('display','none');
                                var postcode = document.getElementById('billing:postcode').value;
								jQuery('#criteria').val(postcode);
								jQuery('#BTN').trigger('click');
                            },
                            onFailure: function(){
                                jQuery('.loader').css('display','none');
                            }
                        });
                },
                onFailure: function(){  }
            }); 
	}

 


