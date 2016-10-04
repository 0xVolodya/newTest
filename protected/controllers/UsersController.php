<?php
//Yii::import('application.extensions.phpmailer.JPhpMailer');

//Yii::import('application.vendor/.autoload');

class UsersController extends Controller
{

    /**
     * helper to send a letter
     */
    private function sentEmail($email)
    {
        $mail = new JPhpMailer;
        $activation = md5(uniqid(rand(), true));
        $message_part1 = "Пожалуйста перейдите по ссылке для активации вашего аккаунта";
        $message_part2 = "http://localhost".Yii::app()->baseUrl."/index.php/users/activate?" .
            'email=' . urlencode($email) .
            '&activation=' . $activation;
//        http://localhost/newTest/index.php/users/activate?activation=3b002881924b0a77645ecc9fa6bda931&email=nadalfederer%40mail.ru
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'lebedevvladimirv.94@gmail.com';                 // SMTP username
        $mail->Password = 'dkflbvbhdfsbfs1';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;
        $mail->addAddress($email);

        $mail->Subject = 'Подтверждение регистрации';
        $mail->Body = "$message_part1 . $message_part2, 'text/html'";

        $mail->send();

        return $activation;
    }

    /**
     * check if all rules in registration rules are completed
     * and sent activation mail on email
     */
    public function actionRegister()
    {
        $model = new Users();

        if (isset($_POST['ajax']) && $_POST['ajax'] == 'users-index-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end;
        }

        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->validate()) {
                $model->activation = $this::sentEmail($model->email);

                if ($model->save()) {
                    $this->renderText('Письмо с подтверждением регистрации выслано');

                    $this->redirect("success");
                }
            }

        }
        $this->render('register', array('model' => $model));


    }

    /**
     * render view after success registration
     */
    public function actionSuccess()
    {
        $this->render('success');
    }

    /**
     * render view after email confirmation
     */
    public function actionSuccessActivate()
    {
        $this->render('successactivate');
    }


    // Uncomment the following methods and override them if needed
    /*
    public function filters()
    {
        // return the filter configuration for this controller, e.g.:
        return array(
            'inlineFilterName',
            array(
                'class'=>'path.to.FilterClass',
                'propertyName'=>'propertyValue',
            ),
        );
    }
*/
    public function actions()
    {
        // return external action classes, e.g.:
        return array(
//            'acti1' => 'path.to.ActionClass',
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => '0xFFFFFF',
            ),

        );
    }

    /**
     * Make user verification after clicking on link in
     * the message from email
     */
    public function actionActivate()
    {

        $model = new Users;

        if (isset($_POST['Users'])) {
            $email = $_POST['Users']["email"];
            $activation = $_POST['Users']["activation"];

            $findUser = Users::model()->findByAttributes(
                array("activation" => $activation,
                    "email" => $email)
            );

            if ($findUser) {
                $findUser->activation = "yes";

                $findUser->update();
                $this->redirect("successActivate");

            }
        }
        $this->render('activate', array('model' => $model));
    }

}