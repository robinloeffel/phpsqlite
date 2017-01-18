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
        loadNames: function(link = '/api/names') {
            let vm = this;

            axios.get(link).then(function(response) {
                vm.names = response.data;
            }).catch(function(error) {
                console.log(error.response.data);
            });
        },

        addName: function(link = '/api/names/add') {
            let vm = this;

            axios.post(link, {
                name: vm.input.name,
                first: vm.input.first,
                second: vm.input.second
            }).then(function(response) {
                vm.loadNames();
                vm.input = {};
            }).catch(function(error) {
                console.log(error.response.data);
            });
        },

        deleteNames: function(link = '/api/names/delete') {
            let vm = this;

            axios.delete(link).then(function(response) {
                vm.names = [];
            }).catch(function(error) {
                console.log(error.response.data);
            });
        },

        deleteName: function(link = '/api/names/delete/' + this.input.deleteId) {
            let vm = this;

            axios.delete(link).then(function(response) {
                vm.loadNames();
            }).catch(function(error) {
                console.log(error.response.data);
            });
        },

        updateName: function(link = '/api/names/update/' + this.input.putId) {
            let vm = this;

            axios.put(link, {
                name: 'broseph'
            }).then(function(response) {
                vm.input.putId = '';
                vm.loadNames();
            }).catch(function(error) {
                console.log(error.response.data);
            });
        }
    }
});
