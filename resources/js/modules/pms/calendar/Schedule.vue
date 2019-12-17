<template>
	<div class="card">
      <!-- <div class="avatar avatar-md mr-3" style="background-image: url(/tabler/demo/faces/male/16.jpg)"></div> -->
      <span class="m-2 row">
      	<div class="col-8">
      		<a href="" class="btn btn-success mr-2">Complete</a>
      		<a href="" class="btn btn-danger">Discard</a>
      	</div>
      	<div class="col-4 text-right">
      		<a href="#" class="btn btn-dark" title="Close" @click.prevent="$emit('unfocus')">Close</a>
      	</div>
        <!-- <a href="#" @click.prevent="toggleEditSchedule()" title="Edit">Edit</a> -->
        <!-- <a href="#" @click.prevent="deleteMasterSchedule(display.schedule_id)" title="Delete">Delete</a> -->
      </span>
  	<!-- <div class="card-body mt-2" v-if="scheduleEdit">
  		<div class="card-body row">
  				<div class="form-group col-sm-6 col-xs-12">
	  				<label for="title"><b>Title : </b></label>
	  				<input v-model="editSchedule.title"  type="text" name="title" id="title" class="form-control">
	  				<div class="validation-message" v-text="validation.getMessage('title')"></div>
	  			</div>
	  			<div class="form-group col-sm-6 col-xs-12">
	  				<label for=""><b>Assignee : </b></label>
						  <multiselect v-model="editSchedule.assignee" 
						  :options="users" 
						  :close-on-select="true" 
						  :clear-on-select="true" 
						  :preserve-search="true" 
						  placeholder="Pick Assignee" 
						  label="name" 
						  track-by="name" >
					  </multiselect>
					  <div class="validation-message" v-text="validation.getMessage('assignee')"></div>
	  			</div>
	  			<div class="form-group col-6 col-xs-12">
	  				<label for=""><b>Users : </b></label>
						  <multiselect v-model="editSchedule.users" 
						  :options="users" 
						  :multiple="true" 
						  :close-on-select="false" 
						  :clear-on-select="false" 
						  :preserve-search="true" 
						  placeholder="Pick some Users" 
						  label="name" 
						  track-by="name" >
					    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
					  </multiselect>
	  			</div>
	  			<div class="col-sm-6 col-xs-12"></div>
	  			<div class="form-group col-sm-6 col-xs-12" v-if="masterSchedule.repeat != 1">
	  				<label for=""><b>This Event repeats...Do you want to change all future occurrences of this event too? </b></label>
						  <multiselect  v-model="editSchedule.editAll"
						  :options="editType" 
						  :close-on-select="true" 
						  :clear-on-select="true" 
						  :preserve-search="true" 
						  placeholder="Pick Assignee" 
						  label="data" 
						  track-by="data" >
					  </multiselect>
	  			</div>
	  			<div class="form-group col-sm-6 col-xs-12" v-if="masterSchedule.repeat != 1">
	  				<br>
					   	<label for=""><b>Repeat Till :</b></label>
					   	<p v-if="editSchedule.editAll.id==1">{{editSchedule.repeatTillDateNoEdit}}</p>
					   	<flat-pickr  v-else class="form-control" v-model="editSchedule.repeatTillDate"></flat-pickr>
			  		</div>
	  			<div class="col-12 form-group">
	  				<vue-editor v-model="editSchedule.description" :editor-toolbar="customToolbar" />
	  			</div>
					<div class="col-12">
	  				<button class="btn btn-danger btn-pill pull-right" @click="toggleEditSchedule()">Cancel</button>
	  				<button class="btn btn-success btn-pill pull-right mr-2" @click="updateSchedule()">Update</button>
	  			</div>
  			</div>
  	</div> -->
    <div class="card-body">
			<p><b>Title: </b>{{ display.title }}</p>
			<p><b>Assignee Name: </b>{{ fetchUser(display.assignee_id) }}</p>
			<p><b>Start Time : </b>{{ display.start | moment("LT dddd, MMMM Do YYYY") }}</p>
			<p><b>End Time : </b>{{ display.end | moment("LT dddd, MMMM Do YYYY") }}</p>
			<p>
				<b>With Users: </b>
				<div class="tags">
				  <span class="tag" v-for="user in JSON.parse(display.users)">{{ fetchUser(user) }}</span>
				</div>
			</p>
			<p><b>Description : </b></p><p v-html="display.description"></p>

  		<comments-component
  			:commentable_id= "display.id" 
  			:logged_user= "logged_user" 
  			:model = "model"
  			:users = "users">
			</comments-component>

  	</div>
	</div>
