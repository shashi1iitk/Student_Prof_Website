<?php

	require_once("session.php");
	
	require_once("class.user.php");
	$auth_user = new USER();
	
	
	$user_id = $_SESSION['user_session'];
	
	$stmt = $auth_user->runQuery("SELECT * FROM users WHERE user_id=:user_id");
	$stmt->execute(array(":user_id"=>$user_id));
	
	$userRow=$stmt->fetch(PDO::FETCH_ASSOC);

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
            <li class="active"><a href="home.php">Your Task</a></li>
            <li><a href="all-task.php">All Task</a></li>
            <?php
            if($userRow['user_prof']=="1"){
            ?>
            <li><a href="assign-task.php">Assign Task</a></li>
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


    <div class="clearfix"></div>
    	
    
<div class="container-fluid" style="margin-top:80px;">
	
    <div class="container">
    
    	<label class="h1">Welcome : <?php print($userRow['user_name']); ?></label>
        <hr />
      <h3>Tasks Table:</h3>
      <table border="1" style="width:100%;padding:10px;">
        <tr>
        <?php
        if($userRow['user_prof']==0){
          ?>
          <th>Professor Name</th>
          <?php
        }
        else{
        ?>
        <th>Students Name</th>
        <?php
      }
      ?>
            <th>Task</th>
            <th>Action</th>
        </tr>

       
        <?php
        if($userRow['user_prof']==1){
        $status=0;
        $stm = $auth_user->runQuery("SELECT student_id,taskname,task_id FROM task WHERE prof_id=:user_id AND status=:status");
        $stm->execute(array(":user_id"=>$user_id,":status"=>$status));
        echo "<tr>";
        while($student=$stm->fetch(PDO::FETCH_ASSOC)){
       echo "<td>";
       $arr=explode(",",$student['student_id']);
       echo "<ol>";
       for($x=1;$x<count($arr);$x++){
               
                    $stu_name = $auth_user->runQuery("SELECT user_name FROM users WHERE user_id=:stu_id");
                    $stu_name->execute(array(":stu_id"=>$arr[$x]));
                    $student_name=$stu_name->fetch(PDO::FETCH_ASSOC);
                  echo "<li>"; echo $student_name['user_name'];echo "</li>";
                  }
                echo "</ol>";
                echo "</td>";
            echo "<td>";
            echo $student['taskname'];
            echo "</td>";
            echo "<td>";
            ?>
             <button class="btn btn-primary" name="<?php echo $student['task_id']; ?>">Complete</button>
            <?php
            if($userRow['user_prof']=="1"){
            ?>
             <button class="btn btn-primary" name="<?php echo $student['task_id']; ?>">Delete</button>
              <button class="btn btn-primary" name="<?php echo $student['task_id']; ?>">Edit</button>
            <?php
            }
            ?>
              </td>
        </tr>
            <?php
  }
}
        if($userRow['user_prof']==0){
        $status=0;
        $stm = $auth_user->runQuery("SELECT student_id,prof_id,taskname,task_id FROM task WHERE status=:status");
        $stm->execute(array(":status"=>$status));
        echo "<tr>";
        while($professor=$stm->fetch(PDO::FETCH_ASSOC)){
      
       $arr=explode(",",$professor['student_id']);
      
       for($x=1;$x<count($arr);$x++){
               
                    if($arr[$x]==$user_id){
                    echo "<td>";
                    $stu_name = $auth_user->runQuery("SELECT user_name FROM users WHERE user_id=:pro_id");
                    $stu_name->execute(array(":pro_id"=>$professor['prof_id']));
                    $professor_name=$stu_name->fetch(PDO::FETCH_ASSOC);
                    echo $professor_name['user_name'];
                 
                echo "</td>";
            echo "<td>";
            echo $professor['taskname'];
            echo "</td>";
            echo "<td>";
            ?>
             <button class="btn btn-primary" name="<?php echo $professor['task_id']; ?>">Complete</button>
              </td>
        </tr>
            <?php
             }
           }
          }
        }
      ?>
      </table>
    </div>
</div>

<script src="bootstrap/js/bootstrap.min.js"></script>

</body>
</html>