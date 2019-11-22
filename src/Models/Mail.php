<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use App\PHPMailer\PHPMailer;

class Mail extends Eloquent
{
	private $host = '';
	private $username = '';
	private $password = '';
	private $port = 587;
	private $smtp_auth = true;
	private $debug = 0;
	private $mail_from = '';
	private $name_from = 'SIS - TICKET';
	private $isHtml = true;

	protected $table = 'tbemails';
	protected $primaryKey = 'emaId';
	
	protected $fillable = [
		'chaId',
		'emaAddress',
	    'emaSuject',	    
	    'emaBody',
	];


	public function send($address, $subject, $body)
    {
    	$mail = new PHPMailer(true);
	                              // Passing `true` enables exceptions
		try {
			$mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);
		    //Server settings
		    $mail->SMTPDebug = $this->debug;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = $this->host;  // Specify main and backup SMTP servers
		    $mail->SMTPAuth = $this->smtp_auth;                               // Enable SMTP authentication
		    $mail->Username = $this->username;                 // SMTP username
		    $mail->Password = $this->password;                           // SMTP password
		    //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		    $mail->Port = $this->port;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom($this->mail_from, $this->name_from);

		    foreach ($address as $key => $value) {
				$mail->addAddress($value, $value);
			}
			
		    //Content
		    $mail->isHTML($this->isHtml);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $body;
		    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients'; // caso seja sem html

		    $mail->send();
		    //echo 'Message has been sent';
		    return true;
		} catch (Exception $e) {
		    //echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
		    return false;
		}
    }
}