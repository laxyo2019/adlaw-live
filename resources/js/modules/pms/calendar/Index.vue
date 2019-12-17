<template>
	<div class="container">
  	<div class="col-10 offset-1">
  		<div class="card mb-n2">
				<div class="card-header">
					<a href="/" title="Back"><i class="fa fa-arrow-left fa-lg"></i></a>
					<span class="card-title mx-auto">
						<b>Schedule</b>
					</span>
					<!-- <a href="#" title="Reset"><i class="fa fa-refresh fa-lg"></i></a> -->
	      </div>
  		</div>
  	</div>
		<div class="col-12">
			<div class="card mb-3">
				<div class="card-body">
					<span v-if="isEmpty(focusedSchedule)">
						<button class="btn btn-info btn-pill" @click="toggleForm()">
				  	<i class="fe fe-plus"></i> New Schedule</button>
				  	<div class="card mt-3" v-if="env.createSchedule" >
					  	<div class="card-body row">
				  			<div class="form-group col-sm-6 col-xs-12">
				  				<label for="title"><b>Title : </b></label>
				  				<input v-model="createSchedule.title"  type="text" name="title" id="title" class="form-control">
				  				<div class="validation-message" v-text="validation.getMessage('title')"></div>
				  			</div>
				  			<div class="form-group col-sm-6 col-xs-12">
				  				<label for=""><b>Assignee : </b></label>
									  <multiselect v-model="createSchedule.assignee" 
										  :options="userLoop" 
										  :close-on-select="true" 
										  :clear-on-select="true" 
										  :preserve-search="true" 
										  placeholder="Pick Assignee" 
										  label="name" 
										  track-by="name"
								  	></multiselect>
								  <div class="validation-message" v-text="validation.getMessage('assignee')"></div>
				  			</div>
				  			<div class="form-group col-6 col-xs-12">
				  				<label for=""><b>Users : </b></label>
								  <multiselect v-model="createSchedule.users" 
									  :options="userLoop" 
									  :multiple="true" 
									  :close-on-select="false" 
									  :clear-on-select="true" 
									  :preserve-search="true" 
									  placeholder="Pick some Users" 
									  label="name" 
									  track-by="name">

									  <!-- To show the already selected users -->
							    	<template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" 
							    		v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span>
							    	</template>

							  	</multiselect>
				  			</div>
								<div class="col-sm-6 col-xs-12">
									<div class="form-group">
					  				<label for=""><b>Repeat : </b></label>
										  <multiselect v-model="createSchedule.repeat" 
											  :options="repeatOptions" 
											  :close-on-select="true" 
											  :clear-on-select="true" 
											  :preserve-search="true" 
											  placeholder="Pick repeat" 
											  label="title" 
											  track-by="title"
										  ></multiselect>
									  <div class="validation-message" v-text="validation.getMessage('repeat')"></div>
					  			</div>
					  			<div class="form-group" v-if="createSchedule.repeat.value!=1 && Object.keys(createSchedule.repeat).length != 0">
								   	<label for=""><b>Repeat Till :</b></label>
								   	 <flat-pickr  class="form-control" v-model="createSchedule.repeatTillDate"></flat-pickr>
								   	 <div class="validation-message" v-text="validation.getMessage('repeatTillDate')"></div>
						  		</div>
								</div>
								<div class="form-group col-sm-6 col-xs-12">
				  					<label for=""><b>Set a start date :</b></label>
										<flat-pickr class="form-control" v-model="createSchedule.startDate" :config="dateConfig1"></flat-pickr>
										<div class="validation-message" v-text="validation.getMessage('startTime')"></div>
				  			</div>
				  			<div class="form-group col-sm-6 col-xs-12">
			  					<label for=""><b>Set a End date :</b></label>
									<flat-pickr class="form-control" v-model="createSchedule.endDate" :config="dateConfig2"></flat-pickr>
									<div class="validation-message" v-text="validation.getMessage('endTime')"></div>
				  			</div>
				  			<div class="form-group col-sm-6 col-xs-12">
			  					<label for=""><b>Reminder Starts At :</b></label>
									<flat-pickr class="form-control" v-model="createSchedule.remind_start" :config="dateConfig3"></flat-pickr>
									<div class="validation-message" v-if="validation.getMessage('remind_startTime')" v-text="validation.getMessage('remind_startTime')"></div>
				  			</div>
								<div class="form-group col-sm-6 col-xs-12 text-right">
									<br>
									<span class="text-left"> <b>Adjust Levels of notifiers: </b></span>
									<button class="btn btn-success" @click="modifyNotifier('push')"><i class="fa fa-plus"></i></button>
									<button class="btn btn-danger" @click="modifyNotifier('pop')"><i class="fa fa-minus"></i></button>
								</div>
								<div class="col-12">
									<table class="col-12 table table-striped table-bordered">
										<thead>
											<tr>
												<th>Level</th>
												<th>Notifier</th>
												<th>End Date</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="(notify,index) in createSchedule.notifiers">
												<td>{{index+1}}</td>
												<td>
													<multiselect
														v-model="notify.user"
													  :options="userLoop " 
													  :close-on-select="true" 
													  :clear-on-select="true" 
													  :preserve-search="true" 
													  placeholder="Pick Assignee" 
													  label="name" 
													  track-by="name" >
												  </multiselect>
												</td>
												<td>
													<flat-pickr  v-model="notify.date" class="form-control" :config="dateConfig4"></flat-pickr>
												</td>
											</tr>
										</tbody>
									</table>
				  			</div>
				  			<div class="col-12 form-group">
				  				<vue-editor v-model="createSchedule.description" :editor-toolbar="customToolbar" />
				  			</div>
				  			<div class="col-12">
				  				<button class="btn btn-danger btn-pill pull-right" @click="toggleForm()">Cancel</button>
				  				<button class="btn btn-success btn-pill pull-right mr-2" @click="storeSchedule()">Save</button>
				  			</div>
				  		</div>
				  	</div>
				  	<div class="col-md-4  col-sm-8 col-xs-10 offset-md-4 offset-sm-2 offset-xs-1">
							 <vue-cal class="vuecal--rounded-theme vuecal--green-theme"
				 					@cell-click="onCellClick($event)"
					         xsmall
					         hide-view-selector
					         :time="false"
					         default-view="month"
					         :disable-views="['week']"
					         :events="displayLoop">
								</vue-cal>
						</div>
						<div class="card">
							<div class="card-body">
								<span v-for="day in daysOfYear">
										<p style="margin: 0 -24px 10px;padding: 2px 5px;background: #e4f5ef;"><b>{{day}}</b></p>
										<span v-for="(display in displayLoop">
											<p v-if="checkSchedule(display,day)" >
												<a class="col-12" @click.prevent="focusedSchedule=display" style="display:inline-block;cursor: pointer;">
													<span>
														<i class="fa fa-long-arrow-right text-success"></i>
														{{display.title}} 
													</span>
												</a>	
												<!-- <span class="col-1">
													<i @click.prevent="update_display('complete',display.id)" class="fa fa-check-square-o text-info"></i>
													<i @click.prevent="update_display('delete',display.id)" class="fa fa-trash text-danger ml-2"></i>
												</span>	 -->
											</p>
										</span>
								</span>
							</div>
						</div>
					</span>
					<span v-else>
						<schedule-component
							@unfocus="focusedSchedule={}"
							:focusedSchedule="focusedSchedule"
				    	:logged_user="logged_user"
				    	:users="users"
						>
						</schedule-component>
					</span>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import moment from 'moment'
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'
import Multiselect from 'vue-multiselect'
import Validation from '../../../utils/Validation.js';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import ScheduleComponent from './Schedule.vue'; 

