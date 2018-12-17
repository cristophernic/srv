<?php

class TurnosController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();

        // VERY IMPORTANT: All controllers/areas that should only be usable by logged-in users
        // need this line! Otherwise not-logged in users could do actions. If all of your pages should only
        // be usable by logged-in users: Put this line into libs/Controller->__construct
        Auth::checkAuthentication();
    }

    public function api()
    {
        $accion = Request::post('accion');
        $resultado = "";
        switch ($accion) {
            case "calendario":
                $resultado = TurnosModel::calendar(Request::post('mes'),Request::post('ano'));
                break;
            case "profesionales":
                $resultado = UserModel::getPublicProfilesOfAllUsers();
                break;
            case "turnos":
                $resultado = TurnosModel::getAllTurnos(Request::post('dia'),Request::post('mes'), Request::post('ano'));
                break;
            case "turnosUno":
                $resultado = TurnosModel::getTurno(Request::post('id'));
                break;
            case "turnosNuevo":
                $resultado = TurnosModel::createTurnos(Request::post('profesional'),Request::post('fechainic'),Request::post('turno'));
                break;
            case "turnosEliminar":
                $resultado = TurnosModel::deteleTurnos(Request::post('id'));
                break;
            case "turnosCambiar":
                $resultado = TurnosModel::changeTurnos(Request::post('id'), Request::post('profesional'), Request::post('profesional_nombre'));
                break;
            case "comentario":
                $resultado = TurnosModel::getComentario(Request::post('fecha'));
                break;
            case "comentarioGuardar":
                $resultado = TurnosModel::createComentario(Request::post('fecha'), Request::post('text'));
                break;
            case "comentarioUpdate":
                $resultado = TurnosModel::updateComentario(Request::post('id'), Request::post('text'));
                break;
            case "sumaturnos":
                $resultado = TurnosModel::countTurno(Request::post('mes'), Request::post('ano'), Request::post('profesional'));
                break;
            case "user_id_profesional":
                $resultado = TurnosModel::getIdProfesional(Session::get('user_id'));
                break;
            case "profesionalBasic":
                $resultado = TurnosModel::getAllProfesionalesBasic();
                break;
            case "user_id_set":
                $resultado = TurnosModel::setIdProfesional(Request::post('id'));
                break;
            case "contrasena":
                $resultado = PasswordResetModel::changePassword(Session::get('user_id'), Request::post('user_password_current'), Request::post('user_password_new'), Request::post('user_password_repeat'));
                break;
            case "email":
                $resultado = UserModel::editUserEmail(Request::post('user_email'));
                break;
            case "nombre":
                $resultado = UserModel::saveUserName(Request::post('user_nombre'));
                break;
            case "telefono":
                $resultado = UserModel::saveTelefono(Request::post('user_telefono'));
                break;
            case "departamentos":
                $resultado = DepartementosModel::getAllDepartamentos();
                break;
            case "departamentosNuevo":
                $resultado = DepartementosModel::createDepartamento($departamento_text);
                break;
        }
        return $this->View->renderJSON($resultado);

    }
}