<template>
  <div class="wrapper manage__tasks">
        <card-component v-show="funcMode==0" style="height: 25rem">
          <template slot="title">
            Tasks
          </template>

          <template slot="options">
            <button>Add Task</button>
          </template>

          <template slot="body">
            <div class="row">
              <div class="col-8">
                <div class="table-responsive">
                  <table class="table card-table table-vcenter text-nowrap datatable">
                      <thead>
                      <tr>
                        <th class="w-1">ID</th>
                        <th>Title</th>
                        <th>User</th>
                        <th>Timer</th>
                        <th>Status</th>
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(task, index) in tasks" :key="index">
                          <td><span class="text-muted">{{ task.id }}</span></td>
                          <td><a href="invoice.html" class="text-inherit">{{ task.title }}</a></td>
                          <td>{{ task.user_id }}</td>
                          <td>Due Date</td>
                          <td><span class="status-icon bg-success"></span>{{ task.task_status }}</td>
                          <td class="text-right">
                            <a href="javascript:void(0)" class="btn btn-secondary btn-sm">Manage</a>
                            <!-- <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" data-toggle="dropdown">Actions</button>
                            </div> -->
                          </td>
                        </tr>
                      </tbody>
                  </table>
                </div>
              </div>
              <div class="col-4">
                <div class="card-body o-auto">
                  <ul class="list-unstyled list-separated">
                    <li class="list-separated-item" v-for="(user, index) in users" :key="index">
                      <div class="row align-items-center" @click="getAssignee(user.id)">
                        <div class="col-auto">
                          <label class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" name="user-radios" value="user.id">
                            <div class="custom-control-label"></div>
                          </label>
                          <!-- <span class="avatar avatar-md d-block" style="background-image: url(/tabler/demo/faces/female/12.jpg)"></span> -->
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
          </template>
        </card-component>

        <card-component v-if="funcMode==1">
          <template slot="title">Add a Task</template>

          <template slot="body">
            <form v-on:submit.prevent="handleSubmit">
              <div class="row">
                <div class="form-groupcol-4">
                  <label for="" class="form-label">Assigned To</label>
                  <span v-text="assignee.name"></span>
                </div> 
              </div>
              <div class="row">
                <div class="form-group col-8">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" name="title" id="title" class="form-control" v-model="task.title">
                </div>
                <div class="form-group col-4">
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
    </div>
  </div> 
</template>

<script>

/* funcMode:
  0 - Show Task Table
  1 - Show Create Form
  2 - Show Particular Task
*/

import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
  components: {
    flatPickr
  },
  data() {
    return {
      task: this.emptyTaskForm(),
      funcMode: 0,
      users: [],
      teams: [],
      tasks: []
    }
  },
  created() {
    this.init();
  },
  methods: {
    init() {
      let team_id = 1;
      // this.getUsers(team_id);
      // this.getAllTeams();
      // this.getTasks(team_id);
    },
    switchTeam() {
      this.listUsers();
      this.listTasks();
    },
    emptyTaskForm() {
      return {
        title: '',
        description: '',
        assignee: null,
        due_date: null,
      }
    },
    getAllTeams() {
      window.axios.get('/api/teams').then(response => { // default team id to be loaded
        this.teams = response.data;
      });
    },
    getAssignee(id) {
      window.axios.get(`/api/user/${id}`).then(response => { // default team id to be loaded
        this.assignee = response.data;
      });
    },
    getUsers(id) {
      // Apply a loader in future

      window.axios.get(`/api/team/${id}/users`).then(response => { // default team id to be loaded
        // console.log('response', response.data);
        this.users = response.data;
      });
    },
    getTasks(id) {
      // Apply a loader in future

      window.axios.get(`/api/team/${id}/tasks`).then(response => { // default team id to be loaded
        // console.log('response', response.data);
        this.tasks = response.data;
      });
    },

    handleSubmit() {
      let postData = this.task;
      let task_type = '';

      postData.assignee_id = this.assignee.id; // add additional param
      postData.team_id = this.teamSelected; // add additional param
      window.axios.post('/api/task', postData).then(response => {
        console.log('response', response.data);
        // this.$emit('newMenuItemAdded', response.data, postData.category);
        this.task = this.emptyTaskForm();
      }).catch(error => {
        console.log('error', error.response);
        // if (error.response.status == 422) {
        //   this.validation.setMessages(error.response.data.errors);
        // }
      });
      
    }
  }
}
</script>

<style>
</style>
