<?php

require_once 'module/module.php';
require_once 'core/coreCapitalSources.php';
require_once 'core/coreContractPartners.php';
require_once 'core/coreMoneyFlows.php';
require_once 'core/corePreDefMoneyFlows.php';

class moduleMoneyFlows extends module {

	function moduleMoneyFlows() {
		$this->module();
		$this->coreCapitalSources=new coreCapitalSources();
		$this->coreContractPartners=new coreContractPartners();
		$this->coreMoneyFlows=new coreMoneyFlows();
		$this->corePreDefMoneyFlows=new corePreDefMoneyFlows();
	}

	function display_add_moneyflows($checked = array()) {
		$all_data=$this->corePreDefMoneyFlows->get_all_data();

			foreach($all_data as $key => $value) {
				if( count($checked) > 0 ) {
					$all_data[$key]["checked"]=$checked[$all_data[$key]["id"]]==1?"checked":"";
				}
				$all_data[$key]["capitalsourcecomment"]=$this->coreCapitalSources->get_comment($all_data[$key]["capitalsourceid"]);
				$all_data[$key]["contractpartnername"]=$this->coreContractPartners->get_name($all_data[$key]["contractpartnerid"]);
			}
				
		$capitalsource_values=$this->coreCapitalSources->get_all_comments();
		$contractpartner_values=$this->coreContractPartners->get_all_names();
		
		$this->template->assign("DATE",date("Y-m-d"));
		$this->template->assign("CAPITALSOURCE_VALUES",  $capitalsource_values  );
		$this->template->assign("CONTRACTPARTNER_VALUES",$contractpartner_values);
		$this->template->assign("ALL_DATA",              $all_data              );
		
		$this->parse_header();
		return $this->template->fetch("./display_add_moneyflows.tpl");
	}

	function save_moneyflows() {
		
		switch($_POST['realaction']) {
			case 'save':
				if(is_array($_POST['id'])) {
					foreach($_POST['id'] as $id => $value ) {
						if ($value == 1) {
							if(empty($_POST['invoicedate'][$id]))
								$_POST['invoicedate'][$id]=$_POST['bookingdate'][$id];
							$this->coreMoneyFlows->add_moneyflow($_POST['bookingdate'][$id],$_POST['invoicedate'][$id],$_POST['amount'][$id],$_POST['capitalsourceid'][$id],$_POST['contractpartnerid'][$id],$_POST['comment'][$id]);
						}
					}
				}
				break;
			case 'reload':
				break;
		}
		
		return $this->display_add_moneyflows($id);
	}
    

	function display_edit_moneyflow($id) {
		$all_data=$this->coreMoneyFlows->get_all_data($id);
		$this->template->assign("ALL_DATA",$all_data);
		
		$capitalsource_values=$this->coreCapitalSources->get_all_comments();
		$contractpartner_values=$this->coreContractPartners->get_all_names();
		
		$this->template->assign("CAPITALSOURCE_VALUES",$capitalsource_values);
		$this->template->assign("CONTRACTPARTNER_VALUES",$contractpartner_values);

		$this->parse_header(1);
		return $this->template->fetch("./display_edit_moneyflow.tpl");
	}

	function edit_moneyflow($id) {
		switch($_POST['realaction']) {
			case 'save':
				$this->template->assign("CLOSE",1);
				if(empty($_POST['invoicedate']))
					$_POST['invoicedate']=$_POST['bookingdate'];
				$this->coreMoneyFlows->update_moneyflow($id,$_POST['bookingdate'],$_POST['invoicedate'],$_POST['amount'],$_POST['capitalsourceid'],$_POST['contractpartnerid'],$_POST['comment']);

				break;
			case 'delete':
				$this->template->assign("CLOSE",1);
				$this->coreMoneyFlows->delete_moneyflow($id);
				break;
			case 'reload':
				break;
		}
		
		return $this->display_edit_moneyflow($id);
	}


}
?>
