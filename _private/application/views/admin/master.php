<!DOCTYPE html>
<head>
	<meta charset="utf-8">

	<!-- Set the viewport width to device width for mobile -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
    <meta name="keywords" content="">

	<meta name="robots" content="index,follow" />
	<meta name="copyright" content="DVO Media" />

	<title><?php echo $title; ?></title>

	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="/css/bootstrap.min.css" rel="stylesheet">
	<style type="text/css">
		body {
			padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
		}
    </style>
	<link href="/css/bootstrap-responsive.min.css" rel="stylesheet">

	<script type="text/javascript" src=""></script>
	<script type="text/javascript">
		function loadScripts(array,callback){
		    var loader = function(src,handler){
		        var script = document.createElement("script");
		        script.src = src;
		        script.onload = script.onreadystatechange = function(){
		        script.onreadystatechange = script.onload = null;
		                handler();
		        }
		        var head = document.getElementsByTagName("head")[0];
		        (head || document.body).appendChild( script );
		    };
		    (function(){
		        if(array.length!=0){
		                loader(array.shift(),arguments.callee);
		        }else{
		                callback && callback();
		        }
		    })();
		}

		loadScripts([
           "//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js",
           "/js/bootstrap.min.js",
           "/js/fastclick.js"
        ],function(){
        	window.addEventListener('load', function() {
			    new FastClick(document.body);
			}, false);
            if (typeof pageLoad === 'function') {
                pageLoad();
            }
        });

	</script>

</head>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="<?php echo $siteurl; ?>"><?php echo $sitetitle; ?></a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li <?php if ($active === 'pages'):?> class="active" <?php endif; ?>><a href="/admin/pages">Pages</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
    </div>
    <div class="container">
    	<?php
    	$userId = $user->getId();
    	if (false === empty($userId)) {
    		print 'Welcome, ' . $user->getUsername() . ' <a href="/login/out">[logout]</a>';
    	} ?>
     	<?php echo $body; ?>
    </div> <!-- /container -->
</body>
</html>