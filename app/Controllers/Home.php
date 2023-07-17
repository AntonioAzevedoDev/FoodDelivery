<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }
    
    public function email() {
        
        $email = \Config\Services::email();
        
        $email->setFrom('lucassilva.eq@gmail.com','Lucas Silva');
        $email->setTo('lucassilva.eq@gmail.com');
       // $email->setCC('');
        //$email->setBCC('');
        $email->setSubject('Email Test');
        $email->setMessage('Testing the email class.');
        
        if($email->send()){
            echo 'Email enviado';
        }else{
            echo $email->printDebugger();
        }
        
        
        
        
    }
}
