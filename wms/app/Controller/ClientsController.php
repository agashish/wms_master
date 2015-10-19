<?php

class ClientsController extends AppController
{
    
    var $name = "Clients";
    
    var $components = array('Session', 'Common', 'Upload','Auth');
    
    var $helpers = array('Html','Form','Common','Session');
    
    public function addclient( $id = null )
    {
        
        if( isset( $id ) & $id > 0 )
            $this->set( 'role','Edit Client' );
        else
            $this->set( 'role','Add Client' );
        
        $this->layout = "index";
        
        /* Set upload path uri */
        $uploadUrl	=	WWW_ROOT .'img/client/';
        $baseUri = Router::url('/');
        $flag = false;
        $setNewFlag = 0;
        
        /* Start here set the country list */        
        $this->set( 'getLocationArray', $this->Common->getCountryList() );
        
        /* Load Model of state */
        $this->set( 'getStateList', $this->Common->getStateList() );
        
        /* Load Model of state */
        $this->set( 'getCityList', $this->Common->getCityList() );
        
        /* Load Model of warehouse */
        $this->set( 'getWarehouseList', $this->Common->getWarehouseList() );
              
        if( $this->request->is('post') )
        {
            
            $id = $this->request->data['Client']['id'];
            
            if( $id > 0 )
            $imgExist = $this->request->data['ClientImage']['imgExist'];
            
            if( !empty($this->request->data) )
            {
                
                /* Get validation result here */
                $this->Client->set( $this->request->data );
                if( $this->Client->validates( $this->request->data ) )
                {
                       $flag = true;                                      
                }
                else
                {
                   $flag = false;
                   $setNewFlag = 1;
                   $error = $this->Client->validationErrors;
                }
                
                $this->Client->ClientDesc->set( $this->request->data );   
                if( $this->Client->ClientDesc->validates( $this->request->data ) )
                {
                       $flag = true;
                }
                else
                {
                   $flag = false;
                   $setNewFlag = 1;
                   $error = $this->Client->ClientDesc->validationErrors;
                }    
               
                $this->Client->ClientWarehouse->set( $this->request->data );   
                if( $this->Client->ClientWarehouse->validates( $this->request->data ) )
                {
                       $flag = true;
                }
                else
                {
                   $flag = false;
                   $setNewFlag = 1;
                   $error = $this->Client->ClientWarehouse->validationErrors;
                }
                
                 if( $setNewFlag == 0 )//if( ($flag1 == true) && ($flag2 == true) && ( $flag3 == true ) )
                 { 

                        /* Start here image is coming to upload or not */
                        $file = $this->request->data['ClientImage']['client_image'];
                        
                        if( isset( $file['name'] ) && $file['name'] != "" )
                        {
                            
                            /* Here upload an image accordingly and validate it */
                            $getImageName = $this->Upload->upload($this->request->data['ClientImage']['client_image'], $uploadUrl );
                            
                            /* Set client image name from upload component */
                            $this->request->data['ClientImage']['client_image'] = $getImageName;
                            
                        }
                        else
                        {                            
                            /* If image array will blank than will set to string name */
                            $this->request->data['ClientImage']['client_image']  = $imgExist;                                
                        }
                        
                        /* Set comma seperated warehouses id's assigning to client */                        
                        $this->request->data['ClientWarehouse']['warehouse_id'] = implode(',', $this->request->data['ClientWarehouse']['warehouse_id']);
                                                
                        /* Client data now saving into clients table */
                        $this->Client->saveAll( $this->request->data );
                        //$id =	$this->Client->getLastInsertId();
                        
                        if( isset( $id ) && ($id > 0) )
                        {
                              
                              $this->Session->setflash( "Client details has been updated.", 'flash_success' );
                              $msg = " has been updated in our list";
                              
                              /* Here unset existing image when user will update with new image */
                              if( ( isset($getImageName) && $getImageName != "" ) )
                              {
                                
                                 $image_name = WWW_ROOT .'img/client/'.$imgExist;
                                 chmod($image_name, 0777);
                                 unlink ( $image_name );
                                
                               }
                        
                        }
                        else
                        {
                            $this->Session->setflash( "Client details has been saved.", 'flash_success' );
                            $msg = " has been added in our list";
                        }
                        
                        /* Set image path for popup appearance */
                        if( $getImageName != "" )
                            $image = Router::url('/'). 'app/webroot/img/client/' .$getImageName;
                        else
                            $image = Router::url('/'). 'app/webroot/img/client/' .$imgExist;
                        
                        $clientName = $this->request->data['Client']['client_name'];
                        
                        /* Start here popup content with client image */                        
                        $popupArray	=	array(
                                              "","<div style = text-align:center; >
                                            <div class=clearfix>
                                            <div class=message>
                                            <img class= manage_image style='border-radius: 158px; height: 100px; width: 100px;' src=".$image." ></div>
                                        </div>
                                        <div class=form-group>
                                            <label for=username ><strong>".ucfirst($clientName)."</strong> ".$msg."</label>                                                                                
                                        </div>
                                        <div class=form-group>
                                            <label for=username >Client :</label><label for=username >".ucfirst($clientName)."</label>                                                                                
                                        </div>                                        
                                    </div>",$baseUri."showall/Client/List");
                        $this->set( "popupArray", $popupArray );
                        
                        /* Send Email through smtop gmail 
                        App::uses('CakeEmail', 'Network/Email');
                        $email = new CakeEmail('gmail');
                        $email->from('ag.ashishaggarwal@gmail.com');
                        $email->to('ashish.gupta@jijgroup.com');
                        $email->subject('Mail Confirmation');
                        $email->send( "Thanks Cakephp Gmail Email gupta ji" );*/
                        
                 }
            }
        }
    }
    
    public function showallclient()
    {
       
        /* Load State model here */
        $this->loadModel( 'Location' );
        $this->loadModel( 'State' );
        $this->loadModel('City');
        $this->loadModel('Warehouse');
        //$this->loadModel('WarehouseDesc');
        
        /* Set image path for popup appearance */
        $imagePath = Router::url('/'). 'app/webroot/img/client/';
        
        /* Start here set custom title and breadcrumbs */
        $this->set( "role","Show All Client" );
        $this->layout = "index";
        
        /* Start here getting the list of roles */
        /* Set conditions */
        $options = array(
            'fields' => array('State.state_name','Location.county_name','City.city_name','Client.id','Client.client_name','Client.status','ClientDesc.is_deleted','ClientImage.client_image','Warehouse.warehouse_name'),
            'joins' => array(
                array(
                    'alias' => 'State',  
                    'table' => 'states',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'State.id = ClientDesc.state_id',
                    )
                ),
                array(
                    'alias' => 'Location',  
                    'table' => 'locations',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Location.id = ClientDesc.location_id',
                    )
                ),
                array(
                    'alias' => 'City',  
                    'table' => 'cities',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'City.id = ClientDesc.city_id',
                    )
                ),
                array(
                    'alias' => 'Warehouse',  
                    'table' => 'warehouses',
                    'type' => 'LEFT',
                    'conditions' => array(
                        'Warehouse.id = ClientWarehouse.warehouse_id',
                    )
                )                
            )
        );
                
        /* Start here getting roles list in array */        
        $getClientArray = $this->Client->find( 'all', $options );       
        $this->set( 'imagePath', $imagePath );
        $this->set( 'getClientArray' , $getClientArray);
        
    }
    
    public function clientdelete( $id = null, $is_deleted = null )
    {
        
        /* Unset view */
        $this->autorender = false;
        
        /* Set custom condition for user appearence */
        if( $is_deleted == 1 )
        {
        
            /* Start here setup delete function */
            $this->Client->updateAll( array( "ClientDesc.is_deleted" => "0", "Client.status" => "1" ), array( "Client.id" => $id ) );
            $msg = "Retreive Successful";
            
        }
        else
        {
            /* Start here setup delete function */
            $this->Client->updateAll( array( "ClientDesc.is_deleted" => "1", "Client.status" => "1" ), array( "Client.id" => $id ) );
            $msg = "Deletion Successful";
        }
        
        /* Redirect action after success */
        $this->Session->setflash( $msg, 'flash_success' );                      
        $this->redirect( array( "controller" => "showall/Client/List" ) );
        
    }
    
    public function actionlocunlock( $id = null, $str = null, $strAction = null )
    {
        
        /* Set here false to render the self view */
        $this->autorender = false;
        
        /* Action perform according active and deactive */
        
        if( $strAction === "CLAction" )
        {            
         
            if( $str === "Deactive" )
            {
                $action = 0;
                $msg = "Active Successful";
            }
            else
            {
                $action = 1;
                $msg = "Deactive Successful";
            }   
            
            $this->Client->updateAll( array( "Client.status" => $action ), array( "Client.id" => $id ) );

            /* Redirect action after success */
            $this->Session->setflash( $msg, 'flash_success' );                      
            $this->redirect( array( "controller" => "showall/Client/List" ) );
            
        }
    }
    
    public function editclient( $id = null )
    {
        
        /*  Layout calling*/
        $this->layout = "index";
        
        /* Set Custom Title here */
        if( isset( $id ) & $id > 0 )
            $this->set( 'role','Edit Client' );
        else
            $this->set( 'role','Add Client' );
                
        /* Start here set the country list */        
        $this->set( 'getLocationArray', $this->Common->getCountryList() );
        
        /* Load Model of state */
        $this->set( 'getStateList', $this->Common->getStateList() );
        
        /* Load Model of state */
        $this->set( 'getCityList', $this->Common->getCityList() );
        
        /* Load Model of warehouse */
        $this->set( 'getWarehouseList', $this->Common->getWarehouseList() );
        
        /* Set other fucntionality for editing the form values and update it accordingly */
        $getClientList = $this->Client->getClientDataById( $id );
        
        /* Set the data over edit view where data would be visible for updating */        
        $this->request->data = $getClientList;        
    }
    
}

?>
