<?php
/**
 *
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts.Email.html
 * @since         CakePHP(tm) v 0.10.0.1076
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
	<title><?php echo $title_for_layout; ?></title>
</head>
<body>
	<?php echo $this->fetch('content'); ?>
        <br /><br />  
<!--	<p><img src="<?php // echo  Router::url('/img/logo-lv.png',true); ?>" title="Litoral Verde" alt="Litoral Verde"/></p>-->
</body>
</html>