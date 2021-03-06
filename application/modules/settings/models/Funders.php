<?php
class Management_Model_Funders extends Zend_Db_Table {
     protected $_name = 'ourbank_funderdetails';

     public function getFundersDetails() {
        $result = $this->fetchAll( "recordstatus_id = '3'"  );
        return $result;
        }

    public function SearchFunder($post = array()) {
        $select = $this->select()
                       ->setIntegrityCheck(false)  
                       ->join(array('a' => 'ourbank_funderdetails'),array('funderaddress_id'))
                       ->where('a.recordstatus_id = 3')
                       ->where('a.fundername like "%" ? "%"',$post['field1'])
                       ->where('a.fundercountry like "%" ? "%"',$post['field2'])
                       ->where('a.funderphone like "%" ? "%"',$post['field3'])
                       ->where('a.emailaddress like "%" ? "%"',$post['field4']);
       $result = $this->fetchAll($select);
       return $result->toArray();
    }

    public function addFunder($post,$funder_id) {
           $data = array('funderaddress_id'=> '',
                         'funder_id'=> $funder_id,
                         'fundername'=>$post['fundername'],
                         'fundershortname'=>$post['fundershortname'],
                         'funderaddress1' =>$post['funderaddress1'],
                         'funderaddress2' =>$post['funderaddress2'],
                         'funderaddress3' =>$post['funderaddress3'],
                         'fundercity' =>$post['fundercity'],
                         'funderstate' =>$post['funderstate'],
                         'fundercountry' =>$post['fundercountry'],
                         'funderpincode' =>$post['funderpincode'],
                         'funderphone' =>$post['funderphone'],
                         'funderpincode' =>$post['funderpincode'],
                         'emailaddress' =>$post['emailaddress'],
                         'recordstatus_id'=>'3',
                         'createdby'=>'1',
                         'createddate'=>date("Y-m-d"));
            $this->insert($data);
    }

    public function viewFunders($funderaddress_id) {
        $result = $this->fetchAll( "recordstatus_id = '3' AND 
                                    funderaddress_id = $funderaddress_id");
        return $result;
   }

}
