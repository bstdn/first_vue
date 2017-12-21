/**
 * Created by Stefan Ho.
 * User: Stefan <xiugang.he@chukou1.com>
 * Date: 2017-10-16 13:56
 */
var Model = {
    PATH_MODULE: BASE_URL + 'people/',
    getAll: function(searchText, page, callback) {
        $.ajax({
            type: 'get',
            url: this.PATH_MODULE + 'get_all',
            data: {
                q: searchText,
                page: page
            },
            success: function(response) {
                callback(JSON.parse(response));
            }
        });
    },
    get: function(id, callback) {
        $.ajax({
            type: 'get',
            url: this.PATH_MODULE + 'get',
            data: {
                id: id
            },
            success: function(response) {
                callback(JSON.parse(response));
            }
        });
    },
    post: function(data, callback) {
        $.ajax({
            type: 'post',
            url: this.PATH_MODULE + 'insert',
            data: data,
            success: function(response) {
                callback(JSON.parse(response));
            }
        });
    },
    put: function(id, data, callback) {
        $.ajax({
            type: 'post',
            url: this.PATH_MODULE + 'update/' + id,
            data: data,
            success: function(response) {
                callback(JSON.parse(response));
            }
        });
    },
    remove: function(id, callback) {
        $.ajax({
            type: 'post',
            url: this.PATH_MODULE + 'delete',
            data: {
                id: id
            },
            success: function(response) {
                callback(JSON.parse(response));
            }
        });
    }
};

var Notification = Vue.extend({
    props: ['showSuccess', 'successMessage', 'showError', 'errorMessage'],
    template: '#notification'
});

Vue.mixin({
    data: function() {
        return {
            MODULE: 'People',
            showNotifSuccess: false,
            showSuccessMessage: '',
            showNotifError: false,
            showErrorMessage: ''
        }
    },
    methods: {
        setNotification: function(type, msg) {
            var self = this;
            if(type === 'success') {
                this.showNotifSuccess = true;
                this.showSuccessMessage = msg;
            } else {
                this.showNotifError = true;
                this.showErrorMessage = msg;
            }

            setTimeout(function() {
                self.showNotifSuccess = false;
                self.showSuccessMessage = '';
                self.showNotifError = false;
                self.showErrorMessage = '';
            }, 3000);
        }
    }
});

Vue.component('notification', Notification);

var cList = Vue.extend({
    template: '#list',
    data: function() {
        return {
            models: [],
            selectAll: false,
            pages: [],
            page: this.$route.query.page ? this.$route.query.page : 1,
            searchText: this.$route.query.q ? this.$route.query.q : ''
        }
    },
    methods: {
        getAll: function() {
            var self = this;
            var search = this.searchText;
            var page = this.page;

            Model.getAll(search, page, function(result) {
                if(result.code === 0) {
                    self.models = result.data.data;
                    for(var i = 0; i < self.models.length; i++) {
                        self.models[i].v_checked = false;
                    }
                    self.pages = result.data.pages;
                }
            });
        },
        search: function() {
            this.page = 1;
            router.push('/?q=' + this.searchText);
            this.getAll();
        },
        paging: function(page) {
            this.page = page;
            router.push('/?q=' + this.searchText + '&page=' + page);
            this.getAll();
        },
        deleteRow: function(event, model) {
            var self = this;
            var tr = $(event.target).parents('tr');

            swal({
                title: "Are you sure?",
                text: 'Are you sure delete this ' + self.MODULE + ' "' + model.first_name + '"?',
                icon: "warning",
                buttons: true,
                dangerMode: true
            }).then(function(willDelete) {
                if(willDelete) {
                    tr.animate({opacity: 0.3});
                    setTimeout(function() {
                        Model.remove(model.id, function(result) {
                            if(result.code === 0) {
                                tr.hide();
                                toastr["success"](result.msg);
                            } else {
                                tr.animate({opacity: 1});
                                toastr["error"](result.msg);
                            }
                        });
                    }, 1000);
                }
            });
        },
        deleteRows: function() {
            var self = this;
            var ids = [];
            this.models.forEach(function(item) {
                if(item.v_checked) {
                    ids.push(item.id);
                }
            });
            if(ids.length > 0) {
                swal({
                    title: "Are you sure?",
                    text: 'Are you sure delete selected?',
                    icon: "warning",
                    buttons: true,
                    dangerMode: true
                }).then(function(willDelete) {
                    if(willDelete) {
                        for(var i = 0; i < ids.length; i++) {
                            Model.remove(ids[i], function() {});
                        }
                        toastr["success"]('Success delete selected');
                        self.getAll();
                        self.selectAll = false;
                    }
                });
            } else {
                toastr["error"]('Not People selected');
            }
        },
        checkAll: function() {
            var self = this;
            this.models.forEach(function(item) {
                item.v_checked = self.selectAll;
            });
        },
        checkItem: function(event) {
            var check_count = 0;
            this.models.forEach(function(item) {
                if(item.v_checked) {
                    check_count++;
                }
            });
            if(event.target.checked) {
                if(check_count === this.models.length) {
                    this.selectAll = true;
                }
            } else {
                this.selectAll = false;
            }
        }
    },
    created: function() {
        this.getAll();
    }
});

