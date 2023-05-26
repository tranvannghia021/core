<?php

namespace App\Jobs;

use App\Helpers\CoreHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VerifyEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user,$type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $user, string $type = 'verify')
    {
        $this->user=$user;
        $this->type=$type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $subject='Verify account from '.env('APP_NAME',config('app.name'));

            $href=config('app.url').'/verify?z='.CoreHelper::encodeState([
                    'id'=>$this->user['id'],
                ]).'&v=1&type='.$this->type.'&ip='.@CoreHelper::ip()['ip'];
            Mail::send('mails.verify-register',['href'=>$href],function  ($message) use ($subject){
                $message->from(config('mail.from.address'))->to($this->user['email'])
                    ->subject($subject);
            });
        }catch (\Exception $exception){
            throw $exception;
        }
    }
}
