<?php
/**
 * File described SmtpMailer class for sending mail
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

namespace framework;

/**
 *  SmtpMailer class for sending email
 *
 * PHP version 5
 *
 * @namespace  framework
 * @author     sivanchenko@mindk.com
 */

class SmtpMailer
{
    private $smtp_username;
    private $smtp_password;
    private $smtp_host;
    private $smtp_port;
    const CODE_OK = "220";
    const CODE_OPERATION_FINISHED = "250";
    const CODE_AUTORIZATION_ACCEPTED = "334";
    const CODE_PASSWORD_ACCEPTED = "235";
    const CODE_READY_FOR_BODY = "354";

    public function __construct()
    {
        $this->smtp_username = ConfigHolder::getConfig('smtp_username');
        $this->smtp_password = ConfigHolder::getConfig('smtp_password');
        $this->smtp_host     = ConfigHolder::getConfig('smtp_host');
        $this->smtp_port     = ConfigHolder::getConfig('smtp_port');
    }

    /**
     * send new bid to email
     * 
     * @param $first_name
     * @param $email
     * @param $project_id
     * @param $bid_id
     * @param $comment
     *
     * @return bool|string
     */
    public function sendNewBid($first_name,$email,$project_id,$bid_id,$comment){
        $text      = ' Hello '.$first_name.'! You took new bid. For accepting proposition, go to the link: 
        <a href="'.ConfigHolder::getConfig('host').'/project/'.$project_id.'/bid/'.$bid_id.'">accept bid</a>
        <br>
        <br>
        Comment from freelancer: '.$comment;
        return $this->send($email, 'New bid from freelancer', $text);
    }

    /**
     *  send accept bid to mail
     * @param $implementer
     * @param $email
     * @param $project_id
     *
     * @return bool|string
     */
    public function sendBidAccept($implementer,$email,$project_id){
        $text      = ' Hello '.$implementer.'! Your Bid was accepted. For looking, go to the link: 
        <a href="'.ConfigHolder::getConfig('host').'/project/'.$project_id.'">Project</a>';

        return $this->send($email, 'Bid accepted', $text);
    }
    /**
     * send email
     *
     * @param string $mailTo  - subscriber
     * @param string $subject - subject
     * @param string $message - body
     * @param string $headers - headers
     *
     * @return bool|string true if success
     */
    private function send($mailTo, $subject, $message)
    {
        $headers   = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: Admin <freelance@gmail.com>\r\n";
        
        $contentMail = "Date: ".date("D, d M Y H:i:s")." UT\r\n";
        $contentMail .= 'Subject: =?utf-8?B?'.base64_encode($subject)."=?=\r\n";
        $contentMail .= $headers."\r\n";
        $contentMail .= $message."\r\n";

        try{
            if (!$socket = @fsockopen($this->smtp_host, $this->smtp_port, $errorNumber, $errorDescription, 30)) {
                throw new \Exception($errorNumber.".".$errorDescription);
            }
            if (!$this->validateResponse($socket, SmtpMailer::CODE_OK)) {
                throw new \Exception('Connection error');
            }

            $server_name = $_SERVER["SERVER_NAME"];
            fputs($socket, "HELO $server_name\r\n");
            if (!$this->validateResponse($socket, SmtpMailer::CODE_OPERATION_FINISHED)) {
                fclose($socket);
                throw new \Exception('Error of command sending: HELO');
            }

            fputs($socket, "AUTH LOGIN\r\n");
            if (!$this->validateResponse($socket, SmtpMailer::CODE_AUTORIZATION_ACCEPTED)) {
                fclose($socket);
                throw new \Exception('Autorization error');
            }

            fputs($socket, base64_encode($this->smtp_username)."\r\n");
            if (!$this->validateResponse($socket, SmtpMailer::CODE_AUTORIZATION_ACCEPTED)) {
                fclose($socket);
                throw new \Exception('Autorization error');
            }

            fputs($socket, base64_encode($this->smtp_password)."\r\n");
            if (!$this->validateResponse($socket, SmtpMailer::CODE_PASSWORD_ACCEPTED)) {
                fclose($socket);
                throw new \Exception('Autorization error');
            }

            fputs($socket, "MAIL FROM: <".$this->smtp_username.">\r\n");
            if (!$this->validateResponse($socket, SmtpMailer::CODE_OPERATION_FINISHED)) {
                fclose($socket);
                throw new \Exception('Error of command sending: MAIL FROM');
            }

            $mailTo = ltrim($mailTo, '<');
            $mailTo = rtrim($mailTo, '>');
            fputs($socket, "RCPT TO: <".$mailTo.">\r\n");
            if (!$this->validateResponse($socket, SmtpMailer::CODE_OPERATION_FINISHED)) {
                fclose($socket);
                throw new \Exception('Error of command sending: RCPT TO');
            }

            fputs($socket, "DATA\r\n");
            if (!$this->validateResponse($socket, SmtpMailer::CODE_READY_FOR_BODY)) {
                fclose($socket);
                throw new \Exception('Error of command sending: DATA');
            }

            fputs($socket, $contentMail."\r\n.\r\n");
            if (!$this->validateResponse($socket, SmtpMailer::CODE_OPERATION_FINISHED)) {
                fclose($socket);
                throw new \Exception("E-mail didn't sent");
            }

            fputs($socket, "QUIT\r\n");
            fclose($socket);
        } catch(\Exception $e){
            return $e->getMessage();
        }
        return true;
    }

    /** validate server response
     *
     * @param $socket
     * @param $response
     *
     * @return bool
     */
    private function validateResponse($socket, $response)
    {
        while (@substr($response_srv, 3, 1) != ' ') {
            if (!($response_srv = fgets($socket, 256))) {
                return false;
            }
        }
        if (!(substr($response_srv, 0, 3) == $response)) {
            return false;
        }
        return true;
    }
}