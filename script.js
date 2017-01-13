'use strict';

Vue.config.devtools = false;
Vue.create = function(options) {
    return new Vue(options);
};

Vue.create({
    el: 'main',
    data: {
        names: []
    },
    methods: {
        loadNames: function(link = 'api.php') {
            this.$http.get(link).then(function(response){
                this.names = response.data;
            }, function(error){
                console.log(error.statusText);
            });
        }
    }
});
