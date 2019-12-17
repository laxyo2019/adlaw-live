<template>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 col-xl-3">

					<div class="list-group text-center mb-5">
						<p class="text-center bg-info text-white" style="font-size: 1.2em">-- Status --</p>
					  <button type="button" class="list-group-item list-group-item-action" :class="">Unread</button>
					  <button type="button" class="list-group-item list-group-item-action">Read</button>
					</div>

					<div class="list-group text-center mb-5">
						<p class="text-center bg-info text-white" style="font-size: 1.2em">-- Priority --</p>
					  <button type="button" class="list-group-item list-group-item-action" :class="">High</button>
					  <button type="button" class="list-group-item list-group-item-action">Low</button>
					</div>

					<div class="list-group text-center mb-5">
						<p class="text-center bg-info text-white" style="font-size: 1.2em">-- Category --</p>
					  <button type="button" class="list-group-item list-group-item-action" :class="">Tasks</button>
					  <button type="button" class="list-group-item list-group-item-action">Agenda</button>
					  <button type="button" class="list-group-item list-group-item-action">Schedules</button>
					</div>

			</div>
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-9 col-xl-9">
				<div class="row alert" :class="ping.data.color" v-for="ping in activity">
					  <h4 class="col-9"><i class="fa fa-tasks mr-2"></i>{{ ping.data.title }}</h4>
					  <p class="col-3 text-right">
					  	<button class="btn btn-sm btn-secondary mr-2" type="button" @click="goToLink(ping.data.link)"><i class="fe fe-arrow-up-left" aria-hidden="true"></i> Open
					  	</button>
					  	<button class="btn btn-sm btn-secondary" type="button" @click="dismissNotification(ping.id, ping.data.priority)"><i class="fe fe-x" aria-hidden="true"></i> Dismiss
					  	</button>
						</p>
						<div class="col-12">
							<p>{{ ping.data.message }}</p>
							<span class="tag">{{ ping.data.created_at | moment("from") }}</span>
						</div>
				</div>
				<!-- <div class="card">
				  <div class="card-body o-auto p-0" style="height: 20rem">
				  	<table class="table card-table">
				  	  <tbody v-if="activity.length > 0">
				  	    <tr v-for="act in activity">
				  	      <td>
				  	      	<a class="text-decoration-none" v-bind:href="act.data.link" :title="act.data.message" style="color:#3e3737">
										{{act.data.message}}
										</a>
				  	    	</td>
				  	      <td class="text-right">
				  	        <span class="badge badge-secondary">{{ act.data.created_at | moment("from")}}</span>
				  	      </td>
				  	    </tr>
				  	  </tbody>
				  	  <tbody v-else>
				  	  	<tr class="text-center">
				  	  	  <td>
				  	  	  	<a href="#" @click.prevent="" class="btn btn-lg btn-secondary" style="margin-top: 8rem">No unread notifications</a>
				  	  		</td>
				  	  	</tr>
				  	  </tbody>
				  	</table>
				  </div>
				</div> -->
			</div>
		</div>
	</div>
	
</template>

<script>
	import { EventBus } from './../../event-bus.js';
	export default {
		props: ['logged_user'],
		components: {},
		data () {
			return {
				activity: [],
			}
		},
		created() {
			Echo.private('App.User.' + this.logged_user.id).notification((notification) => {
        this.activity.unshift(notification);
	    });
		},
		mounted() {
			this.activity = this.logged_user.unread_notifications;
		},
		methods: {
			dismissNotification(id, priority) {
				// if(priority == 'High'){
				// 	Vue.swal('Cannot dismiss a high priority alert without action!');
				// }else{
					axios.get(`/notifications/dismiss/${id}`).then(response => {
						console.log(response.data)
						EventBus.$emit('notification-dismissed', id);
						this.activity = this.activity.filter(a => (a.id !== id));
					}).catch(error => console.log(error.response.data)); 
				// }
			},
			goToLink(link) {
				window.location.href = link;
			}

		}
	}
</script>