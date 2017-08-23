<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<META NAME="Robots" CONTENT="NOINDEX">
    <title>Авторизация в CRM</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="assets/signin.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/bootstrap.css">
  </head>

  <body>

    <div class="container">
      <form class="form-signin" role="form" action="./" method=post autocomplete="off">
        <h2 class="form-signin-heading">Авторизация в CRM</h2>
	<?$bad='files/bad.txt';
	if(is_file($bad)){
	if(filemtime($bad)<(time()-60)){unlink($bad);}else{
	echo '<div class="alert alert-danger"><b>В авторизации отказано!</b></div>';
	}}?>
        <input type="text" name=login class="form-control" placeholder="Логин" required autofocus>
        <input type="password" name=pass class="form-control" placeholder="Пароль" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Вход</button>
      </form>

    </div> <!-- /container -->
	
  </body>
</html>