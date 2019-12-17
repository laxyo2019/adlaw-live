<template>
	<div class="container" >
		<a href="#" class="pull-left mt-2 mb-2 ml-2" @click.prevent="$emit('unfocus')">
			<i class="fa fa-arrow-left">BACK</i>
		</a>
		<div class="card">	
		<!-- <div class="card-header" v-if="action=='creatorResponses'">
			<span class="row col-12">
				<div class="col-7">
					<label for=""><b>Users : </b></label>
					<multiselect v-model="selectedUsers" 
						:options="allUsers" 
						:multiple="true" 
						:close-on-select="false" 
						:clear-on-select="false" 
						:preserve-search="true" 
						placeholder="Pick some" 
						label="name" 
						track-by="name" 
						:preselect-first="true">
						<template slot="selection" slot-scope="{values, search, isOpen}"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
					</multiselect>
				</div>
				<div class="col-3" v-if="0">
					<label for=""><b>Time Period : </b></label>
					<multiselect v-model="SelectedDuration" 
						:options="duration" > 
					</multiselect>
				</div>
				<div class="pull-right col-2"> 
					<br>
					 <span class="btn btn-success btn-pill pull-right" @click='filter'>Filter</span> 
				</div>
			</span>
		</div> -->
			<div class="" v-if="action =='UserResponses'">
					<div class="card-body">
						<div class="col-12" v-if="!todaysResponse()">
							<div class="text-right" v-if="agenda.worktime_check > 0">
								<a href="#" v-if="totalHours < agenda.worktime_check" class="btn btn-success" @click.prevent="modifyFormRow('plus')"><i class="fe fe-plus"></i></a>
								<a href="#" v-if="totalHours>1" class="btn btn-danger" @click.prevent="modifyFormRow('minus')"><i class="fe fe-minus"></i></a>
							</div>
							<div v-else>
								<div class="form-group">
									<vue-editor v-model="content" ></vue-editor>
									<div class="validation-message" v-text="validation.getMessage('content')"></div>
								</div>
							</div>
							<div class="row mt-2" v-for="n in totalHours" >
								<div class="col-10">
									<div class="form-group">
										<input type="text" class="form-control" v-model="loopTasks[n-1].detail" placeholder="Write task details here...">
									</div>
								</div>
								<div class="col-2">
									<div class="form-group">
										<select name="" id="" class="form-control" v-model="loopTasks[n-1].time" >
											<option :value="n"   v-for="n in agenda.worktime_check">{{n}}</option>
										</select>
									</div>
								</div>
							</div>
							<div class="text-right">
								<a href="#" @click.prevent="addResponse()" class="btn btn-pill btn-success">Add
								</a>
								<a href="#" @click.prevent="$emit('unfocus')" class="btn btn-pill btn-danger">Cancel
								</a>
							</div>
						</div>
						<div v-else="" class="text-center">
							<div class="card">
								<div class="card-body">
									<p>
										You have submitted today's response.
									</p>
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="card-body" >
				<div id="timeline">
					<div class="row timeline-movement timeline-movement-top">
							<div class="timeline-badge timeline-future-movement">
								<a href="#">
									<span class="fa fa-plus"></span>
								</a>
							</div>
							<div class="timeline-badge timeline-filter-movement">
								<a href="#">
									<span class="fa fa-clock-o"></span>
								</a>
							</div>
						</div>
					<!-- loop begins here -->
					<div class="row timeline-movement" v-for="resp_group in agenda.response_grps">
						<div class="timeline-badge">
							<span class="timeline-balloon-date-day">{{ resp_group.date | moment("DD") }}<br></span>
							<span class="timeline-balloon-date-month">{{ resp_group.date | moment("MMM") }}</span>
						</div>
						<div class="timeline-item row" style="width:100%">
							<div class="col-sm-11" 
								:style="[selectedUsersArr.indexOf(user_group.responder_id) > -1 ? {'margin':'10px 0 0 20px'}:{'margin':'0'}]" 
								v-for="user_group in resp_group.resp_users">
								<div class="timeline-panel credits" v-if="selectedUsersArr.indexOf(user_group.responder_id) > -1" >
									<ul class="timeline-panel-ul">
										<li>
											<span class="importo">{{user_group.user.name}}</span>
											<a class="pull-right" >
												<i class="fa fa-edit text-success"></i>
											</a>
											<p class="" >
												<small class="text-muted">
													<i class="fa fa-clock-o"></i>{{user_group.respond_time}}
												</small>
											</p>
										</li>
										<li>
										</li>	
									</ul>
									<div class="row">
										<div class="col-12">
											<table v-if="agenda.worktime_check > 0" class="table table-striped table-bordered" style=
											"border-top:0">
												<thead class="table-dark">
													<th class="col-10" style="color:#000">Task details</th>
													<th class="col-2" style="color:#000">Hrs</th>
												</thead>
												<tbody>
													<tr v-for="response in user_group.responses">
														<td class="col-10" v-if="response.body!=null" v-html="response.body"></td>
														<td class="col-10" v-else> <span class="text-danger">Response Missed!!</span></td>
														<td class="col-2" v-html="response.time"></td>
													</tr>
												</tbody>
											</table>
											<div v-else>	
												<p v-for="response in user_group.responses" >
													<span v-if="response.body!=null" v-html="response.body"></span>
													<span v-else class="text-danger">Response Missed!!</span>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import VModal from 'vue-js-modal'
