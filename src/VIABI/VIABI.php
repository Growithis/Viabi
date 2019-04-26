<?php 
namespace VIABI;

  
  class VIABI
  {
      
      public $datatab;
      public $action;
      public $query;
      public $userid;
      public $params;
      public $contexts;
      
      public $speech;
      public $display;
      public $outputContext = array();
      
  
        function setApiKey($key)
        {

            $headers = apache_request_headers();

            if(isset($headers['Authorization'])){
          $matches = array();
          preg_match('/Token token="(.*)"/', $headers['Authorization'], $matches);
          if(isset($matches[1]))
          {
            $token = $matches[1];

            //echo 'ok';

            if($token == $key)
            {
                //echo 'yes';
                
                  $this->listen();

            }
            else
            {
                echo 'API key doesnt match';
            }

          }
          else
          {
              echo 'API key doesnt match';
          }
        }
        else
        {
            echo 'API key doesnt match';
        }



        }
        
        
        function listen()
        {
            $update_response = file_get_contents("php://input");
            $this->datatab = json_decode($update_response, true);
            
            $this->action = $this->datatab['queryResult']['action'];
            $this->query = $this->datatab['queryResult']['queryText'];
            $this->params = $this->datatab['queryResult']['parameters'];
            $this->contexts = $this->datatab['queryResult']['outputContexts'];
            //$this->contexts = $this->datatab['queryResult']['parameters'];
            
            
            //print_r($this->datatab);
        }
        
        
        function getUserId()
        {
            
            //$this->datatab['session'];
            //file_put_contents("test.txt", $this->datatab['session']);
            $id=0;
            $t=explode(";", $this->datatab['session']);
            $db = "";

            if(count($t)>1)
            {
                $t2=array();
                 $this->userid=$t[1];
            }
            else if(count($t) > 2)
            {
                $t2=array();
                $this->userid=$t[1];
                $db = $t[2];
                $t2="";
            }
            else
            {
                $t2="";
            }
            
            return $this->userid;
        }
        
        function getAction()
        {
            return $this->action;
        }
        
        function getQuery()
        {
            return $this->query;
        }
        
        function getParameters()
        {
            return $this->params;
        }
        
        function getContexts()
        {
            return $this->getContexts();
        }
        
        function setSpeech($text)
        {
            $this->speech= $text;
        }
        
        
        
        function display($html)
        {
            $this->display=$html;
        }

        function addContext($contextname,$lifespan)
        {
            
            array_push($this->outputContext,array("name"=>$this->datatab['session'].'/contexts/'.$contextname,'lifespanCount'=>$lifespan));

        }
        
        function response()
        {
            $reponse['speech']=$this->speech;
            $reponse['displayText']=$this->display;
            $reponseDialogFlow = json_encode($reponse);
            $tab['fulfillmentText']=$reponseDialogFlow;
            $tab['outputContexts'] = $this->outputContext;

            echo json_encode($tab,JSON_UNESCAPED_UNICODE);
        }
        
        
        
        
  }
