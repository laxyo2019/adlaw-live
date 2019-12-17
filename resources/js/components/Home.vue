<template>
	<div class="container">   
    <div class="row" style="max-height: 50vh!important;overflow: auto!important;">
    	<div class="waveWrapper waveAnimation" style="z-index:-20">
			  <div class="waveWrapperInner bgTop">
			    <div class="wave waveTop" style="background-image: url('images/wave-top.png')"></div>
			  </div>
			  <div class="waveWrapperInner bgMiddle">
			    <div class="wave waveMiddle" style="background-image: url('images/wave-mid.png')"></div>
			  </div>
			  <div class="waveWrapperInner bgBottom">
			    <div class="wave waveBottom" style="background-image: url('images/wave-bot.png')"></div>
			  </div>
			</div>
      <div class="col-8 offset-2">
        <div class="row">
          <div class="col col-sm-6 col-md-4 col-lg-2 col-xl-2" v-for="mod in modules" @click="openLink(mod.link)">
            <div class="text-center">
              <i class="icon" :class="mod.icon"></i>
            </div>
            <p class="text-center text-white" v-text="mod.name" style="margin-top:10px;font-weight:700"></p>
          </div>
        </div>
      </div>
    </div>
	</div>
</template>
<script>
	export default {
		props: ['logged_user', 'modules'],
		components: {},
		data () {
      return {
        
      }
		},
		methods: {
      openLink(link){
        window.location.href = link;
      }
		}
	}
</script>

<style scoped>
  .icon{
    font-size:40px;
    padding: 20px;
    color: #fff !important;
    -webkit-transition: all 0.5s; /* For Safari 3.1 to 6.0 */
    transition: all 0.5s;
    border: solid 2px ;
  }

  .icon:hover{
    margin-bottom: 5px;
    padding: 23px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
  }

    @keyframes move_wave {
      0% {
          transform: translateX(0) translateZ(0) scaleY(1)
      }
      50% {
          transform: translateX(-25%) translateZ(0) scaleY(0.55)
      }
      100% {
          transform: translateX(-50%) translateZ(0) scaleY(1)
      }
  }

  .waveWrapper {
      overflow: hidden;
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      top: 0;
      margin: auto;
  }
  .waveWrapperInner {
      position: absolute;
      width: 100%;
      overflow: hidden;
      height: 100%;
      bottom: -1px;
      background-image: linear-gradient(to top, #86377b 20%, #27273c 80%);
  }
  .bgTop {
      z-index: 15;
      opacity: 0.5;
  }
  .bgMiddle {
      z-index: 10;
      opacity: 0.75;
  }
  .bgBottom {
      z-index: 5;
  }
  .wave {
      position: absolute;
      left: 0;
      width: 200%;
      height: 100%;
      background-repeat: repeat no-repeat;
      background-position: 0 bottom;
      transform-origin: center bottom;
  }
  .waveTop {
      background-size: 50% 100px;
  }
  .waveAnimation .waveTop {
    animation: move-wave 3s;
     -webkit-animation: move-wave 3s;
     -webkit-animation-delay: 1s;
     animation-delay: 1s;
  }
  .waveMiddle {
      background-size: 50% 120px;
  }
  .waveAnimation .waveMiddle {
      animation: move_wave 10s linear infinite;
  }
  .waveBottom {
      background-size: 50% 100px;
  }
  .waveAnimation .waveBottom {
      animation: move_wave 15s linear infinite;
  }
</style>

