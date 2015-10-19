<?php
class LinnworksapisController extends AppController
{
    /* controller used for linnworks api */
    
    var $name = "Linnworksapis";
    
    var $components = array('Session','Upload','Common','Auth');
    
    var $helpers = array('Html','Form','Common','Session','Soap','Number');
    
     public function index()
		{
			$this->layout = "index";
			$this->set('role', 'Linnworks API');
		}
	
	 public function getCategory()
		{
			/* method's ctp file get category */
			$this->layout = "index";
			$this->set('title', 'Category (Linnworks API)');
		}
	
	 public function getorderStatus()
		{
			/* method's ctp file get order status */
			$this->layout = "index";
			$this->set('title', 'Status (Linnworks API)');

		}
	
	 public function getPostalServices()
		{
			/* method's ctp file get postal services */
			$this->layout = "index";
			$this->set('title', 'Postal Service (Linnworks API)');
		}
	
	 public function getLocations()
		{
			/* method's ctp file get location */
			$this->layout = "index";
			$this->set('title', 'Location (Linnworks API)');
		}
	
	 public function getStockItem()
		{
			/* method's ctp file get stock item */
			$this->layout = "index";
			$this->set('title', 'Stock Items (Linnworks API)');
		}
	public function getOrder( $orderID = null, $pkOrderId = null)
		{
			
			$this->layout = "index";
			if($orderID == 'deleteOrder')
			{
				$delete = 'orderdeleted';
				$this->Session->setflash(  "Order Deleted Successful.",  'flash_success' );
			}
			else
			{
				$delete = '';
			}
			$this->set(compact('orderID', 'pkOrderId','delete'));
		}

public function getFilterOrder()
	{
		if($this->request->data)
		{
			$data	=	$this->request->data;
			$this->set('data' , $data);
		}
		$this->layout = "index";
		$this->set('title', 'Filter order (Linnworks API)');
	}


public function downloadExcel()
	{
		$this->autoRender = false;
		$this->layout = '';
		
		$data['Linnworksapi']['order_type'] 	= 	$this->request->data['Linnworksapis']['order_type'];
		$data['Linnworksapi']['location'] 		= 	$this->request->data['Linnworksapis']['location'];
		$data['Linnworksapi']['source'] 		= 	$this->request->data['Linnworksapis']['source'];
		$data['Linnworksapi']['subsource'] 		= 	$this->request->data['Linnworksapis']['subsource'];
		$data['Linnworksapi']['datefrom'] 		= 	$this->request->data['Linnworksapis']['datefrom'];
		$data['Linnworksapi']['dateto'] 		= 	$this->request->data['Linnworksapis']['dateto'];
		$data['Linnworksapi']['orderid'] 		= 	$this->request->data['Linnworksapis']['orderid'];
		App::import('Helper', 'Soap');
		$SoapHelper = new SoapHelper( new View(null) );
		$getData	=	$SoapHelper->getFilteredOrder( $data );
		
		App::import('Helper', 'Number');
		$numberHelper = new NumberHelper( new View(null) );
		
		App::import('Vendor', 'PHPExcel/IOFactory');
		App::import('Vendor', 'PHPExcel');
		$objPHPExcel = new PHPExcel();   
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'OrderItemNumber');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Name');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Address');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Postcode');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Country');
		$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Item Count');
		$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Contents');
		$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total Packet Value');
		$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Weight');
		$objPHPExcel->getActiveSheet()->setCellValue('J1', 'HS');
		$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Deposit');
		$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Invoice Number');
		$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Bag barcode');
		
		$i = 2;
		foreach($getData->GetFilteredOrdersResponse->GetFilteredOrdersResult->Orders->Order as $order)
				{
					$contents = array();
					$orderItems = array();
					foreach($order->OrderItems->OrderItem as $item)
						{ 
							$contents[] =	$item->Qty.' X '.$item->ItemTitle;
							$orderItems[] =	$item->OrderItemNumber;
						}
		
					$content = implode(" \n", $contents);
					$orderItem = implode(" \n", $orderItems);
					
					$itemCount	=	count($order->OrderItems->OrderItem);
					
					
					
					$address	=	$order->ShippingAddress->Address1.','.
									$order->ShippingAddress->Address2.','.
									$order->ShippingAddress->Address3;
									
					$address = explode(',', $address);
					$address = implode(" \n ", $address);
		
						
		$objPHPExcel->getActiveSheet()->setCellValue('A'.$i.'', $orderItem);
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$i.'', $order->ShippingAddress->Name);
		$objPHPExcel->getActiveSheet()->setCellValue('C'.$i.'', $address);
		$objPHPExcel->getActiveSheet()->setCellValue('D'.$i.'', $order->ShippingAddress->PostCode );
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$i.'', $order->ShippingAddress->CountryCode);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$i.'', $itemCount);
	
		$totlaCost =	$numberHelper->currency( $order->TotalCost, 'EUR' );
		
		$objPHPExcel->getActiveSheet()->setCellValue('G'.$i.'', $content );
		$objPHPExcel->getActiveSheet()->setCellValue('H'.$i.'', $totlaCost);
		$objPHPExcel->getActiveSheet()->setCellValue('I'.$i.'', '5');
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i.'', '5');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i.'', '5');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i.'', '5');
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i.'', '5');
		
		$i++;
				}
		
		
		
		if($this->request->data['Linnworksapis']['order_type'] == 0)
		{
			$objPHPExcel->getActiveSheet()->setTitle('Open Order');
			$objPHPExcel->createSheet();
			$name = 'Open Order';
		}
		if($this->request->data['Linnworksapis']['order_type'] == 1)
		{
			$objPHPExcel->getActiveSheet()->setTitle('Processed Order');
			$objPHPExcel->createSheet();
			$name = 'Procesed Order';
		}
		if($this->request->data['Linnworksapis']['order_type'] == 2)
		{
			$objPHPExcel->getActiveSheet()->setTitle('Cancelled Order');
			$objPHPExcel->createSheet();
			$name = 'Cancelled Order';
		}
		
		
		header('Content-Encoding: UTF-8');
		header('Content-type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment;filename="'.$name.'.csv"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
		
		$objWriter->save('php://output');
	}

	public function generatePickList()
	{
		
		
		App::import('Helper', 'Soap');
		$SoapHelper = new SoapHelper( new View(null) );
	
		$test	=	$this->request->data['Linnworksapis']['orderid'];
	
		$skus	=	explode("---", $test);

		asort($skus);
		$skus	=	array_count_values($skus);


		$this->autoRender = false;
		$this->layout = '';
		
		App::import('Vendor', 'PHPExcel/IOFactory');
		App::import('Vendor', 'PHPExcel');
		$objPHPExcel = new PHPExcel();   
		$objPHPExcel->setActiveSheetIndex(0);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A1', 'SKU');
		$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Qty');
		$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Item Title');
		$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Bin Rack');
		$objPHPExcel->getActiveSheet()->setCellValue('E1', 'BarCode');
		
				$data	 =	array();
				$dataNew =	array();
				$csvData = 	array();
				$index = 0;
				
				foreach( $skus as $key => $value)
				{
					$getData	=	$SoapHelper->getOrderById( $key );
					
					foreach($getData->GetFilteredOrdersResponse->GetFilteredOrdersResult->Orders->Order as $order)
						{	
							foreach($order->OrderItems->OrderItem as $item)
								{ 
									$data[$index]['Qty']		=	$item->Qty;
									$data[$index]['ItemTitle']	=	$item->ItemTitle;
									$data[$index]['binrack'] 	=	$item->Binrack;
									$data[$index]['barcode'] 	=	$item->Barcode;
									$data[$index]['category'] 	=	$item->Category;
									$data[$index]['ChannelSKU'] =	$item->SKU;
									$index++;
								}
						}
				}

		
					$json = json_encode($data);
					$arrays = json_decode($json,TRUE);
					$ind = 0;
					
					foreach($arrays as $array)
					{
						$dataNew[$ind]['Qty']			=	$array['Qty'][0];
						$dataNew[$ind]['ItemTitle']		=	$array['ItemTitle'][0];
						$dataNew[$ind]['binrack']		=	(isset($array['binrack'][0])) ? $array['binrack'][0] : 'null';
						$dataNew[$ind]['barcode']		=	$array['barcode'][0];		
						$dataNew[$ind]['ChannelSKU']	=	$array['ChannelSKU'][0];
						$ind++;
					}
					
					/* get the duplicaate value */
					$duplicatedata = $dataNew; 
					foreach($dataNew as $dataNewOuter => $dataNewOutervalue)
					{
						foreach($dataNew as $dataNewInner => $dataNewInnervalue)
						{
							if($dataNewOutervalue['ChannelSKU'] === $dataNewInnervalue['ChannelSKU'])
							{
								if($dataNewOuter != $dataNewInner)
								{
									$duplicateValue[$dataNewInner]  = $dataNewInnervalue['ChannelSKU'];
								}
							}
						}
					}
				
					if( isset($duplicateValue) && count($duplicateValue) > 0)
					{
					$a = array_unique($duplicateValue);
					
					
					$duplicateArray = $dataNew;	
					foreach($duplicateValue as $key => $value)
					{
						unset($dataNew[$key]);
					
					}
					sort($dataNew);
				
					$result = array_merge($dataNew, $a);
					}
					else
					{
						$result	=	$duplicatedata;
					}
					
					$e = 0; $r = 0;
					foreach($result as $keyIndex => $keyValue ) 
					{
				
						$csvData[$r]['Qty'] = 0;
						foreach($duplicatedata as $dupIndex => $dupValue)
						{
							if(isset($keyValue['ChannelSKU']))
							{
								if($keyValue['ChannelSKU'] == $dupValue['ChannelSKU'])
								{
									$csvData[$r]['Qty']			=	$dupValue['Qty'];
									$csvData[$r]['ItemTitle']	=	$dupValue['ItemTitle'];
									$csvData[$r]['binrack']		=	$dupValue['binrack'];
									$csvData[$r]['barcode']		=	$dupValue['barcode'];	
									$csvData[$r]['ChannelSKU']	=	$dupValue['ChannelSKU'];	
								}
							}
							if(isset($keyValue))
							{
								if($keyValue == $dupValue['ChannelSKU'])
								{
									$csvData[$r]['Qty']			=	$csvData[$r]['Qty'] + $dupValue['Qty'];
									$csvData[$r]['ItemTitle']	=	$dupValue['ItemTitle'];
									$csvData[$r]['binrack']		=	$dupValue['binrack'];
									$csvData[$r]['barcode']		=	$dupValue['barcode'];	
									$csvData[$r]['ChannelSKU']	=	$dupValue['ChannelSKU'];
								}
							}
						} 
						$r++;
					}	
					
				$j = 2;

				foreach($csvData as $csvdata)
				{
					
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$j.'', $csvdata['ChannelSKU']);					
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$j.'', $csvdata['Qty'] .' X '. $csvdata['ItemTitle']);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$j.'', $csvdata['Qty']);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$j.'', $csvdata['binrack']);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$j.'', $csvdata['barcode']);
					$j++;
				}
			
		
				$data =	date("Y-m-d");
				header('Content-Encoding: UTF-8');
				header('Content-type: text/csv; charset=UTF-8');
				header('Content-Disposition: attachment;filename="Pick_List ('.$data.' ).csv"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
				
				$objWriter->save('php://output');
		}

	
		public function getOpenOrder()
		{
			/* for view of only order */
			$this->layout = 'index';
		}
		
		public function orderProcess()
		{
			
			App::import('Vendor', 'linnwork/api/Auth');
			App::import('Vendor', 'linnwork/api/Factory');
			App::import('Vendor', 'linnwork/api/Orders');
		
			$username	=	Configure::read('linnwork_api_username');
			$password	=	Configure::read('linnwork_api_password');
			
			$multi = AuthMethods::Multilogin($username, $password);
			
			$auth = AuthMethods::Authorize($username, $password, $multi[0]->Id);	

			$token 			= 	$auth->Token;	
			$server 		= 	$auth->Server;
			$scanPerformed 	= 	true;
			$orderId		=	$this->request->data['id'];
			
			$result = OrdersMethods::ProcessOrder($orderId,$scanPerformed,$token, $server);
			if($result->Processed == 1)
			{
				echo "1";
				exit;
			}
			else
			{
				echo "2";
				exit;
			}
		}
		
		public function orderCancel()
		{
			
			App::import('Vendor', 'linnwork/api/Auth');
			App::import('Vendor', 'linnwork/api/Factory');
			App::import('Vendor', 'linnwork/api/Orders');
		
			$username	=	Configure::read('linnwork_api_username');
			$password	=	Configure::read('linnwork_api_password');
			
			$multi = AuthMethods::Multilogin($username, $password);
			
			$auth = AuthMethods::Authorize($username, $password, $multi[0]->Id);	

			$token 			= 	$auth->Token;	
			$server 		= 	$auth->Server;
			
			$getContent	=	explode('##$#', $this->request->data['content']);
			$orderId	=	$getContent['0'];
			$fulFilment	=	$getContent['1'];
			$refund		=	$getContent['2'];
			$note 		= 	'test note';
			
			
			$result = OrdersMethods::CancelOrder($orderId,$fulFilment,$refund,$note,$token, $server);
			echo "1";
			exit;
			
		}
		
		public function getOrderDetail($orderID = null, $pkOrderId=null)
		{
			$this->layout = 'index';
			$this->set(compact('orderID', 'pkOrderId'));
		}

		public function orderDelete()
		{
			App::import('Vendor', 'linnwork/api/Auth');
			App::import('Vendor', 'linnwork/api/Factory');
			App::import('Vendor', 'linnwork/api/Orders');
		
			$username	=	Configure::read('linnwork_api_username');
			$password	=	Configure::read('linnwork_api_password');
			
			$multi = AuthMethods::Multilogin($username, $password);
			$auth = AuthMethods::Authorize($username, $password, $multi[0]->Id);	

			$token 			= 	$auth->Token;	
			$server 		= 	$auth->Server;
			
			$orderid	=	$this->request->data['id'];
		
			$result = OrdersMethods::DeleteOrder($orderid, $token, $server);
			
			echo "1";
			exit;
			
		}
	
		/*
		 * 
		 * 
		 * 	@param function to print the labels and packaging slips but order would be open order its mandatory
		 * 
		 * 
		 * 
		 */ 
		 public function getPrints()
		 {
			 
				$this->layout = 'index';
				
				App::import('Vendor', 'linnwork/api/Auth');
				App::import('Vendor', 'linnwork/api/Factory');
				App::import('Vendor', 'linnwork/api/PrintService');
			
				$username	=	Configure::read('linnwork_api_username');
				$password	=	Configure::read('linnwork_api_password');
				
				$multi = AuthMethods::Multilogin($username, $password);
				$auth = AuthMethods::Authorize($username, $password, $multi[0]->Id);	

				$token 			= 	$auth->Token;	
				$server 		= 	$auth->Server;
				
				$templateType = "Invoice Template";
				$prints = PrintServiceMethods::GetTemplateList($templateType,$token, $server);
				$this->set('prints', $prints);
				
		 }
	
		 /*
		 * 
		 * 
		 * 	@param function to provide Pdf link where we can download or pritn and provide the failti to print command itself
		 * 
		 * 
		 * 
		 */ 
		 public function getPdfOfOpenOrderss()
		 {
			 
				$this->layout = 'index';
				
				App::import('Vendor', 'linnwork/api/Auth');
				App::import('Vendor', 'linnwork/api/Factory');
				App::import('Vendor', 'linnwork/api/PrintService');
			
				$username	=	Configure::read('linnwork_api_username');
				$password	=	Configure::read('linnwork_api_password');
				
				$multi = AuthMethods::Multilogin($username, $password);
				$auth = AuthMethods::Authorize($username, $password, $multi[0]->Id);	

				$token 			= 	$auth->Token;	
				$server 		= 	$auth->Server;
				
				$orderIdArray[] = '4fc02c93-2718-4604-bc29-0d4190fb91bb';
				$orderIdArray[] = '17a7742a-9649-4ca9-a054-061dfc27a13e';
				$orderIdArray[] = '064a394f-623e-4c47-9d19-4cb230069f86';
				$IDs = $orderIdArray;
				$parameters = array();
				
				$orderIds = array();
				$orderIds[] = '100031';
				$orderIds[] = '100030';
				$orderIds[] = '100029';
				$orderIds[] = '100028';
	
				$templateType = "Invoice Template";
				$printPdfLink = $result = PrintServiceMethods::CreatePDFfromJobForceTemplate($templateType,$IDs,18,$parameters,'PDF',$token, $server);
				$this->set('printPdfLink', $printPdfLink);
				$this->set('orderIds', $orderIds);
				
		 }
		 
		 
		 
	 public function getPdfOfOpenOrders()
     {
		App::import('Vendor', 'linnwork/api/Auth');
		App::import('Vendor', 'linnwork/api/Factory');
		App::import('Vendor', 'linnwork/api/Orders');
	   
		$username = "jijgrouptest@gmail.com";
		$password = "#noida15";
		$multi = AuthMethods::Multilogin($username, $password);
		
		$auth = AuthMethods::Authorize($username, $password, $multi[0]->Id); 

		$token = $auth->Token; 
		$server = $auth->Server;
		$order[] = 'b9f2eab0-f952-4fdb-91ac-f3af793eb075';
		$results = OrdersMethods::GetOrders($order,'00000000-0000-0000-0000-000000000000',true,true,$token, $server);
		pr($results); exit;
		
     }
	

}
?>
