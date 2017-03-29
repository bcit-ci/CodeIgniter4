<?php namespace Config;

use CodeIgniter\Config\Mail as MailFactory;

class Mail extends MailFactory
{
    //--------------------------------------------------------------------
    // From Address
    //--------------------------------------------------------------------
    // Specifies the address that emails are sent from whenever no
    // other 'from' address is specifically set for the message.
    //
    public $from = [
        'name' => 'CodeIgniter',
        'email' => 'codeigniter@example.com'
    ];

    //--------------------------------------------------------------------
    // Handler
    //--------------------------------------------------------------------
    // Specifies the Handler used to send mail with if none are
    // otherwise specified. Must be the full class name.
    //
    public $group = 'default';

    //--------------------------------------------------------------------
    // Groups
    //--------------------------------------------------------------------
    // Setup multiple configuration settings, for different servers,
    // or when transactional emails are sent by a third-party service,
    // but you want to send other messages locally.
    //
    public $groups = [
        'default' => [
            'handler'  => 'simple',
            'protocol' => 'mail',
        ],
//        'example' => [
//            'handler' => 'simple',
//            'protocol' => 'smtp',
//            'SMTPHost' => '',
//            'SMTPUser' => '',
//            'SMTPPass' => '',
//            'SMTPPort' => 25,
//            'SMTPCrypto' => 'tls'
//        ]
    ];

    //--------------------------------------------------------------------
    // User Agent
    //--------------------------------------------------------------------
    // The "user agent", or the software used to send your message.
    //
    public $userAgent = 'CodeIgniter';

    //--------------------------------------------------------------------
    // Available Handlers
    //--------------------------------------------------------------------
    // The classes and their aliases that can be used to send mail with.
    // This defaults to the built-in MailHandler which can send through
    // mail(), sendmail() or smtp.
    //
    public $availableHandlers = [
        'simple' => \CodeIgniter\Mail\Handlers\MailHandler::class,
        'logger'  => \CodeIgniter\Mail\Handlers\LogHandler::class,
    ];
}
