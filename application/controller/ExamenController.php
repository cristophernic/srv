<?php

class ExamenController extends Controller
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
    public function express()
    {
        $this->View->render('examen/express', array(
            'dicom' => if (Session::get("user_account_type") != 7){ return false}else{ return true},
        ));
    }

    public function seleccionar()
    {
        $this->View->render('examen/seleccionar');
    }
}