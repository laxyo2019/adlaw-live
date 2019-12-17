<template>
  <div style="height: 100vh;">
  	<button type="button" v-scroll-to="{ element: '#connect-container', duration: 1000 }" style="display: none;" ref="scrollBtn"></button>
		<span id="connect-container">
			<!-- <iframe id="connect-window" src="https://connect.laxyo.org" width="100%" height="100%"></iframe> -->
		</span>
	</div>
</template>

<script>
	const VueScrollTo = require('vue-scrollto');
	Vue.use(VueScrollTo)
	export default {
		created() {
			// Check for browser support of event handling capability
			if (window.addEventListener)
			window.addEventListener("load", this.createIframe, false);
			else if (window.attachEvent)
			window.attachEvent("onload", this.createIframe);
			else window.onload = this.createIframe;
		},
		mounted() {
			this.scrollEvent();
		},
		methods: {
			scrollEvent($event) {
        const elem = this.$refs.scrollBtn;
        elem.click();
      },
      createIframe() {
      	//doesn't block the load event
    	  var i = document.createElement("iframe");
    	  i.src = "https://connect.laxyo.org";
    	  i.id = "connect-window";
    	  i.scrolling = "auto";
    	  i.frameborder = "0";
    	  i.width = "100%";
    	  i.height = "100%";
    	  document.getElementById("connect-container").appendChild(i);
      },
      asyncLoad() {
      	// (function(d){
      	//   var iframe = d.body.appendChild(d.createElement('iframe')),
      	//   doc = iframe.contentWindow.document;

      	//   // style the iframe with some CSS
      	//   iframe.style.cssText = "position:absolute;width:200px;height:100px;left:0px;";
      	  
      	//   doc.open().write('<body onload="' + 
      	//   'var d = document;d.getElementsByTagName(\'head\')[0].' + 
      	//   'appendChild(d.createElement(\'script\')).src' + 
      	//   '=\'\/path\/to\/file\'">');
      	  
      	//   doc.close(); //iframe onload event happens

      	//   })(document);
      }
		}
	}
</script>