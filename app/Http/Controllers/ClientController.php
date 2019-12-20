<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Elasticsearch\ClientBuilder;
use Elastica\Client as ElasticaClient;
use Elastica;
use Faker\Factory as Faker;

class ClientController extends Controller
{
    
    //Elasticsearch Client
    protected $elasticsearch;

    //Elastica Client
    protected $elastica;

    //Elastica index
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

    //Data structure of Elasticsearch client
    public function ElasticSearchData(){

    	$params = [
    		'index'=>'pets',
    		'type'=>'birds',
    		'body'=>[
    			'_source'=>[
    				'enabled'=>true,
    			],
    			'properties'=>[
    				'name'			=>array('type'=>'string'),
    				'age' 			=>array('type'=>'long'),
    				'gender'		=>array('type'=>'string'),
    				'color'			=>array('type'=>'string'),
    				'braveBird'		=>array('type'=>'boolean'),
    				'homeTown'		=>array('type'=>'string'),
    				'about'			=>array('type'=>'text'),
    				'registered'	=>array('type'=>'date'),
    			]
    		]
    	];

    	//Define the mapping
    	// $response = $this->elasticsearch->indices()->putMapping($params);

    	// dump($response);

    	$params = [
    		'index'=>'pets',
    		'type' =>'birds'
    	];
    	//Print the mapping
    	$mapping = $this->elasticsearch->indices()->getMapping($params);

    	dump($mapping);

    	// $params = [
    	// 	'index'=>'pets',
    	// 	'type'=>'birds',
    	// 	'id'=>'1',
    	// 	'body'=>[
    	// 		    'name'		=>'Paroat',
    	// 			'age' 		=>2,
    	// 			'gender'	=>'male',
    	// 			'color'		=>'green',
    	// 			'braveBird'	=>true,
    	// 			'homeTown'	=>'India',
    	// 			'about'		=>'Very funny and brave birds and inteligence',
    	// 			'registered'=>date('Y-m-d'),
    	// 	]
    	// ];

    	// $response = $this->elasticsearch->index($params);
    	// dump($response);

    	//Lets use Faker for genarting fake data
    	$faker = Faker::create();

    	$params = [];

    	for ($i=0; $i < 100; $i++) { 
    		$params['body'][] = [
    			'index'=>[
	    				'_index'=>'pets',
	    				'_type'=>' birds'
	    			]	
	    		];

	    	$gender = $faker->randomElement(['male','female']);
	    	$age = $faker->numberBetween(1,15);

	    	$params['body'][] = [
	    		'name'		=>$faker->name($gender),
				'age' 		=>$age,
				'gender'	=>$gender,
				'color'		=>$faker->safeColorName(),
				'braveBird'	=>$faker->boolean,
				'homeTown'	=>"{$faker->city}, {$faker->state}",
				'about'		=>$faker->realText(),
				'registered'=>$faker->dateTimeBetween("-{$age} year","now")->format('Y-m-d'),
	    	];
    	}//for end here

    	$response = $this->elasticsearch->bulk($params);
    	dump($response);

    }

    //Data structure of Elastica client
    public function ElasticaData(){

    	$catType = $this->elasticaIndex->getType('cat');

    	$mapping = new Elastica\Type\Mapping($catType,[
    				'name'			=>array('type'=>'string'),
    				'age' 			=>array('type'=>'long'),
    				'gender'		=>array('type'=>'string'),
    				'color'			=>array('type'=>'string'),
    				'prettyKitty'	=>array('type'=>'boolean'),
    				'homeTown'		=>array('type'=>'string'),
    				'about'			=>array('type'=>'text'),
    				'registered'	=>array('type'=>'date'),
    	]);

    	// $response = $mapping->send();
    	// dump($response);

    	//Let's store the data into Document
	  	$catDocument = new Elastica\Document();

    	$catDocument->setData([
    		    	'name'		=>'Mossi',
    				'age' 		=>2,
    				'gender'	=>'male',
    				'color'		=>'green',
    				'prettyKitty'	=>true,
    				'homeTown'	=>'India',
    				'about'		=>'Very funny and brave Kitty and inteligence',
    				'registered'=>date('Y-m-d'),
    	]);

    	$response = $catType->addDocument($catDocument);
    	dump($response);

    	$faker = Faker::create();

    	$documents = [];

    	for ($i=0; $i < 100; $i++) { 

	    	$gender = $faker->randomElement(['male','female']);
	    	$age = $faker->numberBetween(1,15);

	    	$documents[] = (new Elastica\Document())->setData([
	    		'name'		=>$faker->name($gender),
				'age' 		=>$age,
				'gender'	=>$gender,
				'color'		=>$faker->safeColorName(),
				'prettyKitty'=>$faker->boolean,
				'homeTown'	=>"{$faker->city}, {$faker->state}",
				'about'		=>$faker->realText(),
				'registered'=>$faker->dateTimeBetween("-{$age} year","now")->format('Y-m-d'),
	    	]);
    	}//for end here

    	$response = $catType->addDocuments($documents);
    	dump($response);

    }

