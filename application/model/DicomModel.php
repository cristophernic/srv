<?php

class DicomModel
{
    /**
     * Get all notes (notes are just example data that the user has created)
     * @return array an array with several objects (the results)
     */
    public static function getAllImages($rut, $StudyDate)
    {
            $database = "";
            $result = new stdClass();
            $StudyDate = intval($StudyDate);
            try {
                $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
                $database = new PDO(
                   Config::get('DB_TYPE') . ':host=' . Config::get('DB_HOST') . ';dbname=dicom' .
                    ';port=' . Config::get('DB_PORT') . ';charset=' . Config::get('DB_CHARSET'),
                   Config::get('DB_USER'), Config::get('DB_PASS'), $options
                   );
            } catch (PDOException $e) {
    
                // Echo custom message. Echo error code gives you some info.
                echo 'Database connection can not be estabilished. Please try again later.' . '<br>';
                echo 'Error code: ' . $e->getCode();
    
                // Stop application :(
                // No connection, reached limit connections etc. so no point to keep it running
                exit;
            }
            $sql = "SELECT ObjectFile FROM DICOMImages where ImageDate = :ImageDate and ImagePat = :ImagePat";
            $query = $database->prepare($sql);
            $query->execute(array(':ImagePat' => $rut, ':ImageDate' => $StudyDate));

            if ($query->rowCount() > 0) {
                $imagenes = $query->fetchAll();
                $result->exist = true;

                $archivos = scandir(Config::get('DICOM_DIRECTORY') . $rut . "/");

                if ($archivos == false){
                    $result->empty = true;
                }
                else{
                    $result->empty = false;
                    $archivosJPG = array();

                    foreach($imagenes as $imagen){
                        $strArchivoJPG = Config::get('DICOM_DIRECTORY') . substr($imagen->ObjectFile, 0, strlen($imagen->ObjectFile) -3) . "jpg";

                        if(file_exists($strArchivoJPG)){
                            array_push($archivosJPG, substr($imagen->ObjectFile, 0, strlen($imagen->ObjectFile) -3) . "jpg");
                        }
                        else{
                            $strCommand =  "/usr/bin/dcmj2pnm +fo +oj " . Config::get('DICOM_DIRECTORY') . $imagen->ObjectFile .  " " . $strArchivoJPG;
                            $out = exec($strCommand);
                            array_push($archivosJPG, substr($imagen->ObjectFile, 0, strlen($imagen->ObjectFile) -3) . "jpg");
                        }

                    }

                    $result->JPGFiles = $archivosJPG;
                }
            }
            else{
                $result->exist = false;
            }

        return $result;
    }
    public static function lastpatients()
    {
            $database = "";

            try {
                $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
                $database = new PDO(
                   Config::get('DB_TYPE') . ':host=' . Config::get('DB_HOST') . ';dbname=dicom' .
                    ';port=' . Config::get('DB_PORT') . ';charset=' . Config::get('DB_CHARSET'),
                   Config::get('DB_USER'), Config::get('DB_PASS'), $options
                   );
            } catch (PDOException $e) {
    
                // Echo custom message. Echo error code gives you some info.
                echo 'Database connection can not be estabilished. Please try again later.' . '<br>';
                echo 'Error code: ' . $e->getCode();
    
                // Stop application :(
                // No connection, reached limit connections etc. so no point to keep it running
                exit;
            }
            $sql = "SELECT * FROM dicom.DICOMPatients tab1 Left join crecimientofetal.user_exmprevio tab2 on tab1.PatientID = tab2.user_id ORDER BY AccessTime DESC LIMIT 20";
            $query = $database->prepare($sql);
            $query->execute();

            return $query->fetchAll();
    }

    public static function getpatients($RUT)
    {
            $database = "";

            try {
                $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
                $database = new PDO(
                   Config::get('DB_TYPE') . ':host=' . Config::get('DB_HOST') . ';dbname=dicom' .
                    ';port=' . Config::get('DB_PORT') . ';charset=' . Config::get('DB_CHARSET'),
                   Config::get('DB_USER'), Config::get('DB_PASS'), $options
                   );
            } catch (PDOException $e) {
    
                // Echo custom message. Echo error code gives you some info.
                echo 'Database connection can not be estabilished. Please try again later.' . '<br>';
                echo 'Error code: ' . $e->getCode();
    
                // Stop application :(
                // No connection, reached limit connections etc. so no point to keep it running
                exit;
            }
            $sql = "SELECT PatientID, PatientNam, PatientBir, AccessTime FROM dicom.DICOMPatients WHERE PatientID LIKE ? ORDER BY AccessTime DESC LIMIT 20";
            $query = $database->prepare($sql);
            $query->execute(array($RUT. '%'));

            return $query->fetchAll();
    }

