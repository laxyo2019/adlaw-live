<template>
	<div class="container" >
		<div class="">
			<a href="#" class="pull-left mt-2 mb-2 ml-2" @click.prevent="$emit('unfocus')"><i class="fa fa-arrow-left">BACK</i></a>
			<div class="card">
				<div class="">


					<div class="card-header" v-if="action=='creatorResponses'">
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
									<template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
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
					</div>




		</div>
		<div class="" v-if="action =='UserResponses'">

			<div class="card-body">
				<div class="col-12" v-if="!todaysResponse()">
					<div class="text-right">
						<a href="#" class="btn btn-success" @click.prevent="modifyFormRow('plus')"><i class="fe fe-plus"></i></a>
						<a href="#" class="btn btn-danger" @click.prevent="modifyFormRow('minus')"><i class="fe fe-minus"></i></a>
					</div>

					<div class="row">
						<div class="col-10">
							<label for="">Task Detail</label>
						</div>
						<div class="col-2">
							<label for="">Time Duration (in hrs)</label>
						</div>
					</div>

					<div class="row" v-for="n in loopLength">
						<div class="col-10">
							<div class="form-group">
								<input type="text" class="form-control" v-model="loopTasks[n-1].detail" placeholder="Write task details here...">
							</div>
						</div>
						<div class="col-2">
							<div class="form-group">
								<select name="" id="" v-model="loopTasks[n-1].time" class="form-control">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="more than 8">More than 8</option>
								</select>
							</div>
						</div>

					</div>

					<div class="form-group">
						<!-- <vue-editor v-model="content" :editortoolbar="customToolbar"></vue-editor> -->
						<vue-editor v-model="content" ></vue-editor>
						<div class="validation-message" v-text="validation.getMessage('response')"></div>
					</div>
					<div class="text-right">
						<a href="#" @click.prevent="handleAnswerInit" class="btn btn-pill btn-success">Add
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
		<div class="card-body">
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
				<div class="row timeline-movement" v-for="day in daysOfYear">
					<div class="timeline-badge">
						<span class="timeline-balloon-date-day">{{ day | moment("DD") }}<br></span>
						<span class="timeline-balloon-date-month">{{ day | moment("MMM") }}</span>
					</div>
					<div class="timeline-item row" style="width:100%">
						<div class="col-sm-11" style="margin:10px 0 0 20px;" v-for="user in users">
							<div class="timeline-panel credits" >
								<ul class="timeline-panel-ul">
									<li>
										<span class="importo">{{user.name}}</span>
										<a class="pull-right" @click="openEditModal(day,fetch_response(day,user.id))">
											<i class="fa fa-edit text-success"></i>
										</a>
										<p class="" v-if="checkDate(day, user.id)">
											<small class="text-muted">
												<i class="fa fa-clock-o"></i> {{fetch_response(day,user.id,true) | getHIS}}
											</small>
										</p>

									</li>
									<li>
										<div v-if="(checkDate(day, user.id))">
											<table class="table table-striped table-bordered">
												<thead class="table-dark">
													<th style="color:#000">Task details</th>
													<th style="color:#000">Time Duration (in hrs)</th>
												</thead>
												<tbody>
													<tr v-for="task in fetch_response(day,user.id).forTask">
														<td>{{task.detail}}</td>
														<td>{{task.time}}</td>
													</tr>
												</tbody>
											</table>
											<span><b>SUMMARY : </b></span>
											<span class="causale"  v-html="fetch_response(day,user.id).body"></span> 
										</div>

										<span v-else>
											<span class="text-danger" v-if="expired(day)">Response missed</span>
											<span class="text-info" v-else>Not responded Yet!!</span>
										</span>
									</li>	
								</ul>
								<comments-component v-if="fetch_response(day,user.id,false,true)" :can_delete="[1]" :entity_id="fetch_response(day,user.id,false,true)" :logged_user="logged_user" :team_id="team.id" :comment_type=4></comments-component>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<modal name="editResponseModal">
	<div class="col-12">
		<div class="form-group">
			<vue-editor v-model="editResponse.body" />
			<div class="validation-message" v-text="editValidation.getMessage('body')"></div>
		</div>
		<div class="text-right">
			<a href="#" 
			@click.prevent="updateResponse" 
			class="btn btn-pill btn-success">Update
		</a>
		<a href="#" 
		@click.prevent="hideModal()" 
		class="btn btn-pill btn-danger">Cancel
	</a>
