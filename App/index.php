<?php
namespace App;

// Incluindo o autoload do Composer e importando classes necessárias
require "../vendor/autoload.php";
use App\Model\Cliente;
use App\Repository\ClienteRepository;

// Definindo os cabeçalhos para permitir requisições de outros servidores
header("Access-Control-Allow-Origin: *"); // Permite requisições de outros servidores
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Roteamento de acordo com o método da requisição
switch ($_SERVER['REQUEST_METHOD']) {
    // OPTIONS
    case 'OPTIONS':
        // Retornando a lista de métodos permitidos
        $allowed_methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
        http_response_code(200);
        echo json_encode($allowed_methods);
        break;

    // POST
    case 'POST':
        // Campos obrigatórios para criar um cliente
        $requiredFields = ['nome', 'email', 'cidade', 'estado'];
        // Obtendo os dados da requisição POST
        $data = json_decode(file_get_contents("php://input")); 
        
        // Verificando se os dados são válidos
        if (!isValid($data, $requiredFields)) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Dados de entrada inválidos."]);
            break;
        }

        // Criando um novo objeto Cliente
        $cliente = new Cliente();
        // Definindo os dados do cliente
        $cliente->setNome($data->nome);
        $cliente->setEmail($data->email);
        $cliente->setCidade($data->cidade);
        $cliente->setEstado($data->estado);

        // Instanciando o repositório do cliente e inserindo os dados
        $repository = new ClienteRepository();
        $success = $repository->insert($cliente);
        
        // Retornando a resposta adequada
        if ($success) {
            http_response_code(201); // Created
            echo json_encode(["message" => "Dados inseridos com sucesso."]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Falha ao inserir dados."]);
        }
        break;

    // GET
    case 'GET':
        try{
            // Instanciando um novo cliente e o repositório
            $cliente = new Cliente();
            $repository = new ClienteRepository();

            // Verificando se foi passado um ID na requisição
            if (isset($_GET['id'])) {
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

                // Verificando se o ID fornecido é válido
                if ($id === false) {
                    http_response_code(400); // Bad Request
                    echo json_encode(['error' => 'O valor do ID fornecido não é um inteiro válido.']);
                    exit;
                } else {
                    // Buscando cliente pelo ID
                    $cliente->setClienteId($id);
                    $result = $repository->getById($cliente);
                }
            } else {
                // Buscando todos os clientes
                $result = $repository->getAll();
            }

            // Retornando a resposta adequada
            if ($result) {
                http_response_code(200); // OK
                echo json_encode($result);
            } else {
                http_response_code(404); // Not Found
                echo json_encode(["message" => "Nenhum dado encontrado."]);
            }
        } catch(Exception $error){
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Erro: " . $error->getMessage()]);
        }

        break;

    // PUT
    case 'PUT':
        // Obtendo os dados da requisição PUT
        $data = json_decode(file_get_contents("php://input"));

        // Campos obrigatórios para atualizar um cliente
        $requiredFields = ['nome', 'email', 'cidade', 'estado'];
        
        // Verificando se os dados são válidos
        if (!isValid($data, $requiredFields)) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Dados de entrada inválidos."]);
            break;
        }

        // Criando um novo objeto Cliente
        $cliente = new Cliente();
        $repository = new ClienteRepository();
        
        // Definindo os dados do cliente
        $cliente->setNome($data->nome);
        $cliente->setEmail($data->email);
        $cliente->setCidade($data->cidade);
        $cliente->setEstado($data->estado);

        // Verificando se foi fornecido um ID na requisição
        if(isset($data->cliente_id)){
            $cliente->setClienteId($data->cliente_id);

            // Verificando se o cliente com o ID fornecido existe
            if($repository->getById($cliente)){
                // Atualizando os dados do cliente
                $success = $repository->update($cliente);
                
                // Retornando a resposta adequada
                if ($success) {
                    http_response_code(200); // OK
                    echo json_encode(["message" => "Dados atualizados com sucesso."]);
                } else {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(["message" => "Falha ao atualizar dados."]);
                }
            } else { 
                http_response_code(404); // Not Found
                echo json_encode(["message" => "Falha ao atualizar, nenhum dado encontrado."]);
            }
        } else { 
            // Caso não tenha sido fornecido um ID, cria um novo cliente
            $success = $repository->insert($cliente);
            
            // Retornando a resposta adequada
            if ($success) {
                http_response_code(200); // OK
                echo json_encode(["message" => "Dados inseridos com sucesso."]);
            } else {
                http_response_code(500); // Internal Server Error
                echo json_encode(["message" => "Falha ao inserir dados."]);
            }
        }
        
        break;

    // DELETE
    case 'DELETE':
        // Obtendo os dados da requisição DELETE
        $data = json_decode(file_get_contents("php://input")); 
        // Campos obrigatórios para a exclusão de um cliente
        $requiredFields  = ['id'];

        // Verificando se os dados são válidos
        if (!isValid($data, $requiredFields)) {
            http_response_code(400); // Bad Request
            echo json_encode(["error" => "Dados de entrada inválidos."]);
            break;
        }

        // Obtendo o ID do cliente a ser excluído
        $id = $data->id;

        // Criando um novo cliente
        $cliente = new Cliente();
        $cliente->setClienteId($id);

        // Instanciando o repositório do cliente
        $repository = new ClienteRepository();
        // Buscando o cliente pelo ID
        $result = $repository->getById($cliente);

        // Verificando se o cliente existe
        if(!$result){
            http_response_code(404); // Not Found
            echo json_encode(["message" => "Nenhum dado encontrado."]);
        }
        
        // Removendo o cliente
        $success = $repository->delete($cliente);

        // Retornando a resposta adequada
        if ($success) {
            http_response_code(200); // OK
            echo json_encode(["message" => "Dados apagados com sucesso."]);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(["message" => "Falha ao apagar dados."]);
        }

        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(["error" => "Método não permitido."]);
        break;
}

// Função para validar se os campos obrigatórios estão presentes nos dados
function isValid($data, $requiredFields) {
    foreach ($requiredFields as $field) {
        if (!isset($data->$field)) {
            return false;
        }
    }
    return true;
}
               
