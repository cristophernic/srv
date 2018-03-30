<?php

class PdfController extends Controller
{
    /**
     * Construct this object by extending the basic Controller class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handles what happens when user moves to URL/index/index - or - as this is the default controller, also
     * when user moves to /index or enter your application at base level
     */
    public function img($user_id = null, $img_id = null, $StudyDate = null)
    {
        if ($user_id != null && $img_id != null && $StudyDate != null){
            
            $nombre = NombreModel::getAll($user_id);
            $nombre = explode("^", $nombre[0].PatientNam);
            $nombre = $nombre[1] . " " . $nombre[0];
                
            $this->View->renderWithoutHeaderAndFooter('pdf/index', array(
                'user_id' => $user_id,
                'user_name' => $nombre,
                'img_id' => $img_id,
                'StudyDate' => $StudyDate
            ));
        }
        else{
            Redirect::to('examen/express');
        }
    }
}
