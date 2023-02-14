var config = {
    map: {
       '*': {
           'deloitte/map-loader' : 'Deloitte_ClickAndCollect/js/map-loader',
           'deloitte/stores-provider' : 'Deloitte_ClickAndCollect/js/model/stores-provider',
           'deloitte/map' : 'Deloitte_ClickAndCollect/js/view/map',
           'Magento_Checkout/js/model/shipping-save-processor/default': 'Deloitte_ClickAndCollect/js/model/shipping-save-processor/default',
           deloitte_stockists: 'Deloitte_ClickAndCollect/js/deloitte_stockists',
           async: 'Deloitte_ClickAndCollect/js/async',
           stockists_countries: 'Deloitte_ClickAndCollect/js/stockists_countries',
           stockists_mapstyles: 'Deloitte_ClickAndCollect/js/stockists_mapstyles',
           stockists_search: 'Deloitte_ClickAndCollect/js/stockists_search',
           stockists_widget: 'Deloitte_ClickAndCollect/js/stockists_widget',
           stockists_geolocation: 'Deloitte_ClickAndCollect/js/stockists_geolocation',
           stockists_slick: 'Deloitte_ClickAndCollect/js/stockists_slick',
           stockists_individual_stores: 'Deloitte_ClickAndCollect/js/deloitte_individual_stores'
       }
    },
    config: {
    	mixins: {
            'Magento_Checkout/js/view/shipping': {
                'Deloitte_ClickAndCollect/js/view/plugin/shipping': true
            }
        }
    }
};
