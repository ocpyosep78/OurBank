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
class Receipts_IndexController extends Zend_Controller_Action {
	public function init() 
        {
                    $this->view->pageTitle='Receipt and payment';
                    $this->view->title = "Transaction";
            $this->view->adm = new App_Model_Adm();
            $this->view->dateconvertor = new App_Model_dateConvertor();
    
        $storage = new Zend_Auth_Storage_Session();
        $data = $storage->read();
        if(!$data){
                $this->_redirect('index/login'); // once session get expired it will redirect to Login page
        }

        $sessionName = new Zend_Session_Namespace('ourbank');
        $userid=$this->view->createdby = $sessionName->primaryuserid; // get the stored session id 

        $login=new App_Model_Users();
        $loginname=$login->username($userid);
        foreach($loginname as $loginname) {
            $this->view->username=$loginname['username']; // get the user name
        }

	}

	public function indexAction() {

		$storage = new Zend_Auth_Storage_Session();
		$data = $storage->read();
		if(!$data){
			$this->_redirect('index/login');
		}
                $path = $this->view->baseUrl();
                $username = $this->view->username;
		$receiptsform = new Receipts_Form_Receipts($path);
		$this->view->form = $receiptsform;
                $receipts= new Receipts_Model_Receipts();

//                 $mainBranch = $receipts->getHeadOffice($username);
//                     foreach($mainBranch as $mainBranch) {
//                     $receiptsform->officeType->addMultiOption($mainBranch['id'],$mainBranch['name']);
//                 }

                $receipts= new Receipts_Model_Receipts();
                $mainBranch = $receipts->getHeadOffice($this->view->username); 
                foreach($mainBranch as $mainBranch) { if($mainBranch) { $officeid= $mainBranch['id']; }} 
                $branch= $receipts->getBranch($officeid);
                foreach($branch as $branch) { if ($branch) { $branchid=$branch['id']; }}


                $glcode=$receipts->listOfglcode();
                foreach($glcode as $glcodes) {
//                     $receiptsform->fromglcode->addMultiOption($glcodes['id'],$glcodes['header']);
                    $receiptsform->fromglcode->addMultiOption($glcodes['id'],$glcodes['header']." -[".$glcodes['glcode']."]");

                    $receiptsform->toglcode->addMultiOption($glcodes['id'],$glcodes['header']." -[".$glcodes['glcode']."]");
                }

		$select = $receipts->paymenttype();
		foreach ($select as $paymenttype1){
			$receiptsform->paymenttype->addMultiOption($paymenttype1['id'],$paymenttype1['id']." -[".$paymenttype1['description']."]");
		}



		if ($this->_request->isPost() && $this->_request->getPost('Submit')) {

			$formData = $this->_request->getPost();
			if ($this->_request->isPost()) {

				$paymenttype=$this->view->paymenttype=$this->_request->getParam('paymenttype'); 
				if( $paymenttype ==1 || $paymenttype ==""  ) {
					$receiptsform->paymenttype_details->setRequired(false);
				}

				if ($receiptsform->isValid($formData)) {echo "1";

					//$branchid = $this->_request->getParam('subOffice1'); 
					$fromglcode = $this->_request->getParam('fromglcode'); 
					$fromglsubcodeid = $this->_request->getParam('glsubcodeid');
					$toglcode = $this->_request->getParam('toglcode'); 
					$toglsubcodeid = $this->_request->getParam('toglsubcodeid');
					$amount = $this->_request->getParam('amount');
					$transactiondate = $this->_request->getParam('transactiondate');
					$description = $this->_request->getParam('description');
					$paymenttype_details = $this->_request->getParam('paymenttype_details');

                                        $receipts= new Receipts_Model_Receipts();
                                        if ($fromglcode) {
                                                    $fromledgercode=$receipts->listOfledgercode($fromglcode);
                                                    foreach($fromledgercode as $fromledgercodes) {
                                                            $fromledgertype=$fromledgercodes['name'];
                                                    } }

                                        if ($toglcode) {
                                                    $toledgercode=$receipts->listOfledgercode($toglcode);
                                                    foreach($toledgercode as $toledgercodes) {
                                                        $toledgertype=$toledgercodes['name'];
                                                    } }
$flag=0;
                                        if($toglsubcodeid=="") {
                                            echo "select the to GL code again"; $flag=1;
                                        }
                                            //elseif($branchid=="") {
                                           // echo "select the Office type again"; $flag=1;
                                       // }
                                                 elseif($fromglsubcodeid=="")  {
                                            echo "select the from GL code again"; $flag=1;
                                        } else {

                        $sessionName = new Zend_Session_Namespace('ourbank');
                        $user_id = $sessionName->primaryuserid;
                        $receipts= new Receipts_Model_Receipts();
                        if($fromglcode) {
                            if($fromledgertype=="Income") {
                                $tablenamefrom="ourbank_Income";
                            } elseif($fromledgertype=="Expenditure") {
                                $tablenamefrom="ourbank_Expenditure";
                            }elseif($fromledgertype=="Assets") {
                                $tablenamefrom="ourbank_Assets";
                            } elseif($fromledgertype=="Liabilities") {
                                $tablenamefrom="ourbank_Liabilities";
                            } }
                        }


                        if($toledgertype=="Income") {
                            $tablenameto="ourbank_Income";
                        } elseif($toledgertype=="Expenditure") {
                            $tablenameto="ourbank_Expenditure";
                        }elseif($toledgertype=="Assets") {
                            $tablenameto="ourbank_Assets";
                        } elseif($toledgertype=="Liabilities") {
                            $tablenameto="ourbank_Liabilities";
                        }

//if ($fromglsubcodeid == '') { $fromglsubcodeid = 0; }
//if ($paymenttype == '') { $paymenttype = 5; }

			$Transactiondata = (array('transaction_id'=>'',
					'account_id' => '',
					'glsubcode_id_from' => $fromglsubcodeid,
					'glsubcode_id_to' => $toglsubcodeid,
					'transaction_date'=>$transactiondate,
					'amount_to_bank'=>$amount,
					'amount_from_bank'=>'',
					'paymenttype_id'=>$paymenttype,
					'transactiontype_id'=>'1',
					'recordstatus_id'=>'3',
					'reffering_vouchernumber'=>$paymenttype_details,
					'transaction_description'=>$description,
					'balance'=>'',
					'confirmation_flag'=>'',
					//'created_by'=>$user_id,$this->view->createdby
                                        'created_by'=>$this->view->createdby,
					'created_date'=>date("Y-m-d")
			)); //echo '<pre>'; print_r($Transactiondata); echo $fromglsubcodeid; echo $toglsubcodeid; echo $branchid;
			$transaction_id=$receipts->addtransactions($Transactiondata);

                        if($fromglcode) {
                            $receipts->addfromaccounts($tablenamefrom,$branchid,$fromglsubcodeid,$toglsubcodeid,$transaction_id,$amount);
                        }
                            $receipts->addtoaccounts($tablenameto,$branchid,$fromglsubcodeid,$toglsubcodeid,$transaction_id,$amount);
if($flag==0){
$this->_redirect('transaction');}
                                        }
                                }

                        }
			//$this->_redirect('transaction');

		}
// 			$this->_redirect('transaction');


