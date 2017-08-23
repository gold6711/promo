<?include "total.php"?><!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="images/favicon.ico">

    <title><?=title?></title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	<link rel="stylesheet" href="assets/bootstrap.css">
    <link href="assets/dashboard.css" rel="stylesheet">

    <script src="assets/jquery-3.1.1.min.js"></script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./"><?=title?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <?=menu()?>
          </ul>
		  <div class='row'>
		  <form class="navbar-form navbar-right col-md-8" action=profile>
            <input type=number min=1 max=9999 name=edit value='<?=$_GET['edit']?>' class="form-control" placeholder="ID карты">
          </form>
        <form class="navbar-form navbar-right col-md-4" action=users>
            <input type=search name=search value='<?=$_GET['search']?>' class="form-control" placeholder="Поиск по сотрудникам&hellip;">
          </form>
		  </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <?=menu(0,1)?>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header"><?=title?></h1>


          <h2 class="sub-header"><?=table('menu','link',$_GET['act'],'name')?></h2>
<?
if(!$_GET['act']){$_GET['act']='users';}
$page='pages/'.$_GET['act'].".php";
if(is_file($page)){include $page;}?>
          
        </div>
      </div>
    </div>

    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/docs.min.js"></script>
  </body>
</html>