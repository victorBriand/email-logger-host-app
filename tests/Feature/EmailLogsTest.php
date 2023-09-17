<?php

namespace Tests\Feature;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\SentMessage as MailerSentMessage;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;
use Vic\EmailLogger\Listeners\EmailSentListener;

it('should log every outgoing email', function () {
    Mail::fake();
    Event::fake();

    Log::spy();

    (new EmailSentListener())->handle(new MessageSent(
        new SentMessage(
            new MailerSentMessage(
                new RawMessage('test'),
                new Envelope(
                    new Address('sender@example.com'),
                    [
                        new Address('recipient1@example.com'),
                        new Address('recipient2@example.com'),
                    ]
                )
            )
        )
    ));

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Email Sent');
});
