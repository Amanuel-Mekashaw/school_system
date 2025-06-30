require_once __DIR__ . '/../controllers/StudentController.php';
require_once __DIR__ . '/../controllers/TeacherController.php';
require_once __DIR__ . '/../controllers/SubjectController.php';

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

switch ($path) {
    case '/api/students':
        routeResource(new StudentController(), 'students');
        break;
    case '/api/teachers':
        routeResource(new TeacherController(), 'teachers');
        break;
    case '/api/subjects':
        routeResource(new SubjectController(), 'subjects');
        break;
    default:
        // existing /api/communication-records routing here
        break;
}

