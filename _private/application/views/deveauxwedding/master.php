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
        <link rel="stylesheet" type="text/css" href="/css/jquery.flipcountdown.css" />
	<link href="/css/style.css" rel="stylesheet">
	<style type="text/css">
        body {
            background: url('http://weddingwoo.s3.amazonaws.com/uploads/background/51b270b2174f01360d00001b/a6/c727cb2ab52d4a58612c52f2d22cad/Haruna_Engagement_086.jpg') no-repeat center center fixed;
            background: url('images/hodsock.jpg') no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }

        @font-face
        {
            font-family: greatVibes;
            src: url('fonts/GreatVibes-Regular.ttf');
        }

        .navbar {
            font-family: greatVibes;
            font-size: 1.4em;
        }

        .navbar-inverse .brand, .navbar-inverse .nav>li>a {
            color: #000000;
        }

        .navbar-inner, .navbar-inverse .navbar-inner {
            background: transparent;
            border: 0;
            -webkit-box-shadow: none;
            box-shadow: none;
        }

        .navbar .nav>.active>a, .navbar .nav>.active>a:hover, .navbar .nav>.active>a:focus {
            background: transparent;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        
        .main-container {
            background-color: rgba(255,255,255,0.7);
            padding: 10px;
            border-radius: 5px;
        }
        
         .navbar-fixed-top {
            position: relative;
        }
         
        .nav-collapse, .nav-collapse.collapse {
            background-color: rgba(255,255,255,0.7);
        }
        
        @media screen and (max-width: 585px) {
            .xdsoft_flipcountdown.xdsoft_size_lg >.xdsoft_digit.xdsoft_space {
                clear: both;
                width: 0;
            }
            
            #untilweddingvert {
                display: table;
            }
            #untilweddinghoriz {
                display: none;
            }
        }
        @media screen and (min-width: 585px) {
           #untilweddingvert {
                display: none;
            }
            #untilweddinghoriz {
                display: table;
            }
        }
    </style>
	<link href="/css/bootstrap-responsive.min.css" rel="stylesheet">
        <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-42398015-3', 'deveaux.co.uk');
            ga('send', 'pageview');

        </script> 
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
           "/js/fastclick.js",
           "/js/jquery.flipcountdown.js"
        ],function(){
             jQuery(function($){
             	$('#retroclockbox1').flipcountdown();
             });

            jQuery(function(){
              var NY = Math.round((new Date('10/11/2014 13:30:00')).getTime()/1000);
              jQuery('#weddingdate,#weddingdate2').flipcountdown({  
                 size:'lg',
                 tick:function(){
                     var nol = function(h){
                         return h>9?h:'0'+h;
                     }
                     var  range    =   NY-Math.round((new Date()).getTime()/1000),
                     secday  =   86400, sechour = 3600,
                     days  =   parseInt(range/secday),
                     hours  =   parseInt((range%secday)/sechour),
                     min  =   parseInt(((range%secday)%sechour)/60),
                     sec  =   ((range%secday)%sechour)%60;
                     
                     return nol(days)+' '+nol(hours)+' '+nol(min)+' '+nol(sec);
                 }
              });
            });

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
				<a class="brand" href="//<?php echo $siteurl; ?>"><?php echo $sitetitle; ?></a>
				<div class="nav-collapse collapse">
					<ul class="nav">
						<li <?php if ($active === 'home'):?> class="active" <?php endif; ?>><a href="/">Home</a></li>
                                                <li <?php if ($active === 'gifts'):?> class="active" <?php endif; ?>><a href="/gifts">Gifts</a></li>
                                                <li <?php if ($active === 'rsvp'):?> class="active" <?php endif; ?>><a href="/rsvp">RSVP</a></li>
                                                <li <?php if ($active === 'wedding-information'):?> class="active" <?php endif; ?>><a href="/wedding-information">Wedding Information</a></li>
						<li <?php if ($active === 'directions'):?> class="active" <?php endif; ?>><a href="/directions">Directions</a></li>
                                                <li <?php if ($active === 'accomodation'):?> class="active" <?php endif; ?>><a href="/accomodation">Accomodation</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
    </div>
    <div class="main-container container">
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
