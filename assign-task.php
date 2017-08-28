<?php

	require_once("session.php");
	require_once("class.user.php");
  require_once("class.task.php");

	$auth_user = new USER();
	$user_id = $_SESSION['user_session'];
	$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

$task=new TASK();
if(isset($_POST['btn-assign']))
{
  $taskname = strip_tags($_POST['taskname']);
  $student = $_POST['student'];

  
  if($taskname=="")  {
    $error[] = "Enter Task !";  
  }
  else if($student=="") {
    $error[] = "Select atleast one student!";  
  }
  else{
    $selected_student=null;
     foreach($student as $selected) {
      $selected_student =  $selected_student.",".$selected;

           }

          try {
                      $task->assign($taskname,$user_id,$selected_student);
               }
          catch(PDOException $e)
               {
                  echo $e->getMessage();
                }
        }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="jquery-1.11.3-jquery.min.js"></script>
<link rel="stylesheet" href="style.css" type="text/css"  />
<title>welcome - <?php print($userRow['user_email']); ?></title>
</head>

<body>


<nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Task Cage</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="home.php">Your Task</a></li>
            <li><a href="all-task.php">All Task</a></li>
            <?php
            if($userRow['user_prof']=="1"){
            ?>
            <li class="active"><a href="assign-task.php">Assign Task</a></li>
            <?php
            }
            ?>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
			  <span class="glyphicon glyphicon-user"></span>&nbsp;Hi' <?php echo $userRow['user_email']; ?>&nbsp;<span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span>&nbsp;View Profile</a></li>
                <li><a href="logout.php?logout=true"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

	<div class="clearfix">
    
  </div>
	
    <div class="container-fluid" style="margin-top:80px;">
	
    <div class="container">
    <form action="assign-task.php" method="POST">
    <h3>Enter Task:</h3>
<?php
      if(isset($error))
      {
        foreach($error as $error)
        {
           ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
        }
      }
      ?>


    <textarea rows="5" cols="75" name="taskname"></textarea>
      
    <h3>Select Students:</h3>
    <?php
   $student="0";
  $stmt = $auth_user->runQuery("SELECT user_name,user_id FROM users WHERE user_prof=:student");
  $stmt->execute(array(":student"=>$student));
  
       
while ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
  ?>
<input type="checkbox" name="student[]" value="<?php  echo $user['user_id']; ?>"><?php  echo $user['user_name']; ?><br>
  <?php
}
  ?> 
<button class="btn btn-primary" name="btn-assign">Submit</button>
</form>
    </div>
</div>
<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>