<?php

class DicomController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        header('HTTP/1.0 404 Not Found', true, 404);
        $this->View->render('error/404');
    }

    public function view()
    {
        $this->View->render('dicom/index');
    }

    public function getimages($rut, $StudyDate){
        $this->View->renderJSON(DicomModel::getAllImages($rut,$StudyDate));
    }

    public function getlastpatients(){
        $this->View->renderJSON(DicomModel::lastpatients());
    }

    public function patients($RUT){
        $this->View->renderJSON(DicomModel::getpatients($RUT));
    }
}
