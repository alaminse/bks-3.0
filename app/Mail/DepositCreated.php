<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DepositCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $deposit;
    public $user;

    public function __construct($deposit, $user)
    {
        $this->deposit = $deposit;
        $this->user = $user;
    }

    public function build()
    {
        $type = $this->deposit->account_number != null ? 'WITHDRAW' : 'DEPOSIT';

        return $this->subject($type . ' Submitted Successfully')
                    ->view('includes.deposit-created');
    }

}
