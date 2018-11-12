<?php

/**
 * NoteModel
 * This is basically a simple CRUD (Create/Read/Update/Delete) demonstration.
 */
class TurnosModel
{

    public static function calendar($mes, $ano){
        
        try {
            $fecha = new DateTime($ano . '-' . $mes .'-01');
            $diaDeLaSemana = $fecha->format('N'); 
            $diasEnElMes = $fecha->format('t');

            $return = new stdClass();
            $return->fecha = $fecha;
            $return->diaDeLaSemana = $diaDeLaSemana;
            $return->diasEnElMes = $diasEnElMes;
            $return->turnos = self::getMonthTurnos($mes, $ano);
            $return->comentarios = self::getAllComentarios($mes, $ano);

            return $return;

        } catch (Exception $e) {
            echo $e->getMessage();
            exit(1);
        }
    }
    
    public static function getAllProfesionales()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        if (Session::get("user_account_type") == 6){
            $sql = "SELECT profesional_id, profesional_name, profesional_rut, profesional_telefono, profesional_correo FROM profesionales";
            $query = $database->prepare($sql);
            $query->execute();
        }
        else{
            $profesional_id = self::getIdProfesional(Session::get('user_id'));
            $profesional_id = $profesional_id->profesional_userid;
            $sql = "SELECT DISTINCT profesional_id, profesional_name, profesional_rut, profesional_telefono, profesional_correo FROM profesionales where profesional_id = :profesional_id";
            $query = $database->prepare($sql);
            $query->execute(array(':profesional_id' => $profesional_id));
        }

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

    public static function getAllProfesionalesBasic()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

            $sql = "SELECT profesional_id, profesional_name, profesional_rut, profesional_telefono, profesional_correo FROM profesionales WHERE profesional_userid = 0";
            $query = $database->prepare($sql);
            $query->execute();

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

    public static function getIdProfesional($user_id){

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT profesional_id, profesional_userid FROM profesionales WHERE profesional_userid = :profesional_userid";
        $query = $database->prepare($sql);
        $query->execute(array(':profesional_userid' => $user_id));

        return $query->fetch();
    }

