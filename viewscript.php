<script type="text/javascript">
	
	var intervalID = setInterval(
		function(){
			var url = window.location.href.concat("&autoupdate=1");
			$.get(url, function(data, status){
				if(data.length != 0) {
					document.getElementsByTagName("body")[0].innerHTML = data;
				}
			});
		}, 1000);
	</script>