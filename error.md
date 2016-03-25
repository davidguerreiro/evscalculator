---
title: Error handling
---

Depending on the request and the conditions, the API would return a number of different errors.
With the exception of successful `DELETE` requests, the API will always return an object with at least **3 properties**.

For example:


```json
{
	"stat": "ok",
	"count": 55,
	"data": [
		{
			"id": 21,
			"name": "Graveler",
			"stat_name": "defense",
			"stat_value": 10,
			"game": 0
		},
		[ … ]
	]
}
```

Both `stat` and `count` will always be available. The values can change depending on the following:

If the request is **successful**.

- `stat` will be `ok`.
- `data` will be available.
- `count` will be the number of elements in `data`.

If the request causes an **error**.

- `stat` will be `error`.
- `data` will NOT be availabe.
- `errors` will be available.
- `count` will be the number of elements in `errors`.

If some of the data is **invalid** (validation).

- `stat` will be `invalid`.
- `data` may be available with only the valid data, depending on the request.
- `errors` will be available with the validation tips.
- `count` will be the number of elements in `errors`.



This way you can assume almost every request will have a `stat` and `count` you can base your program on.
The `count` will help you abstract the length of the array. 

Both `data` and `errors` will return always an object array or a single object (if `count` equals `1`).
