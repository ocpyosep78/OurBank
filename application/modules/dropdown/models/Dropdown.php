<?php 
class Dropdown_Model_Dropdown extends Zend_Db_Table 
{
     protected $_name='ourbank_master_gender';
    public function tableContent($tableName) 
    {        $select = $this->select()
                        ->setIntegrityCheck(false)
			->from(array('a' => $tableName));
       $result = $this->fetchAll($select);
       return $result->toArray();
     // die($select->__toString($select));
    }
    
    public function insertContent($tName,$input = array())
    {
       $db = $this->getAdapter();
	$db->insert("$tName",$input);
	return '1';
      
    }
	public function editRecord($tName,$id)
    {
        $select = $this->select()
                ->setIntegrityCheck(false)  
                ->join(array('a' => $tName),array('a.id'))
                ->where('a.id =?',$id);
        $result = $this->fetchAll($select);
        return $result->toArray();
    }
	public function deleteRecord($tName,$id)  
    {
        $db = $this->getAdapter();
        $db->delete($tName, $id);
        return '1';
    }
public function getdetailss($tName,$id)  
    {
      		$select = $this->select()
            ->setIntegrityCheck(false)
			->from(array('a' => $tName),array('id','name as habit','name_regional'))
			->where('a.id = ?',$id);

		$result = $this->fetchAll($select);
       return $result->toArray();
    }

public function getdetails($tName,$id) {

			switch($tName) {

				case 'ourbank_master_villagelist':
					{ 
						$select = $this->select()
              			->setIntegrityCheck(false)
						->from(array('a' => $tName),array('id','name as habit','panchayath_id','a.name_regional'))
				->where('a.id = ?',$id)
						->from(array('b' =>'ourbank_master_gillapanchayath'),array('id as gpid','name as gpname','hobli_id'))
				->where('b.id =a.panchayath_id')
 						->from(array('c' =>'ourbank_master_hoblilist'),array('id as hbid','name as hbname','taluk_id'))
				->where('c.id =b.hobli_id')
 						->from(array('d' =>'ourbank_master_taluklist'),array('id as tid','name as tname','district_id'))
				->where('d.id =c.taluk_id')
						->from(array('e' =>'ourbank_master_districtlist'),array('id as did','name as dname','state_id'))
                ->where('e.id =d.district_id');
				
				$result = $this->fetchAll($select);
       			return $result->toArray();
				}break;
				
				case 'ourbank_master_gillapanchayath':
					{ 
						$select = $this->select()
              			->setIntegrityCheck(false)
						->from(array('a' => $tName),array('id','name as habit','hobli_id','a.name_regional'))
				->where('a.id = ?',$id)
						->from(array('c' =>'ourbank_master_gillapanchayath'),array('id as gpid','name as gpname','hobli_id'))
                ->where('c.id =a.hobli_id')
						->from(array('d' =>'ourbank_master_hoblilist'),array('id as hbid','name as hbname','taluk_id'))
                ->where('d.id =c.hobli_id')
						->from(array('e' =>'ourbank_master_taluklist'),array('id as tid','name as tname','district_id'))
                ->where('e.id =d.taluk_id')
						->from(array('f' =>'ourbank_master_districtlist'),array('id as did','name as dname','state_id'))
						->from(array('g' =>'ourbank_master_state'),array('id as sid','name as sname'))
                ->where('f.id =e.district_id');
				
				$result = $this->fetchAll($select);
       			return $result->toArray();
				}break;

				case 'ourbank_master_hoblilist':
					{ 
				$select = $this->select()
              ->setIntegrityCheck(false)
						->from(array('a' => $tName),array('id','name as habit','taluk_id','a.name_regional'))
				->where('a.id = ?',$id)
						->from(array('b' =>'ourbank_master_taluklist'),array('id as tid','name as tname','district_id'))
                ->where('b.id =a.taluk_id')
						->from(array('c' =>'ourbank_master_districtlist'),array('id as did','name as dname','state_id'))
						->from(array('g' =>'ourbank_master_state'),array('id as sid','name as sname'))
                ->where('c.id =b.district_id');
				
				$result = $this->fetchAll($select);
       			return $result->toArray();
				}break;
				case 'ourbank_master_taluklist':
					{ 
				$select = $this->select()
              ->setIntegrityCheck(false)
						->from(array('a' => $tName),array('id','name as habit','district_id','a.name_regional'))
				->where('a.id = ?',$id)
						->from(array('b' =>'ourbank_master_districtlist'),array('id as did','name as dname','state_id'))
						->from(array('g' =>'ourbank_master_state'),array('id as sid','name as sname'))
                ->where('b.id =a.district_id');
				
				$result = $this->fetchAll($select);
       			return $result->toArray();
				}break;
				case 'ourbank_master_mastertables':
					{ 
				$select = $this->select()
              ->setIntegrityCheck(false)
						->from(array('a' => $tName),array('id','name as habit','descriptions','a.name_regional'))
				->where('a.id = ?',$id);
				
				$result = $this->fetchAll($select);
       			return $result->toArray();
				}break;
				case 'ourbank_master_districtlist':
					{ 
				$select = $this->select()
              ->setIntegrityCheck(false)
						->from(array('a' => $tName),array('id','name as habit','state_id','a.name_regional'))
						->from(array('g' =>'ourbank_master_state'),array('id as sid','name as sname'))

				->where('a.id = ?',$id);
				
				$result = $this->fetchAll($select);
       			return $result->toArray();
				}break;



				case 'ourbank_master_branch':
					{ 
				$select = $this->select()
				->setIntegrityCheck(false)
						->from(array('a' => $tName),array('id','name as habit','bank_id','a.name_regional'))
				->where('a.id = ?',$id)
						->from(array('b' => 'ourbank_master_bank'),array('id as bankid','name as bankname','accounttype_id'))
				->where('b.id  =a.bank_id');
/*die($select->__toString($select));*/
				$result = $this->fetchAll($select);
       			return $result->toArray();
				}break;

				case 'ourbank_master_habitation':
					{ 
 				$select = $this->select()
              ->setIntegrityCheck(false)
						->from(array('a' => $tName),array('id','name as habit','village_id','a.name_regional'))
				->where('a.id = ?',$id)
						->from(array('b' =>'ourbank_master_villagelist'),array('id as vid','name as vname','panchayath_id'))
                ->where('b.village_id =a.village_id')
						->from(array('c' =>'ourbank_master_gillapanchayath'),array('id as gpid','name as gpname','hobli_id'))
                ->where('c.id =b.panchayath_id')
						->from(array('d' =>'ourbank_master_hoblilist'),array('id as hbid','name as hbname','taluk_id'))
                ->where('d.id =c.hobli_id')
						->from(array('e' =>'ourbank_master_taluklist'),array('id as tid','name as tname','district_id'))
                ->where('e.id =d.taluk_id')
						->from(array('g' =>'ourbank_master_state'),array('id as sid','name as sname'))
						->from(array('f' =>'ourbank_master_districtlist'),array('id as did','name as dname','state_id'))
                ->where('f.id =e.district_id');
// // 				die($select->__toString($select));
				$result = $this->fetchAll($select);
       			return $result->toArray();
				}break;
		}

				$select = $this->select()
              ->setIntegrityCheck(false)
				->from(array('a' => $tName),array('id','name as habit','name_regional'))
				->where('a.id = ?',$id);

				$result = $this->fetchAll($select);
       			return $result->toArray();
	}
 public function tabledata($tablename)
    {
	$select = $this->select()
		->setIntegrityCheck(false)  

		->join(array('b' => $tablename),array('b.id'));
		

		//die($select->__toString($select));
		$result = $this->fetchAll($select);
		return $result->toArray();
    }
 public function district($state) {

 $select = $this->select()
             ->setIntegrityCheck(false)
			->from(array('a' => 'ourbank_master_districtlist'),array('a.id as did','a.name as dname','a.name_regional'))
			->where('a.state_id = ?',$state);

// die($select->__toString($select));


       $result = $this->fetchAll($select);
       return $result->toArray();
    }