    public static function setIdProfesional($id){
        if (!$id || strlen($id) == 0) {
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE profesionales SET profesional_userid = :profesional_userid WHERE profesional_id = :profesional_id";
        $query = $database->prepare($sql);
        $query->execute(array(':profesional_id' => $id, ':profesional_userid' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }

    /**
     * Get a single keyword
     * @param int $keyword_id id of the specific keyword
     * @return object a single object (the result)
     */
    public static function getProfesional($profesional_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT profesional_id, profesional_name, profesional_rut, profesional_telefono, profesional_correo FROM profesionales WHERE profesional_id = :profesional_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':profesional_id' => $profesional_id));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();
    }

    /**
     * Set a keyword (create a new one)
     * @param string $keyword_text keyword text that will be created
     * @return bool feedback (was the keyword created properly ?)
     */
    public static function createProfesional($name,$rut, $correo,$telefono)
    {
        if (!$name || strlen($name) == 0) {
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO profesionales (profesional_name, profesional_rut, profesional_telefono, profesional_correo) VALUES (:profesional_name, :profesional_rut, :profesional_telefono, :profesional_correo)";
        $query = $database->prepare($sql);
        $query->execute(array(':profesional_name' => $name, ':profesional_rut' => $rut, ':profesional_telefono' => $telefono, ':profesional_correo' => $correo));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }

    /**
     * Update an existing keyword
     * @param int $keyword_id id of the specific keyword
     * @param string $keyword_text new text of the specific keyword
     * @return bool feedback (was the update successful ?)
     */
    public static function updateProfesional($profesional_id, $profesional_nombre, $profesional_rut, $profesional_correo, $profesional_telefono)
    {
        if (!$profesional_id || !$profesional_nombre) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE profesionales SET profesional_name = :profesional_name, profesional_rut = :profesional_rut, profesional_correo = :profesional_correo, profesional_telefono = :profesional_telefono WHERE profesional_id = :profesional_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':profesional_name' => $profesional_nombre, ':profesional_rut' => $profesional_rut, ':profesional_correo' => $profesional_correo, ':profesional_telefono' => $profesional_telefono, ':profesional_id' => $profesional_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

    /**
     * Delete a specific keyword
     * @param int $keyword_id id of the keyword
     * @return bool feedback (was the keyword deleted properly ?)
     */
    public static function deleteProfesional($profesional_id)
    {
        if (!$profesional_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM profesionales WHERE profesional_id = :profesional_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':profesional_id' => $profesional_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_DELETION_FAILED'));
        return false;
    }

    public static function getMonthTurnos($mes, $ano)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $fecha1 = $ano . "-" . $mes . "-" .'-01';
        $fecha = new DateTime($ano . '-' . $mes .'-01');
        $fecha2 = $ano . "-" . $mes . "-" . $fecha->format('t');

        $sql = "SELECT turno_id, turno_profesional, turno_fechain, turno_turno, turno_profesional_nombre FROM turnos WHERE turno_fechain BETWEEN :turno_fechain AND :turno_fechaout";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_fechain' => $fecha1, ':turno_fechaout' => $fecha2));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

    public static function countTurno($mes, $ano, $profesional)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $fecha1 = $ano . "-" . $mes . "-" .'-01';
        $fecha = new DateTime($ano . '-' . $mes .'-01');
        $fecha2 = $ano . "-" . $mes . "-" . $fecha->format('t');

        $sql = "SELECT turno_turno FROM turnos WHERE turno_turno = 0 AND turno_profesional = :profesional AND turno_fechain BETWEEN :turno_fechain AND :turno_fechaout";
        $query = $database->prepare($sql);
        $query->execute(array(':profesional' => $profesional, ':turno_fechain' => $fecha1, ':turno_fechaout' => $fecha2));

        // fetchAll() is the PDO method that gets all result rows
        $dia =  $query->rowCount();

        $sql = "SELECT turno_turno FROM turnos WHERE turno_turno = 1 AND turno_profesional = :profesional AND turno_fechain BETWEEN :turno_fechain AND :turno_fechaout";
        $query = $database->prepare($sql);
        $query->execute(array(':profesional' => $profesional, ':turno_fechain' => $fecha1, ':turno_fechaout' => $fecha2));

        $noche =  $query->rowCount();

        $return = new stdClass();
        $return->conteo = (($dia + $noche) * 12) . ' Hrs ( ' . ($dia + $noche) . ' turnos )';
        return $return;
    }

    public static function getAllTurnos($dia, $mes, $ano)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $fecha = $ano . "-" . $mes . "-" . $dia;
        $sql = "SELECT turno_id, turno_profesional, turno_fechain, turno_turno, turno_profesional_nombre FROM turnos WHERE turno_fechain = :turno_fechain";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_fechain' => $fecha));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

    /**
     * Get a single keyword
     * @param int $keyword_id id of the specific keyword
     * @return object a single object (the result)
     */
    public static function getTurno($id_turno)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT turno_id, turno_profesional, turno_fechain, turno_turno, turno_profesional_nombre FROM turnos WHERE turno_id = :turno_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_id' => $id_turno));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();
    }

    /**
     * Set a keyword (create a new one)
     * @param string $keyword_text keyword text that will be created
     * @return bool feedback (was the keyword created properly ?)
     */
    public static function createTurnos($profesional,$profesional_nombre, $fechainic,$turno)
    {
        if (!$profesional) {
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return 'fuck';
        }

        if ($turno == 2){
            self::createTurnos($profesional,$profesional_nombre, $fechainic,0);
            self::createTurnos($profesional,$profesional_nombre, $fechainic,1);
        }
        else {
            $database = DatabaseFactory::getFactory()->getConnection();

            $sql = "INSERT INTO turnos (turno_profesional, turno_profesional_nombre, turno_fechain, turno_turno) VALUES (:turno_profesional, :turno_profesional_nombre, :turno_fechain, :turno_turno)";
            $query = $database->prepare($sql);
            $query->execute(array(':turno_profesional' => $profesional, ':turno_profesional_nombre' => $profesional_nombre, ':turno_fechain' => $fechainic, ':turno_turno' => intval($turno)));

            if ($query->rowCount() == 1) {
                return true;
            }

            // default return
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return false;
        }
    }

    /**
     * Update an existing keyword
     * @param int $keyword_id id of the specific keyword
     * @param string $keyword_text new text of the specific keyword
     * @return bool feedback (was the update successful ?)
     */
    public static function changeTurnos($turno_id, $turno_profesional, $turno_profesional_nombre)
    {
        if (!$turno_id || !$turno_profesional) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE turnos SET turno_profesional = :turno_profesional, turno_profesional_nombre = :turno_profesional_nombre WHERE turno_id = :turno_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_profesional' => $turno_profesional, ':turno_profesional_nombre' => $turno_profesional_nombre, ':turno_id' => $turno_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

    /**
     * Delete a specific keyword
     * @param int $keyword_id id of the keyword
     * @return bool feedback (was the keyword deleted properly ?)
     */
    public static function deleteTurnos($turno_id)
    {
        if (!$turno_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM turnos WHERE turno_id = :turno_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_id' => $turno_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_DELETION_FAILED'));
        return false;
    }

    public static function getAllComentarios($mes, $ano)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $fecha1 = $ano . "-" . $mes . "-" .'-01';
        $fecha = new DateTime($ano . '-' . $mes .'-01');
        $fecha2 = $ano . "-" . $mes . "-" . $fecha->format('t');

        $sql = "SELECT comentario_id, comentario_fecha, comentario_text FROM comentarios WHERE comentario_fecha BETWEEN :comentario_fechain AND :comentario_fechaout";
        $query = $database->prepare($sql);
        $query->execute(array(':comentario_fechain' => $fecha1, ':comentario_fechaout' => $fecha2));

        // fetchAll() is the PDO method that gets all result rows
        return $query->fetchAll();
    }

    public static function getComentario($comentario_fecha)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT comentario_id, comentario_fecha, comentario_text FROM comentarios WHERE comentario_fecha = :comentario_fecha LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':comentario_fecha' => $comentario_fecha));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();
    }

    public static function createComentario($fecha,$text)
    {
        if (!$fecha) {
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return 'fuck';
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO comentarios (comentario_fecha, comentario_text) VALUES (:comentario_fecha, :comentario_text)";
        $query = $database->prepare($sql);
        $query->execute(array(':comentario_fecha' => $fecha, ':comentario_text' => $text));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }

    public static function updateComentario($comentario_id, $text)
    {
        if (!$comentario_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE comentarios SET comentario_text = :comentario_text WHERE comentario_id = :comentario_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':comentario_text' => $text, ':comentario_id' => $comentario_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

}
