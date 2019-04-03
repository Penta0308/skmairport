<html>
	<head>
		<script src="/jquery-3.3.1.min.js" ></script>
		<script src="/jquery.form.min.js" ></script>
		<style>
			.wrapper {
				width:120vh;
				height:80vh;
			}
			
			.deptitlebar {
				background:#000;
				color:#fff;
				font-size:3.8vh;
				border-left:hidden;
				border-right:hidden;
			}
			
			.depinv {
				fill:#fff;
				background-color:#000;
			}
			
			.dep {
				height:4vh;
				padding:0.1vh;
			}
			
			.center {
				text-align:center;
			}
			
			#airportimage {
				width:120vh;
			}
		</style>
	</head>
	<body>
		<script>
			var apn = "<?php echo $_GET['a']; ?>";
			var craftlist = new Array();
			
			function aircraft() {
				var name;
				var Number;
				var dest;
			}
			
			function trim(stringToTrim) {
				return stringToTrim.replace(/^\s+|\s+$/g,"");
			}
			
			function compare(a, b) {
				return a.time - b.time;
			}
			
			$(function()
			{
				if(!apn.localeCompare(""))
				{
					console.log("Airport Name Not Set");
					return;
				}
				$.ajax({
					type:"GET",
					url:"http://airport.skmserver.tk/airport/"+apn+"/airport.txt",
					dataType : "text",
					success: function(textdata){
						console.log(textdata);
						var airportinfo = textdata.split('\n');
						$("#airporttitle").append(airportinfo[0]);
						$("#airportimage").attr("src", "/airport/"+apn+"/airport.png");
						$.ajax({
							type:"GET",
							url:"http://airport.skmserver.tk/airport/"+apn+"/",
							dataType:"text",
							success:function(ads){
								jQuery(ads).find("a").each(function(a, b) {
									if(a<5) return;
									var str = jQuery(b).attr("href");
									if(!str.localeCompare("airport.txt") || !str.localeCompare("airport.png")) return;
									console.log(str);
									$.ajax({
										type:"GET",
										url:"http://airport.skmserver.tk/airport/"+apn+"/"+str,
										dataType:"text",
										success: function(apdata){
											var aircraftinfo = apdata.split('\n');
											for(var a=0 ; a < aircraftinfo.length ; a++)
											{
												var nowinfo = aircraftinfo[a].split(',');
												for(var b=0 ; b < 7 ; b++)
												{
													if(nowinfo[1].trim().substring(b, b+1).localeCompare("1")) continue;
													var nowpointer = craftlist.length;
													craftlist[nowpointer] = new aircraft();
													craftlist[nowpointer].name = str.split('.')[0]+trim(nowinfo[0]);
													craftlist[nowpointer].time = parseInt(b) * 1440 + parseInt(nowinfo[2].trim().substring(0, 2)) * 60 + parseInt(nowinfo[2].trim().substring(3, 5));
													craftlist[nowpointer].dest = nowinfo[3].trim();
												}
											}
										},
										error: function(xhr, status, error) {
											console.log(error);
										}  
									});
								});
							},
							error: function(xhr, status, error) {
								console.log(error);
							}
						});
					},
					error: function(xhr, status, error) {
						console.log(error);
						if(!error.localeCompare("Not Found")) alert("404 (Not Found)");
					}
				});
				
				
				console.log(
					craftlist.sort(function(a, b) {
						console.log(a, b);
						return a.time < b.time ? -1 : a.time > b.time ? 1 : 0;
					})
				);
			});
		</script>
		<table class="wrapper">
		<tr class="deptitlebar dep depinv">
		<td colspan="4">
			<img src="/departures.svg" class="dep depinv center"></img>
		</td>
		<td colspan="6">
			<a class="dep depinv center">DEPARTURES</a>
		</td>
		<td>
			<a id="airporttitle"></a>
		</td>
		</tr>
		
		<tr>
		<td id="infoarea">
	
		</td>
		<tr>
	
		<tr>
		<td colspan="20">
			<img width="1200px" id="airportimage" src="#" class="airportlogo"></img>
		</td>
		</tr>
	</body>
</html>