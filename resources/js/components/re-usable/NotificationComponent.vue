<template>
  <div class="dropdown d-flex">
    <a href="/notifications" class="nav-link icon" title="Notifications">
      <i class="fa fa-bell-o fa-lg"></i>
      <span class="badge badge-pill badge-danger" v-if="pings.length > 0" v-text="pings.length"></span>
    </a>
	</div>
</template>
<style scoped>
	.dropdown-item.active, .dropdown-item:active{
		background: transparent!important;
	}
</style>
<script>
  import { EventBus } from './../../event-bus.js';
	export default {
		props: ['notifications', 'logged_user'],
		components: {},
		data () {
			return {
				pings : [],
			}
		},
		created() {
			Echo.private('App.User.' + this.logged_user.id).notification((notification) => {
        this.playSound('/sounds/open-ended.mp3');
        this.pings.unshift(notification);
	    });
		},
		mounted() {
			this.pings = this.notifications;
      EventBus.$on('notification-dismissed', notify_id => {
        console.log('event bus working!');
        this.pings = this.pings.filter(p => (p.id !== notify_id));
      });
		},
		methods: {
    	playSound (sound) {
	      if(sound) {
	        var audio = new Audio(sound);
				  audio.play();
	      }
	    },
		}
	}
</script>
<style>
	.avatar-icon{
		font-size: 125%;
    vertical-align: sub;
    position: absolute;
    left: 0;
    right: 0;
    margin: auto;
    top: 10px;
	}
</style>

<!-- <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
  <div class='o-auto' style='max-height: 350px'>
  	<a href="#" class="dropdown-item d-flex" v-for="ping in pings" :key="ping.id">
    <span style='border:solid 1px' class="border-primary bg-white avatar avatar-md d-block mr-3 align-self-center">
    	<i :class='ping.data.class' class="text-primary"></i>
    	<i class="text-primary avatar-icon"></i>
    </span>
    <div>
      <strong>Nathan</strong> pushed new commit: Fix page load performance issue.
     <a class="text-decoration-none" v-bind:href="ping.data.link" :title="ping.data.message" style="color:#3e3737"> 
    {{sliceStr(ping.data.message)}}
     </a>
      <div class="small text-muted">{{ ping.data.created_at | moment("from")}}</div>
    </div>
  </a>
  </div>
  <div class="dropdown-divider"></div>
  <a href="#" v-if="pings.length > 0" @click.prevent="markAllAsRead" class="dropdown-item text-center text-muted-dark">Mark all as read</a>
</div> -->