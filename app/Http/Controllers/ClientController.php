<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use Elastica\Client as ElasticaClient;

class ClientController extends Controller
{
    
    //Elastic Search Client
    protected $elasticsearch;

    //Elastica Client
    protected $elastica;

    public function __construct(){

    	$this->elasticsearch = ClientBuilder::create()->build();

    	//Create elastica config
    	$elastica_config = [
    		'host'=>'localhost',
    		'port'=>9200,
    		'index'=>'pets'
    	];

    	$this->elastica = new ElasticaClient($elastica_config);
    }

    public function elasticSearchTest(){
    	
    	dump($this->elasticsearch);

    	//Retreive the document that we indexed

    	echo "\n\n Retreive document\n";

    	$params = [
    		'index'=>'pets',
    		'type'=>'dog',
    		'id'=>1
    	];

    	$response = $this->elasticsearch->get($params);
    	dump($response);
    }
}
