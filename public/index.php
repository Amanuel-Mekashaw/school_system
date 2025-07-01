<?php require_once __DIR__ . '/../controllers/StudentController.php';
require_once __DIR__ . '/../controllers/TeacherController.php';
require_once __DIR__ . '/../controllers/SubjectController.php';
require_once __DIR__ . '/../controllers/CommunicationController.php';

function routeResource($controller, $resource) {
    $id = $_GET['id'] ?? null;
    $input = json_decode(file_get_contents("php://input"), true);

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            return $id ? $controller->getOne($id) : $controller->getAll();
        case 'POST':
            return $controller->create($input);
        case 'PUT':
            return $controller->update($id, $input);
        case 'DELETE':
            return $controller->delete($id);
        default:
            http_response_code(405);
            echo json_encode(["error" => "Method not allowed"]);
    }
}
// echo $_SERVER['REQUEST_URI'];


// $path = $_SERVER['REQUEST_URI'];

$uri = $_SERVER['REQUEST_URI'];
$parsed = parse_url($uri);
$path = $parsed['path'];
$method = $_SERVER['REQUEST_METHOD'];



switch ($path) {
    case '/school_system/public/index.php/api/students':
        routeResource(new StudentController(), 'students');
        break;

    case '/school_system/public/index.php/api/teachers':
        routeResource(new TeacherController(), 'teachers');
        break;

    case '/school_system/public/index.php/api/subjects':
        
        routeResource(new SubjectController(), 'subjects');
        break;

    case '/school_system/public/index.php/api/communication-records':
        $controller = new CommunicationController();

        if ($method === 'GET') {
            $controller->getFilteredRecord($_GET);
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $controller->create($data); // assuming you have this
        } elseif ($method === 'PUT') {
            parse_str(file_get_contents("php://input"), $data);
            $controller->update($_GET['id'], $data);
        } elseif ($method === 'DELETE') {
            $controller->delete($_GET['id']);
        } else {
            http_response_code(405);
            echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Route not found"]);
        break;
}

/* switch ($path) {
    case '/school_system/public/index.php/api/students':
        routeResource(new StudentController(), 'students');
        break;
    case '/school_system/public/index.php/api/teachers':
        routeResource(new TeacherController(), 'teachers');
        break;
    case '/school_system/public/index.php/api/subjects':
        routeResource(new SubjectController(), 'subjects');
        break;
    case '/school_system/public/index.php/api/communication-records':
        $controller = new CommunicationController();
        $controller->getFilteredRecord($_GET);
        break;
    default:
        // existing /api/communication-records routing here
        break;
}
*/
