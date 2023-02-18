define([
    'uiComponent',
    'ko',
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/modal',
    'Magento_Checkout/js/model/quote',
    'deloitte/map-loader',
    'deloitte/map'   
], function (Component, ko, $, $t, modal, quote, MapLoader, map) {
    'use strict';

    var popUp = null;
    
    return Component.extend({
        defaults: {
            template: 'Deloitte_ClickAndCollect/checkout/shipping/select-store'
        },
        isClickAndCollect: ko.observable(false),
        isSelectStoreVisible: ko.observable(false),
        isMapVisible: ko.observable(false),

        initialize: function () {
            var self = this;
            quote.shippingMethod.subscribe(function () {
            	if (quote.shippingMethod().carrier_code == 'cnc') {
                    self.isClickAndCollect(true);
                    var stores = $.parseJSON(window.checkoutConfig.shipping.select_store.stores);
                    if(stores.totalRecords > 1) {
                        self.isSelectStoreVisible(true);
                    }
            	} else {
                    self.isClickAndCollect(false);
            	}
            });

            this.isMapVisible.subscribe(function (value) {
                if (value) {
                    self.getPopUp().openModal();
                } else {
                    self.getPopUp().closeModal();
                }
            });

            ko.bindingHandlers.datetimepicker = {
                init: function (element, valueAccessor, allBindingsAccessor) {
                    var $el = $(element);
                    $el.datetimepicker({
                        'showTimepicker': false,
                        'format': 'yyyy-MM-dd',
                        'minDate': 0,
                    });
                    var writable = valueAccessor();
                    if (!ko.isObservable(writable)) {
                        var propWriters = allBindingsAccessor()._ko_property_writers;
                        if (propWriters && propWriters.datetimepicker) {
                            writable = propWriters.datetimepicker;
                        } else {
                            return;
                        }
                    }
                    writable($(element).datetimepicker("getDate"));
                },
                update: function (element, valueAccessor) {
                    var widget = $(element).data("DateTimePicker");
                    if (widget) {
                        var date = ko.utils.unwrapObservable(valueAccessor());
                        widget.date(date);
                    }
                }
            };

            $('body').on('click', '.apply-store', function() {
                $('#pickup-store').val($(this).data('id'));
                $('#selected-store-msg')
                    .show()
                    .find('span')
                    .text( $(this).data('name') );
                self.isMapVisible(false);
            });
            
            return this._super();
        },

        showMap: function () {
            this.isMapVisible(true);
        },
        getPopUp: function () {
            var self = this,
                buttons;

            if (!popUp) {
                MapLoader.done($.proxy(map.initMap, this)).fail(function() {
                    console.error("ERROR: Google maps library failed to load");
                });
                popUp = modal({
                    'responsive': true,
                    'innerScroll': true,
                    'buttons': [],
                    'type': 'slide',
                    'modalClass': 'mc_cac_map',
                    closed: function() {
                        self.isMapVisible(false)
                    }
                }, $('.checkout-storelocator'));
            }

            $('#stockist-search-term').keypress(function(e) {
                var searchInput = document.getElementById('stockist-search-term');
                var autocomplete = new google.maps.places.Autocomplete(searchInput);
                google.maps.event.addListener(autocomplete, 'place_changed', function() {
                    map.initSearch(config);
                });                   
            });

            var config = window.checkoutConfig.shipping.select_store;
            
            
            $("#stockists-submit").on("click", function() {
                map.initSearch(config);
            });
            
            $("body").on("click", ".results-content", function () {
                var id = $(this).attr('data-marker');
                var searchResult = $(this).hasClass("search-result");
                map.changeMarker(id,searchResult);
            });

            $(".geocode-location").on("click", function() {
                map.initMap();
            });
           
            return popUp;
        }
    });
});