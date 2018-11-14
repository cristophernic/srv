<?php

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

    public static function getMonthTurnos($mes, $ano)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $fecha1 = $ano . "-" . $mes . "-" .'-01';
        $fecha = new DateTime($ano . '-' . $mes .'-01');
        $fecha2 = $ano . "-" . $mes . "-" . $fecha->format('t');

        $sql = "SELECT turnos.turno_id, turnos.turno_profesional, turnos.turno_fechain, turnos.turno_turno, users.user_nombre FROM turnos INNER JOIN users ON turnos.turno_profesional = users.user_id WHERE turnos.turno_fechain BETWEEN :turno_fechain AND :turno_fechaout";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_fechain' => $fecha1, ':turno_fechaout' => $fecha2));

        return $query->fetchAll();
    }

    public static function getProfesionalesTurnos($fecha)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT turno_id, turno_profesional, turno_fechain, turno_turno FROM turnos WHERE turno_fechain = :turno_fechain";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_fechain' => $fecha));

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
        $sql = "SELECT turnos.turno_id, turnos.turno_profesional, turnos.turno_fechain, turnos.turno_turno, users.user_nombre FROM turnos WHERE turno_fechain = :turno_fechain INNER JOIN users ON turnos.turno_profesional = users.user_id";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_fechain' => $fecha));

        return $query->fetchAll();
    }

    public static function getTurno($id_turno)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT turnos.turno_id, turnos.turno_profesional, turnos.turno_fechain, turnos.turno_turno, users.user_nombre FROM turnos INNER JOIN users ON turnos.turno_profesional = users.user_id WHERE turno_id = :turno_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_id' => $id_turno));

        return $query->fetch();
    }

    public static function createTurnos($profesional, $fechainic,$turno)
    {
        $return = new stdClass();
        $return->resultado = false;

        if (!$profesional) {
            $return->resultado = false;
        }

        if ($turno == 2){
            self::createTurnos($profesional,$fechainic,0);
            self::createTurnos($profesional,$fechainic,1);
        }
        else {
            $database = DatabaseFactory::getFactory()->getConnection();

            $sql = "SELECT turno_profesional, turno_fechain, turno_turno FROM turnos WHERE turno_profesional = :turno_profesional AND turno_fechain = :turno_fechain AND turno_turno = :turno_turno)";
            $query = $database->prepare($sql);
            $query->execute(array(':turno_profesional' => $profesional, ':turno_fechain' => $fechainic, ':turno_turno' => intval($turno)));

            if ($query->rowCount() == 1) {
                $return->resultado = false;
            }
            else{
                $sql = "INSERT INTO turnos (turno_profesional, turno_fechain, turno_turno) VALUES (:turno_profesional, :turno_fechain, :turno_turno)";
                $query = $database->prepare($sql);
                $query->execute(array(':turno_profesional' => $profesional, ':turno_fechain' => $fechainic, ':turno_turno' => intval($turno)));

                if ($query->rowCount() == 1) {
                    $return->resultado = true;
                }

                $return->resultado = false;
            }
        }

        return $return;
    }

    public static function changeTurnos($turno_id, $turno_profesional, $turno_profesional_nombre)
    {
        if (!$turno_id || !$turno_profesional) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE turnos SET turno_profesional = :turno_profesional WHERE turno_id = :turno_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':turno_profesional' => $turno_profesional, ':turno_id' => $turno_id));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

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

        return $query->fetchAll();
    }

    public static function getComentario($comentario_fecha)
    {
        $return = new stdClass();
        $return->autorizado = false;

        $turnos = self::getProfesionalesTurnos($comentario_fecha);

        foreach ($turnos as $turno) {
            if ($turno->turno_profesional == Session::get('user_id')){
                $return->autorizado = true;
            }
        }

        if ($return->autorizado){
            $database = DatabaseFactory::getFactory()->getConnection();

            $sql = "SELECT comentario_id, comentario_fecha, comentario_text FROM comentarios WHERE comentario_fecha = :comentario_fecha LIMIT 1";
            $query = $database->prepare($sql);
            $query->execute(array(':comentario_fecha' => $comentario_fecha));

            if ($query->rowCount() == 1) {
                $return->comentario = $query->fetch();
            }
            else{
                $return->comentario = '';
            }
        }

        return $return;
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