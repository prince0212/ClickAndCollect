define([
    'jquery',
    'mage/url'
], function ($, url) {
    'use strict';
    var markers = [];
    var searchMarkers = [];

    // for custom marker icon
    url.setBaseUrl(BASE_URL);
    var link = url.build('media/store-locator/map_pin.png');
    return {
        initMap: function() {
            $('.results-content').empty();
            navigator.geolocation.getCurrentPosition(function(position) {
                var self = this;
                var myLatLng = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                var map = new google.maps.Map(document.getElementById('map-canvas'), {
                    zoom: window.checkoutConfig.shipping.select_store.zoom,
                    center: myLatLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

               
                var marker = new google.maps.Marker({
                    map: map,
                    position: myLatLng
                });
                var circle = new google.maps.Circle({
                    center: myLatLng,
                    map: map,
                    strokeColor: "#0000A0",
                    strokeOpacity: 0.2,
                    strokeWeight: 2,
                    fillColor: "#00FFFF",
                    fillOpacity: 0.2,
                    radius: 20000 // in meters
                });
                circle.bindTo('center', marker, 'position');
                var stores = $.parseJSON(window.checkoutConfig.shipping.select_store.stores);
                var infoWindow = new google.maps.InfoWindow();
                var html = '';
                markers = [];
                $.each(stores, function(index, obj) {
                    if(index == 'totalRecords'){
                        return;
                    }
                    $.each(obj, function(key, store) {
                        var latitude = parseFloat(store.latitude),
                            longitude = parseFloat(store.longitude),
                            latLng = new google.maps.LatLng(latitude, longitude),
                            record_id = "" + latitude + longitude;
                            marker = new google.maps.Marker({
                                record_id: record_id,
                                position: latLng,
                                map: map,
                                title: store.name,
                                global_name: store.name,
                                global_address: store.address,
                                global_city: store.city,
                                global_postcode: store.postcode,
                                global_country: store.country,
                                zIndex: 999999,
                                icon: link
                            });
                            markers.push(marker);
                        //list all the stores
                        html += "<div class='results-content loaded-results' data-marker='" + latitude + longitude + "'>";
                        html += "<div class='results-item-data'>";
                        html += "<p class='results-title'>" + store.name + "</p>";
                        html += "<p class='results-phone'>" + store.city + "</p></div></div>";
                            
                        (function(marker, store) {
                            google.maps.event.addListener(marker, 'click', function(e) {
                                infoWindow.setContent('<div class="stockists-window"><p class="stockists-title"> '+ store.name + '</p> <p><strong>Street: </strong>' + store.street + '</p><p><strong>City: </strong>' + store.city + '</p><p><strong>PostCode: </strong>' + store.postcode + '</p><p><strong>Country: </strong>' + store.country_id + '</p>'
                                    +'<br /><button data-id="'
                                    + store.name + '" data-name="'+ store.name +'" class="apply-store action primary">Pick Up Here!</button></div>');
                                infoWindow.open(map, marker);
                                map.setCenter(latLng);
                            });
                        })(marker, store);
                    });
                });
                $('.results-store').html(html);
            });
        },
        initSearch: function(config) {
            var myLatLng = {
                lat: config.lat,
                lng: config.lng
            };
            var map = new google.maps.Map(document.getElementById('map-canvas'), {
                zoom: config.zoom,
                center: myLatLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            
            var geocoder = new google.maps.Geocoder();
            var infoWindow = new google.maps.InfoWindow();
            var stores = $.parseJSON(window.checkoutConfig.shipping.select_store.stores);
            var search = $("#stockist-search-term").val();
            $(".results-store").empty();
            $('.results-content').hide();
            $(".results-store").append("<span class='results-word'>Results for <span class='italic'>" + search + ":</span></span><br />");
            geocoder.geocode(
                {'address': search},
                function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            if (results[0]["types"][0] == "country") {
                                map.setZoom(5);
                                map.setCenter(results[0].geometry.location);
                                var marker = new google.maps.Marker({
                                    map: map,            
                                    position: results[0].geometry.location
                                });
                                for ( var i = 0; i < markers.length; i++) {
                                    if (markers[i].global_country == code_country) {
                                        if(config.unit == "default"){
                                            var store_distance = parseFloat(distance*0.001).toFixed(2);
                                            var unitOfLength = "kilometres";
                                        } else if(config.unit == "miles"){
                                            var store_distance = parseFloat(distance*0.000621371192).toFixed(2);
                                            var unitOfLength = "miles";
                                        }
                                        var contentToAppend = "<div class='results-content search-result' data-miles='"+store_distance+"' data-marker='"+markers[i].record_id+"'><p class='results-title'>"+markers[i].global_name+"</p>";
                                        if (markers[i].global_address) {
                                            contentToAppend += "<p class='results-address'>"+markers[i].global_address+"</p>";
                                        }
                                        if (markers[i].global_city) {
                                            contentToAppend += "<p class='data-phone'>"+markers[i].global_city+" "+markers[i].global_postcode+"</p>";
                                        }
                                        contentToAppend += "</div>";
                                        $(".results-store").append(contentToAppend);
                                    }
                                }
                            }
                            else{
                                map.setZoom(9);
                                map.setCenter(results[0].geometry.location);
                                var marker = new google.maps.Marker({
                                    map: map,            
                                    position: results[0].geometry.location,
                                });
                                var circle = new google.maps.Circle({
                                    map: map,
                                    radius: config.radius,    // value from admin settings
                                    fillColor: config.fillColor,
                                    fillOpacity: config.fillOpacity, 
                                    strokeColor: config.strokeColor,
                                    strokeOpacity: config.strokeOpacity,
                                    strokeWeight: config.strokeWeight
                                });
                                circle.bindTo('center', marker, 'position');
                                for ( var i = 0; i < markers.length; i++) {
                                    var distance = google.maps.geometry.spherical.computeDistanceBetween(marker.position, markers[i].position);
                                    if (distance < config.radius) {
                                        if(config.unit == "default") {
                                            var store_distance = parseFloat(distance*0.001).toFixed(2);
                                            var unitOfLength = "kilometres";
                                        } else if(config.unit == "miles"){
                                            var store_distance = parseFloat(distance*0.000621371192).toFixed(2);
                                            var unitOfLength = "miles";
                                        }
                                        var contentToAppend = "<div class='results-content search-result' data-miles='"+store_distance+"' data-marker='"+markers[i].record_id+"'><p class='results-title'>"+markers[i].global_name+"</p>";
                                        if (markers[i].global_address) {
                                            contentToAppend += "<p class='results-address'>"+markers[i].global_address+"</p>";
                                        }
                                        if (markers[i].global_city) {
                                            contentToAppend += "<p class='data-phone'>"+markers[i].global_city+" "+markers[i].global_postcode+"</p>";
                                        }
                                        contentToAppend += "<p class='data-miles'>"+store_distance+" "+unitOfLength+"</p></div>";
                                        $(".results-store").append(contentToAppend);
                                    }
                                }
                                var $wrapper = $('.results-store');
                                //sort the result by distance
                                $wrapper.find('.results-content').sort(function(a, b) {
                                    return +a.dataset.miles - +b.dataset.miles;
                                })
                                .appendTo($wrapper);
                               $.each(stores, function(index, obj) {
                                    if(index == 'totalRecords'){
                                        return;
                                    }
                                    $.each(obj, function(key, store) {
                                        var latitude = parseFloat(store.latitude),
                                            longitude = parseFloat(store.longitude),
                                            latLng = new google.maps.LatLng(latitude, longitude),
                                            record_id = "" + latitude + longitude;
                                            marker = new google.maps.Marker({
                                                    record_id: record_id,
                                                    position: latLng,
                                                    map: map,
                                                    title: store.name,
                                                    global_name: store.name,
                                                    global_address: store.address,
                                                    global_city: store.city,
                                                    global_postcode: store.postcode,
                                                    global_country: store.country,
                                                    zIndex: 999999,
                                                    icon: link
                                            });
                                            searchMarkers.push(marker);
                                        (function(marker, store) {
                                            google.maps.event.addListener(marker, 'click', function(e) {
                                                infoWindow.setContent('<div class="stockists-window"><p class="stockists-title"> '+ store.name + '</p> <p><strong>Street: </strong>' + store.street + '</p><p><strong>City: </strong>' + store.city + '</p><p><strong>PostCode: </strong>' + store.postcode + '</p><p><strong>Country: </strong>' + store.country_id + '</p>'
                                    +'<br /><button data-id="'
                                    + store.name + '" data-name="'+ store.name +'" class="apply-store action primary">Pick Up Here!</button></div>');
                                                infoWindow.open(map, marker);
                                            });
                                        })(marker, store);
                                    });
                                });
                                
                            }
                        }
                    }
                    else {
                        alert("No stores near your location.");
                    }
                }
            );
        },
        changeMarker: function (id,searchResult) {
            if(searchResult){
               for (var i = 0; i < searchMarkers.length; i++) {
                if (searchMarkers[i].record_id == id) {
                    google.maps.event.trigger(searchMarkers[i], 'click');
                }
            } 
            }else{
                for (var i = 0; i < markers.length; i++) {
                if (markers[i].record_id == id) {
                    google.maps.event.trigger(markers[i], 'click');
                }
            }
            }
            
        }
    }
});