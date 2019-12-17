<template>
	<div class="card">
	  <div class="card-header">
	    <h2 class="card-title">My Tasks</h2>
	    <div class="card-options">
	      <a href="#" class="btn btn-primary btn-sm">Pending</a>
	      <a href="#" class="btn btn-secondary btn-sm ml-2">Completed</a>
	      <a href="#" class="btn btn-secondary btn-sm ml-2">Awaiting</a>
	      <a href="#" class="btn btn-secondary btn-sm ml-2">Expired</a>
	    </div>
	  </div>
	  <table class="table card-table" style="height: 15rem">
	    <tbody>
	      <tr>
	        <td>Admin Template</td>
	        <td class="text-right">
	          <span class="badge badge-default">65%</span>
	        </td>
	      </tr>
	    </tbody>
	  </table>
	</div>
</template>

<script>
	export default {
		props: ['logged_user'],
		components: {},
		data() {
			return {

			}
		}
	}
</script>