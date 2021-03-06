<?php

/**
 * UserModel
 * Handles all the PUBLIC profile stuff. This is not for getting data of the logged in user, it's more for handling
 * data of all the other users. Useful for display profile information, creating user lists etc.
 */
class UserModel
{
    /**
     * Gets an array that contains all the users in the database. The array's keys are the user ids.
     * Each array element is an object, containing a specific user's data.
     * The avatar line is built using Ternary Operators, have a look here for more:
     * @see http://davidwalsh.name/php-shorthand-if-else-ternary-operators
     *
     * @return array The profiles of all users
     */
    public static function getPublicProfilesOfAllUsers()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_nombre, user_telefono FROM users WHERE NOT user_account_type = 6";
        $query = $database->prepare($sql);
        $query->execute();

        $all_users_profiles = array();

        foreach ($query->fetchAll() as $user) {

            // all elements of array passed to Filter::XSSFilter for XSS sanitation, have a look into
            // application/core/Filter.php for more info on how to use. Removes (possibly bad) JavaScript etc from
            // the user's values
            array_walk_recursive($user, 'Filter::XSSFilter');

            $all_users_profiles[$user->user_id] = new stdClass();
            $all_users_profiles[$user->user_id]->user_id = $user->user_id;
            $all_users_profiles[$user->user_id]->user_name = $user->user_name;
            $all_users_profiles[$user->user_id]->user_email = $user->user_email;
            $all_users_profiles[$user->user_id]->user_nombre = $user->user_nombre;
            $all_users_profiles[$user->user_id]->user_telefono = $user->user_telefono;
        }

