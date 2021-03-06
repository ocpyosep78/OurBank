<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
?>

<?php
class Transaction_Model_Receipts extends Zend_Db_Table {
	protected $_name = 'ourbank_Expenditure';

	public function listOfglcode() {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_glcodeupdates'),array('glcode_id'))
			->where('a.recordstatus_id = 3 or a.recordstatus_id = 1')
			->join(array('b' => 'ourbank_glcode'),'a.glcode_id=b.glcode_id');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}


	public function listOfglsubcode($glcode) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_glcodeupdates'),array('glcode_id'))
			->where('a.glcode_id = ?',$glcode)
			->where('a.recordstatus_id = 3 or a.recordstatus_id = 1')
			->join(array('b' => 'ourbank_glsubcodeupdates'),'a.glcode_id=b.glcode_id')
			->where('b.recordstatus_id = 3 or b.recordstatus_id = 1')
			->join(array('c' => 'ourbank_glsubcode'),'b.glsubcode_id=c.glsubcode_id');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

	public function listOfledgercode($glcode) {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_glcodeupdates'),array('glcode_id'))
			->where('a.glcode_id = ?',$glcode)
			->where('a.recordstatus_id = 3 or a.recordstatus_id = 1')
			->join(array('b' => 'ourbank_ledgertypes'),'a.ledgertype_id=b.ledgertype_id');
		$result = $this->fetchAll($select);
		return $result->toArray();
	}

        public function getHeadOffice() {
            $db = $this->getAdapter();
            $sql = "select office_id, office_name, officetype_id from
               ourbank_officenames where officetype_id = 
               (select officetype_id-1 from ourbank_officehierarchy where Hierarchy_level = (select max(Hierarchy_level) from ourbank_officehierarchy))";
            return $db->fetchAll($sql);

        }

	public function addtransactions($input = array())
	{
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$result = $this->db->insert('ourbank_transaction',$input);
		return $this->db->lastInsertId('ourbank_transaction');
        }

	public function fetchassetsaccount($memberbranch_id) 
	{
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$sql = "select * from   ourbank_bankaccounts where (bank_branch_id='$memberbranch_id') LIMIT 1";
		$result = $this->db->fetchAll($sql,array());
		return $result;
	}

	public function fetchincomeaccount($memberbranch_id) 
	{
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$sql = "select * from   ourbank_bankaccounts where (bank_branch_id='$memberbranch_id') LIMIT 1,1";
		$result = $this->db->fetchAll($sql,array());
		return $result;
	}


	public function fetchlibalitesaccount($memberbranch_id) 
	{
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$sql = "select * from   ourbank_bankaccounts where (bank_branch_id='$memberbranch_id') LIMIT 2,1";
		$result = $this->db->fetchAll($sql,array());
		return $result;
	}

	public function fetchExpendituraccount($memberbranch_id) 
	{
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$sql = "select * from   ourbank_bankaccounts where (bank_branch_id='$memberbranch_id') LIMIT 3,1";
		$result = $this->db->fetchAll($sql,array());
		return $result;
	}

	public function addfromaccounts($tablenamefrom,$bankIDfrom,$fromglsubcodeid,$toglsubcodeid,$transaction_id,$amount) {

		$this->db = Zend_Db_Table::getDefaultAdapter();
		$data = array('bank_id'=> $bankIDfrom,
				'glsubcode_id_to'=>$fromglsubcodeid,
				'tranasction_number'=>$transaction_id,
				'credit'=>'',
				'debit'=>$amount,
				'record_status'=>'3');
		return $this->db->insert($tablenamefrom,$data);
	}


	public function addtoaccounts($tablenameto,$bankIDto,$fromglsubcodeid,$toglsubcodeid,$transaction_id,$amount) {

		$this->db = Zend_Db_Table::getDefaultAdapter();
		$data = array('bank_id'=> $bankIDto,
				'glsubcode_id_to'=>$toglsubcodeid,
				'tranasction_number'=>$transaction_id,
				'credit'=>$amount,
				'debit'=>'',
				'record_status'=>'3');
		return $this->db->insert($tablenameto,$data);
	}

	public function paymenttype() {
		$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_paymenttypes'),array('paymenttype_id'));
		$result = $this->fetchAll($select);
		return $result->toArray();
	}
}
