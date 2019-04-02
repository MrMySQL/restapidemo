# rest api demo

### Sign Up

#### Path
```POST /auth/signup```

#### Request body
| Parameters|Description|Requirements|
| ---|---|---|
| email | user email | <ul><li>not null</li><li>valid email</li></ul>|
| password | user pass | not null |

#### Example
```
{
  "email": "example@email.com",
  "password": "pass"
}
```

#### Response
Return token as string
```
5ca363413e601
```

### Sign In

#### Path
```POST /auth/signin```

#### Request body
| Parameters|Description|Requirements|
| ---|---|---|
| email | user email | <ul><li>not null</li><li>valid email</li></ul>|
| password | user pass | not null |

#### Example
```
{
  "email": "example@email.com",
  "password": "pass"
}
```

#### Response
Return token as string
```
5ca363413e601
```

### Create Task

#### Path
```POST /tasks/new```

#### Request body
| Parameters|Description|Requirements|
| ---|---|---|
| title | task title | <ul><li>not null</li><li>valid email</li></ul>|
| due | due date | string parsable with PHP, like "*tomorrow*" |
| priority | task priority | one of these: low, normal, high |

#### Example
```
{
	"title": "title",
	"due": "tomorrow",
	"priority": "normal"
}
```

#### Response
Returns created task
```
{
  "id": "21",
  "title": "title",
  "due": "2019-04-03 00:00:00",
  "priority": "normal",
  "done": null
}
```



### Get All User Tasks

#### Path
```GET /tasks/```

#### Authorization
Bearer based on token, taken while Sign In / Sign Up

Header:

```Authorization: Bearer 5ca363413e601```

#### Parameters

Next parameters are available:

| Parameter Name | Values |
| --- | --- |
|orderby|title, due, priority|
|direction|asc, desc|
|page|some positive integer|

Example:
```GET /tasks?orderby=title&direction=asc&page=1```

#### Response
Returns all user tasks
```
[
  {
    "id": "21",
    "title": "title1",
    "due": "2019-04-03 00:00:00",
    "priority": "normal",
    "done": "1"
  },
  {
    "id": "31",
    "title": "title2",
    "due": "2019-04-03 00:00:00",
    "priority": "normal",
    "done": null
  }
]
```



### Mark task as "Done"

#### Path
```POST /tasks/done```

#### Request body
| Parameters|Description|Requirements|
| ---|---|---|
| id | task id | positive integer|

#### Example
```
{
	"id": 1
}
```

#### Response
Returns true or false in case of error. Actually returns true even if ID does not exist.
```
true
```


### Delete Task

#### Path
```POST /tasks/delete```

#### Request body
| Parameters|Description|Requirements|
| ---|---|---|
| id | task id | positive integer|

#### Example
```
{
	"id": 1
}
```

#### Response
Returns true or false in case of error. Actually returns true even if ID does not exist.
```
true
```

### Error response example
Every response can return an error having next template:
```
{
  "status": "error",
  "messages": [
    "Message one",
    "Message two"
  ]
}
```