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
	<link href="/css/style.css" rel="stylesheet">
	<style type="text/css">
		body {
			padding-top: 120px; /* 90px to make the container go all the way to the bottom of the topbar */
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
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=6566506367";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner" style="background: #ffffff; padding-top: 10px; padding-bottom: 10px; border: 0">
			<div class="container">
			    <h1>Layer Zero Services</h1>
                        </div>
		</div>
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="//<?php echo $siteurl; ?>">Home</a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li <?php if ($active === 'home'):?> class="active" <?php endif; ?>><a href="/">Home</a></li>
						<li <?php if ($active === 'structured-cabling-installers'):?> class="active" <?php endif; ?>><a href="/structured-cabling-installers">Structured Cabling Installers</a></li>
						<li <?php if ($active === 'mande-consultants'):?> class="active" <?php endif; ?>><a href="/mande-consultants">M&E Consultants</a></li>
                                                <li <?php if ($active === 'it-consultants'):?> class="active" <?php endif; ?>><a href="/it-consultants">IT Consultants</a></li>
                                                <li <?php if ($active === 'manufacturers'):?> class="active" <?php endif; ?>><a href="/manufacturers">Manufacturers</a></li>
                                                <li <?php if ($active === 'end-users'):?> class="active" <?php endif; ?>><a href="/end-users">End Users</a></li>
                                                <li <?php if ($active === 'distributors'):?> class="active" <?php endif; ?>><a href="/distributors">Distributors</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
    </div>
    <div class="container">
    	<div>
	    	<?php
	    	$userId = $user->getId();
	    	if (false === empty($userId)) {
	    		print 'Welcome, ' . $user->getUsername() . ' <a href="/login/out">[logout]</a>';
	    	} ?>
	    </div>
     	<?php echo $body; ?>
    </div> <!-- /container -->
</body>
</html>
