<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indexcomp extends CI_Controller {

	function __construct() {
        parent::__construct();
    }

	public function index()
	{	
		$data['tables'] = $this->db->list_tables();
		$this->load->view('header');
		$this->load->view('main',$data);
		$this->load->view('footer');
	}

	public function do_upload(){

		$config = array( // paramaters for file upload
		'upload_path' => "/home/anuj/Documents/upload",
		'allowed_types' => "*",
		'overwrite' => TRUE,
		);

		$this->load->library('upload', $config); 

		if($this->upload->do_upload()) //uploading of file 
		{				
			$data = array('upload_data' => $this->upload->data()); // return values after uploading of file

			$full_path=$data['upload_data']['full_path'];	
			$table_name = $this->input->post('table');
			$_SESSION['table_name']=$table_name;		 
			$this->view_excel($full_path,$table_name);
			//$this->load->view('upload_success',$data);
		}
		else
		{
			$error = array('error' => $this->upload->display_errors());
			$this->load->view('error', $error);
		}
	}


	public function view_excel($file_path,$table_name){ 

				 $this->load->library('PHPExcel');
				 $_SESSION['file_path']=$file_path;
				 $_SESSION['table_name']=$table_name; // file path  stored in session for further use
				 try {
				/// it will be your file name that you are posting with a form or can pass static name $_FILES["file"]["name"];
					$objPHPExcel = PHPExcel_IOFactory::load($file_path);

				     }
				 catch(Exception $e)
				    {
							 $this->resp->success = FALSE;
							 $this->resp->msg = 'Error Uploading file';
							 echo json_encode($this->resp);
							 exit;
				    }

				$allDataInSheet['excel_fields'] = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				$allDataInSheet['database_fields'] = $this->db->list_fields($table_name);
				$allDataInSheet['primary_key'] = $this->db->field_data($table_name);
				$this->load->view('header');
				$this->load->view('pairing',$allDataInSheet);
				$this->load->view('footer');

	}

	public function preview(){

		$this->load->library('PHPExcel');
		/*instializing variables using post */
		$database = $this->input->post('database');
		$excel = $this->input->post('excel');
		$action=$this->input->post('action');
		$num_rows=$this->input->post('num_rows');
		$p_key=$this->input->post('p_key');
		$delete_data=$this->input->post('delete_data');
		$update=$this->input->post('update');
		$column=array();
		$file_path = $_SESSION['file_path']; // path of file from session

		$objPHPExcel = PHPExcel_IOFactory::load($file_path);//loading excel
		$table_header = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		$rows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();//loading rows

		if ($num_rows<$rows and $num_rows!=null)
		{$rows=$num_rows;}

		foreach ($excel as $key_ex => $value_ex) { 
				foreach ($table_header[1] as $key => $value) {
					if($value_ex==$value and $value!=null)
						{
							$letter=$key;
							$index=$letter.'1:'.$letter.$rows;
							$data = $objPHPExcel->getActiveSheet()->rangeToArray($index); //accessing data in column form
							$column[]=$data;
						}
				}
			
		}
															//echo(json_encode($column));echo('<br><br><br>');
			$insert_data = array_map(null, ...$column); 	//converting column data to row form
															//echo(json_encode($column));

			if ($action=='true' && $delete_data=='false'&& $update=='false')
			{	echo 'insert';
				$resp = $this->insert_data($insert_data,$database);
				
			}
			elseif ($action=='true' && $delete_data=='true') 
			{	echo 'delete';
				$resp = $this->delete_data($insert_data,$database,$p_key);
			}
			elseif ($action=='true' && $update=='true') 
			{	echo 'update';
				$resp = $this->update_data($insert_data,$database,$p_key);
			}

			elseif($action=='false')
			{
					echo("<h3>Total number of rows are :- ".$rows."</h3>");
							
					echo '<table class="table">';
					foreach ($database as $key => $value) { //table header from db
						echo '<th>'.$value.'</th>';
					}

					foreach ($insert_data as $key => $value) {   //ajax output and table creation
			 					echo'<tr>';
						foreach ($value as $key1 => $value1) {
							foreach ($value1 as $key2 => $value2) {
								echo '<td>'.$value2.'</td>';
								}
									
							}
								echo'</tr>';	
					} 
					echo '</table><br>';
			}
	}

	public function insert_data($insert_data,$header){
			
			$this->load->model('Upload_data_model');
			$data=array();
			$k=0;
			/*foramting data to be inserted by model*/
			foreach ($insert_data as $key_out => $value_out) {
				if($k==0)
				{	$k++;
					continue;// to avoid headers
					
				}
				for($i=0;$i<sizeof($header);$i++)
				{
					$temp[$header[$i]]=$value_out[$i][0];
				}
				$data[]=$temp;
			}
			//echo json_encode($data);
			$response = $this->Upload_data_model->insert($data);

			if ($response == 'true')
			{			
				echo 'data added successfully'; 
				return true;
			}
			else{
				echo "unable to add data\n";
				foreach ($response as $key => $value) {
					echo $value."\n";
				}
				return false;
			}

		}

		public function delete_data($insert_data,$header,$p_key){
			
			$this->load->model('Upload_data_model');
			$data=array();
			$k=0;
			/*foramting data to be inserted by model*/
			foreach ($insert_data as $key_out => $value_out) {
				if($k==0)
				{	$k++;
					continue;// to avoid headers
					
				}
				for($i=0;$i<sizeof($header);$i++)
				{
					$temp[$header[$i]]=$value_out[$i][0];
				}
				$data[]=$temp;
			}
			
			$response = $this->Upload_data_model->delete($data,$p_key);

			if ($response == 'true')
			{			
				echo 'data deleted successfully'; 
				return true;
			}
			else{
				echo "unable to delete data\n";
				foreach ($response as $key => $value) {
					echo $value."\n";
				}
				return false;
			}

		}

		public function update_data($insert_data,$header,$p_key){
			
			$this->load->model('Upload_data_model');
			$data=array();
			$k=0;
			/*foramting data to be inserted by model*/
			foreach ($insert_data as $key_out => $value_out) {
				if($k==0)
				{	$k++;
					continue;// to avoid headers
					
				}
				for($i=0;$i<sizeof($header);$i++)
				{
					$temp[$header[$i]]=$value_out[$i][0];
				}
				$data[]=$temp;
			}
			
			$response = $this->Upload_data_model->update($data,$p_key);

			if ($response == 'true')
			{			
				echo 'data added successfully'; 
				return true;
			}
			else{
				echo "unable to add data\n";
				foreach ($response as $key => $value) {
					echo $value."\n";
				}
				return false;
			}

		}



	public function database_view(){
		$this->load->model('Upload_data_model');
		$result['view_db'] = $this->Upload_data_model->view();
		$this->load->view('header');
		$this->load->view('view_db',$result);
		$this->load->view('footer');
	}


	/*
	public function excel_index($num){ //function to convert number to excel indices
 
			    $numeric = $num % 26;
			    $letter = chr(65 + $numeric);
			    $num2 = intval($num / 26);
			    if ($num2 > 0) {
			        return getNameFromNumber($num2 - 1) . $letter;
			    } else {
			        return $letter;
			    }
			
	}		*/
}

