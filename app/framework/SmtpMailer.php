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
    public $smtp_username;
    public $smtp_password;
    public $smtp_host;
    public $smtp_port;
    const CODE_OK = "220";
    const CODE_OPERATION_FINISHED = "250";
    const CODE_AUTORIZATION_ACCEPTED = "334";
    const CODE_PASSWORD_ACCEPTED = "235";
    const CODE_READY_FOR_BODY = "354";

    public function __construct($smtp_username, $smtp_password, $smtp_host, $smtp_port = 25)
    {
        $this->smtp_username = $smtp_username;
        $this->smtp_password = $smtp_password;
        $this->smtp_host     = $smtp_host;
        $this->smtp_port     = $smtp_port;
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
    function send($mailTo, $subject, $message, $headers)
    {
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