<template>
  <div class="countdown">
    <div class="block">
      <p class="digit m-0">{{ days | two_digits }}</p>
      <!-- <p class="text">D</p> -->
    </div>
    :
    <div class="block">
      <p class="digit m-0">{{ hours | two_digits }}</p>
      <!-- <p class="text">H</p> -->
    </div>
    :
    <div class="block">
      <p class="digit m-0">{{ minutes | two_digits }}</p>
      <!-- <p class="text">M</p> -->
    </div>
    :
    <div class="block">
      <p class="digit m-0">{{ seconds | two_digits }}</p>
      <!-- <p class="text">S</p> -->
    </div>
  </div>
</template>

<script>
  export default {
    mounted() {
      window.setInterval(() => {
          this.now = Math.trunc((new Date()).getTime() / 1000);
      },1000);
    },
    props: {
      date: {
        type: String
      }
    },
    data() {
      return {
        now: Math.trunc((new Date()).getTime() / 1000)
      }
    },
    computed: {
      dateInMilliseconds() {
        return Math.trunc(Date.parse(this.date) / 1000)
      },
      seconds() {
        return (this.dateInMilliseconds - this.now) % 60;
      },
      minutes() {
        return Math.trunc((this.dateInMilliseconds - this.now) / 60) % 60;
      },
      hours() {
        return Math.trunc((this.dateInMilliseconds - this.now) / 60 / 60) % 24;
      },
      days() {
        return Math.trunc((this.dateInMilliseconds - this.now) / 60 / 60 / 24);
      }
    }
  };

  Vue.filter('two_digits', (value) => {
    if (value < 0) {
      return '00';
    }
    if (value.toString().length <= 1) {
      return `0${value}`;
    }
    return value;
  });
</script>

<style scoped>
/*@import url(https://fonts.googleapis.com/css?family=Roboto+Condensed:400|Roboto:100);*/
.countdown {
  display: flex;
}

.block {
    display: flex;
    flex-direction: column;
    margin: 0px 5px;
}

/*.text {
    color: #1abc9c;
    font-size: 12px;
    font-family: 'Roboto Condensed', serif;
    text-align: center;
}*/

.digit {
    color: #e3342f;
    text-align: center;
}
</style>