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

    //Make elastica index vaiable
    protected $elasticaIndex;


    public function __construct(){

    	$this->elasticsearch = ClientBuilder::create()->build();

    	//Create elastica config
    	$elastica_config = [
    		'host'=>'localhost',
    		'port'=>9200,
    		'index'=>'pets'
    	];

    	$this->elastica = new ElasticaClient($elastica_config);

    	$this->elasticaIndex = $this->elastica->getIndex('pets');
    }

    //test ElasticSearch Client
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

    //test the Elastica Client
    public function ElasticaTest(){

    	//print the elastica Object
    	dump($this->elastica);

    	//print the elasticIndex
    	dump($this->elasticaIndex);

    	echo "\n\n Get type and mapping\n";

    	$dogType = $this->elasticaIndex->getType('dog');

    	dump($dogType->getMapping());

    	//Retreive the document that we inserted

    	echo "\n\n Retrive the document\n";

    	$response = $dogType->getDocument('1');

    	dump($response);
    }
}
