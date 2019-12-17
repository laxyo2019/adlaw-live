<template>
  <div class="card">
    <div class="card-header">

      <h3 class="card-title">
        <slot name="title"></slot>
      </h3>
      
      <div class="card-options">
        <slot name="options"></slot>
      </div>

    </div>

    <div class="card-body">
      <slot name="body"></slot>
    </div>

  </div>
</template>

<script>
export default {
  
}
</script>