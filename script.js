'use strict';

Vue.config.devtools = false;
Vue.create = function(options) {
    return new Vue(options);
};

Vue.create({
    el: 'main',
    data: {
        names: [],
        input: {}
    },
    methods: {
        loadNames: function(link = 'api.php') {
            let vm = this;

            axios.get(link).then(function(response) {
                vm.names = response.data;
            }).catch(function(error) {
                console.log(error.statusText);
            });
        },

        addName: function(link = 'api.php') {
            let vm = this;

            axios.post(link, {
                name: vm.input.name,
                first: vm.input.first,
                second: vm.input.second
            }).then(function (response) {
                vm.names = vm.names.concat(response.data);
                vm.input = {};
            }).catch(function (error) {
                console.log(error.statusText);
            });
        },

        deleteNames: function(link = 'api.php') {
            let vm = this;

            axios.delete(link).then(function(response) {
                vm.names = response.data;
            }).catch(function(error) {
                console.log(error.statusText);
            });
        }
    }
});
