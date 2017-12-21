<?php
Template::add_js('build/vue.js', 'footer');
Template::add_js('build/vue-router.js', 'footer');
Template::add_js('build/sweetalert.min.js', 'footer');
Template::add_js('build/toastr.min.js', 'footer');
Template::add_js('build/toastr.options.js', 'footer');
Template::add_css('build/css/toastr.min.css');
Template::add_js('build/people.js', 'footer');
?>
<div id="app">
    <h1 class="page-header">Dashboard</h1>

    <!-- Notif -->
    <notification
            v-bind:show-success="showNotifSuccess"
            v-bind:success-message="showSuccessMessage"
            v-bind:show-error="showNotifError"
            v-bind:error-message="showErrorMessage">
    </notification>
    <!-- end Notif -->
    <router-view></router-view>
</div>

<template id="notification">
    <div class="row alert alert-success alert-dismissible fade in" role="alert" v-if="showSuccess">
        <strong>Success!</strong> {{successMessage}}
    </div>
    <div class="row alert alert-danger alert-dismissible fade in" role="alert" v-else-if="showError">
        <strong>Error!</strong> {{errorMessage}}
    </div>
</template>

<template id="list">
    <div class="row panel panel-default">
        <div class="panel-heading">
            <router-link to="/add" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Add</router-link>
            <button v-on:click="deleteRows" type="button" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-trash" aria-hidden="true">Remove Selected</button>
            <div class="pull-right form-inline">
                <div class="input-group">
                    <input @keyup="search" v-model="searchText" type="text" name="table_search" class="form-control input-sm" placeholder="Search">
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-search"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th><input type="checkbox" class="J_SelectAll" v-model="selectAll" v-on:click="checkAll"></th>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Gender</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="row in models">
                    <td><input type="checkbox" class="J_SelItem" v-bind:value="row.id" v-model="row.v_checked" v-on:click="checkItem"></td>
                    <td>{{row.id}}</td>
                    <td>{{row.first_name}}</td>
                    <td>{{row.last_name}}</td>
                    <td>
                        <span v-if="row.gender == 'Male'" class="label label-success">{{row.gender}}</span>
                        <span v-else class="label label-warning">{{row.gender}}</span>
                    </td>
                    <td>{{row.email}}</td>
                    <td>
                        <router-link :to="{ name: 'pathView', params: { id: row.id }}" class="btn btn-xs btn-info"><i class="glyphicon glyphicon-eye-open"></i> view</router-link>
                        <router-link :to="{ name: 'pathUpdate', params: { id: row.id }}" class="btn btn-xs btn-success"><i class="glyphicon glyphicon-pencil"></i> update</router-link>
                        <button class="btn btn-danger btn-xs" v-on:click="deleteRow($event, row)"><i class="glyphicon glyphicon-remove"></i> remove</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li v-bind:class="{'disabled': !pages.prev}">
                        <a href="javascript:void(0);" v-on:click.prevent="paging(pages.prev)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li v-for="page in pages.pages" v-bind:class="{'active': !page.number}">
                        <a href="javascript:void(0);" v-on:click.prevent="paging(page.number)">{{page.text}}</a>
                    </li>
                    <li v-bind:class="{'disabled': !pages.next}">
                        <a href="javascript:void(0);" v-on:click.prevent="paging(pages.next)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>

<template id="form">
    <div class="row panel panel-default">
        <div class="panel-heading">
            Form {{label}}
        </div>
        <form class="form-horizontal">
            <div class="panel-body">
                <div class="form-group">
                    <label for="firstname" class="col-sm-3 text-right">First name</label>
                    <div class="col-sm-5">
                        <input v-model="firstname" type="text" class="form-control" id="firstname" placeholder="First Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="lastname" class="col-sm-3 text-right">Last name</label>
                    <div class="col-sm-5">
                        <input v-model="lastname" type="text" class="form-control" id="lastname" placeholder="Last Name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="gender" class="col-sm-3 text-right">Gender</label>
                    <div class="col-sm-5">
                        <select v-model="gender" class="form-control" name="gender">
                            <option value="">-- Select a gender --</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-3 text-right">Email</label>
                    <div class="col-sm-5">
                        <input v-model="email" type="email" class="form-control" id="email" placeholder="Email">
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <router-link to="/" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Back</router-link>
                <div class="pull-right">
                    <button v-on:click.prevent="saveAndBack" type="button" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-floppy-disk"></i> Submit & Back</button>
                    <button v-on:click.prevent="save" type="button" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> Submit</button>
                    <input type="hidden" name="id" v-model="id">
                </div>
            </div>
        </form>
    </div>
</template>

<template id="view">
    <div class="row panel panel-default">
        <div class="panel-heading">
            Detail - {{model.id}}
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <tbody>
                <tr>
                    <td>First name</td>
                    <td>{{model.first_name}}</td>
                </tr>
                <tr>
                    <td>Last name</td>
                    <td>{{model.last_name}}</td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>{{model.gender}}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{model.email}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <router-link to="/" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-arrow-left"></i> Back</router-link>
        </div>
    </div>
</template>