</div>
</div>
</modal>
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
				editValidation: new Validation(),
				validation: new Validation(),
				loopLength: 0,
				loopTasks: [
				{
					detail: '',
					time: ''
				},
				],
				content: "",
				customToolbar: [["bold", "italic", "underline"], [{ list: "ordered" }, { list: "bullet" }], ["code-block"]],
				SelectedDuration:null,
				duration: ['All','Today', 'Weekly', 'Monthly', 'Last Month', 'Last three months'],
				agenda : {},
				responses: [],
				editResponse:{
					body:'',
					id:null,
				},
				users : [],
				allUsers:[],
				selectedUsers:[],
				daysOfYear: [],
			}
		},
		created(){	
			this.agenda = this.openedAgenda;
			this.responses = this.openedAgenda.responses;
			this.loopDates();
			this.get_users();
		},
		methods: {
			modifyFormRow(func) {
				if(func == 'plus' && this.loopLength < 15)
				{
					this.loopTasks.push({
						detail: '',
						time: ''
					});
					this.loopLength++;
				}
				else if(func == 'minus' && this.loopLength > 1)
				{
					this.loopLength--;
					this.loopTasks.pop();
				}
			},
			hideModal(){
				this.$modal.hide('editResponseModal');
			},
			updateResponse(){
				window.axios.post('/pms/agenda/update_response', {body:this.editResponse.body,id:this.editResponse.id}).then(response => {
					this.responses.forEach((v, k) => {
						if (v.id == response.data.id)
							Vue.set(this.responses,k, response.data);
					});
					Vue.toasted.success(`Updated Successfully`, {
						duration: 2000
					});
					this.editValidation = new Validation();
					this.editResponse.body = '';
					this.editResponse.id = null;
					this.hideModal();
				}).catch(error => {
					if (error.response.status == 422) {
						this.editValidation.setMessages(error.response.data.errors);
					}else{
						console.log(error.response.data)
					}
				});
			},
			openEditModal(day,response){
				let cDay = moment(day).format("YYYY-MM-DD");
				let today = moment().format("YYYY-MM-DD");
				console.log(cDay , today);
				if(cDay!=today){
					Vue.toasted.error(`Only today's response can be edited.`, {
						action : {
							text : 'Okay',
							onClick : (e, toastObject) => {
								toastObject.goAway(0);
							}
						},
					});
				}else if(response.responder_id !=  this.logged_user.id){
					Vue.toasted.error(`Only responder can edit this response.`, {
						action : {
							text : 'Okay',
							onClick : (e, toastObject) => {
								toastObject.goAway(0);
							}
						},
					});
				}else{
					this.editResponse.body = response.body;
					this.editResponse.id = response.id;
					this.$modal.show('editResponseModal');
				}
				
			},
			expired(day){
				// console.log(day);
				let x = false;
				let day2 = (moment(day).format("YYYY-MM-DD")) +' '+this.agenda.expiry_time;
				let now = moment(String(new Date())).format('YYYY-MM-DD HH:mm:ss');
				// console.log('now and esp--'+now,'---'+day2);
				if(day2 < now){
					x = true;
				}
				return x;
			},
			filter(){
				this.users = this.selectedUsers;
			},
			get_users(){
				window.axios.post('/pms/get_users',{users:this.agenda.users}).then(response => {
					if(this.action == "creatorResponses"){
		     			// console.log('type',this.action);
		     			this.users = response.data;
		     			this.selectedUsers = response.data;
		     			this.allUsers = response.data;
		     		}else{
		     			// console.log('type',this.action);
		     			response.data.forEach((v, k) => {
		     				if (this.logged_user.id == v.id){
		     					this.users.push(v);
		     				}
		     			});
		     			let responses = this.responses;
		     			let updated_res = [];
		     			let logged_user = this.logged_user.id;
		     			responses.forEach(function(v,k){
		     				if(v.responder_id == logged_user){
		     					updated_res.push(v);
		     				}
		     			});
		     			this.responses=updated_res;
		     			console.log('resposnse',responses);
		     		}

		     	})
				.catch(error => {
					console.log('error', error);
				});
			},
			fetch_response(date,responder_id,created_at=false,return_id=false){
				var currentDate = new Date(date);
				var date = currentDate.getDate();
				var month = currentDate.getMonth(); //Be careful! January is 0 not 1
				var year = currentDate.getFullYear();
				let finalDate='';
				if(date.toString().length == 1 ){
					date = '0' + date;
				}
				if((month+1).toString().length==2){
					finalDate = year + "-" +(month + 1) + "-" + date;
				}else{
					finalDate = year + "-" +0+(month + 1) + "-" + date;
				}
				let response = '';
				let time = '';
				this.responses.forEach(function(e){
					if(e.responder_id == responder_id && ((e.created_at).split(" "))[0] == finalDate){
						response = e;
						response.forTask = e.tasks != null ? JSON.parse(e.tasks) : [];
						time = e.created_at;
					}
				});
				if(created_at){
					return time;
				}

				if(return_id && (response != 'undefined' && response != '')){
					if(this.agenda.creator_id == this.logged_user.id || JSON.parse(this.agenda.permissions)['comment'].indexOf(this.logged_user.id) > -1 || response.responder_id == this.logged_user.id){
						return response.id;
					}
					return false;
				}
				
				return response;
			},
			checkDate(date, user) {
				let x = false;
				let dateString = '';
				var currentDate = new Date(date);
				var date = currentDate.getDate();
				var month = currentDate.getMonth(); //Be careful! January is 0 not 1
				var year = currentDate.getFullYear();
				if(date.toString().length == 1 ) {
					date = "0"+date;
				}	

				if((month+1).toString().length==2){
					dateString = year + "-" +(month + 1) + "-" + date;
				}else{
					dateString = year + "-" +0+(month + 1) + "-" + date;
				}	

				let loopResponses = this.responses;
				loopResponses.forEach(function(e){
					let y =  ((e.created_at).split(' '))[0];
					if(dateString == y && user == e.responder_id)
					{
						x = true;
						// console.log('match date', new Date(e.created_at));
					}
				}); 
				return x;
			},
			loopDates() {
				let now = new Date();
				let dateLoop = [];
				let agenda_start = (((this.agenda.created_at).split(' '))[0]).split('-');
				let month = parseInt(agenda_start[1]);

				if(month.length == 2 && month < 10){
					agenda_start[1] = (agenda_start[1].split(''))[1]; 
				}

				for (var d = new Date(parseInt(agenda_start[0]), (parseInt(agenda_start[1]) - 1), parseInt(agenda_start[2])); d <= now; d.setDate(d.getDate() + 1)) {
					dateLoop.push(new Date(d));
				}
				let pauses = this.agenda.pauses;
				let active_days = JSON.parse(this.agenda.days);
				let filteredLoop = [];
				let filteredNewLoop =[];
				let pauseDateStatus = false;
				dateLoop.forEach(function(x){
					// console.log('date loop start',x);
					if(active_days.indexOf(x.getDay()) > -1)
					{
						pauses.forEach(function(e){
							let pauseDate = (e.date).split('-');
							let pauseDatemonth = parseInt(pauseDate[1]);
							if(pauseDatemonth.length == 2 && pauseDatemonth < 10){
								pauseDate[1] = (pauseDate[1].split(''))[1]; 
							}
							let date = new Date(parseInt(pauseDate[0]), (parseInt(pauseDate[1]) - 1), parseInt(pauseDate[2]));
							if(moment(x).isSame(date)){
								pauseDateStatus = true;
							}
						}); 
						if(!pauseDateStatus){
							filteredLoop.push(x);
						}
					}
					pauseDateStatus = false;
				});
				this.daysOfYear = filteredLoop.reverse();	
			},

			todaysResponse(){
				var count = false;
				var todayDate = moment(String(new Date())).format('YYYY-MM-DD');
				let user_id = this.logged_user.id;
				this.responses.forEach(function(e){
					if(e.created_at.split(' ')[0] == todayDate && user_id==e.responder_id){
						count=true;
					}
				});
				return count;
			},
			handleAnswerInit() {
				let error = false;
				let postData = {};
				postData.tasks = this.loopTasks;
				postData.team = this.team;
				postData.response = this.content;
		  	postData.agenda_id = this.agenda.id; // add additional param
		  	let time = 0;
		  	let more_than_8 = false;
		  	this.loopTasks.forEach(function(v,k){
		  		if(v.detail=="" || v.time == "")
		  		{
		  			error = true;
		  		}else if(v.time == 'more than 8'){
		  			more_than_8 = true;
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
		  	else if(! (more_than_8 == true || time >= 8)){
		  		Vue.toasted.error("** The total hours for today's agenda must be 8 hours or more.", {
		  			action : {
		  				text : 'Okay',
		  				onClick : (e, toastObject) => {
		  					toastObject.goAway(0);
		  				}
		  			},
		  		});
		  	}else{
		  		this.submitResponse(postData);
		  	}
		  },
		  submitResponse(postData){
		  	window.axios.post('/pms/addAgendaResponse', postData).then(response => {
		  		if(response.status == 201){
		  			this.responses.push(response.data);
		  			Vue.toasted.success(`Added Successfully`, {
		  				duration: 2000
		  			});
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
		  			this.validation.setMessages(error.response.data.errors);
		  		}else{
		  			console.log(error.response.data)
		  		}
		  	});
		  }
		},

	}


	Vue.filter("getHIS", function(value) {
		return moment(String(value)).format('DD-MM-YYYY HH:mm:ss')
	})
</script>

<style >
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
