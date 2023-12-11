<!DOCTYPE html>
<html>
<head>
    <title>StayFrosty - Page not found 404</title>
    <style>
        /* Styles of the 404 page of my website. */
        /*#272931*/
        body {
            background: #272931;
            color: #d3d7de;
            font-family: "Courier new";
            font-size: 18px;
            line-height: 1.5em;
            cursor: default;
        }

        a {
            color: #fff;
        }

        .code-area {
            position: absolute;
               width: 320px;
            min-width: 320px;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
        }

        .code-area > span {
            display: block;
        }

        @media screen and (max-width: 320px) {
            .code-area {
                font-size: 5vw;
                min-width: auto;
                width: 95%;
                margin: auto;
                padding: 5px;
                padding-left: 10px;
                line-height: 6.5vw;
            }
        }

    </style>
</head>
<body>

<div class="code-area">
	<span style="color: #777;font-style:italic;">
        <?php echo date( "H:i d-m-Y");?><br>
        // 404 page not found.
	</span>
    <span style="color:#4ca8ef;">
        $page
        <span style="color:#bdbdbd;">
			=
		</span><span style="color:#5549ef;">
			"<?php echo $_SERVER['REQUEST_URI'];?>";
		</span>
    </span>
    <span>
		<span style="color:#d65562;">
			if
		</span>
		(<span style="color:#4ca8ef;">$page</span> == <span style="font-style: italic;color:#bdbdbd;">null</span>)
		{
	</span>
    <span>
		<span style="padding-left: 15px;color:#2796ec">
			<i style="width: 10px;display:inline-block"></i>return
		</span>
		<span>
			<span style="color: #a6a61f">"I CANT FIND THE PAGE, please go back"</span>;
		</span>
		<span style="display:block">}</span>
		<span style="color: #777;font-style:italic;">
            <?php include "config.php";?>
			// <a href="<?php echo SITE_URL;?>">Go home!</a>
		</span>
	</span>
</div>
</body>
</html>
