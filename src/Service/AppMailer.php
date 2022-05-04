<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AppMailer
{
    private MailerInterface $mailer;
    private string $from;
    private string $to;
    private string $fromName;

    /** @var ContainerInterface */
    private $templating;

    /**
     * AppMailer constructor.
     * @param MailerInterface $mailer
     * @param ParameterBagInterface $bag
     * @param Environment $twig
     */
    public function __construct(MailerInterface $mailer, ParameterBagInterface $bag, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->from = (string)$bag->get('mailer_from');
        $this->to = (string)$bag->get('mailer_to');
        $this->fromName = (string)$bag->get('mailer_from_name');
        $this->templating = $twig;
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    private function send($subject, $html)
    {
        $email = (new Email())
            ->from(new Address($this->from, $this->fromName))
            ->to($this->to)
            ->subject($subject)
            ->html($html);

        $this->mailer->send($email);
    }

    /**
     * @param $name
     * @param $email
     * @param $content
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function contactFormSubmit($name, $email, $content)
    {
        $this->send('Contact Us stromberguitars.com', $this->templating->render(
            'site/email/contact_us_template.html.twig',
            ['name' => $name, 'email' => $email, 'content' => $content]
        ));
    }

    /**
     * @param $name
     * @param $email
     * @param $content
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function contactMerchFormSubmit($name, $email, $content)
    {
        $this->send('Merch stromberguitars.com', $this->templating->render(
            'site/email/merch_template.html.twig',
            ['name' => $name, 'email' => $email, 'content' => $content]
        ));
    }
}