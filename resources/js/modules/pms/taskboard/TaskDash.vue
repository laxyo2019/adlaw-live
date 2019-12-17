<template>
	<div>
		<DashCard :title="'To Do'"></DashCard>
		<DashCard :title="'In-Progress'"></DashCard>
		<DashCard :title="'Done'"></DashCard>
	</div>
</template>

<script>
	import DashCard from './DashCard.vue'
	export default {
		components: {
			DashCard
		}
	}
</script>