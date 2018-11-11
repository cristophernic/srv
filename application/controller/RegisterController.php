<?php
class RegisterController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        if (LoginModel::isUserLoggedIn()) {
            Redirect::home();
        } else {
            $this->View->render('register/index');
        }
    }
    public function register_action()
    {
        $registration_successful = RegistrationModel::registerNewUser();

        if ($registration_successful) {
            Redirect::home();
        } else {
            Redirect::home();
        }
    }
    public function verify($user_id, $user_activation_verification_code)
    {
        if (isset($user_id) && isset($user_activation_verification_code)) {
            RegistrationModel::verifyNewUser($user_id, $user_activation_verification_code);
            $this->View->render('register/verify');
        } else {
            Redirect::to('login/index');
        }
    }
    public function showCaptcha()
    {
        CaptchaModel::generateAndShowCaptcha();
    }
}