export default{
	props: ['logged_user', 'users', 'displays'],
	components : {
		Multiselect, flatPickr, VueCal, ScheduleComponent
	},
	created(){
		this.fillRepeatOptions();
		this.loopDates();
	},
	data(){
		return{
			displayLoop : this.displays,
			userLoop : this.users,
			daysOfYear:[],
			focusedSchedule:{},
			validation: new Validation(),
			dateConfig1:{enableTime: true,
							 dateFormat: "Y-m-d H",
							 defaultDate: (moment().format("YYYY-MM-DD"))+" 01",
							},
			dateConfig2:{enableTime: true,
							 dateFormat: "Y-m-d H",
							 defaultDate: (moment().format("YYYY-MM-DD"))+" 23",
							},
			dateConfig3:{enableTime: false,
							 dateFormat: "Y-m-d H",
							},
			dateConfig4:{enableTime: false,
							 dateFormat: "Y-m-d",
							},
			env:{
				createSchedule:false, //toggles create form
			},
			createSchedule: this.emptyForm(),
			repeatOptions:[], //options in drop down for schedule repeat
			customToolbar: [
        ["bold", "italic", "underline"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["image", "code-block"]
      ],
		}
	},
	methods:{	 
		checkSchedule(schedule, day) {
			let	start = moment(schedule.start,'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');	
			let end = moment(schedule.end,'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD');
			let now = moment(day,'DD-MM-YYYY').format('YYYY-MM-DD');

			if(start <= now && end >= now)
			{
				return true;
			}
		},
		//rows below calendar - set the range of displays
		loopDates(start = moment().startOf('month').format('DD-MM-YYYY')) {
			let dateLoop = [];
			let end = moment(start,'DD-MM-YYYY').add(1, 'months').endOf('month').format('DD-MM-YYYY');
			while (moment(end,'DD-MM-YYYY') >= moment(start,'DD-MM-YYYY')) {
		   	dateLoop.push(start);
		   	start =  moment(start,'DD-MM-YYYY').add(1, 'days').format('DD-MM-YYYY');
			}
			this.daysOfYear = dateLoop;
		},

		//show displays - the test (clicked date) at top
		onCellClick(test) {
	    this.loopDates(moment(test).format('DD-MM-YYYY'));
	  },

		fillRepeatOptions(){
			let	occur = [ 
					{'title':"Don't Repeat",'value':1},
					{'title':"Every Day",'value':2},
					{'title':"Every Week",'value':3},
					{'title':"Every Month",'value':4},
					{'title':"Every Year",'value':5}
				];
		  this.repeatOptions = occur;
		},
		modifyNotifier(action){
			console.log(action);
			if(action=="push"){
				let x = {
						'user':null,
						'date':null
					};
					this.createSchedule.notifiers.push(x);
			}else{
				this.createSchedule.notifiers.pop();
			}
		},
		emptyForm(){
			return {
				title: null,
				assignee: null,
				users: null,
				repeatTillDate: null,
				description: null,
				remind_start: null,
				repeat:{},
				startDate:(moment().format("YYYY-MM-DD"))+" 01",
				endDate:(moment().format("YYYY-MM-DD"))+" 23",
				notifiers:[
					{
						'user': null,
						'date': null
					}
				]
			}
		},
		isEmpty(obj) {
	    for(var key in obj) {
        if(obj.hasOwnProperty(key))
          return false;
	    }
	    return true;
		},	
		toggleForm(){
			if(this.env.createSchedule){
				this.validation = new Validation();
				this.createSchedule = this.emptyForm();
			}
			this.env.createSchedule = !this.env.createSchedule;
		},
		update_display(action, id) {
			Vue.swal({
			  title: 'Are you sure to '+action+' this Schedule?',
			  text: "You won't be able to revert this!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, do it!'
			}).then((result) => {
				if (result.value) {
				window.axios.post(`/pms/update_schedule`,{id:id,action:action}).then(response => {
						this.displays = this.displays.filter(u => (u.id != id));
						this.schedules.forEach((v, k) => {
							 if (v.id == response.data.id)
							 Vue.set(this.schedules,k, response.data);
						});
					}).catch(error=>{
						console.log('error',error);
					});
				}
			})
		},

		storeSchedule(){
			let error = false;
			//check if date or name is empty in a notifiers
			this.createSchedule.notifiers.forEach(function(v,k){
	  		if(v.date == null || v.user == null)
	  		{	
	  			error = true;
	  		}
		  });
			if(error){
	  		Vue.toasted.error('** Please fill all notifiers and respective end date.', {
	  			action : {
	  				text : 'Okay',
	  				onClick : (e, toastObject) => {
	  					toastObject.goAway(0);
	  				}
	  			},
	  		});
	  	}
			else{
				window.axios.post(`/pms/schedule`,this.createSchedule).then(response => {
					if(response.status == 201) {
						console.log('201',response.data);
					} else {
							Vue.swal({
							  type: 'error',
							  title: 'Error!',
							  text: response.data
							})
					}
				}).catch(error => {
					 if (error.response.status == 422) {
		          this.validation.setMessages(error.response.data.errors);
		        }else{
		        	console.log(error.response.data)
		        }
				});
			}
		},
	}
}
</script>
<style>
	/*edit count of events on particular date*/
	.vuecal__cell-events-count {
	  width: 4px;
	  min-width: 0;
	  height: 4px;
	  padding: 0;
	  color: transparent !important;
	}
</style>