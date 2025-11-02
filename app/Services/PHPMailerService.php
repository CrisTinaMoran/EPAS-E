<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class PHPMailerService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        
        // Server settings
        $this->mail->isSMTP();
        $this->mail->Host       = env('MAIL_HOST', 'smtp.gmail.com');
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = env('MAIL_USERNAME');
        $this->mail->Password   = env('MAIL_PASSWORD');
        $this->mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
        $this->mail->Port       = env('MAIL_PORT', 587);
        
        // Sender settings
        $this->mail->setFrom(
            env('MAIL_FROM_ADDRESS', 'noreply@epase-lms.test'),
            env('MAIL_FROM_NAME', 'EPAS-E LMS')
        );
    }

    public function sendVerificationEmail($user, $verificationUrl)
    {
        try {
            // Clear any previous addresses
            $this->mail->clearAddresses();
            
            // Recipient
            $this->mail->addAddress($user->email, $user->full_name);
            
            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Verify Your Email - EPAS-E LMS';
            $this->mail->Body    = $this->getVerificationEmailTemplate($user, $verificationUrl);
            $this->mail->AltBody = $this->getPlainTextVerificationEmail($user, $verificationUrl);

            $this->mail->send();
            Log::info("Verification email sent successfully to: {$user->email}");
            return true;
        } catch (Exception $e) {
            Log::error("PHPMailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    protected function getVerificationEmailTemplate($user, $verificationUrl)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { padding: 20px; background: #f9f9f9; }
                .button { 
                    display: inline-block; 
                    padding: 12px 24px; 
                    background: #007bff; 
                    color: #ffffff !important; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    font-weight: bold;
                    text-align: center;
                }
                .footer { padding: 20px; text-align: center; font-size: 0.9em; color: #666; }
                .details { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #ddd; }
                .url-box { background: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid #dee2e6; word-break: break-all; font-size: 0.9em; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1 style='margin:0; color: #ffffff;'>EPAS-E LMS</h1>
                <p style='margin:0; color: #ffffff;'>Electronic Products Assembly and Servicing</p>
            </div>
            
            <div class='content'>
                <h2 style='color: #333;'>Verify Your Email Address</h2>
                
                <p>Hello <strong>{$user->first_name}</strong>,</p>
                
                <p>Thank you for registering with EPAS-E Learning Management System. Please verify your email address to complete your registration.</p>
                
                <div class='details'>
                    <p><strong>Account Details:</strong></p>
                    <ul>
                        <li><strong>Name:</strong> {$user->full_name}</li>
                        <li><strong>student:</strong> {$user->student_id}</li>
                        <li><strong>Email:</strong> {$user->email}</li>
                    </ul>
                </div>
                
                <p style='text-align: center;'>
                    <a href='{$verificationUrl}' class='button' style='color: #ffffff !important;'>
                        Verify Email Address
                    </a>
                </p>
                
                <p>If the button doesn't work, copy and paste this link in your browser:</p>
                <div class='url-box'>{$verificationUrl}</div>
                
                <p>If you did not create an account, please ignore this email.</p>
                
                <p><strong>Note:</strong> Your account requires administrative approval before you can access the system.</p>
            </div>
            
            <div class='footer'>
                <p>&copy; " . date('Y') . " EPAS-E LMS. All rights reserved.</p>
                <p>This is an automated message, please do not reply to this email.</p>
            </div>
        </body>
        </html>
        ";
    }

    protected function getPlainTextVerificationEmail($user, $verificationUrl)
    {
        return "
        Verify Your Email - EPAS-E LMS

        Hello {$user->first_name},

        Thank you for registering with EPAS-E Learning Management System.

        Account Details:
        - Name: {$user->full_name}
        - student: {$user->student_id}
        - Email: {$user->email}

        Please verify your email address by clicking the link below:
        {$verificationUrl}

        If you did not create an account, please ignore this email.

        Note: Your account requires administrative approval before you can access the system.

        © " . date('Y') . " EPAS-E LMS. All rights reserved.
        ";
    }

    public function sendPasswordResetEmail($user, $resetUrl)
    {
        try {
            // Clear any previous addresses
            $this->mail->clearAddresses();
            
            // Recipient
            $this->mail->addAddress($user->email, $user->full_name);
            
            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Reset Your Password - EPAS-E LMS';
            $this->mail->Body    = $this->getPasswordResetEmailTemplate($user, $resetUrl);
            $this->mail->AltBody = $this->getPlainTextPasswordResetEmail($user, $resetUrl);

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            Log::error("PHPMailer Error: {$this->mail->ErrorInfo}");
            return false;
        }
    }

    protected function getPasswordResetEmailTemplate($user, $resetUrl)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #007bff; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { padding: 20px; background: #f9f9f9; }
                .button { 
                    display: inline-block; 
                    padding: 12px 24px; 
                    background: #007bff; 
                    color: #ffffff !important; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    font-weight: bold;
                    text-align: center;
                }
                .footer { padding: 20px; text-align: center; font-size: 0.9em; color: #666; }
                .details { background: white; padding: 15px; border-radius: 5px; margin: 15px 0; border: 1px solid #ddd; }
                .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; border-radius: 5px; margin: 15px 0; color: #856404; }
                .url-box { background: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid #dee2e6; word-break: break-all; font-size: 0.9em; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1 style='margin:0; color: #ffffff;'>EPAS-E LMS</h1>
                <p style='margin:0; color: #ffffff;'>Electronic Products Assembly and Servicing</p>
            </div>
            
            <div class='content'>
                <h2 style='color: #333;'>Reset Your Password</h2>
                
                <p>Hello <strong>{$user->first_name}</strong>,</p>
                
                <p>You are receiving this email because we received a password reset request for your account.</p>
                
                <div class='details'>
                    <p><strong>Account Details:</strong></p>
                    <ul>
                        <li><strong>Name:</strong> {$user->full_name}</li>
                        <li><strong>Email:</strong> {$user->email}</li>
                    </ul>
                </div>
                
                <p style='text-align: center;'>
                    <a href='{$resetUrl}' class='button' style='color: #ffffff !important;'>
                        Reset Password
                    </a>
                </p>
                
                <p>If the button doesn't work, copy and paste this link in your browser:</p>
                <div class='url-box'>{$resetUrl}</div>
                
                <div class='warning'>
                    <p><strong>Important:</strong> This password reset link will expire in 1 hour.</p>
                    <p>If you did not request a password reset, no further action is required.</p>
                </div>
            </div>
            
            <div class='footer'>
                <p>&copy; " . date('Y') . " EPAS-E LMS. All rights reserved.</p>
                <p>This is an automated message, please do not reply to this email.</p>
            </div>
        </body>
        </html>
        ";
    }

    protected function getPlainTextPasswordResetEmail($user, $resetUrl)
    {
        return "
        Reset Your Password - EPAS-E LMS

        Hello {$user->first_name},

        You are receiving this email because we received a password reset request for your account.

        Account Details:
        - Name: {$user->full_name}
        - Email: {$user->email}

        Please reset your password by clicking the link below:
        {$resetUrl}

        Important: This password reset link will expire in 1 hour.

        If you did not request a password reset, no further action is required.

        © " . date('Y') . " EPAS-E LMS. All rights reserved.
        ";
    }
}