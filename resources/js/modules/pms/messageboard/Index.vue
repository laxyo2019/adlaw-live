<template>
	<div class="messageboard__wrapper">
		<span v-if="isEmpty(focusedPost)">
			<div class="row">
				<div class="form-group col-8">
			  	<button type="button"
			  		class="btn btn-pill btn-primary" @click="env.createPost = !env.createPost">
			  		<i class="fe fe-plus"></i> New Post</button>
			  </div>
			  <div class="col-4">
	        	<div class="input-group">
	        	  <input type="text" class="form-control" placeholder="Search a post..." 
	        	  	v-model="searchKey"  @keyup="getResults">
	        	  <span class="input-group-btn ml-2">
	        	    <button class="btn btn-sm btn-default" @click="getResults">
	        	      <span class="fe fe-search"></span>
	        	    </button>
	        	  </span>
	        	   <span class="input-group-btn ml-2">
	        	      <button class="btn btn-sm btn-default" @click="refresh">
	        	        <span class="fe fe-refresh-cw"></span>
	        	      </button>
	        	    </span>
	        	</div>
			  </div>
		  </div>

			<span v-if="env.createPost">
		  	<form-component @close-form="closeForm" @post-stored="postStored" :postCategories="meta.filetypes" :board_id="board.id" :creator_id="logged_user.id">
		  	</form-component>
		  </span>

		 	 <div class="card" v-for="post in posts.data" :key="post.id" style="cursor: pointer" @click="openPost(post.id)">
		  	<div class="d-flex align-items-center pt-4 pl-3 pb-4 mt-auto">
	        <div class="avatar avatar-lg mr-3" style="background-image: url(/tabler/demo/faces/male/16.jpg)" title="Creator Name"></div>
	        <div >
	          <b style="font-size:1.2em;" v-text="post.title"></b>
	          <small class="d-block text-muted">{{fetchCategory(post.category_id)}} by {{fetchUser(post.creator_id)}} . {{humanReadableTime(post.created_at)}}</small>
	        </div>
	        <div class="ml-auto">
	          <span class="badge badge-pill badge-primary mr-3">{{post.comments.length}}</span>
	        </div>
	      </div>
		  </div>
		   <pagination :data="posts" @pagination-change-page="getResults" align="right">
        	<span slot="prev-nav">Previous</span>
					<span slot="next-nav">Next</span>
			</pagination>
		</span>

		<span v-else>
			<post-component :usersInTeam="team.users_in_team" :post="focusedPost" @close="focusedPost = {}" :user="logged_user" :team_id="team.id"></post-component>
		</span>
	</div>
</template>

<script>
	import moment from 'moment'
	import FormComponent from './Form.vue'; 
	import PostComponent from './Post.vue'; 
	export default {
		props: ['team', 'board', 'logged_user', 'meta'],
		components: { 	
			FormComponent,
			PostComponent
		},
		data() {
			return {
				searchKey: null,
				posts: {},
				env: {
					createPost: false
				},
				teamUsersInfo:{},
				searchKey: '',
				focusedPost: {}
			}
		},
		created() {
			// this.posts = Object.assign({}, this.board.posts);
			this.teamUsersInfo = this.team.users_in_team;
		},
		mounted() {
			this.getResults();
		},
		methods: {
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
			getResults(page = 1) {
				axios.post('/pms/posts/search?page=' + page,{keyword:this.searchKey,board_id:this.board.id})
					.then(response => {
						this.posts = response.data;
					});
			},
			refresh() {
				this.searchKey = null;
				this.getResults();
			},
			closeForm() {
				this.env.createPost = false;
			},
			isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
		    }
		    return true;
			},
			searchPosts() {
				
			},
			humanReadableTime(date){
				return moment(date,'YYYY-MM-DD HH:mm:ss').fromNow();
			},
			postStored(post) {
				// console.log('test ',post);
				this.posts.data.unshift(post);
			},
			fetchUser(id){
				let x='';
				let users =this.teamUsersInfo;
				users.forEach(function(v,k){
					if(id==v.id){
						x = v.name;
					}
				});
			return x;
			},
			openPost(id) {
				axios.get(`/pms/posts/${id}`).then(response => {
					this.focusedPost = response.data;
				}).catch(error => console.log(error.response.data));
			}
		}
	}
</script>