        return $all_users_profiles;
    }

    public static function getPublicProfilesOfAllUsersAndDepartaments()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT users.user_id, user_departamento.departamento_id, departamentos.departamento_name, users.user_nombre, users.user_telefono, users.user_email FROM users INNER JOIN user_departamento ON user_departamento.user_id = users.user_id INNER JOIN departamentos ON departamentos.departamento_id = user_departamento.departamento_id WHERE NOT users.user_account_type = 6 ORDER BY departamentos.departamento_name;";
        $query = $database->prepare($sql);
        $query->execute();

        $all_users_profiles = array();

        foreach ($query->fetchAll() as $user) {

            // all elements of array passed to Filter::XSSFilter for XSS sanitation, have a look into
            // application/core/Filter.php for more info on how to use. Removes (possibly bad) JavaScript etc from
            // the user's values
            array_walk_recursive($user, 'Filter::XSSFilter');

            $all_users_profiles[$user->user_id] = new stdClass();
            $all_users_profiles[$user->user_id]->user_id = $user->user_id;
            $all_users_profiles[$user->user_id]->departamento_id = $user->departamento_id;
            $all_users_profiles[$user->user_id]->departamento_name = $user->departamento_name;
            $all_users_profiles[$user->user_id]->user_nombre = $user->user_nombre;
            $all_users_profiles[$user->user_id]->user_telefono = $user->user_telefono;
            $all_users_profiles[$user->user_id]->user_email = $user->user_email;
        }

        return $all_users_profiles;
    }

    public static function getPublicProfilesOfAllUsersAndDepartamentsFilter($departamento_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT users.user_id, user_departamento.departamento_id, departamentos.departamento_name, users.user_nombre, users.user_telefono, users.user_email FROM users INNER JOIN user_departamento ON user_departamento.user_id = users.user_id INNER JOIN departamentos ON departamentos.departamento_id = user_departamento.departamento_id WHERE NOT users.user_account_type = 6 AND user_departamento.departamento_id = :departamento_id ORDER BY departamentos.departamento_name;";
        $query = $database->prepare($sql);
        $query->execute(array(':departamento_id' => $departamento_id));

        $all_users_profiles = array();

        foreach ($query->fetchAll() as $user) {

            // all elements of array passed to Filter::XSSFilter for XSS sanitation, have a look into
            // application/core/Filter.php for more info on how to use. Removes (possibly bad) JavaScript etc from
            // the user's values
            array_walk_recursive($user, 'Filter::XSSFilter');

            $all_users_profiles[$user->user_id] = new stdClass();
            $all_users_profiles[$user->user_id]->user_id = $user->user_id;
            $all_users_profiles[$user->user_id]->departamento_id = $user->departamento_id;
            $all_users_profiles[$user->user_id]->departamento_name = $user->departamento_name;
            $all_users_profiles[$user->user_id]->user_nombre = $user->user_nombre;
            $all_users_profiles[$user->user_id]->user_telefono = $user->user_telefono;
            $all_users_profiles[$user->user_id]->user_email = $user->user_email;
        }

        return $all_users_profiles;
    }

    /**
     * Gets a user's profile data, according to the given $user_id
     * @param int $user_id The user's id
     * @return mixed The selected user's profile
     */
    public static function getPublicProfileOfUser($user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_active, user_has_avatar, user_deleted
                FROM users WHERE user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user_id' => $user_id));

        $user = $query->fetch();

        if ($query->rowCount() == 1) {
            if (Config::get('USE_GRAVATAR')) {
                $user->user_avatar_link = AvatarModel::getGravatarLinkByEmail($user->user_email);
            } else {
                $user->user_avatar_link = AvatarModel::getPublicAvatarFilePathOfUser($user->user_has_avatar, $user->user_id);
            }
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_USER_DOES_NOT_EXIST'));
        }

        // all elements of array passed to Filter::XSSFilter for XSS sanitation, have a look into
        // application/core/Filter.php for more info on how to use. Removes (possibly bad) JavaScript etc from
        // the user's values
        array_walk_recursive($user, 'Filter::XSSFilter');

        return $user;
    }

    /**
     * @param $user_name_or_email
     *
     * @return mixed
     */
    public static function getUserDataByUserNameOrEmail($user_name_or_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id, user_name, user_email FROM users
                                     WHERE (user_name = :user_name_or_email OR user_email = :user_name_or_email)
                                           AND user_provider_type = :provider_type LIMIT 1");
        $query->execute(array(':user_name_or_email' => $user_name_or_email, ':provider_type' => 'DEFAULT'));

        return $query->fetch();
    }

    /**
     * Checks if a username is already taken
     *
     * @param $user_name string username
     *
     * @return bool
     */
    public static function doesUsernameAlreadyExist($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id FROM users WHERE user_name = :user_name LIMIT 1");
        $query->execute(array(':user_name' => $user_name));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Checks if a email is already used
     *
     * @param $user_email string email
     *
     * @return bool
     */
    public static function doesEmailAlreadyExist($user_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("SELECT user_id FROM users WHERE user_email = :user_email LIMIT 1");
        $query->execute(array(':user_email' => $user_email));
        if ($query->rowCount() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Writes new username to database
     *
     * @param $user_id int user id
     * @param $new_user_name string new username
     *
     * @return bool
     */
    public static function saveNewUserName($user_id, $new_user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_name = :user_name WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_name' => $new_user_name, ':user_id' => $user_id));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }


    public static function saveUserName($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_nombre = :user_nombre WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_nombre' => $user_name, ':user_id' => Session::get('user_id')));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }


    public static function saveTelefono($user_telefono)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_telefono = :user_telefono WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_telefono' => $user_telefono, ':user_id' => Session::get('user_id')));
        if ($query->rowCount() == 1) {
            return true;
        }
        return false;
    }

    /**
     * Writes new email address to database
     *
     * @param $user_id int user id
     * @param $new_user_email string new email address
     *
     * @return bool
     */
    public static function saveNewEmailAddress($user_id, $new_user_email)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("UPDATE users SET user_email = :user_email WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_email' => $new_user_email, ':user_id' => $user_id));
        $count = $query->rowCount();
        if ($count == 1) {
            return true;
        }
        return false;
    }

    /**
     * Edit the user's name, provided in the editing form
     *
     * @param $new_user_name string The new username
     *
     * @return bool success status
     */
    public static function editUserName($new_user_name)
    {
        // new username same as old one ?
        if ($new_user_name == Session::get('user_name')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_SAME_AS_OLD_ONE'));
            return false;
        }

        // username cannot be empty and must be azAZ09 and 2-64 characters
        if (!preg_match("/^[a-zA-Z0-9]{2,64}$/", $new_user_name)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        // clean the input, strip usernames longer than 64 chars (maybe fix this ?)
        $new_user_name = substr(strip_tags($new_user_name), 0, 64);

        // check if new username already exists
        if (self::doesUsernameAlreadyExist($new_user_name)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USERNAME_ALREADY_TAKEN'));
            return false;
        }

        $status_of_action = self::saveNewUserName(Session::get('user_id'), $new_user_name);
        if ($status_of_action) {
            Session::set('user_name', $new_user_name);
            Session::add('feedback_positive', Text::get('FEEDBACK_USERNAME_CHANGE_SUCCESSFUL'));
            return true;
        } else {
            Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
            return false;
        }
    }

    /**
     * Edit the user's email
     *
     * @param $new_user_email
     *
     * @return bool success status
     */
    public static function editUserEmail($new_user_email)
    {
        // email provided ?
        if (empty($new_user_email)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EMAIL_FIELD_EMPTY'));
            return false;
        }

        // check if new email is same like the old one
        if ($new_user_email == Session::get('user_email')) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EMAIL_SAME_AS_OLD_ONE'));
            return false;
        }

        // user's email must be in valid email format, also checks the length
        // @see http://stackoverflow.com/questions/21631366/php-filter-validate-email-max-length
        // @see http://stackoverflow.com/questions/386294/what-is-the-maximum-length-of-a-valid-email-address
        if (!filter_var($new_user_email, FILTER_VALIDATE_EMAIL)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN'));
            return false;
        }

        // strip tags, just to be sure
        $new_user_email = substr(strip_tags($new_user_email), 0, 254);

        // check if user's email already exists
        if (self::doesEmailAlreadyExist($new_user_email)) {
            Session::add('feedback_negative', Text::get('FEEDBACK_USER_EMAIL_ALREADY_TAKEN'));
            return false;
        }

        // write to database, if successful ...
        // ... then write new email to session, Gravatar too (as this relies to the user's email address)
        if (self::saveNewEmailAddress(Session::get('user_id'), $new_user_email)) {
            Session::set('user_email', $new_user_email);
            Session::set('user_gravatar_image_url', AvatarModel::getGravatarLinkByEmail($new_user_email));
            Session::add('feedback_positive', Text::get('FEEDBACK_EMAIL_CHANGE_SUCCESSFUL'));
            return true;
        }

        Session::add('feedback_negative', Text::get('FEEDBACK_UNKNOWN_ERROR'));
        return false;
    }

    /**
     * Gets the user's id
     *
     * @param $user_name
     *
     * @return mixed
     */
    public static function getUserIdByUsername($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id FROM users WHERE user_name = :user_name AND user_provider_type = :provider_type LIMIT 1";
        $query = $database->prepare($sql);

        // DEFAULT is the marker for "normal" accounts (that have a password etc.)
        // There are other types of accounts that don't have passwords etc. (FACEBOOK)
        $query->execute(array(':user_name' => $user_name, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch()->user_id;
    }

    /**
     * Gets the user's data
     *
     * @param $user_name string User's name
     *
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public static function getUserDataByUsername($user_name)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT user_id, user_name, user_email, user_password_hash, user_active,user_deleted, user_suspension_timestamp, user_account_type,
                       user_failed_logins, user_last_failed_login
                  FROM users
                 WHERE (user_name = :user_name OR user_email = :user_name)
                       AND user_provider_type = :provider_type
                 LIMIT 1";
        $query = $database->prepare($sql);

        // DEFAULT is the marker for "normal" accounts (that have a password etc.)
        // There are other types of accounts that don't have passwords etc. (FACEBOOK)
        $query->execute(array(':user_name' => $user_name, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch();
    }

    /**
     * Gets the user's data by user's id and a token (used by login-via-cookie process)
     *
     * @param $user_id
     * @param $token
     *
     * @return mixed Returns false if user does not exist, returns object with user's data when user exists
     */
    public static function getUserDataByUserIdAndToken($user_id, $token)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // get real token from database (and all other data)
        $query = $database->prepare("SELECT user_id, user_name, user_email, user_password_hash, user_active,
                                          user_account_type,  user_has_avatar, user_failed_logins, user_last_failed_login
                                     FROM users
                                     WHERE user_id = :user_id
                                       AND user_remember_me_token = :user_remember_me_token
                                       AND user_remember_me_token IS NOT NULL
                                       AND user_provider_type = :provider_type LIMIT 1");
        $query->execute(array(':user_id' => $user_id, ':user_remember_me_token' => $token, ':provider_type' => 'DEFAULT'));

        // return one row (we only have one result or nothing)
        return $query->fetch();
    }

    public static function savefur()
    {
        $rut = strip_tags(Request::post('rut'));
        $fur = strip_tags(Request::post('fur'));

        $database = DatabaseFactory::getFactory()->getConnection();

        // comprobar si la paciente ya tiene una fur guardada
        $query = $database->prepare("SELECT fur_id, user_id, fur_date FROM fur WHERE user_id = :user_id  LIMIT 1");
        $query->execute(array(':user_id' => $rut));
        if ($query->rowCount() > 0){
            //update
            $sql = "UPDATE fur SET fur_date = :fur_date WHERE user_id = :user_id LIMIT 1";
            $query = $database->prepare($sql);
            $query->execute(array(':fur_date' => $fur, ':user_id' => $rut));
            if ($query->rowCount() == 1) {
                return true;
            }
        }
        else{
            //createNew
            $sql = "INSERT INTO fur (user_id, fur_date) VALUES (:user_id, :fur_date)";
            $query = $database->prepare($sql);
            $query->execute(array(':user_id' => $rut, ':fur_date' => $fur));
            if ($query->rowCount() == 1) {
                return true;
            }
        }
    }

    public static function savexmprev()
    {
        $rut = strip_tags(Request::post('rut'));
        $exm = strip_tags(Request::post('exm'));
        $exmtxt = strip_tags(Request::post('exmtxt'));

        $database = DatabaseFactory::getFactory()->getConnection();

        // comprobar si la paciente ya tiene un motivo de exámen previo guardado
        $query = $database->prepare("SELECT user_id, user_exm FROM user_exmprevio WHERE user_id = :user_id  LIMIT 1");
        $query->execute(array(':user_id' => $rut));

        if ($query->rowCount() > 0){
            //update
            $sql = "UPDATE user_exmprevio SET user_exm = :user_exm, user_exmtxt = :user_exmtxt WHERE user_id = :user_id LIMIT 1";
            $query = $database->prepare($sql);
            $query->execute(array(':user_exmtxt' => $exmtxt,':user_exm' => $exm, ':user_id' => $rut));
            if ($query->rowCount() == 1) {
                return true;
            }
        }
        else{
            //createNew
            $sql = "INSERT INTO user_exmprevio (user_id, user_exm,user_exmtxt) VALUES (:user_id, :user_exm, :user_exmtxt)";
            $query = $database->prepare($sql);
            $query->execute(array(':user_id' => $rut, ':user_exm' => $exm,':user_exmtxt' => $exmtxt));
            if ($query->rowCount() == 1) {
                return true;
            }
        }
    }
    
    public static function getfur($user_id)
    {

        $database = DatabaseFactory::getFactory()->getConnection();

        // comprobar si la paciente ya tiene una fur guardada
        $query = $database->prepare("SELECT fur_id, user_id, fur_date FROM fur WHERE user_id = :user_id  LIMIT 1");
        $query->execute(array(':user_id' => $user_id));
        if ($query->rowCount() > 0){
            //update
            return $query->fetch();
        }
    }

    /**
     * Writes new email address to database
     *
     * @param $user_id int user id
     * @param $new_user_email string new email address
     *
     * @return bool
     */
    public static function deleteUser($user_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("DELETE FROM users WHERE user_id = :user_id LIMIT 1");
        $query->execute(array(':user_id' => $user_id));
        $count = $query->rowCount();
        if ($count == 1) {
            return true;
        }
        return false;
    }
}