 public function gillapanchayath($hobli) {

 $select = $this->select()
             ->setIntegrityCheck(false)
			->from(array('a' => 'ourbank_master_gillapanchayath'),array('a.id as gpid','a.name as gpname','a.name_regional'))
			->where('a.hobli_id = ?',$hobli);

// //  die($select->__toString($select));


       $result = $this->fetchAll($select);
       return $result->toArray();
    }
 public function village($gillapanchayath) {

 $select = $this->select()
             ->setIntegrityCheck(false)
		->from(array('a' => 'ourbank_master_villagelist'),array('a.id as vid','a.name as vname','a.village_id','a.name_regional'))
			->where('a.panchayath_id = ?',$gillapanchayath);

// // die($select->__toString($select));


       $result = $this->fetchAll($select);
       return $result->toArray();
    }

 public function taluk($district) {

 $select = $this->select()
             ->setIntegrityCheck(false)
			->from(array('a' => 'ourbank_master_taluklist'),array('a.id as tid' ,'a.name as tname','a.name_regional'))
			->where('a.district_id = ?',$district);

//  die($select->__toString($select));


       $result = $this->fetchAll($select);
       return $result->toArray();
    }
public function hobli($taluk) {
 $select = $this->select()
             ->setIntegrityCheck(false)
			->from(array('a' => 'ourbank_master_hoblilist'),array('a.id as hbid','a.name as hbname','a.name_regional'))
			->where('a.taluk_id = ?',$taluk);

// die($select->__toString($select));


       $result = $this->fetchAll($select);
       return $result->toArray();
    }

public function Createtable($tablename) {
        $db = $this->getAdapter();

 		$sql = "CREATE TABLE $tablename (
         id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(100),
created_by int(11),
created_date datetime
       );";
		$result=$db->query($sql);
         return $result;
    }
public function insert($commonname) {
        $db = $this->getAdapter();

 		$sql = "CREATE TABLE $commonname (
         id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
         name VARCHAR(100),
created_by int(11),
created_date datetime
       );";
		$result=$db->query($sql);
         return $result;
    }

}
    
