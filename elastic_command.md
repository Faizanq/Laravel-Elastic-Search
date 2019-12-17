**Check indices status

curl localhost:9200/_cat/indices?v

**Inserting record

curl -XPUT localhost:9200/pets/dog/1?pretty -d '{"name":"Susana","age":2,"gender":"female","color":"brown","goodDog":true,"hometown":"Nogal Massachusetts","about":"Ad labories occaecat at elit. Sit consequat velit sitest reprehenderit","registered":"2013-06-11"}'

**Checking Mapping

curl localhost:9200/pets/_mapping/dog/?pretty

**Search Query

curl 'localhost:9200/pets/dog/_search?q=name:susana&pretty'


**Bulk Data Inserting

curl -XPOST localhost:9200/_bulk?pretty -d '

{"index":{"_index":"pets","_type":"dog","_id":10}}
{"name":"Tahera","age":2,"gender":"male","color":"brown","goodDog":false,"hometown":"Nogal Massachusetts","about":"Ad labories occaecat at elit. Sit consequat velit sitest reprehenderit","registered":"2013-07-11"}

{"index":{"_index":"pets","_type":"dog","_id":11}}
{"name":"Sansa","age":3,"gender":"female","color":"white","goodDog":true,"hometown":"Tal Massachusetts","about":"Ad labories occaecat at elit. Sit consequat velit sitest reprehenderit","registered":"2013-08-11"}
'
*Note: Above new line must after the json to avoid(Validation Failed: 1: no requests added;) error

**Count Query

curl 'localhost:9200/pets/dog/_count?q=name:susana&pretty'


**DSL Query

1)

{
  "query": {
    "bool": {
      "must": [
        {
          "match": {
            "about":"labories" 
          }
        }
      ],
      "must_not": [
      ],
      "should": [
      ]
    }
  },
  "from": 0,
  "size": 10
}

2)

{
  "query": {
    "bool": {
      "must": [
        {
          "match": {
            "about":"labories" 
          }
        }
      ],
      "must_not": [
      ],
      "should": [
        {
          "term":{
            "gender":"male" 
          }
        },
        {
          "term":{
            "goodDog":true
          }
        }
      ]
    }
  },
  "from": 0,
  "size": 10
}