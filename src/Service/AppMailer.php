<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class AppMailer
{
    private MailerInterface $mailer;
    private string $from;
    private string $fromName;

    public function __construct(MailerInterface $mailer, ParameterBagInterface $bag)
    {
        $this->mailer = $mailer;
        $this->from = (string)$bag->get('mailer_from');
        $this->fromName = (string)$bag->get('mailer_from_name');
    }

    public function contactFormSubmit($name, $email, $content)
    {
        $email = (new Email())
            ->from(new Address($email, $name))
            ->to($this->from)
            ->subject('Contact with Stromberg')
            ->text($content);

        $this->mailer->send($email);
    }
}