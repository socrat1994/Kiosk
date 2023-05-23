<?php
namespace General;

class Request
{
    public static function get()
    {
        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'POST': $inputs = $_POST; break;
            case 'GET': $inputs = $_GET; break;
            default:
                parse_str(file_get_contents('php://input'), $inputs);
                break;
        }
        foreach ($inputs as $key => $value)
        {
            $safe[$key] = htmlspecialchars($value);
        }
        return $safe??null;
    }

    public function getJson()
    {
       return json_decode(file_get_contents('php://input'), true);
    }

    public function getSantized()
    {
        $inputs = $this->get();
        foreach ($inputs as $key => $value) {
            $key = reg_replace('/[^0-9a-zA-Z_]/', '', $key);
            $value = reg_replace('/[^0-9a-zA-Z_]/', '', $value);
            $output[$key] = $value;
        }
        return $output;
    }
}
?>