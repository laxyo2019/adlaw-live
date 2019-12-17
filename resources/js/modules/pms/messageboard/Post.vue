<template>
  <div class="card">
  	<div class="d-flex align-items-center pt-4 pl-3 mt-auto">
      <div class="avatar avatar-md mr-3" style="background-image: url(/tabler/demo/faces/male/16.jpg)"></div>
      <div>
        <a href="./profile.html" class="text-default" v-text="fetchUser(post.creator_id)"></a>
        <small class="d-block text-muted">{{humanReadableTime(post.created_at)}}</small>
      </div>
      <div class="ml-auto">
        <a href="#" title="close" class="icon d-none d-md-inline-block ml-3 mb-5" @click.prevent="$emit('close')">
        	<i class="fa fa-remove fa-lg mr-3"></i>
        </a>
      </div>
    </div>

  	<div class="card-body row">
  		<p class="col-sm-6 col-xs-12" ><b>Post Title : </b><span class="card-title">{{ post.title }}</span></p>
  		<p style="border-left: solid 1px #cec5c5;" class="col-sm-6 col-xs-12"><b>Post Category : </b><span class="card-title">{{ fetchCategory(post.category_id) }}</span></p>
  		<p class="col-12"><b>Post Body : </b><span v-html="post.body"></span></p>
  		<media-component :post_id="post.id" :logged_user="user"></media-component>
  		<comments-component 
  			:entity_id="post.id" 
  			:logged_user="user" 
  			:team_id="team_id" 
  			:comment_type="2"
  			:users_in_team="usersInTeam"></comments-component>
  	</div>
  </div>
</template>

<script>
	import moment from 'moment'
	export default {
		props: ['post', 'user', 'team_id', 'usersInTeam'],
		data(){
			return{
			}
		},
		methods:{
			fetchCategory(ident){
				let x ='';
				switch(ident){
					case 1:
						x = 'Announcement';
						break;
					case 2:
						x = 'FYI';
						break;
					case 3:
						x = 'Notice';
						break;
				}
				return x;
			},
			humanReadableTime(date){
				return moment(date,'YYYY-MM-DD HH:mm:ss').fromNow();
			},
			fetchUser(id){
				let x='';
				let users =this.usersInTeam;
				users.forEach(function(v,k){
					if(id==v.id){
						x = v.name;
					}
				});
			return x;
			},
		}
	}
</script>