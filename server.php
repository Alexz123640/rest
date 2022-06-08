<?php 
/*HTTP authentication
$user =array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '';
$pass =array_key_exists('PHP_AUTH_PW', $_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';
if($user !== 'admin' && $pass !== 'admin'){
    die('Access denied');
}*/

/*autehntication HMAC
if(
    !array_key_exists('HTTP_X_HASH', $_SERVER) ||
    !array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) ||
    !array_key_exists('HTTP_X_UID', $_SERVER)
){
    die('Access denied');
}
list($hash, $timestamp, $uid) = 
[$_SERVER['HTTP_X_HASH'], $_SERVER['HTTP_X_TIMESTAMP'], $_SERVER['HTTP_X_UID']];

$secret= 'shhh';
$newHash=sha1($timestamp.$secret.$uid);
*/
header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
//DEFINIMOS LOS RECURSOS DISPONIBLES
$allowedResourceTypes=[
    'books',
    'authors',
    'genres'
];
//VALIDAMOS QUE EL RECURSO ESTE DISPONIBLE
$resourceType=$_GET['resource_type'];

 //VALIDAMOS QUE EL RECURSO SEA UNO DE LOS PERMITIDOS
if( !in_array($resourceType, $allowedResourceTypes) ){
    
    http_response_code(404);
    die('Invalid resource type');
}
//DEFINIMOS LOS RECURSOS
$books=[
    1=>[
        'title'=>'La Odisea',
        'id_autor'=>1,
        'genre_id'=>1
    ],
    2=>[
        'title'=>'La Oleada',
        'id_autor'=>2,
        'genre_id'=>1
    ],
];
header('Content-Type: application/json');
$resourceId=array_key_exists('resource_id',$_GET)?$_GET['resource_id']:'';

//DEFINIMOS LA ACCION A REALIZAR
switch(strtoupper($_SERVER['REQUEST_METHOD'])){
    case 'GET':
        if( empty( $resourceId )){        
        echo json_encode($books);
        }
        else{
            if(array_key_exists($resourceId,$books)){
                echo json_encode($books[$resourceId]);}
        }
        break;
    case 'POST':
        /*curl -X “POST” http://localhost:8000/books -d “{”“title”":"“Nuevo Libro”","“id_autor”":1,"“genre_id”":2}"*/
        $json = file_get_contents('php://input');//obtenemos el json que nos llega
        $books[] = json_decode($json,true);//lo decodificamos y lo guardamos en el array
        //echo array_keys($books)[count($books)-1];
        echo json_encode($books);
        break;
    case 'PUT':
        if( !empty($resourceId) && array_key_exists($resourceId,$books)){
            $json = file_get_contents('php://input');
            $books[$resourceId] = json_decode($json,true);
            echo json_encode($books);//retomamos la coleccion de libros
        }
        break;
    case 'DELETE':
        $verb = 'DELETE';
        break;
    default:
        $verb = 'UNKNOWN';
        break;
}