</template>
<!-- All repeat type
1 = Don't Repeat
2 = Every Day
3 = Every Week
4 = Every Month
5 = Every Year -->
<script>
	import flatPickr from 'vue-flatpickr-component';
	import moment from 'moment'
	import Multiselect from 'vue-multiselect'
	import Validation from '../../../utils/Validation.js';
	import { VueEditor } from "vue2-editor";
	export default {
		props:['logged_user', 'users', 'focusedSchedule'],
		components: {
	    Multiselect, VueEditor, flatPickr
	  },
		data() {
			return{
				model: 'App\\Models\\SchedulesDisplay', //For Comments Component
				dateConfig: {enableTime: true, dateFormat: "Y-m-d H"},
				validation: new Validation(),
				customToolbar: [
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["image", "code-block"]
      ],
				editType:[ 
						{'id': 1, 'data':'No, Just change this event'},
						{'id': 2, 'data':'Yes, change future occurrences too'}
					],
				masterSchedule:[],
				editSchedule:this.emptyEditSchedule(),
				scheduleEdit:false,
				// up:false,
				display:this.focusedSchedule,
			}
		},
		created(){	
			this.getFocusedSchedule();
		},
		methods: {
			deleteMasterSchedule(id){
					Vue.swal({
					  title: 'Are you sure to delete the Master Schedule?',
					  text: "All Schedules and their data will also get deleted and You won't be able to revert this!!",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, delete!'
					}).then((result) => {
						if (result.value) {
						 window.axios.delete(`/pms/schedule/${id}`).then(response=>{
						 	console.log(response.data);
								Vue.toasted.success('Deleted successfully', {duration:2000});
								location.reload();
							}).catch(error=>{
								console.log(error);
							});
						}
					})
			},
			updateSchedule(){
				let id = this.masterSchedule.id;
				let postData = {};
					postData.title = this.editSchedule.title;
					postData.assignee = this.editSchedule.assignee;
					postData.users =  this.editSchedule.users;
					postData.startDate =  this.editSchedule.startDate;
					postData.endDate =  this.editSchedule.endDate;
					postData.repeat =  this.editSchedule.repeat;
					postData.repeatTillDate = this.editSchedule.repeatTillDate;
					postData.editType = this.editSchedule.editAll.id;
					postData.description = this.editSchedule.description;
					postData.displayId = this.display.id;
					postData.master_id = this.masterSchedule.id;
				window.axios.patch(`/pms/schedule/${id}`,postData).then(response=>{
					// console.log(response);
					// this.display = response.data;
					// this.getMasterSchedule();
					// this.editSchedule = this.emptyEditSchedule();
					// this.scheduleEdit=false;
					if(response.status==201){
						Vue.toasted.success('Updated successfully', {duration:2000});
						location.reload();
					}else{
						Vue.swal({
						  type: 'error',
						  title: 'Error !',
						  text: response.data
						})
					}
					
				}).catch(error=>{
 					if (error.response.status == 422) {
	          this.validation.setMessages(error.response.data.errors);
	        }else{
	        	 console.log(error.response.data)
	        }
				});
			},
			getFocusedSchedule(){
				window.axios.get(`/pms/schedule/${this.focusedSchedule.id}`).then(response=>{
						console.log(response.data);
						this.masterSchedule= response.data;
				}).catch(error => console.log(error.response.data));
			},
			emptyEditSchedule(){
				return{
					title: null,
					assignee: null,
					startDate: null,
					endDate: null,
					users:[],
					editAll: {data:"No, Just change this event",id:1},
					repeatTillDate: null,
					repeatTillDateNoEdit: null,
					description: null,

				}
			},
			// toggleEditSchedule(){
			// 	if(this.scheduleEdit){
			// 			this.editSchedule = this.emptyEditSchedule();
			// 	}else{
			// 		this.editSchedule.title = this.display.title;
			// 		this.editSchedule.startDate = this.display.start;
			// 		this.editSchedule.endDate = this.display.end;
			// 		this.editSchedule.repeat = this.masterSchedule.repeat; 
			// 		this.editSchedule.repeatTillDateNoEdit = this.masterSchedule.expiry_date;
			// 		this.editSchedule.repeatTillDate = this.masterSchedule.expiry_date;
			// 		this.editSchedule.description = this.display.description;
			// 		this.editSchedule.assignee = this.fetchUserObject(this.display.assignee_id);
			// 		let users=[];
			// 		let usersArray = JSON.parse(this.display.users);
			// 		this.editSchedule.users = users;
			// 	}
			// 	this.scheduleEdit = !this.scheduleEdit;
			// },
			humanReadableTime(date) {
				return moment(date,'YYYY-MM-DD HH:mm:ss').fromNow();
			},
			fetchUserObject(id) {
				let x = '';
				this.users.forEach(function(v,k) {
					if(id == v.id) {
						x = v;
					}
				});
				return x;
			},
			fetchUser(id) {
				let x = '';
				this.users.forEach(function(v,k) {
					if(id == v.id){
						x = v.name;
					}
				});
			return x;
			},
		
		},

	}
</script>