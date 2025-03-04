<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

//class ComplaintRemarkNotification extends Notification implements ShouldQueue
class ComplaintRemarkNotification extends Notification
{
    use Queueable;
    public $complaint;
    public $complaint_remark;
   
    public function __construct($complaint, $complaint_remark)
    {
        $this->complaint = $complaint;
        $this->complaint_remark = $complaint_remark;
    }

    public function via($notifiable)
    {
        //return ['mail'];
		//return ['database'];
		return ['database','mail'];
    }
	
	public function toMail($notifiable)
    {
    
      
     return (new MailMessage)
                    ->from('info@hru.org.pk') // this line right here...
                    //->cc($this->complaint['email'])
                    ->bcc('ayaz@a2zcreatorz.com')
                    //->bcc('ayaz.a2zcreatorz@gmail.com')
                    ->subject('HRU GRM Complaint Remark')
                    ->greeting('Greeting')
                    
		  ->line('Complaint Remark:')
		 ->line($this->complaint_remark['remark'])
		 ->line('Here are the details:')
		 ->line('Ticket No: '.$this->complaint['ticket_no'])
		 ->line('HRU Beneficiary ID: '.$this->complaint['hru_beneficiary_id'])
		 ->line('Full Name: '.$this->complaint['full_name'])
		 ->line('Father Name: '.$this->complaint['father_name'])
		 ->line('Email: '.$this->complaint['email'])
		 ->line('Mobile: '.$this->complaint['mobile'])
		 ->line('CNIC: '.$this->complaint['cnic'])
		 ->line('District: '.$this->complaint['getdistrict']['name'] ?? '')
		 ->line('Tehsil: '.$this->complaint['gettehsil']['name'] ?? '')
		 ->line('UC: '.$this->complaint['getuc']['name'] ?? '')

		 ->line('Sincerely,')
		 ->line('Housing Reconstruction Unit')
		 ->line('info@hru.org.pk / +92812081372, ‪+92812846555‬')
		 
		 //->line('Thanks again for your valuable feedback.')
           ->action('Visit Website', url('/'))
           ->line('Thank you for using our application!')
           ->line('Best regards!');

       
    }


    public function toArray($notifiable)
    {
        return [
            'complaint_id' => $this->complaint['id'],
			'ticket_no' => $this->complaint['ticket_no'],
			'hru_beneficiary_id' => $this->complaint['hru_beneficiary_id'],
            'full_name' => $this->complaint['full_name'],
            'father_name' => $this->complaint['father_name'],
            'email' => $this->complaint['email'],
			'cnic' => $this->complaint['cnic']
        ];
    }
}