        function glsubcodeAction()
        {
	       $this->_helper->layout->disableLayout();
               $glcode=$this->_request->getParam('glcode');

               $receipts= new Receipts_Model_Receipts();
               $this->view->glsubcodeid=$receipts->listOfglsubcode($glcode);

	}

        function getglsubcodeAction()
            {
	       $this->_helper->layout->disableLayout();
               $glcode=$this->_request->getParam('glcode');

               $receipts= new Receipts_Model_Receipts();
               $this->view->glsubcodeid=$receipts->listOfglsubcode($glcode);
	   }

        public function getbranchAction() 
            {
                $this->_helper->layout->disableLayout();
                $office_id = $this->_request->getParam('id');

                $individual = new Receipts_Model_Receipts();
                $this->view->branchs = $individual->getBranchEdit($office_id);

            }

        public function getbalanceAction()
        {
            $this->_helper->layout->disableLayout();
            $glsubcode=$this->_request->getParam('glsubcode');
            $this->view->adm = new App_Model_Adm();
            $receipts= new Receipts_Model_Receipts();
            $toledgertype=''; $tablenameto='';
            if ($glsubcode) {
                $toledgercode=$receipts->listOfledgercode($glsubcode);
                foreach($toledgercode as $toledgercodes) {
                $toledgertype=$toledgercodes['name'];
                            } }

            if($toledgertype=="Income") {
                            $tablenameto="ourbank_Income";
                        } elseif($toledgertype=="Expenditure") {
                            $tablenameto="ourbank_Expenditure";
                        }elseif($toledgertype=="Assets") {
                            $tablenameto="ourbank_Assets";
                        } elseif($toledgertype=="Liabilities") {
                            $tablenameto="ourbank_Liabilities";
                        }

            $this->view->balance2=$receipts->getbalanceto($glsubcode,$tablenameto);
        }

        public function balanceAction()
        {
            $this->_helper->layout->disableLayout();
            $glsubcode=$this->_request->getParam('glsubcode');
            $this->view->adm = new App_Model_Adm();
            $receipts= new Receipts_Model_Receipts();
            $fromledgertype=''; $tablenamefrom='';
            if ($glsubcode) {
                $fromledgercode=$receipts->listOfledgercode($glsubcode);
                foreach($fromledgercode as $fromledgercodes) {
                $fromledgertype=$fromledgercodes['name'];
                            } }

            if($fromledgertype=="Income") {
                            $tablenamefrom="ourbank_Income";
                        } elseif($fromledgertype=="Expenditure") {
                            $tablenamefrom="ourbank_Expenditure";
                        }elseif($fromledgertype=="Assets") {
                            $tablenamefrom="ourbank_Assets";
                        } elseif($fromledgertype=="Liabilities") {
                            $tablenamefrom="ourbank_Liabilities";
                        }
            if($glsubcode){
            $this->view->balance1=$receipts->getbalancefrom($glsubcode,$tablenamefrom);} else {$this->view->balance1='';}
        }

        public function paymenttypeAction()
        {
              /*  $this->_helper->layout->disableLayout();
                $id = $this->_request->getParam('id');
                $individual = new Receipts_Model_Receipts();
                if($id){
                $id =5;
                $this->view->paymenttype = $individual->paymenttype_id($id);
                } else { $this->view->paymenttype = $individual->paymenttype();} */
        }

        public function latestbalancefromAction()
        {
            $this->_helper->layout->disableLayout();
            $amt=$this->_request->getParam('amount');
            $frombalance=$this->_request->getParam('balance1');
            $fromlatestbalance = $frombalance - $amt;
            $this->view->latestbalancefrom = $fromlatestbalance; 
        }

         public function latestbalancetoAction()
        {
            $this->_helper->layout->disableLayout();
            $amt=$this->_request->getParam('amount');
            $tobalance=$this->_request->getParam('balance2');
            $tolatestbalance = $tobalance + $amt; 
            $this->view->latestbalanceto = $tolatestbalance; 
        }

}

