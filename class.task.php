<?php

require_once('dbconfig.php');

class TASK
{	
	
	private $conn;
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
		
	public function assign($t_prof_id,$t_student_id,$t_taskname,$t_task_id)
	{
		try
		{
			
			$t_status = 0;
			
			$stmt = $this->conn->prepare("INSERT INTO task(prof_id,student_id,taskname,task_id,status) VALUES(:t_prof_id, :t_student_id, :t_taskname, :t_task_id, :t_status)");
												  
			$stmt->bindparam(":t_prof_id", $t_prof_id);
			$stmt->bindparam(":t_student_id", $t_student_id);
			$stmt->bindparam(":t_taskname", $t_taskname);
			$stmt->bindparam(":t_task_id", $t_task_id);		  
			$stmt->bindparam(":t_status", $t_status);
			
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	
	public function edit($t_task_id, $new_taskname)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT task_id, taskname FROM task WHERE task_id =:t_task_id");
			$stmt->execute(array(':t_task_id'=>$t_task_id));
			$taskRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() != 0)
			{
					$taskrow['taskname'] = $new_taskname;
					
					return true;
			}
			else
				{
					
					return false;

				}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
	public function delete($t_task_id)
	{
		try
		{
			$stmt = $this->conn->prepare("DELETE FROM task WHERE task_id =:t_task_id");
			$stmt->execute(array(':t_task_id'=>$t_task_id));
			
			return true;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	
}
?>