<?php 
   class Upload_data_model extends CI_Model {
	
      function __construct() { 
         parent::__construct(); 
      } 

   
      public function insert($data) { 
        
         $table_name = $_SESSION['table_name'];
         $err=true;
         foreach ($data as $key => $value) {
            if(!$this->db->insert($table_name, $value)){
               $err=$this->db->error();
               //return $err;
            }
         }
        return $err;
        
      } 
      
      public function view()
      {
         $table_name = $_SESSION['table_name'];
         //$this->db->select(*);            
        $query = $this->db->get($table_name);
        $result = $query->result_array();

        $count = count($result); 

        if(empty($count)){
            return false;
        }
        else{
            return $result;
        }

      }

      public function delete($data,$p_key) { 
         $table_name = $_SESSION['table_name'];
        foreach ($data as $key => $value) 
          {
            //print_r($value);echo $p_key;echo $value[$p_key];
            $this->db->where($p_key, $value[$p_key]);
            $err=$this->db->delete($table_name);
          if(!$err)
            {
              $err=$this->db->error();
                 return $err;
            }
        }return true;
      } 
   
      public function update($data,$p_key) { 
        $table_name = $_SESSION['table_name'];
        foreach ($data as $key => $value) 
          {
            //print_r($value);echo $p_key;echo $value[$p_key];
          $this->db->where($p_key, $value[$p_key]);
          $err=$this->db->update($table_name, $value); 
          
          if(!$err)
            {
              $err=$this->db->error();
                 return $err;
            }
        }return true;
    }
   } 
?> 