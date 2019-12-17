<template>
	<div class="container">
		<div class="card">
				<div class="card-header row m-0">
					<h4 class="col-sm-2 col-xs-12 pull-left">Taskboards</h4>
					<div class=" col-sm-6 col-xs-12 offset-sm-4 form-group">
	        	<input type="text" class="form-control mt-2" placeholder="Search a taskboad..." v-model="searchKey" @keyup="getResults">
	        </div>
				</div>
				<div class="card-body" v-if="isEmpty(focusedTaskboard)">
					<div>
						<div class="table-responsive" style="min-height: 15rem;">
							<table class="table table-hover table-outline table-vcenter text-nowrap card-table">
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Created At</th>
										<th class="">Actions</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(taskboard, index) in taskboards.data" :key="index">
										<td>{{ taskboard.id }}</td>  	
										<td>{{ taskboard.title }}</td>
										<td>{{ taskboard.created_at }}</td>
										<td class="">
												<a class="btn btn-sm btn-primary text-white" @click.prevent="focusedTaskboard = taskboard">
													<i class="fa fa-edit"></i>
													Edit
												</a>
												<!-- <a class="text-danger ml-1" @click.prevent="deleteTask(taskboard)">
													<i class="fa fa-trash"></i>
												</a> -->
										</td>
									</tr>
								</tbody>
							</table>
							 <pagination :data="taskboards" @pagination-change-page="getResults" align="right">
			        		<span slot="prev-nav">Previous</span>
									<span slot="next-nav">Next</span>
			      	</pagination>
						</div>
					</div>
				</div>
				<div v-else>
					<TaskboardComponent	
					@unfocus="focusedTaskboard={}"
					:focusedTaskboard="focusedTaskboard"
    		></TaskboardComponent>
				</div>
		</div>
	</div>
</template>
<script>
import Validation from '../../../utils/Validation.js';
import TaskboardComponent from './Taskboard.vue';
export default {
	props: [],
	components : {
		TaskboardComponent
	},
	data () {
		return {
			validation: new Validation(),
			searchKey:'',
			taskboards:{},
			focusedTaskboard:{},
		}
	},
	mounted() {
		this.getResults();
	},
	methods: {
		isEmpty(obj) {
		    for(var key in obj) {
	        if(obj.hasOwnProperty(key))
	            return false;
		    }
		    return true;
			},
		getResults(page = 1) {
			axios.post('/taskboards/paginate?page=' + page,{keyword:this.searchKey})
				.then(response => {
					this.taskboards = response.data;
				});
		},
		deleteTask(taskboard){
			Vue.swal({
			  title: 'Are you sure?',
			  text: "You won't be able to revert this!",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
			  if (result.value) {
			   	axios.delete(`/taskboards/${taskboard.id}`).then(response => {
						this.taskboards.data = this.taskboards.data.filter(t => (t.id !=taskboard.id));
					}).catch(error=>{
						console.log(error);
					});
			  }
			})
		}
	},
	created () {
		
	}
}
</script>