var cForm = Vue.extend({
    template: '#form',
    data: function() {
        return {
            id: '',
            firstname: '',
            lastname: '',
            gender: '',
            email: '',
            action: '',
            label: 'Add'
        }
    },
    methods: {
        save: function() {
            var self = this;
            var data = {
                firstname: this.firstname,
                lastname: this.lastname,
                gender: this.gender,
                email: this.email
            };
            var action = this.action;
            var id = this.id;
            if(action === 'POST') {
                Model.post(data, function(result) {
                    if(result.code === 0) {
                        self.resetForm();
                        app.setNotification('success', '');
                    } else {
                        app.setNotification('error', result.msg);
                    }
                });
            } else {
                Model.put(id, data, function(result) {
                    if(result.code === 0) {
                        app.setNotification('success', '');
                    } else {
                        app.setNotification('error', result.msg);
                    }
                });
            }
        },
        resetForm: function() {
            this.id = '';
            this.firstname = '';
            this.lastname = '';
            this.gender = '';
            this.email = '';
        },
        setForm: function() {
            var self = this;
            var id = this.$route.params.id ? this.$route.params.id : null;
            if(id) {
                Model.get(id, function(result) {
                    if(result.code === 0) {
                        self.id = result.data.id;
                        self.firstname = result.data.first_name;
                        self.lastname = result.data.last_name;
                        self.gender = result.data.gender;
                        self.email = result.data.email;
                    } else {
                        router.push('/');
                        Model.getAll();
                    }
                });

                self.action = 'PUT';
                self.label = 'Update';
            } else {
                self.action = 'POST';
            }
        }
    },
    created: function() {
        this.setForm();
    }
});

var cView = Vue.extend({
    template: '#view',
    data: function() {
        return {
            model: {}
        }
    },
    methods: {
        get: function() {
            var self = this;
            var id = this.$route.params.id;
            if(id) {
                Model.get(id, function(result) {
                    if(result.code === 0) {
                        self.model = result.data;
                    } else {
                        router.push('/');
                        Model.getAll();
                    }
                });
            }
        }
    },
    created: function() {
        this.get();
    }
});

const routes = [
    {
        path: '/', component: cList
    },
    {
        path: '/view/:id',
        name: 'pathView',
        component: cView
    },
    {
        path: '/add', component: cForm
    },
    {
        path: '/update/:id',
        name: 'pathUpdate',
        component: cForm
    }
];

const router = new VueRouter({
    routes: routes
});

var app = new Vue({
    router
}).$mount('#app');
