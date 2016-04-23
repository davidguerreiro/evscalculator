---
title: The request
implemented: true
---

There are some common properties to every request in the API you should know about.

## Sorting

You can sort by a property on any request that returns an object array using the `sort` parameter. 
If you want to reverse the result, you can add a `-` to the beggining of that property. For example: 

```
GET {{ site.api_url }}/v1/hordes?sort=-id
```

In this case the response would be sorted by ID in descendant order. In the future, `sort` will support sorting by multiple fields as a list of comma-separated field list.

## JSONP

The API supports JSONP for old browser support on every valid endpoint. To get the JSONP response you must:

* Specify a **callback** parameter, which must be a string.
* Use JSON format or no format at all (fallbacks to JSON).

The response will then have a `application/javascript` MIME type.

### Example request

`GET {{ site.api_url }}/v1/hordes?callback=foo`

### Example response

```json
foo({  
   "stat":"ok",
   "count":55,
   "data":[  
      {  
         "id":21,
         "name":"Graveler",
         "stat_name":"defense",
         "stat_value":10,
         "game":0
      },
      [ … ]
   ]
})
```