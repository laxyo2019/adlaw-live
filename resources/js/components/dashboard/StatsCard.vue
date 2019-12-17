<template>
	<div class="card">
	  <div class="card-header">
	    <h2 class="card-title">My Stats</h2>
	  </div>
	  <table class="table card-table o-auto" style="height: 15rem;">
	    <tbody>
	    	<tr v-for="stat in stats" v-if="stats.length > 0">
	    	  <td>Admin Template</td>
	    	  <td class="text-right">
	    	    <span class="badge badge-success">Finished</span>
	    	  </td>
	    	</tr>
	      <tr v-else>
	        <td>Admin Template</td>
	        <td class="text-right">
	          <span class="badge badge-success">Finished</span>
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
				stats: []
			}
		}
	}
</script>