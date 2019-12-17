<template>
  <div class="wrapper manage__tasks">

    <div class="row">
      <div class="col-8">
        <div class="pull-right">
          <button class="btn btn-primary" @click="funcMode=1">Add Document</button>
        </div>
      </div>
      <div class="col-4">
        <div class="form-group">
          <select name="team-select" 
				          id="team-select" 
				          class="form-control" 
				          v-model="teamSelected"
				          v-on:change="switchTeam">
            <option value="">Select Team</option>
            <option v-for="team in teams" v-bind:value="team.id">{{ team.name }}</option>
          </select>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-8">
        <card-component v-show="funcMode==0" style="height: 25rem">
          <template slot="title">
            Docs
          </template>

          <template slot="body">
            <div class="table-responsive">
              <table class="table card-table table-vcenter text-nowrap datatable">
                  <thead>
                  <tr>
                    <th class="w-1">ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(task, index) in tasks" :key="index">
                      <td><span class="text-muted">{{ task.id }}</span></td>
                      <td><a href="invoice.html" class="text-inherit">{{ task.title }}</a></td>
                      <td><span class="status-icon bg-success"></span>{{ task.task_status }}</td>
                      <td class="text-right">
                      	<a href="javascript:void(0)" class="btn btn-secondary btn-sm">Share</a>
                        <a href="javascript:void(0)" class="btn btn-secondary btn-sm ml-2">Multi-Share</a>
                        <!-- <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">Actions</button>
                        </div> -->
                      </td>
                    </tr>
                  </tbody>
              </table>
            </div>
          </template>
        </card-component>

        <card-component v-if="funcMode==1" style="height:25rem">
          <template slot="title">Upload a Document</template>

          <template slot="body">
            <form v-on:submit.prevent="handleSubmit">
              <div class="row">
                <div class="form-group col-8">
                  <div class="form-label">Task Type</div>
                  <div class="custom-controls-stacked">
                    <label class="custom-control custom-radio custom-control-inline">
                      <input type="radio" class="custom-control-input" id="quick" value="0" v-model="taskMode">
                      <span class="custom-control-label">Create a Quick Task</span>
                    </label>
                    <label class="custom-control custom-radio custom-control-inline">
                      <input type="radio" class="custom-control-input" id="list" value="1" v-model="taskMode">
                      <span class="custom-control-label">Create a Tasklist</span>
                    </label>
                  </div>
                </div>
                <div class="form-groupcol-4" v-if="taskMode==0">
                  <label for="" class="form-label">Assigned To</label>
                  <span v-text="assignee.name"></span>
                </div> 
              </div>
              <div class="row">
                <div class="form-group col-8">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" name="title" id="title" class="form-control" v-model="task.title">
                </div>
                <div class="form-group col-4" v-if="taskMode==0">
                  <label for="due_date" class="form-label">Due Date</label>
                  <flat-pickr class="form-control" v-model="task.due_date"></flat-pickr>
                </div>
              </div>
              <div class="form-group col-8">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control" id="description" cols="3" rows="3" v-model="task.description"></textarea>
              </div>
              <div class="form-group">
                <button class="btn btn-success">Save</button>
                <button @click="funcMode=0" class="btn btn-danger ml-2">Cancel</button>
              </div>
            </form>
          </template>
        </card-component>
      </div>
      <div class="col-4">
        <div class="card" style="height:25rem">
          <div class="card-header">
            <h3 class="card-title">Members</h3>
          </div>
          <div class="card-body o-auto" style="height: 15rem">
            <ul class="list-unstyled list-separated">
              <li class="list-separated-item" v-for="(user, index) in users" :key="index">
                <div class="row align-items-center" @click="getAssignee(user.id)">
                	<div class="col-auto ml-5">
                		<label class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input" name="user-checkbox" value="user.id">
                      <span class="custom-control-label"></span>
                    </label>
                  </div>
                  <div class="col">
                    <div>
                      <a href="javascript:void(0)" class="text-inherit">{{ user.name }}</a>
                    </div>
                    <small class="d-block item-except text-sm text-muted h-1x">{{ user.email }}</small>
                  </div>
                  <div class="col-auto">
                    <div class="item-action dropdown">
                      <a href="javascript:void(0)" data-toggle="dropdown" class="icon"><i class="fe fe-more-vertical"></i></a>
                      <div class="dropdown-menu dropdown-menu-right">
                        <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-tag"></i> Action </a>
                        <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-edit-2"></i> Another action </a>
                        <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-message-square"></i> Something else here</a>
                        <div class="dropdown-divider"></div>
                        <a href="javascript:void(0)" class="dropdown-item"><i class="dropdown-icon fe fe-link"></i> Separated link</a>
                      </div>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div> 
</template>

<script>

/* funcMode:
  0 - Show Task Table
  1 - Show Create Form
  2 - Show Particular Task
*/

/* taskMode:
  0 - Quick Task
  1 - Task List
*/

import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
  components: {
    flatPickr
  },
  data() {
    return {
      task: this.emptyDocForm(),
      assignee: '', 
      funcMode: 0,
      taskMode: 0,
      users: [],
      teams: [],
      teamSelected: 1,
      tasks: []
    }
  },
  created() {
  	this.getAllTeams();
    this.init(1);
  },
  methods: {
    init() {
    	this.listUsers(1);
      this.listDocs(1);
    },
    switchTeam() {
      this.listUsers();
      this.listDocs();
    },
    emptyDocForm() {
      return {
        title: '',
        due_date: null,
        description: ''
      }
    },
    getAllTeams() {
      window.axios.get('/api/teams').then(response => { // default team id to be loaded
        this.teams = response.data;
      });
    },
    listUsers() {
    	window.axios.get(`/api/team/${this.teamSelected}/users`).then(response => {
        this.users = response.data;
      });
    },
    listDocs(id) {
      // Apply a loader in future
      window.axios.get(`/api/team/${id}/tasks`).then(response => { // default team id to be loaded
        // console.log('response', response.data);
        this.tasks = response.data;
      });
    },
    getAssignee(id) {
      window.axios.get(`/api/user/${id}`).then(response => { // default team id to be loaded
        this.assignee = response.data;
      });
    },
    handleSubmit() {
      let postData = this.task;
      let task_type = '';

      if(this.assignee.id == null) {
       alert('Select a user');
      }
      else {
        postData.assignee_id = this.assignee.id; // add additional param
        postData.team_id = this.teamSelected; // add additional param
        if (this.taskMode == 0) {
          task_type = 'quick'; 
        } else {
          task_type = 'list';
        }
        postData.task_type = task_type; // add additional param
        window.axios.post('/api/task', postData).then(response => {
          console.log('response', response.data);
          // this.$emit('newMenuItemAdded', response.data, postData.category);
          // this.food = this.emptyMenuForm();
        }).catch(error => {
          console.log('error', error.response);
          // if (error.response.status == 422) {
          //   this.validation.setMessages(error.response.data.errors);
          // }
        });
      }
    }
  }
}
</script>

<style>
</style>