    //Simple Ealstic search queries
    public function ElasticSearchQuries(){

    	$params = [
    		'index'=>'pets',
    		'type'=>'birds',
    		'body'=>[
    			'query'=>[
    				'match'=>[
    					'name'=>'Paroat'
    				]
    			]
    		]
    	];

    	$response = $this->elasticsearch->search($params);
    	dump($response);


    	$params = [
    		'index'=>'pets',
    		'type'=>'birds',
    		'size'=>15,
    		'body'=>[
    			'query'=>[
    				'match'=>[
    					'about'=>'funny'
    				]
    			]
    		]
    	];

    	$response = $this->elasticsearch->search($params);
    	dump($response);

    	//Boolean Query

    	$params = [
    		'index'=>'pets',
    		'type'=>'birds',
    		'size'=>15,
    		'body'=>[
    			'query'=>[
	    			'bool'=>[
	    				'must'=>[
	    					'match'=>[
	    						'name'=>'Paroat'
	    					]
	    				],
	    				'should'=>[
	    					'term'=>[
	    						'braveBird'=>true
	    					],
	    					'term'=>[
	    						'gender'=>'male'
	    					]
	    				],
	    				'filter'=>[
	    					'range'=>[
	    						'registered'=>[
	    							'gte'=>'2015-12-12'
	    						]
	    					]
	    				]
	    			]
	    		]
    		]
    	];

    	$response = $this->elasticsearch->search($params);
    	dump($response);

    }

    //Simple Elastica Search Quries
    public function ElasticaQuries(){

    		//Get the Type 
    		$catType = $this->elasticaIndex->getType('cat');

    		//Create Query Object
    		$query = new Elastica\Query;

    		//Create match query Object
    		$match = new Elastica\Query\Match('name','MD');

    		//Now set this match query into Query object
    		$query->setQuery($match);

    		//Now search the cat Document through CatType
    		$response = $catType->search($query);
    		dump($response);

    		//Run query on about field

    		$query = new Elastica\Query;

    		//Create match query Object
    		$match = new Elastica\Query\Match;
    		$match->setField('about','Alice');
    		$query->setSize(15);

    		//Now set this match query into Query object
    		$query->setQuery($match);

    		//Now search the cat Document through CatType
    		$response = $catType->search($query);
    		dump($response);


    		//Run Bool Query

    		$query = new Elastica\Query;

    		//Create match query Object
    		$bool = new Elastica\Query\BoolQuery;
    		$mustMatch = new Elastica\Query\Match('name','MD');

    		$shouldOne = new Elastica\Query\Term(['prettyKitty'=>true]);
    		$shouldTwo = new Elastica\Query\Term(['gender'=>'male']);

    		$filterRange = new Elastica\Query\Range('registered',['gte'=>'2018-02-02']);

    		$bool->addMust($mustMatch);
    		$bool->addShould($shouldOne);
    		$bool->addShould($shouldTwo);
    		$bool->addFilter($filterRange);

    		$query->setQuery($bool);

    		//Now search the cat Document through CatType
    		$response = $catType->search($query);
    		dump($response);

    }

}