    public static function getStudies($RUT)
    {
            $database = "";

            try {
                $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
                $database = new PDO(
                   Config::get('DB_TYPE') . ':host=' . Config::get('DB_HOST') . ';dbname=dicom' .
                    ';port=' . Config::get('DB_PORT') . ';charset=' . Config::get('DB_CHARSET'),
                   Config::get('DB_USER'), Config::get('DB_PASS'), $options
                   );
            } catch (PDOException $e) {
    
                // Echo custom message. Echo error code gives you some info.
                echo 'Database connection can not be estabilished. Please try again later.' . '<br>';
                echo 'Error code: ' . $e->getCode();
    
                // Stop application :(
                // No connection, reached limit connections etc. so no point to keep it running
                exit;
            }
            $sql = "SELECT StudyDate, StudyTime FROM dicom.DICOMStudies WHERE PatientID = :patient ORDER BY AccessTime DESC LIMIT 20";
            $query = $database->prepare($sql);
            $query->execute(array(':patient' => $RUT));

            return $query->fetchAll();
    }
    /**
     * Get a single note
     * @param int $note_id id of the specific note
     * @return object a single object (the result)
     */
    public static function getNote($note_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, note_id, note_text FROM notes WHERE user_id = :user_id AND note_id = :note_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => Session::get('user_id'), ':note_id' => $note_id));

        // fetch() is the PDO method that gets a single result
        return $query->fetch();
    }

    /**
     * Set a note (create a new one)
     * @param string $note_text note text that will be created
     * @return bool feedback (was the note created properly ?)
     */
    public static function createNote($note_text)
    {
        if (!$note_text || strlen($note_text) == 0) {
            Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO notes (note_text, user_id) VALUES (:note_text, :user_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':note_text' => $note_text, ':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_CREATION_FAILED'));
        return false;
    }

    /**
     * Update an existing note
     * @param int $note_id id of the specific note
     * @param string $note_text new text of the specific note
     * @return bool feedback (was the update successful ?)
     */
    public static function updateNote($note_id, $note_text)
    {
        if (!$note_id || !$note_text) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE notes SET note_text = :note_text WHERE note_id = :note_id AND user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':note_id' => $note_id, ':note_text' => $note_text, ':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_EDITING_FAILED'));
        return false;
    }

    /**
     * Delete a specific note
     * @param int $note_id id of the note
     * @return bool feedback (was the note deleted properly ?)
     */
    public static function deleteNote($note_id)
    {
        if (!$note_id) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "DELETE FROM notes WHERE note_id = :note_id AND user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':note_id' => $note_id, ':user_id' => Session::get('user_id')));

        if ($query->rowCount() == 1) {
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_NOTE_DELETION_FAILED'));
        return false;
    }


    public static function sendDicomImages()
    {
        $user_id = Request::post('user_id');
        $img_id = Request::post('img_id');
        $studyDate = Request::post('studyDate');
        $user_email = Request::post('user_email');

        $respuesta = new stdClass();
        $mail = new Mail;

        if ($user_id && $img_id && $studyDate && $user_email){

            $files = self::getAllImages($user_id, $studyDate);
            $files = $files['JPGFiles'];
            $filesJPG = [];

            print_r($files);
            print_r($img_id);
            foreach($files as $file){
                if (in_array($contador, $img_id)) {
                    array_push($filesJPG, $file);
                }
                $contador++;
            }

            print_r($filesJPG);
            //$body = Config::get('EMAIL_PASSWORD_RESET_CONTENT') . ' ' . Config::get('URL') .
            //Config::get('EMAIL_PASSWORD_RESET_URL') . '/' . urlencode($user_name) . '/' . urlencode($user_password_reset_hash);

            //return $respuesta->send = $mail->sendMailWithAttachment($user_email, Config::get('EMAIL_PASSWORD_RESET_FROM_EMAIL'), 
            //"Crecimiento Fetal", "Imágenes Gineco-Obstétricas", $body, $filesJPG);

        }
        //return $respuesta->send = false;
    }
}
