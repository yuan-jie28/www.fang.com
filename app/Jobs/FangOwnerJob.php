<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Mail;
use Illuminate\Mail\Message;

class FangOwnerJob implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 成员属性  只有定义成 成员属性才能在handle方法中使用
    public $userData;

    public function __construct(array $data) {
        $this->userData = $data;
    }
    /**
     * Execute the job.
     * 工作任务  消费者
     */
    public function handle() {
        $email = $this->userData['email'];
        $name = $this->userData['name'];

        Mail::raw('添加您的信息成功，稍后我们会有工作人员联系您', function (Message $message) use ($email, $name) {
            $message->subject('信息添加通知邮件');
            $message->to($email, $name);
        });
    }
}
