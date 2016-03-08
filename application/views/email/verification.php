<html>
<style type="text/css">

@font-face {
    font-family: menu-font;
    src: url('<?= $base_url ?>assets/dist/css/fonts/EncodeSansCompressed-SemiBold.ttf');
}

</style>

<body style='font-family: Helvetica,sans-serif'>
	<div style=''>
		<div style="padding:1em 0em;text-align:center;background: #231B16;">
			<img style='height:100px; display:inline-block;vertical-align: middle;' src='<?= $base_url ?>/assets/ce-logo.png' />
			<h2 style='margin:0em 2em;display:inline-block;vertical-align: middle;color:white'>Thanks for using Wefriends.</h2>
		</div>
		<div style='background:#E4D4CA;padding:2em;'>
			Welcome!<br>
			Please click on the link below to confirm your email address.<br>
			<br>
			<a href='<?php echo "$base_url/Users/verify?token=$token"; ?>' ><?php echo "$base_url/Users/verify?token=$token"; ?></a>
			<br>
			<br>
			Please copy the link to your browser if you are unable to click in email.<br>
			This link will expire in less than 5 days.<br>
			If you did not sign up for the app. Please ignore this mail.<br>
			<br>
			Best regards.<br>
			Wefriends team.<br>
		</div>
	</div>
	<footer style='background: #231B16;color:white; height:3em'>
		<div style='width:100%;text-align:right'> <!-- Start footer menu. -->
   			<div style='font-size:0.8em;padding: 3em 4em 2em 0em;'>wefriends</div>
 		</div>
 	</footer>
</body>

</html>