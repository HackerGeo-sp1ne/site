<head>
	<!-- Required meta tags -->
	<meta charset="UTF-8" />
	<title><?php echo(SITE_NAME." | ".$title); ?></title>
	<meta name="description" content="<?php echo(SITE_DESC); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:site_name" content="<?php echo(SITE_NAME); ?>" />
	<meta property="og:title" content="<?php echo($title); ?>" />
	<meta property="og:url" content="<?php echo(SITE_URL); ?>" />
	<meta property="og:description" content="<?php echo(SITE_DESC); ?>" />
	<meta property="og:image" content="" />
	
	<meta property="twitter:description" content="<?php echo(SITE_DESC); ?>" />
	<meta property="twitter:title" content="<?php echo($title); ?>" />

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="robots" content="all,follow" />
	<link rel="stylesheet" href="<?php echo (isset($use_directory) ? "../" : "");?>template\vendor\bootstrap\css\bootstrap.min.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,700" />
	<link rel="stylesheet" href="<?php echo (isset($use_directory) ? "../" : "");?>template\css\style.kenny.dark.css" id="theme-stylesheet" />
	<link rel="stylesheet" href="<?php echo (isset($use_directory) ? "../" : "");?>template\css\custom.css" />
	<link rel="stylesheet" href="<?php echo (isset($use_directory) ? "../" : "");?>template\css\snow.css" />
	<link rel="shortcut icon" href="<?php echo (isset($use_directory) ? "../" : "");?>template\img\favicon.html" />
	<link rel="shortcut icon" type="image/png" href="<?php echo (isset($use_directory) ? "../" : "");?>template\img\logo.png"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/2.2.1/js.cookie.min.js"></script>

    <link type="css" rel="stylesheet" href="src/node_modules/bootstrap/dist/css/bootstrap.css" crossorigin="anonymous">

    <!-- SELLIX -->
	<link rel="stylesheet" href="https://cdn.sellix.io/static/css/embed.css"/>
	<script src="https://cdn.sellix.io/static/js/embed.js" ></script>

	<!-- RECLAME -->
<!--	<script src="https://www.google.com/recaptcha/api.js" async defer></script>-->
<!--	<script data-ad-client="ca-pub-4106199750890370" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>-->
</head>

<?php if (SNOW_FALL) {?>
<div class="snow-container"></div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const snowContainer = document.querySelector(".snow-container");

            const particlesPerThousandPixels = 0.1;
            const fallSpeed = 1.25;
            const pauseWhenNotActive = true;
            const maxSnowflakes = 300;
            const snowflakes = [];

            let snowflakeInterval;
            let isTabActive = true;

            function resetSnowflake(snowflake) {
                const size = Math.random() * 5 + 1;
                const viewportWidth = window.innerWidth - size; // Adjust for snowflake size
                const viewportHeight = window.innerHeight;

                snowflake.style.width = `${size}px`;
                snowflake.style.height = `${size}px`;
                snowflake.style.left = `${Math.random() * viewportWidth}px`; // Constrain within viewport width
                snowflake.style.top = `-${size}px`;

                const animationDuration = (Math.random() * 3 + 2) / fallSpeed;
                snowflake.style.animationDuration = `${animationDuration}s`;
                snowflake.style.animationTimingFunction = "linear";
                snowflake.style.animationName =
                    Math.random() < 0.5 ? "fall" : "diagonal-fall";

                setTimeout(() => {
                    if (parseInt(snowflake.style.top, 10) < viewportHeight) {
                        resetSnowflake(snowflake);
                    } else {
                        snowflake.remove(); // Remove when it goes off the bottom edge
                    }
                }, animationDuration * 1000);
            }

            function createSnowflake() {
                if (snowflakes.length < maxSnowflakes) {
                    const snowflake = document.createElement("div");
                    snowflake.classList.add("snowflake");
                    snowflakes.push(snowflake);
                    snowContainer.appendChild(snowflake);
                    resetSnowflake(snowflake);
                }
            }

            function generateSnowflakes() {
                const numberOfParticles =
                    Math.ceil((window.innerWidth * window.innerHeight) / 1000) *
                    particlesPerThousandPixels;
                const interval = 5000 / numberOfParticles;

                clearInterval(snowflakeInterval);
                snowflakeInterval = setInterval(() => {
                    if (isTabActive && snowflakes.length < maxSnowflakes) {
                        requestAnimationFrame(createSnowflake);
                    }
                }, interval);
            }

            function handleVisibilityChange() {
                if (!pauseWhenNotActive) return;

                isTabActive = !document.hidden;
                if (isTabActive) {
                    generateSnowflakes();
                } else {
                    clearInterval(snowflakeInterval);
                }
            }

            generateSnowflakes();

            window.addEventListener("resize", () => {
                clearInterval(snowflakeInterval);
                setTimeout(generateSnowflakes, 1000);
            });

            document.addEventListener("visibilitychange", handleVisibilityChange);
        });
    </script>
<?php }?>
