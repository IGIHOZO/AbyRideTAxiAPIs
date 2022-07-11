<?php 
//====================================================================================================== CONNECTION
class DbConnect
{
    private $host='localhost';
    private $dbName = 'pyyquokz_abyride_taxi';
    private $user = 'pyyquokz';
    private $pass = 'Ride!#AbyCredentials#!';
    public $conn;
    
    // private $host='localhost';
    // private $dbName = 'abyride_taxi';
    // private $user = 'root';
    // private $pass = '';
    // public $conn;

    public function connect()
    {
        try {
         $conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbName, $this->user, $this->pass);
         return $conn;
        } catch (PDOException $e) {
            echo "Database Error ".$e->getMessage();
            return null;
        }
    }
}

/**
 * =========================================== MAIN VIEW
 */
class MainView extends DbConnect
{
	
	function listOfCarModels(){
		$con = parent::connect();
		$models = $con->prepare("SELECT * FROM carmodels WHERE carmodels.Status=1");
		$models->execute();
		if ($models->rowCount()>=1) {
				echo "<option selected>Select Car Model-Type</option>";
			while ($ft_carmodels = $models->fetch(PDO::FETCH_ASSOC)) {
				$id = $ft_carmodels['ModelId'];
				$model = $ft_carmodels['Model'];
				echo "<option value='$id'>$model</option>";
			}
		}else{
			echo "<option value=''>No Model</option>";
		}
	}
}
 ?>