Vue.use(VModal)
import moment from 'moment'
import Multiselect from 'vue-multiselect'
import Validation from '../../../utils/Validation.js';
import { VueEditor } from "vue2-editor";
export default {
	props:['openedAgenda', 'logged_user','action','team'],
	components: {
		 Multiselect, VueEditor
	},
	data() {
		return{
			validation: new Validation(),
			content: "",
			users : [],
			allUsers:[],
			selectedUsers:[],
			selectedUsersArr:[],
			agenda: [],
			totalHours: 0,
			loopTasks: [],
		}
	},
	methods:{
		addResponse(){
			let error = false;
			let postData = {};
			postData.tasks = this.loopTasks;
			postData.team = this.team;
			postData.content = this.content;
		  postData.agenda= this.agenda;
	  	let time = 0;
	  	let more_than_8 = false;
	  	this.loopTasks.forEach(function(v,k){
	  		if(v.detail=="" || v.time == "")
	  		{
	  			error = true;
	  		}else{
	  			time = time + parseInt(v.time);
	  		}
		  });
		  if(error){
	  		Vue.toasted.error('** Please fill all task details and time duration.', {
	  			action : {
	  				text : 'Okay',
	  				onClick : (e, toastObject) => {
	  					toastObject.goAway(0);
	  				}
	  			},
	  		});
	  	}
	  	else if(time < this.agenda.worktime_check){
	  			let display_error = "** The total hours for today's agenda must be " +this.agenda.worktime_check+" hours or more.";
		  		Vue.toasted.error(display_error, {
		  			action : {
		  				text : 'Okay',
		  				onClick : (e, toastObject) => {
		  					toastObject.goAway(0);
		  				}
		  			},
		  		});
		  	}else{
		  		this.storeResponse(postData);
		  	}
		},

		storeResponse(postData){
		  	window.axios.post('/pms/agenda/response', postData).then(response => {
		  		if(response.status == 201){
		  			Vue.toasted.success(`Added Successfully`, {
		  				duration: 2000
		  			});
		  			this.agenda = response.data;
		  		}else{
		  			Vue.toasted.error(`${response.data}`, {
		  				action : {
		  					text : 'Okay',
		  					onClick : (e, toastObject) => {
		  						toastObject.goAway(0);
		  					}
		  				},
		  			});
		  		}
		  		this.validation = new Validation();						
		  		this.content = '';
		  	}).catch(error => {
		  		if (error.response.status == 422) {
		  			console.log(error.response.data)
		  			this.validation.setMessages(error.response.data.errors);
		  		}else{
		  			console.log(error.response.data)
		  		}
		  	});
		  },

		modifyFormRow(func) {
			if(func == 'plus' && this.totalHours < this.agenda.worktime_check)
			{
				this.loopTasks.push({
					detail: '',
					time: 1
				});
				this.totalHours++;
			}
			else if(func == 'minus' && this.totalHours > 1)
			{
				this.loopTasks.pop();
				this.totalHours--;
			}
		},
		todaysResponse(){
			var count = false;
			var todayDate = moment(String(new Date())).format('YYYY-MM-DD');

			let user_id = this.logged_user.id;

			this.agenda.response_grps.forEach(function(e){
				if(e.date == todayDate){
					e.resp_users.forEach(function(v,k){
						if(user_id == v.responder_id){
							count = v.responder_id;
						}	
					});
				}
			});
			console.log('e.responder_id',count);
			return count;
		},
		fetch_responder(){
				return true;
		},
		get_users(){
			let can_respond = JSON.parse(this.agenda.permissions).can_respond;
				window.axios.post('/pms/get_users',{users:can_respond}).then(response => {
					if(this.action == "creatorResponses"){
						//all responses awill display for creator--
		     			this.users = response.data;
		     			this.selectedUsers = response.data;
		     			this.allUsers = response.data;
		     			let selectedUsersArr = [];
		     			this.selectedUsers.forEach(function(v,k){
		     				selectedUsersArr.push(v.id);
		     			});

		     			this.selectedUsersArr = selectedUsersArr;
		     		}else{
		     			response.data.forEach((v, k) => {
		     				if (this.logged_user.id == v.id){
		     					this.users.push(v);
		     				}
		     			});
		     			this.selectedUsersArr.push(this.logged_user.id);
		     		}

		     	})
				.catch(error => {
					console.log('error', error);
				});
			},
		},
	created(){	
		this.agenda = this.openedAgenda;
		let n;
		for(n=0;n<this.agenda.worktime_check;n++){
			this.loopTasks.push({
					detail: '',
					time: 1
				});
		}
		this.get_users();
		this.totalHours = this.agenda.worktime_check;
	},
}
</script>
<style >
table p{
	margin-bottom: 0;
}
.v--modal{
	height:initial!important;
	min-height: 300px!important;
	padding: 10px;
}
.ql-editor {
	min-height: 80px !important;
}
#timeline {
	list-style: none;
	position: relative;
}
#timeline:before {
	top: 0;
	bottom: 0;
	position: absolute;
	content: " ";
	width: 2px;
	background-color: #4997cd;
	left: 0%;
	margin-left: -1.5px;
}
#timeline .clearFix {
	clear: both;
	height: 0;
}
.close{
	top: -10px!important;
	right: -17px!important;
}
#timeline .timeline-badge {
	color: #fff;
	width: 50px;
	height: 50px;
	font-size: 1.2em;
	text-align: center;
	position: absolute;
	top: 20px;
	left: 1%;
	margin-left: -25px;
	background-color: #4997cd;
	z-index: 100;
	border-top-right-radius: 50%;
	border-top-left-radius: 50%;
	border-bottom-right-radius: 50%;
	border-bottom-left-radius: 50%;
}
#timeline .timeline-badge span.timeline-balloon-date-day {
	/*font-size: 1.4em;*/
}
#timeline .timeline-badge span.timeline-balloon-date-month {
	font-size: .7em;
	position: relative;
	top: -10px;
}
#timeline .timeline-badge.timeline-filter-movement {
	background-color: #ffffff;
	font-size: 1.7em;
	height: 35px;
	margin-left: -18px;
	width: 35px;
	top: 40px;
}
#timeline .timeline-badge.timeline-filter-movement a span {
	color: #4997cd;
	font-size: 1.3em;
	top: -1px;
}
#timeline .timeline-badge.timeline-future-movement {
	background-color: #ffffff;
	height: 35px;
	width: 35px;
	font-size: 1.7em;
	top: -16px;
	margin-left: -18px;
}
#timeline .timeline-badge.timeline-future-movement a span {
	color: #4997cd;
	font-size: .9em;
	top: 2px;
	left: 1px;
}
#timeline .timeline-movement {
	border-bottom: dashed 1px #4997cd;
	position: relative;
}
#timeline .timeline-movement.timeline-movement-top {
	height: 60px;
}
#timeline .timeline-movement .timeline-item {
	padding: 20px 0;
}
#timeline .timeline-movement .timeline-item .timeline-panel {
	border: 1px solid #d4d4d4;
	border-radius: 3px;
	background-color: #FFFFFF;
	color: #666;
	padding: 10px;
	position: relative;
	-webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
	box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
}
#timeline .timeline-movement .timeline-item .timeline-panel .timeline-panel-ul {
	list-style: none;
	padding: 0;
	margin: 0;
}
#timeline .timeline-movement .timeline-item .timeline-panel.credits .timeline-panel-ul {
	text-align: left;
}
#timeline .timeline-movement .timeline-item .timeline-panel.credits .timeline-panel-ul li {
	color: #666;
}
#timeline .timeline-movement .timeline-item .timeline-panel.credits .timeline-panel-ul li span.importo {
	color: #468c1f;
	font-size: 1.3em;
}
#timeline .timeline-movement .timeline-item .timeline-panel.debits .timeline-panel-ul {
	text-align: left;
}
#timeline .timeline-movement .timeline-item .timeline-panel.debits .timeline-panel-ul span.importo {
	color: #e2001a;
	font-size: 1.3em;
}
</style>