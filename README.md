# Api Rest Excersise

## Requeriments

Create a REST API webservice that can manage files according to the following:

* View list of files
* Create a new file
* Upload a file
* Limit the file to 500kb
* Delete file logically
* Delete file physically
* Upload files in bulk

## Considerations:

* Endpoint usage must be token protected.
* Usage must be accounted for
* Limit to 3 queries per minute

# Solution: Upload Api rest Example APP

Please configure your virtual host to *src* folder, this folder contains the laravel project, if you need up this app in docker, need up my docker server service first https://github.com/xbust3r/docker-server 

## Upload a File
`POST /api/v1/file/upload/`

#### Type Data
`BODY form-data`

#### Data

| Field Name | Field Type |  Description   |
| :---         |     :---:      |:--------------:|
| file| file   | file max 500k  |


#### Response

    HTTP/1.1 200 OK
    Date: Mon, 24 Feb 2022 12:36:30 GMT
    Status: 200 OK
    Connection: close
    Content-Type: application/json
	
	{"success":true,"message":"Your file has been uploaded successfully","data":{"id":8}}

## Upload a Multiples Files
`POST /api/v1/file/upload/multiple`

#### Type Data
`BODY form-data`

#### Data

| Field Name | Field Type |    Description     |
|:-----------|     :---:      |:------------------:|
| file[]     | Array file   | each file max 500k |

#### Response

    HTTP/1.1 200 OK
    Date: Mon, 24 Feb 2022 12:36:30 GMT
    Status: 200 OK
    Connection: close
    Content-Type: application/json
	
	{"success":true,"message":"Your files has been uploaded successfully","data":{"ids":[6,7]}}

### Delete File
`DELETE /api/v1/file/upload/<id>`

#### Type Data
`BODY raw

#### Data

| Field Name | Field Type |                         Description                          |
|:-----------|:----------:|:------------------------------------------------------------:|
| erase          |   boolean    | Optional - send a *true* value for erase the file physically |

#### Response

    HTTP/1.1 200 OK
    Date: Mon, 24 Feb 2022 12:36:30 GMT
    Status: 200 OK
    Connection: close
    Content-Type: application/json
	
	{"success":true,"message":"We are delete and erase your upload register"}

## List FIles
`GET /api/v1/file/upload`

#### Response

    HTTP/1.1 200 OK
    Date: Mon, 24 Feb 2022 12:36:30 GMT
    Status: 200 OK
    Connection: close
    Content-Type: application/json
	
	{"success":true,"data":[{"id":1,"name":"1666600087_landings format.xlsx","description":null,"file":"http://upload.test/uploads/1/1666600087_landings format.xlsx"},{"id":2,"name":"1666600104_landings format.xlsx","description":null,"file":"http://upload.test/uploads/1/1666600104_landings format.xlsx"}]}

## GET FIle
`GET /api/v1/file/upload/<id>`

#### Response

    HTTP/1.1 200 OK
    Date: Mon, 24 Feb 2022 12:36:30 GMT
    Status: 200 OK
    Connection: close
    Content-Type: application/json
	
	{"success":true,"data":{"id":1,"name":"1666600087_landings format.xlsx","description":null,"file":"http:\/\/upload.test\/uploads\/1\/1666600087_landings format.xlsx"}}

