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
/*
 *  create form elements for hierarchy
 */
class Officehierarchy_Form_Hierarchy extends Zend_Form
{
 
    public function __construct($options,$levelno)
    {
    	parent::__construct($options);
        $this->setName('updateloanscheme');
	//set drop down and validate
	if($options == 0)
	{

		        $OFFICE_NO = new Zend_Form_Element_Select('officeNo');
        		$OFFICE_NO->setRequired(true);
        		$OFFICE_NO->addMultiOption('','Select...');
        		$OFFICE_NO->addMultiOption('3',' 3');
       			$OFFICE_NO->addMultiOption('4',' 4');
        		$OFFICE_NO->addMultiOption('5',' 5');
        		$OFFICE_NO->addMultiOption('6',' 6');
        		$OFFICE_NO->addMultiOption('7',' 7');
       			$OFFICE_NO->addMultiOption('8',' 8');
        		$OFFICE_NO->addMultiOption('9',' 9');
        		$OFFICE_NO->addMultiOption('10',' 10');

		$OFFICE_NO->setAttrib('class','selectbutton');
		$OFFICE_NO->setAttrib('onchange', 'reload(this.value)');

        $officetype = new Zend_Form_Element_Hidden('officeType1');
        $officetype->setRequired(true)
		  ->addValidators(array(
        array('NotEmpty')
        )
        );
	$officetype->setAttrib('class', 'txt_put');
	$officetype->setAttrib('id', 'officeType1');
        $officecode = new Zend_Form_Element_Hidden('officeCode1');
        $officecode->setRequired(true)
		   ->addValidators(array(
        array('NotEmpty')
        )
        );
	$officecode->setAttrib('class', 'txt_put');
	$officecode->setAttrib('id', 'officeCode1')
		   ->setAttrib('size', '2');

        $this->addElements(array($officetype,$officecode));

	//set office levels
	for($i=2;$i<=$levelno;$i++)
	{
    	$id = new Zend_Form_Element_Hidden('id'.$i);

        $officetype = new Zend_Form_Element_Hidden('officeType'.$i);
        $officetype->setRequired(true)
		  ->addValidators(array(
        array('NotEmpty')
        )
        );
	$officetype->setAttrib('class', 'txt_put');
	$officetype->setAttrib('id', 'officeType'.$i);
        $officecode = new Zend_Form_Element_Hidden('officeCode'.$i);
        $officecode->setRequired(true)
		   ->addValidators(array(
        array('NotEmpty')
        )
        );
	$officecode->setAttrib('class', 'txt_put');
	$officecode->setAttrib('id', 'officeCode'.$i)
		   ->setAttrib('size', '2');

        $this->addElements(array($id,$officetype,$officecode));
        }
        $submit = new Zend_Form_Element_Submit('Edit');
		$submit->setAttrib('class', 'officesubmit');
		$submit->setLabel('edit');
        $this->addElements(array($submit));
	$this->addElements(array($OFFICE_NO));
	}


	else
	{
	for($i=1;$i<=$options;$i++)
	{
    	$id = new Zend_Form_Element_Hidden('id'.$i);
	$hierarchy_level = new Zend_Form_Element_Hidden('hierarchyLevel'.$i);
    	 	$officetype = new Zend_Form_Element_Hidden('officeType'.$i);
        $officetype->setRequired(true)
		   ->addValidators(array(
	//add validation and fix length
        array('NotEmpty')
        ,array('stringLength', false, array(4, 50))
        ));
	$officetype->setAttrib('class', 'txt_put');
	$officetype->setAttrib('id', 'officeType'.$i);
        $officecode = new Zend_Form_Element_Hidden('officeCode'.$i);
	//add validation and fix length
        $officecode->setRequired(true)
		   ->addValidators(array(
        array('NotEmpty')
        ,array('stringLength', false, array(2, 2))
        ));
	$officecode->setAttrib('class', 'txt_put');
	$officecode->setAttrib('id', 'officeCode'.$i)
		   ->setAttrib('size', '2');

        $this->addElements(array($id,$officetype,$officecode,$hierarchy_level));
      }
	$OFFICE_NO = new Zend_Form_Element_Hidden('officeNo');
	$Office_level = new Zend_Form_Element_Text('officeLevel');
	//add validation and fix length
	$Office_level->setAttrib('size', '2');
	$Office_level->setAttrib('id', 'officeLevel');
	$Office_level->setAttrib('class', 'txt_put');
	$Office_level->addDecorators(array(
    'ViewHelper',
    'Errors',
    array('HtmlTag', array('tag' => 'h')),
    array('Label', array('tag' => 'h')),
));
        $submit = new Zend_Form_Element_Submit('Edit');
		$submit->setLabel('edit');
	$Next = new Zend_Form_Element_Submit('Next');
	//add submit button
	$submit->setAttrib('class', 'officebutton');
	$Next->setAttrib('class', 'officesubmit');
	$Next->setLabel('Next');
        $this->addElements(array($submit,$OFFICE_NO,$Office_level,$Next));
	}
      }
 }
