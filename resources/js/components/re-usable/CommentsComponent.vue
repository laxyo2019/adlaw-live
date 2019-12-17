<template>
	<div class="card">
		<div class="row ml-2 mr-2 mt-2 mb-n5">
			<p class="col-6">All Comments</p>
			<p class="col-6 text-right">{{comments.length}} Comments</p>
		</div>
		<div class="card-body">
			<div class="form__wrapper">
				<div class="form-group">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Write a comment..." 
							v-model="comment" @keyup.enter="storeComment">
            <span class="input-group-append">
              <button class="btn btn-primary" type="button" @click="storeComment">Comment</button>
            </span>
          </div>
        </div>
			</div>
			<div class="alert alert-primary" v-for="comment in comments" :key="comment.id">
				<a v-if="canDelete(comment)" @click.prevent="deleteComment(comment.id)" class="float-right text-danger">
					<i class="fe fe-trash"></i>
				</a>	
        	{{ comment.body }}
        <small class="d-block text-muted">
        	<span>{{ comment.user.name }}</span>
        	<br>
        	<span>{{ comment.created_at | moment('from') }}</span>
        </small>
			</div>

		</div>
	</div>
</template>

<script>
	export default {
		props: ['logged_user', 'commentable_id', 'model'],
		data() {
			return {
				comment: '',
				// editMode: false,
				// userLoop: this.users,
				comments: []
			}
		},
		created(){
			this.getComments(this.commentable_id, this.model);
			// this.getTeamUsers();
		},
		mounted() {
			Echo.private(`comments.${this.displayid}`)
		    .listen('NewComment', (e) => {
	        console.log(e.comment);
	        this.comments.unshift(e.comment);
	        Vue.toasted.show(`${e.comment.user.name} has commented on a ${this.getCommentType()} in ${e.team.name} team`, 
	        {
						duration: 5000
					});
		    });
		},
		methods: {
			canDelete(comment){				
				if (this.logged_user.id === comment.user_id) {
					return true;
				}
				return false;
			},

			// getTeamUsers() {
			// 	axios.get(`/admin/teams/get_users/${this.team_id}`).then(response => {
			// 		this.userLoop = response.data;
			// 		this.getAvatars();
			// 	}).catch(error => console.log(error.response.data));
			// },
			
			getComments(id, type) {
				axios.post('/pms/fetch-comments', {'commentable_id': id, 'commentable_type': type}).then(response => {
					this.comments = response.data;
				}).catch(error => console.log(error.response.data));
			},

			storeComment() {
				axios.post('/pms/comments', {
					'user_id': this.logged_user.id,
					'comment': this.comment,
					'commentable_id': this.commentable_id,
					'model': this.model
				}).then(response => {
					console.log(response.data);
					this.comment = '';
					this.comments.unshift(response.data);
				}).catch(error => console.log(error.response.data));
			},

			deleteComment(id) {
				if(confirm('Are you sure?'))
				{
					axios.delete(`/pms/comments/${id}`).then(response => {
						this.comments = this.comments.filter(c => (c.id !== id));
						Vue.toasted.show(response.data, {
							duration: 2000
						});
					}).catch(error => console.log(error.response.data));
				}
			}

			// getCommentType() {
			// 	switch(this.comment_type) {
			// 	  case 1:
			// 	    return 'Task';
			// 	    break;
			// 	  case 2:
			// 	    return 'Post';
			// 	    break;
			//    	case 3:
			// 	    return 'Schedule';
			// 	    break; 
			// 	  case 4:
			// 	    return 'Agenda';
			// 	    break; 
			// 	  default:
			// 	    // code block
			// 	}
			// },

			// getAvatars() {
			//   let ids = [];
			//   this.users_in_team.forEach(function(e){
			//     ids.push(e.id);
			//   });
			//   axios.post('/user/profile/get_avatars', {users: ids}).then(response => {
			//     console.log(response.data);
			//     this.avatars = response.data;
			//   }).catch(error => console.log(error.response.data));
			// },

			   //    getInitials(name) {
   //      let initials = name.match(/\b\w/g) || [];
   //      return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
   //    },
			// getRandomClass() {
			//   let items = ['avatar-blue', 'avatar-azure','avatar-indigo','avatar-purple', 'avatar-pink','avatar-red',
			//   'avatar-orange', 'avatar-yellow', 'avatar-lime', 'avatar-green', 'avatar-teal', 'avatar-cyan'];
			//   return items[Math.floor(Math.random()*items.length)];
			// },
		}
	}
</script>